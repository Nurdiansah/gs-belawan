<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {

    $id = dekripRambo($_GET['id']);

    // UPDATE
    $query = "DELETE FROM sr WHERE id_sr ='$id' ";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {

        setcookie('pesan', 'Data berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=buat_sr");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->