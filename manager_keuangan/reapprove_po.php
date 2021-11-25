<?php
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_po = $_POST['id_po'];
    $komentar = $_POST['komentar'];

    // cek tabel tolak_po, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_po WHERE po_id = '$id_po'");
    $dataTolak = mysqli_fetch_assoc($cekTolak);

    if ($dataTolak['alasan_tolak_mgrga'] == NULL && $dataTolak['alasan_tolak_pajak'] == NULL) {
        $aksi_tolak = "DELETE FROM tolak_po WHERE po_id = '$id_po'";
    } else {
        $aksi_tolak = "UPDATE tolak_po SET alasan_tolak_direktur = NULL, waktu_tolak_direktur = NULL
                        WHERE po_id = '$id_po'";
    }
    // selesai cek tabel tolak_po


    // cek ditabel reapprove_po, jika udh ada isinya dari pengajuan tsb maka hanya update saja
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_po WHERE po_id = '$id_po'");
    $totalReapp = mysqli_num_rows($cekReapp);

    if ($totalReapp == 0) {
        $aksi_reapp = "INSERT INTO reapprove_po (po_id, alasan_reapprove_mgrfin, waktu_reapprove_mgrfin) VALUES
                        ('$id_po', '$komentar', NOW());";
    } else {
        $aksi_reapp = "UPDATE reapprove_po SET alasan_reapprove_mgrfin = '$komentar', waktu_reapprove_mgrfin = NOW()
                        WHERE po_id = '$id_po';";
    }
    // end

    // AKSI UNTUK JALANIN DATANYA
    $tolak = mysqli_multi_query($koneksi, "UPDATE po SET status_po = '5',
                                                    app_mgr_finance = NOW(),
                                                    id_direktur= NULL
                                                WHERE id_po = '$id_po';

                                            $aksi_tolak;   
                                            $aksi_reapp; 
                                            ");

    if ($tolak) {
        header('Location: index.php?p=ditolak_po');
    }
    // END AKSI JALANIN DATANYA
}
