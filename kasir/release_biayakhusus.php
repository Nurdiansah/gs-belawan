<<<<<<< HEAD
<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = dekripRambo($_GET['id']);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    $query1 = mysqli_query($koneksi, "UPDATE bkk_final SET status_bkk = 0, created_on_bkk ='$tanggal' WHERE id='$id' ");


    // query data buat diemail
    $queryEmail = mysqli_query($koneksi, "SELECT * FROM bkk_final
                                            WHERE id = '$id'
                                    ");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    // query buat ngirim keorang email
    $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                            INNER JOIN divisi d
                                                ON u.id_divisi = d.id_divisi
                                            WHERE level = 'manager_keuangan'");

    // data email
    while ($dataUser = mysqli_fetch_assoc($queryUser)) {
        $link = "url=index.php?p=verifikasi_bkk&lvl=manager_keuangan";
        $name = $dataUser['nama'];
        $email = $dataUser['email'];
        $subject = "Approval Biaya Khusus " . $dataEmail['id'];
        $body = addslashes("<font style='font-family: Courier;'>
                        Dear Bapak/Ibu <b>$name</b>,<br><br>
                        Diberitahukan bahwa divisi <b>Kasir</b> telah membuat pengajuan Biaya Khusus, dengan rincian sbb:<br>
                        <table>
                            <tr>
                                <td style='font-family: Courier;'>ID</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['id'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Nominal</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['nominal']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Keterangan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Remarks</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['remarks'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['created_on_bkk'] . "</td>
                            </tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                    </font>");

        // insert queue email
        $queue = createQueueEmail($name, $email, $subject, $body);
    }


    if ($queue && $query1) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Biaya Khusus berhasil direlease!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        // buat notif wa
        setcookie('status', 200, time() + (3), '/');
        setcookie('noWa', 6282126039936, time() + (3), '/');
        setcookie('body',  'Mohon approval pengajuan Biaya operasional Id : ' . $id, time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Biaya Khusus gagal direlease!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
        setcookie('status', 0, time() + (3), '/');
    }
    header("location:index.php?p=biaya_khusus");
}
=======
<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = dekripRambo($_GET['id']);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    $query1 = mysqli_query($koneksi, "UPDATE bkk_final SET status_bkk = 1, created_on_bkk ='$tanggal' WHERE id='$id' ");


    // query data buat diemail
    $queryEmail = mysqli_query($koneksi, "SELECT * FROM bkk_final
                                            WHERE id = '$id'
                                    ");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    // query buat ngirim keorang email
    $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                            INNER JOIN divisi d
                                                ON u.id_divisi = d.id_divisi
                                            WHERE level = 'manager_keuangan'");

    // data email
    while ($dataUser = mysqli_fetch_assoc($queryUser)) {
        $link = "url=index.php?p=verifikasi_bkk&lvl=manager_keuangan";
        $name = $dataUser['nama'];
        $email = $dataUser['email'];
        $subject = "Approval Biaya Khusus " . $dataEmail['id'];
        $body = addslashes("<font style='font-family: Courier;'>
                        Dear Bapak/Ibu <b>$name</b>,<br><br>
                        Diberitahukan bahwa divisi <b>Kasir</b> telah membuat pengajuan Biaya Khusus, dengan rincian sbb:<br>
                        <table>
                            <tr>
                                <td style='font-family: Courier;'>ID</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['id'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Nominal</td>
                                <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['nominal']) . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Keterangan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['keterangan'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Remarks</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['remarks'] . "</td>
                            </tr>
                            <tr>
                                <td style='font-family: Courier;'>Tanggal Pengajuan</td>
                                <td style='font-family: Courier;'>: " . $dataEmail['created_on_bkk'] . "</td>
                            </tr>
                        </table>
                        <br>
                        Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                        Best Regards,<br>
                        This email auto generate by system.
                    </font>");

        // insert queue email
        $queue = createQueueEmail($name, $email, $subject, $body);
    }


    if ($queue && $query1) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Biaya Khusus berhasil direlease!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Biaya Khusus gagal direlease!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=biaya_khusus");
}
>>>>>>> 746ee0e599a28820859b01f1c1b41fbc3a15960a
