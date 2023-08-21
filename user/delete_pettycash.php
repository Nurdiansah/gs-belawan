<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['delete'])) {
    $id_pettycash = $_POST['id'];
    $doc_lpj = $_POST['doc_lpj'];

    unlink("../file/doc_lpj/$doc_lpj");

    // UPDATE
    $query = "DELETE FROM transaksi_pettycash  WHERE id_pettycash ='$id_pettycash' ";
    $hasil = mysqli_query($koneksi, $query);

    $delRsem = DelRealisasiSem($id_pettycash, "PCS");

    if ($hasil) {

        setcookie('pesan', 'Pettycash Berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=" . $_POST['url']);
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->