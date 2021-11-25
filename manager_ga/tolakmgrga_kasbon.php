<?php
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    // cek tabel tolak_kasbon, jika kosong maka insert jika udah ada maka update aja
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'");
    $totalCek = mysqli_num_rows($cekTolak);

    if ($totalCek == '0') {
        $aksi_tolak = "INSERT INTO tolak_kasbon (kasbon_id, alasan_tolak_mgrga, waktu_tolak_mgrga) VALUES
                        ('$id_kasbon', '$komentar', NOW())";
    } else {
        $aksi_tolak = "UPDATE tolak_kasbon SET alasan_tolak_mgrga = '$komentar', waktu_tolak_mgrga = NOW()
                        WHERE kasbon_id = '$id_kasbon'";
    }
    // selesai cek tabel tolak_kasbon

    // cek tabel reapprove_kasbon, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
    $dataReapp = mysqli_fetch_assoc($cekReapp);

    if ($dataReapp['alasan_reapprove_mgrfin'] == NULL && $dataReapp['alasan_reapprove_mgrga'] == NULL) {
        $aksi_reapp = "DELETE FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'";
    } else {
        $aksi_reapp = "UPDATE reapprove_kasbon SET alasan_reapprove_mgrga = NULL, waktu_reapprove_mgrga = NULL, alasan_reapprove_pajak = NULL, waktu_reapprove_pajak = NULL
                        WHERE kasbon_id = '$id_kasbon'";
    }
    // selesai cek tabel reapprove_kasbon

    $tolak = mysqli_multi_query($koneksi, "UPDATE kasbon SET status_kasbon = '202',
                                                                app_pajak = NULL
                                                WHERE id_kasbon = '$id_kasbon';
                                                
                                            $aksi_reapp;
                                            $aksi_tolak;");

    if ($tolak) {
        header('Location: index.php?p=verifikasi_kasbon&sp=vk_purchasing');
    }
}
