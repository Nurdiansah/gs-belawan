<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // UPDATE
    $query = "DELETE FROM bkk_final  WHERE id ='$id' ";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {

        setcookie('pesan', 'Biaya khusus berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=biaya_khusus");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->