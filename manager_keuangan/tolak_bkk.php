<?php

include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id = $_POST['id'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    if ($_POST['pengajuan'] == "PO") {
        $status = "202";
    } else {
        $status = "101";
    }

    // ngecek jika udh ada datanya maka update aja
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_bkk_final WHERE id_bkk_final = '$id'");
    $dataCek = mysqli_num_rows($cekTolak);

    if ($dataCek == 0) {
        $aksi_tolak = "INSERT INTO tolak_bkk_final (id_bkk_final, alasan_tolak_mgrfin, waktu_tolak_mgrfin) VALUES
                                                    ('$id', '$komentar', NOW())";
    } else {
        $aksi_tolak = "UPDATE tolak_bkk_final SET alasan_tolak_mgrfin = '$komentar', waktu_tolak_mgrfin = NOW()
                                                    WHERE id_bkk_final = '$id'";
    }
    // selesai

    $queryTolak = mysqli_multi_query($koneksi, "UPDATE bkk_final SET status_bkk = '$status' WHERE id = '$id';

                                                DELETE FROM reapprove_bkk_final WHERE id_bkk_final = '$id';

                                                $aksi_tolak;
    ");

    if ($queryTolak) {
        setcookie('pesan', 'BKK berhasil di Reject', time() + (3), '/');

        header('Location: index.php?p=' . $_POST['url'] . '');
    }
}
