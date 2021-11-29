<?php
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $nilai_pph = $_POST['pph_nilai'];
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml_bkk']);

    // $komentar = "Pengajuan disubmit kembali oleh pajak";


    // cek tabel tolak_kasbon, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    // $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'");
    // $dataTolak = mysqli_fetch_assoc($cekTolak);

    // if ($dataTolak['alasan_tolak_mgrfin'] == NULL && $dataTolak['alasan_tolak_direktur'] == NULL) {
    //     $aksi_tolak = "DELETE FROM tolak_kasbon WHERE id_tolak = '$id_kasbon'";
    // } else {
    //     $aksi_tolak = "UPDATE tolak_kasbon SET alasan_tolak_mgrga = NULL, waktu_tolak_mgrga = NULL
    //                     WHERE kasbon_id = '$id_kasbon'";
    // }
    // selesai cek tabel tolak_kasbon

    // cek ditabel reapprove_kasbon, jika udh ada isinya dari pengajuan tsb maka hanya update saja
    // $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
    // $totalReapp = mysqli_num_rows($cekReapp);

    // if ($totalReapp == 0) {
    //     $aksi_reapp = "INSERT INTO reapprove_kasbon (kasbon_id, alasan_reapprove_pajak, waktu_reapprove_pajak) VALUES
    //                     ('$id_kasbon', '$komentar', NOW());";
    // } else {
    //     $aksi_reapp = "UPDATE reapprove_kasbon SET alasan_reapprove_pajak = '$komentar', waktu_reapprove_pajak = NOW()
    //                     WHERE kasbon_id = '$id_kasbon';";
    // }
    // end

    // AKSI UNTUK JALANIN DATANYA
    $tolak = mysqli_multi_query($koneksi, "UPDATE kasbon SET
                                                                nilai_barang = '$nilai_barang',
                                                                nilai_jasa = '$nilai_jasa',
                                                                nilai_ppn = '$nilai_ppn',
                                                                nilai_pph = '$nilai_pph',
                                                                id_pph = '$id_pph',
                                                                harga_akhir = '$harga',
                                                                status_kasbon = '5',
                                                                app_pajak = NOW(),
                                                                komentar_mgr_finjkt = NULL
                                                WHERE id_kasbon = '$id_kasbon';

                                            -- $aksi_tolak;   
                                            -- $aksi_reapp; 
                                            ");

    if ($tolak) {
        header('Location: index.php?p=ditolak_kasbon&sp=tolak_purchasing');
    } else {
        echo 'error' . mysqli_error($koneksi);
    }
    // END AKSI JALANIN DATANYA
}
