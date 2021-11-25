<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id_kasbon = $_POST['id'];
    $id_user = $_POST['id_user'];

    $tanggal = dateNow();

    $queryCek = mysqli_query($koneksi, "SELECT id_direktur
										  FROM kasbon
										  WHERE id_kasbon = '$id_kasbon' ");
    $rowCek = mysqli_fetch_assoc($queryCek);
    $id_direktur = $rowCek['id_direktur'];

    // Commit query
    mysqli_begin_transaction($koneksi);

    if (!isset($id_direktur)) {
        // Jika di approval 1 kosong


        $query1 = mysqli_query($koneksi, "UPDATE kasbon 
                    SET app_direktur = '$tanggal',   
                    id_direktur = '$id_user'
        			WHERE id_kasbon = '$id_kasbon'");

        $queue = "berhasil";
    } else {
        // Approval Direktur 2

        $query1 = mysqli_query($koneksi, "UPDATE kasbon SET status_kasbon = 5,
                                            app_direktur2 = '$tanggal', 
                                            id_direktur2 = $id_user
                                        WHERE id_kasbon = '$id_kasbon'
                ");

        // query data buat di email
        $queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                                        JOIN sr s
                                                            ON s.id_sr = ks.sr_id
                                                        JOIN divisi d
                                                            ON d.id_divisi = ks.divisi_id
                                                        WHERE ks.id_kasbon = '$id_kasbon'");
        $dataEmail = mysqli_fetch_assoc($queryEmail);

        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                                        INNER JOIN divisi d
                                                        ON u.id_divisi = d.id_divisi											
                                                        AND level = 'kasir'");

        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $link = "url=index.php?p=payment_kasbon&sp=pk_sr";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Payment Kasbon SR " . $id_kasbon;
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
                                        <td style='font-family: Courier;'>: Service Request " . $dataEmail['nm_barang'] . "</td>
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
                                        <td style='font-family: Courier;'>Approval Manager GA</td>
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Manager Finance</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_finance'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Direktur 1</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_direktur'] . "</td>
                                    </tr>
                                    <tr>
                                        <td style='font-family: Courier;'>Approve Direktur 2 Finance</td>
                                        <td style='font-family: Courier;'>: " . $dataEmail['app_direktur'] . "</td>
                                    </tr>
                                </table>
                                <br>
                                Mohon untuk melakukan <i>Verifikasi</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

            $queue = createQueueEmail($name, $email, $subject, $body);
        }
    }

    if ($queue && $query1) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'Kasbon berhasil di Approve!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);
        echo mysqli_error($koneksi);
        die;
        setcookie('pesan', 'Kasbon gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_kasbon&sp=vk_sr");
}
