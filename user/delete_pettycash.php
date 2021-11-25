<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['delete'])) {
    $id_pettycash = $_POST['id'];

    // UPDATE
    $query = "DELETE FROM transaksi_pettycash  WHERE id_pettycash ='$id_pettycash' ";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {

        setcookie('pesan', 'Pettycash Berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=buat_petty");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->