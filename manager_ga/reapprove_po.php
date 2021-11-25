<?php
include "../fungsi/koneksi.php";

if (isset($_POST['kirim'])) {
    $id_po = $_POST['id_po'];
    $komentar = $_POST['komentar'];

    // cek tabel tolak_po, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_po WHERE po_id = '$id_po'");
    $dataTolak = mysqli_fetch_assoc($cekTolak);

    if ($dataTolak['alasan_tolak_pajak'] == NULL && $dataTolak['alasan_tolak_mgrfin'] == NULL && $dataTolak['alasan_tolak_direktur'] == NULL) {
        $aksi_tolak = "DELETE FROM tolak_po WHERE po_id = '$id_po'";
    } else {
        $aksi_tolak = "UPDATE tolak_po SET alasan_tolak_mgrfin = NULL, waktu_tolak_mgrfin = NULL
                        WHERE po_id = '$id_po'";
    }
    // selesai cek tabel tolak_po


    // cek ditabel reapprove_po, jika udh ada isinya dari pengajuan tsb maka hanya update saja
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_po WHERE po_id = '$id_po'");
    $totalReapp = mysqli_num_rows($cekReapp);

    if ($totalReapp == 0) {
        $aksi_reapp = "INSERT INTO reapprove_po (po_id, alasan_reapprove_mgrga, waktu_reapprove_mgrga) VALUES
                        ('$id_po', '$komentar', NOW());";
    } else {
        $aksi_reapp = "UPDATE reapprove_po SET alasan_reapprove_mgrga = '$komentar', waktu_reapprove_mgrga = NOW()
                        WHERE po_id = '$id_po';";
    }
    // end

    // AKSI UNTUK JALANIN DATANYA
    $tolak = mysqli_multi_query($koneksi, "UPDATE po SET status_po = '4',
                                                    app_mgr_ga = NOW()
                                                WHERE id_po = '$id_po';

                                            $aksi_tolak;   
                                            $aksi_reapp; 
                                ");

    if ($tolak) {
        header('Location: index.php?p=ditolak_po');
    }
    // END AKSI JALANIN DATANYA
}
