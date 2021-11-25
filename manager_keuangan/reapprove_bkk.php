<?php

include "../fungsi/koneksi.php";

if (isset($_POST['kirim'])) {
    $id = $_POST['id'];
    $komentar = $_POST['komentar'];

    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_bkk_final WHERE id_bkk_final = '$id'");
    $dataReapp = mysqli_num_rows($cekReapp);

    if ($dataReapp == 0) {
        $aksi_reapp = "INSERT INTO reapprove_bkk_final (id_bkk_final, alasan_reapprove_mgrfin, waktu_reapprove_mgrfin) VALUES
                        ('$id', '$komentar', NOW())";
    } else {
        $aksi_reapp = "UPDATE reapprove_bkk_final SET alasan_reapprove_mgrfin = '$komentar',
                                waktu_reapprove_mgrfin = NOW()
                        WHERE id_bkk_final = '$id'";
    }

    $reApprove = mysqli_multi_query($koneksi, "UPDATE bkk_final SET status_bkk = '2' WHERE id = '$id';
                                                DELETE FROM tolak_bkk_final WHERE id_bkk_final = '$id';
                                                $aksi_reapp;
    ");

    if ($reApprove) {
        header("Location: index.php?p=ditolak_bkk");
    }
}
