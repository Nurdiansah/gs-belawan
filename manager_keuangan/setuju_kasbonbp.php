<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['setuju'])) {
    $id_kasbon = $_POST['id'];
    $vrf_pajak = $_POST['vrf_pajak'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // APPROVE KASBON

    if ($vrf_pajak == 'bp') {
        // jika kasbon verifikasi sebelum pembayaran
        $query = "UPDATE kasbon SET  status_kasbon = '3', app_supervisor = '$tanggal'
                WHERE id_kasbon ='$id_kasbon' ";
        $hasil = mysqli_query($koneksi, $query);
    } else if ($vrf_pajak == 'as') {
        // jika kasbon verifikasi setelah lpj
        $query = "UPDATE kasbon SET  status_kasbon = '4', app_supervisor = '$tanggal'
                WHERE id_kasbon ='$id_kasbon' ";
        $hasil = mysqli_query($koneksi, $query);
    }

    if ($hasil) {
        header("location:index.php?p=approval_kasbon");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!--  -->