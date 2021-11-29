<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['setuju'])) {
    $id_kasbon = $_POST['id'];
    $vrf_pajak = $_POST['vrf_pajak'];
    $komentar = $_POST['komentar'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // cek tabel tolak_kasbon, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    // $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'");
    // $dataTolak = mysqli_fetch_assoc($cekTolak);

    // if ($dataTolak['alasan_tolak_mgrfin'] == NULL && $dataTolak['alasan_tolak_direktur'] == NULL) {
    //     $aksi_tolak = "DELETE FROM tolak_kasbon WHERE id_tolak = '$id_kasbon'";
    // } else {
    //     $aksi_tolak = "UPDATE tolak_kasbon SET alasan_tolak_mgrfin = NULL, waktu_tolak_mgrfin = NULL
    //                 WHERE kasbon_id = '$id_kasbon'";
    // }
    // selesai cek tabel tolak_kasbon


    // cek ditabel reapprove_kasbon, jika udh ada isinya dari pengajuan tsb maka hanya update saja
    // $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
    // $totalReapp = mysqli_num_rows($cekReapp);

    // if ($totalReapp == 0) {
    //     $aksi_reapp = "INSERT INTO reapprove_kasbon (kasbon_id, alasan_reapprove_mgr, waktu_reapprove_mgr) VALUES
    //                 ('$id_kasbon', '$komentar', NOW());";
    // } else {
    //     $aksi_reapp = "UPDATE reapprove_kasbon SET alasan_reapprove_mgr = '$komentar', waktu_reapprove_mgr = NOW()
    //                 WHERE kasbon_id = '$id_kasbon';";
    // }
    // end

    // REAPPROVE KASBON
    // if ($vrf_pajak == 'bp') {
    // jika kasbon verifikasi sebelum pembayaran
    //     $query = "UPDATE kasbon SET  status_kasbon = '2', app_manager = '$tanggal'
    //             WHERE id_kasbon ='$id_kasbon'";
    // } else if ($vrf_pajak == 'as') {
    // jika kasbon verifikasi setelah lpj
    $query = "UPDATE kasbon SET  status_kasbon = '3', app_manager = '$tanggal', komentar_mgr_fin = NULL
                WHERE id_kasbon ='$id_kasbon'";
    // }


    $hasil = mysqli_multi_query($koneksi, "DELETE FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon';
                                            $query;
                                            -- $aksi_reapp;
                                            ");

    if ($hasil) {
        header("location: index.php?p=ditolak_kasbon&sp=tolak_user");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!--  -->