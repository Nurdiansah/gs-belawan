<?php
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $komentar = $_POST['komentar'];

    // cek tabel tolak_kasbon, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'");
    $dataTolak = mysqli_fetch_assoc($cekTolak);

    // if ($dataTolak['alasan_tolak_direktur'] != NULL) {
    //     $aksi_tolak = "DELETE FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'";
    // } else {
    //     $aksi_tolak = "UPDATE tolak_kasbon SET alasan_tolak_direktur = NULL, waktu_tolak_direktur = NULL
    //                     WHERE kasbon_id = '$id_kasbon'";
    // }
    // selesai cek tabel tolak_kasbon


    // cek ditabel reapprove_kasbon, jika udh ada isinya dari pengajuan tsb maka hanya update saja
    // $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
    // $totalReapp = mysqli_num_rows($cekReapp);

    // if ($totalReapp == 0) {
    //     $aksi_reapp = "INSERT INTO reapprove_kasbon (kasbon_id, alasan_reapprove_mgrfin, waktu_reapprove_mgrfin) VALUES
    //                     ('$id_kasbon', '$komentar', NOW());";
    // } else {
    //     $aksi_reapp = "UPDATE reapprove_kasbon SET alasan_reapprove_mgrfin = '$komentar', waktu_reapprove_mgrfin = NOW()
    //                     WHERE kasbon_id = '$id_kasbon';";
    // }
    // end

    // cek jika kasbon dari kasir, maka proses langsung ke direksi
    $queryCekKasbon = mysqli_query($koneksi, "SELECT * FROM kasbon
                                                JOIN detail_biayaops
                                                    ON id_dbo = id
                                                WHERE id_kasbon = '$id_kasbon'");
    $dataCekKasbon = mysqli_fetch_assoc($queryCekKasbon);

    // jika dia divisi kasir, maka status kasbonnya 3 (ke pajak)
    if ($dataCekKasbon['id_divisi'] == "11") {
        $status_kasbon = "2";
    } else {
        $status_kasbon = "4";
    }
    // end cek

    // AKSI UNTUK JALANIN DATANYA
    $tolak = mysqli_multi_query($koneksi, "UPDATE kasbon SET status_kasbon = '$status_kasbon',
                                                                app_mgr_finance = NOW()
                                                WHERE id_kasbon = '$id_kasbon';

                                            -- $aksi_tolak;
                                            -- $aksi_reapp; 
                                            ");

    if ($tolak) {
        header('Location: index.php?p=ditolak_kasbon&sp=tolak_user');
    }
    // END AKSI JALANIN DATANYA
}
