<?php
include "../fungsi/koneksi.php";

if (isset($_POST['kirim'])) {
    $id_bkk = $_POST['id_bkk'];
    $komentar = $_POST['komentar'];

    $tanggal = date("Y-m-d");

    // ngecek klo blm ada tatanya maka insert, klo udh ada di tbl itu cuma update aja
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_bkk WHERE id_bkk = '$id_bkk'");
    $dataCek = mysqli_num_rows($cekReapp);
    if ($dataCek == 0) {
        $aksi_reapp = "INSERT INTO reapprove_bkk (id_bkk, alasan_reapprove_mgr, waktu_reapprove_mgr) VALUES
                        ('$id_bkk', '$komentar', NOW())";
    } else {
        $aksi_reapp = "UPDATE reapprove_bkk SET alasan_reapprove_mgr = '$komentar',
                                        waktu_reapprove_mgr = NOW()
                        WHERE id_bkk = '$id_bkk'";
    }
    // end

    $reApprove = mysqli_multi_query($koneksi, "UPDATE bkk SET komentar_mgrfin = NULL,
                                                              tgl_verifikasimanager = '$tanggal',
                                                              status_bkk = '4'
                                                WHERE id_bkk = '$id_bkk';
                                                
                                                $aksi_reapp;
    ");

    if ($reApprove) {
        setcookie('pesan', 'Berhasil di Re Approve !', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header('Location: index.php?p=ditolak_bno');
    }
}
