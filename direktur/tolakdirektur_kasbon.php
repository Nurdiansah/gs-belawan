<?php
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    // cek tabel tolak_kasbon, jika kosong maka insert jika udah ada maka update aja
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'");
    $totalCek = mysqli_num_rows($cekTolak);

    if ($totalCek == '0') {
        $aksi_tolak = "INSERT INTO tolak_kasbon (kasbon_id, alasan_tolak_direktur, waktu_tolak_direktur) VALUES
                        ('$id_kasbon', '$komentar', NOW())";
    } else {
        $aksi_tolak = "UPDATE tolak_kasbon SET alasan_tolak_direktur = '$komentar', waktu_tolak_direktur = NOW()
                        WHERE kasbon_id = '$id_kasbon'";
    }
    // selesai cek tabel tolak_kasbon

    // cek tabel reapprove_kasbon, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
    $dataReapp = mysqli_fetch_assoc($cekReapp);

    if ($dataReapp['alasan_reapprove_mgrga'] == NULL && $dataReapp['alasan_reapprove_pajak'] == NULL && $dataReapp['alasan_reapprove_purchasing'] == NULL) {
        $aksi_reapp = "DELETE FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'";
    } else {
        $aksi_reapp = "UPDATE reapprove_kasbon SET alasan_reapprove_mgrfin = NULL, waktu_reapprove_mgrfin = NULL
                        WHERE kasbon_id = '$id_kasbon'";
    }
    // selesai cek tabel reapprove_kasbon

    $tolak = mysqli_multi_query($koneksi, "UPDATE kasbon SET status_kasbon = '404',
                                                                id_direktur = NULL,
                                                                app_direktur = NULL,
                                                                id_direktur2 = NULL,
                                                                app_direktur2 = NULL,
                                                                app_mgr_finance = NULL
                                                WHERE id_kasbon = '$id_kasbon';

                                                $aksi_reapp;
                                                $aksi_tolak;");

    if ($tolak) {
        header('Location: index.php?p=verifikasi_kasbon&sp=' . $_POST['url'] . '');
    }
}
