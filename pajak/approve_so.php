<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $id_user = $_POST['id_user'];
    $id_manager = $_POST['id_manager'];

    $tanggal = dateNow();

    // query data buat diemail
    $queryEmail = mysqli_query($koneksi, "SELECT * FROM so s
                                        JOIN user u
                                            ON u.id_user = s.id_manager     
                                        JOIN divisi d
                                            ON d.id_divisi = s.id_divisi
                                        WHERE s.id_so = '$id'
                ");
    $dataEmail = mysqli_fetch_assoc($queryEmail);

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    // Update approval SO
    $query = "UPDATE so SET status = '3', app_pajak = NOW()
                WHERE id_so = '$id' ";
    $update = mysqli_query($koneksi, $query);

    // query buat ngirim keorang email
    $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                                INNER JOIN divisi d
                                                ON u.id_divisi = d.id_divisi
                                                WHERE nm_divisi = 'finance'
                                                AND level = 'manager_keuangan'");

    // data email
    while ($dataUser = mysqli_fetch_assoc($queryUser)) {
        $link = "url=index.php?p=approval_sr&lvl=manager_finance";
        $name = $dataUser['nama'];
        $email = $dataUser['email'];
        $subject = "Approval Service Order " . $dataEmail['nm_barang'];
        $body = addslashes("<font style='font-family: Courier;'>
                            Dear Bapak/Ibu <b>$name</b>,<br><br>
                            Diberitahukan bahwa divisi <b>" . $dataEmail['nm_divisi'] . "</b> telah membuat pengajuan SR, dengan rincian sbb:<br>
                            <table>
                                <tr>
                                    <td style='font-family: Courier;'>Nama Barang / Alat</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['nm_barang'] . "</td>
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
                                    <td style='font-family: Courier;'>Nominal</td>
                                    <td style='font-family: Courier;'>: " . formatRupiah2($dataEmail['grand_total']) . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Tempo Pembayaran</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['tgl_tempo'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Tanggal</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['created_at'] . "</td>
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
                                    <td style='font-family: Courier;'>Approve Manager GA</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_ga'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Verifikasi Pajak</td>
                                    <td style='font-family: Courier;'>: " . $tanggal . "</td>
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


    if ($update && $queue) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'SR berhasil di Verifikasi!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'SR gagal di Verifikasi!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_sr");
}

?>
<!--  -->