<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['setuju'])) {
    $id_kasbon = $_POST['id'];
    $vrf_pajak = $_POST['vrf_pajak'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // APPROVE KASBON

    // if ($vrf_pajak == 'bp') {
    // jika kasbon verifikasi sebelum pembayaran
    $query = "UPDATE kasbon SET  status_kasbon = '2', app_mgr_ga = '$tanggal', komentar_pajak = NULL
                WHERE id_kasbon ='$id_kasbon' ";
    $hasil = mysqli_query($koneksi, $query);
    // } else if ($vrf_pajak == 'as') {
    //     // jika kasbon verifikasi setelah lpj
    //     $query = "UPDATE kasbon SET  status_kasbon = '3', app_mgr_ga = '$tanggal'
    //             WHERE id_kasbon ='$id_kasbon' ";
    //     $hasil = mysqli_query($koneksi, $query);
    // }

    if ($hasil) {
        header("location:index.php?p=ditolak_kasbon&sp=tolak_user");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!--  -->