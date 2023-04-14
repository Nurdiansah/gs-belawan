<?php

// session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {

    $id = base64_decode($_GET['id']);

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash WHERE id_pettycash = '$id' "));

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryManager = mysqli_query($koneksi, "SELECT * -- email, nama, nm_divisi
                                            FROM transaksi_pettycash tp   
                                            JOIN user u
                                                ON u.id_user = tp.id_manager       
                                            JOIN divisi d
                                                ON d.id_divisi = u.id_divisi
                                            WHERE id_pettycash = '$id'
                                            ");
    $dataManager = mysqli_fetch_assoc($queryManager);

    $link = "url=index.php?p=approval_pettycash&lvl=manager";

    // data email
    $name = $dataManager['nama'];
    $email = $dataManager['email'];
    $subject = "Approval Pettycash " . $dataManager['kd_pettycash'];
    $body = addslashes("<font style='font-family: Courier;'>
                            Dear Bapak/Ibu <b>$name</b>,<br><br>
                            Diberitahukan bahwa <b>" . $dataManager['created_pettycash_by'] . "</b> telah membuat pengajuan Petty Cash, dengan rincian sbb:<br>
                            <table>
                                <tr>
                                    <td style='font-family: Courier;'>Kode Transaksi</td>
                                    <td style='font-family: Courier;'>: " . $dataManager['kd_pettycash'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Divisi</td>
                                    <td style='font-family: Courier;'>: " . $dataManager['nm_divisi'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Keterangan</td>
                                    <td style='font-family: Courier;'>: " . $dataManager['keterangan_pettycash'] . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Total</td>
                                    <td style='font-family: Courier;'>: " . formatRupiah2($dataManager['total_pettycash']) . "</td>
                                </tr>
                                <tr>
                                    <td style='font-family: Courier;'>Tanggal</td>
                                    <td style='font-family: Courier;'>: " . $dataManager['created_pettycash_on'] . "</td>
                                </tr>
                            </table>
                            <br>
                            Mohon untuk melakukan <i>Approval</i> / <i>Reject</i> pada sistem E-Fin Graha Segara <a href='" . host() . "index.php?$link' target='_blank'>disini</a><br><br>
                            Best Regards,<br>
                            This email auto generate by system.
                        </font>");

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    // insert queue email
    $queue = createQueueEmail($name, $email, $subject, $body);

    // update release pettycash
    $updPetty = mysqli_query($koneksi, "UPDATE transaksi_pettycash SET status_pettycash=1 , created_pettycash_on ='$tanggal' WHERE id_pettycash='$id' ");

    $id_anggaran = $data['id_anggaran'];
    $nominal = $data['total_pettycash'];

    $insReaSem =  insRealisasiSem('PCS', $id, $id_anggaran, $nominal);

    // Artinya kita sudah bisa melakukan COMMIT
    if ($queue && $updPetty) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Pettycash Berhasil di release !', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:index.php?p=buat_petty");
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Pettycash Gagal di release  !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=buat_petty");
    }
}
