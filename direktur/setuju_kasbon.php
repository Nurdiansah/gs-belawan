<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['kirim'])) {
    $id_kasbon = $_POST['id'];
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];
    $id_user = $rowUser['id_user'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryCek = mysqli_query($koneksi, "SELECT id_direktur
										  FROM kasbon
										  WHERE id_kasbon = '$id_kasbon' ");
    $rowCek = mysqli_fetch_assoc($queryCek);
    $id_direktur = $rowCek['id_direktur'];
    // var_dump($id_direktur);
    if (!isset($id_direktur)) {
        // Jika di approval 1 kosong
        $query1 = "UPDATE kasbon SET app_direktur = '$tanggal', id_direktur = $id_user
    									  WHERE id_kasbon = '$id_kasbon'";

        $queue = "berhasil";
    } else {
        $query1 = "UPDATE kasbon SET status_kasbon = 5,
                        app_direktur2 = '$tanggal',
                        id_direktur2 = $id_user
					WHERE id_kasbon = '$id_kasbon'";


        // buat ngecek kasbon user apa bukan
        $queryCek = mysqli_query($koneksi, "SELECT * FROM kasbon WHERE id_kasbon = '$id_kasbon'");
        $dataCek = mysqli_fetch_assoc($queryCek);

        if ($dataCek['from_user'] == '1') {
            // query data buat diemail kasbon user
            $queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
												JOIN user u
													ON u.id_user = ks.id_manager   
												JOIN detail_biayaops dbo
													ON id_dbo = dbo.id    
												JOIN divisi d
													ON d.id_divisi = dbo.id_divisi
												WHERE id_kasbon = '$id_kasbon'
												");
            $dataEmail = mysqli_fetch_assoc($queryEmail);

            // query buat ngirim keorang email
            $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
												INNER JOIN divisi d
												ON u.id_divisi = d.id_divisi
												WHERE nm_divisi = 'kasir'
												AND level = 'kasir'");

            // data email
            while ($dataUser = mysqli_fetch_assoc($queryUser)) {
                $linkUser = "url=index.php?p=payment_kasbon&sp=pk_user&lvl=kasir";
                $name = $dataUser['nama'];
                $email = $dataUser['email'];
                $subject = "Payment Kasbon " . $dataEmail['id_kasbon'];
                $body = addslashes("<font style='font-family: Courier;'>
                        Dear Bapak/Ibu <b>$name</b>,<br><br>
                        Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan Kasbon, dengan rincian sbb:<br>
                        <table>
                            <tr>
                                <td style='font-family: Courier;'>Kode Transaksi</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['id_kasbon'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Divisi</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Keterangan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Total</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['harga_akhir']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['tgl_kasbon'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Approve Manager</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['app_manager'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Verifikasi Pajak</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['app_pajak'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Approve Manager Finance</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_finance'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Approve Direksi 1</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['app_direktur'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Approve Direksi 2</td>
                                <td style='font-family: Courier;'>: " . $tanggal . "</td>
                            </tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Payment</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$linkUser' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                    </font>");

                // insert queue email
                $queue = createQueueEmail($name, $email, $subject, $body);
            }
        } else {

            // query data buat diemail kekasir kasbon purchasing
            $queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
													JOIN detail_biayaops dbo
														ON id_dbo = dbo.id
													JOIN biaya_ops bo
														ON bo.kd_transaksi = dbo.kd_transaksi
													JOIN divisi d
														ON d.id_divisi = dbo.id_divisi
													WHERE id_kasbon = '$id_kasbon'
													");
            $dataEmail = mysqli_fetch_assoc($queryEmail);

            // query buat ngirim keorang email
            $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
													INNER JOIN divisi d
													ON u.id_divisi = d.id_divisi
													WHERE nm_divisi = 'kasir'
													AND level = 'kasir'");

            // data email
            while ($dataUser = mysqli_fetch_assoc($queryUser)) {
                $linkPurchasing = "url=index.php?p=payment_kasbon&sp=pk_purchasing&lvl=kasir";
                $name = $dataUser['nama'];
                $email = $dataUser['email'];
                $subject = "Payment Kasbon " . $id_kasbon;
                $body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan Kasbon, dengan rincian sbb:<br>
                                <table>
                                    <tr>
                                        <td style='font-family: Courier;'>Kode Transaksi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['id_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Divisi</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Keterangan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Total</td>
                                        <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['harga_akhir']) . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['tgl_kasbon'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Manager</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_mgr'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Bidding Purchasing</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_purchasing'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Verifikasi Pajak</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_pajak'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approval Manager GA</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_ga'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approval Manager Finance</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_finance'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approval Direktur 1</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_direktur'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approval Direktur 2</td>
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Payment</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$linkPurchasing' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

                // insert queue email
                $queue = createQueueEmail($name, $email, $subject, $body);
            }
        }
    }

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan verifikasi kasbon id: $id_kasbon')";

    $query = mysqli_multi_query($koneksi, "DELETE FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon';

											DELETE FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon';
			
											$queryLog;

											$query1;
	");


    if ($query && $queue) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Kasbon berhasil di Approve!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Kasbon gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}
