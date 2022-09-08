<?php
include "../fungsi/koneksi.php";
include "../fungsi/koneksipusat.php";

if (isset($_POST['tolak'])) {
    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];
    $komentar = $_POST['komentar'];
    
    // ngecek jika udh ada datanya maka update aja
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_bkk_final WHERE id_bkk_final = '$id_bkk'");
    $dataCek = mysqli_num_rows($cekTolak);

    if ($dataCek == 0) {
        $aksi_tolak = "INSERT INTO tolak_bkk_final (id_bkk_final, alasan_tolak_pajak, waktu_tolak_pajak) VALUES
                                                    ('$id_bkk', '$komentar', NOW())";
    } else {
        $aksi_tolak = "UPDATE tolak_bkk_final SET alasan_tolak_pajak = '$komentar', waktu_tolak_pajak = NOW()
                                                    WHERE id_bkk_final = '$id_bkk'";
    }
    // selesai

    $queryTolak = mysqli_multi_query($koneksi, "UPDATE bkk_ke_pusat SET status_bkk = '101' WHERE id = '$id_bkk';

                                                -- DELETE FROM reapprove_bkk_final WHERE id_bkk_final = '$id_bkk';

                                                $aksi_tolak;
    ");

    if ($queryTolak) {
        header('Location: index.php?p=' . $_POST['url'] . '');
    }
}
