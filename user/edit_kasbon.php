<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['edit'])) {
    $id_kasbon = $_POST['id'];
    $id_dbo = $_POST['id_dbo'];
    $id_anggaran = $_POST['id_anggaran'];
    $keterangan = $_POST['keterangan'];
    $harga_akhir = penghilangTitik($_POST['nominal']);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // UPDATE DBO
    $queryDbo = "UPDATE detail_biayaops SET id_anggaran = '$id_anggaran' ,keterangan = '$keterangan'
                                WHERE id ='$id_dbo' ";
    mysqli_query($koneksi, $queryDbo);

    // UPDATE
    $query = "UPDATE kasbon SET harga_akhir = '$harga_akhir'
                                WHERE id_kasbon ='$id_kasbon' ";
    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        setcookie('pesan', 'Kasbon Berhasil di edit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:index.php?p=buat_kasbon");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->