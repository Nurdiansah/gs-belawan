<?php
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $komentar = $_POST['komentar'];

    // cek tabel tolak_kasbon, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'");
    $dataTolak = mysqli_fetch_assoc($cekTolak);

    if ($dataTolak['alasan_tolak_pajak'] == NULL && $dataTolak['alasan_tolak_mgrfin'] == NULL && $dataTolak['alasan_tolak_direktur'] == NULL) {
        $aksi_tolak = "DELETE FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'";
    } else {
        $aksi_tolak = "UPDATE tolak_kasbon SET alasan_tolak_mgrfin = NULL, waktu_tolak_mgrfin = NULL
                        WHERE kasbon_id = '$id_kasbon'";
    }
    // selesai cek tabel tolak_kasbon


    // cek ditabel reapprove_kasbon, jika udh ada isinya dari pengajuan tsb maka hanya update saja
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
    $totalReapp = mysqli_num_rows($cekReapp);

    if ($totalReapp == 0) {
        $aksi_reapp = "INSERT INTO reapprove_kasbon (kasbon_id, alasan_reapprove_mgrga, waktu_reapprove_mgrga) VALUES
                        ('$id_kasbon', '$komentar', NOW());";
    } else {
        $aksi_reapp = "UPDATE reapprove_kasbon SET alasan_reapprove_mgrga = '$komentar', waktu_reapprove_mgrga = NOW()
                        WHERE kasbon_id = '$id_kasbon';";
    }
    // end

    // AKSI UNTUK JALANIN DATANYA
    $tolak = mysqli_multi_query($koneksi, "UPDATE kasbon SET status_kasbon = '3',
                                                                app_mgr_ga = NOW()
                                                WHERE id_kasbon = '$id_kasbon';

                                            $aksi_tolak;   
                                            $aksi_reapp; 
                                            ");

    if ($tolak) {
        header('Location: index.php?p=ditolak_kasbon&sp=tolak_purchasing');
    }
    // END AKSI JALANIN DATANYA
}
