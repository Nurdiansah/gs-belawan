<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $id_divisi = $_POST['id_divisi'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryKasir = mysqli_query($koneksi, "SELECT email, nama FROM user                                       
                                            WHERE level='kasir'
                                            ");
    $dataKasir = mysqli_fetch_assoc($queryKasir);

    // notif ke kasir
    $email = $dataKasir['email'];
    $subject = 'Payment Pettycash';
    $body = 'Body';

    // notif ke user

    $queryUser = mysqli_query($koneksi, "SELECT email, nama
                                            FROM user  usr
                                            INNER JOIN divisi dvs
                                                ON usr.id_divisi = dvs.id_divisi                                     
                                            WHERE level = 'admin_divisi'
                                            AND nm_divisi = 'Kasir'
                                            ");

    $queryManager = mysqli_query($koneksi, "SELECT * -- email, nama, nm_divisi
                                            FROM transaksi_pettycash tp   
                                            JOIN user u
                                                ON u.nama = tp.created_pettycash_by       
                                            JOIN divisi d
                                                ON d.id_divisi = u.id_divisi
                                            WHERE id_pettycash = '$id'
                                            ");
    $dataEmail = mysqli_fetch_assoc($queryManager);


    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    // buat looping biar semua divisi kasir dikirimin email
    while ($dataUser = mysqli_fetch_assoc($queryUser)) {
        $link = "url=index.php?p=payment_pettycash&lvl=kasir";
        // data email
        $name = $dataUser['nama'];
        $email = $dataUser['email'];
        $subject = "Payment Pettycash " . $dataEmail['kd_pettycash'];
        $body = addslashes("<font style='font-family: Courier;'>
                            Dear Bapak/Ibu <b>$name</b>,<br><br>
                            Diberitahukan bahwa <b>" . $dataEmail['created_pettycash_by'] . "</b> telah membuat pengajuan Petty Cash, dengan rincian sbb:<br>
                            <table>
                                <tr>
                                    <td style='font-family: Courier;'>Kode Transaksi</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['kd_pettycash'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Divisi</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Keterangan</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['keterangan_pettycash'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Total</td>
                                    <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['total_pettycash']) . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Tanggal</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['created_pettycash_on'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Approval Manager</td>
                                    <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                </tr>
                            </table>
                            <br>
                            Mohon untuk melakukan <i>Payment</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                            Best Regards,<br>
                            This email auto generate by system.
                        </font>");

        // insert queue email
        $queue = createQueueEmail($name, $email, $subject, $body);
    }
    // end email ke kasir

    $linkUser = "url=index.php?p=buat_petty&lvl=admin_divisi";
    $nameUser = $dataEmail['nama'];
    $emailUser = $dataEmail['email'];
    $subjectUser = "Pengambilan Dana Pettycash " . $dataEmail['kd_pettycash'];
    $bodyUser = addslashes("<font style='font-family: Courier;'>
                        Dear Bapak/Ibu <b>$nameUser</b>,<br><br>
                        Diberitahukan bahwa pengajuan Petty Cash <b>" . $dataEmail['kd_pettycash'] . "</b> sudah di Approve, dengan rincian sbb:<br>
                        <table>
                            <tr>
                                <td style='font-family: Courier;'>Kode Transaksi</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['kd_pettycash'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Divisi</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['nm_divisi'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Keterangan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['keterangan_pettycash'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Total</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['total_pettycash']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Tanggal</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['created_pettycash_on'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Approval Manager</td>
                                <td style='font-family: Courier;'>: " . $tanggal . "</td>
                            </tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Pengambilan Dana</i> ke kasir, dan Cetak LPD pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$linkUser' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                    </font>");

    $queueUser = createQueueEmail($nameUser, $emailUser, $subjectUser, $bodyUser);

    $updPetty = mysqli_query($koneksi, "UPDATE transaksi_pettycash SET status_pettycash=2, app_mgr ='$tanggal' WHERE id_pettycash='$id' ");

    // Artinya kita sudah bisa melakukan COMMIT
    if ($queue && $queueUser && $updPetty) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Pettycash Berhasil di verifikasi !', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Pettycash Gagal di verifikasi  !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=approval_pettycash");
}
