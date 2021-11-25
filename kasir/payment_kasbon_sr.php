<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['id'])) {
    $id_kasbon = $_POST['id'];

    $tanggal = dateNow();

    // query data buat diemail
    $queryEmail = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                            JOIN sr s
                                                ON s.id_sr = ks.sr_id
											JOIN divisi d
											    ON d.id_divisi = ks.divisi_id
											WHERE ks.id_kasbon = '$id_kasbon'
											");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    mysqli_begin_transaction($koneksi);

    // query buat ngirim keorang email
    $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
											INNER JOIN divisi d
											ON u.id_divisi = d.id_divisi											
											AND level = 'purchasing'");

    // data email
    while ($dataUser = mysqli_fetch_assoc($queryUser)) {
        $link = "url=index.php?p=lpj_kasbon&sp=lpj_ksr";
        $name = $dataUser['nama'];
        $email = $dataUser['email'];
        $subject = "Pembayaran Kasbon SR " . $id_kasbon;
        $body = addslashes("<font style='font-family: Courier;'>
                                Dear Bapak/Ibu <b>$name</b>,<br><br>
                                Diberitahukan bahwa kasir telah melakukan penyerahan dana Kasbon, dengan rincian sbb:<br>
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
                                        <td style='font-family: Courier;'>Tanggal Penyerahan Dana</td>
                                        <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                    </tr>                                    
                                </table>
                                <br>
                                Mohon untuk melakukan <i>LPJ</i> jika sudah dilakukan pembelian/perbaikan barang,  pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                                Best Regards,<br>
                                This email auto generate by system.
                            </font>");

        $queue = createQueueEmail($name, $email, $subject, $body);
    }

    $query1 = mysqli_query($koneksi, "UPDATE kasbon
										  SET status_kasbon=6 , waktu_penerima_dana = '$tanggal' 
										  WHERE id_kasbon = '$id_kasbon' ");



    if ($queue && $query1) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'Kasbon berhasil di bayar!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);
        echo mysqli_error($koneksi);
        die;
        setcookie('pesan', 'Kasbon gagal di bayar!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=payment_kasbon&sp=pk_sr");
}
