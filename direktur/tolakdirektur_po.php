<?php

include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_po = $_POST['id_po'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    // cek tabel tolak_po, jika kosong maka insert jika udah ada maka update aja
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_po WHERE po_id = '$id_po'");
    $totalCek = mysqli_num_rows($cekTolak);

    if ($totalCek == '0') {
        $aksi_tolak = "INSERT INTO tolak_po (po_id, alasan_tolak_direktur, waktu_tolak_direktur) VALUES
                        ('$id_po', '$komentar', NOW())";
    } else {
        $aksi_tolak = "UPDATE tolak_po SET alasan_tolak_direktur = '$komentar', waktu_tolak_direktur = NOW()
                        WHERE po_id = '$id_po'";
    }
    // selesai cek tabel tolak_po

    // cek tabel reapprove_po, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_po WHERE po_id = '$id_po'");
    $dataReapp = mysqli_fetch_assoc($cekReapp);

    if ($dataReapp['alasan_reapprove_mgrga'] == NULL && $dataReapp['alasan_reapprove_pajak'] == NULL && $dataReapp['alasan_reapprove_purchasing'] == NULL) {
        $aksi_reapp = "DELETE FROM reapprove_po WHERE po_id = '$id_po'";
    } else {
        $aksi_reapp = "UPDATE reapprove_po SET alasan_reapprove_mgrfin = NULL, waktu_reapprove_mgrfin = NULL
                        WHERE po_id = '$id_po'";
    }
    // selesai cek tabel reapprove_po

    $tolak = mysqli_multi_query($koneksi, "UPDATE po SET status_po = '404',
                                                id_direktur = '',
                                                app_direksi = NULL,
                                                id_direktur2 = NULL,
                                                app_direksi2 = NULL,
                                                app_mgr_finance = NULL
                                            WHERE id_po = '$id_po';

                                            $aksi_reapp;
                                            $aksi_tolak;");

    if ($tolak) {
        header("Location: index.php?p=verifikasi_po");
    } else {
        echo "Ada error " . mysqli_error($koneksi);
    }
}
