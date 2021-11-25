<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $id_user = $_POST['id_user'];

    $tanggal = dateNow();

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    $queryCek = mysqli_query($koneksi, "SELECT * FROM so WHERE id_so = '$id'");
    $dataCek = mysqli_fetch_assoc($queryCek);

    if ($dataCek['app_direktur1'] == NULL || $dataCek['direktur1'] == NULL) {
        // Update approval SO
        $update = mysqli_query($koneksi, "UPDATE so SET app_direktur1 = NOW(), direktur1 = '$id_user' WHERE id_so = '$id'");

        $queue = "berhasil";
    } else {
        // Update approval SO
        $update = mysqli_query($koneksi, "UPDATE so SET app_direktur2 = NOW(),
                                                    direktur2 = '$id_user',
                                                    status = '5'
                                        WHERE id_so ='$id'
                                        AND direktur1 <> '$id_user'
                    ");

        // query data buat diemail
        $queryEmail = mysqli_query($koneksi, "SELECT * FROM so s
                                                JOIN user u
                                                    ON u.id_user = s.id_manager     
                                                JOIN divisi d
                                                    ON d.id_divisi = s.id_divisi
                                                WHERE s.id_so = '$id'
                ");
        $dataEmail = mysqli_fetch_assoc($queryEmail);

        // query buat ngirim keorang email
        $queryUser = mysqli_query($koneksi, "SELECT * FROM user u
                                            INNER JOIN divisi d
                                                ON u.id_divisi = d.id_divisi
                                            WHERE nm_divisi = 'kasir'
                                            AND level = 'kasir'
                ");

        // data email
        while ($dataUser = mysqli_fetch_assoc($queryUser)) {
            $link = "url=index.php?p=payment_sr&lvl=kasir";
            $name = $dataUser['nama'];
            $email = $dataUser['email'];
            $subject = "Payment Service Order " . $dataEmail['nm_barang'];
            $tanggal_tempo = $dataEmail['tgl_tempo'];
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
                                    <td style='font-family: Courier;'>: " . $dataEmail['app_pajak'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Approve Manager Finance</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['app_mgr_fin'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Approve Direktur 1</td>
                                    <td style='font-family: Courier;'>: " . $dataEmail['app_direktur1'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Approve Direktur 2</td>
                                    <td style='font-family: Courier;'>: " . $tanggal . "</td>
                                </tr>
                            </table>
                            <br>
                            Mohon untuk melakukan <i>Payment</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                            Best Regards,<br>
                            This email auto generate by system.
                        </font>");

            // insert queue email
            $queue = createQueueEmailTempo($name, $email, $subject, $body, $tanggal_tempo);
        }

        // print_r($queue);
        // die;
    }


    if ($update && $queue) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'SO berhasil di Approve!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'SO gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=approval_sr");
}

?>
<!--  -->