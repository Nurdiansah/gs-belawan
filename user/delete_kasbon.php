<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['delete'])) {
    $id_kasbon = $_POST['id'];
    $id_dbo = $_POST['id_dbo'];
    $doc_pendukung = $_POST['doc_pendukung'];

    unlink("../file/doc_pendukung/$doc_pendukung");

    // DELETE DBO
    $queryDbo = "DELETE FROM detail_biayaops  WHERE id ='$id_dbo' ";
    mysqli_query($koneksi, $queryDbo);

    // DELETE Kasbon
    $query = "DELETE FROM kasbon  WHERE id_kasbon ='$id_kasbon' ";
    $hasil = mysqli_query($koneksi, $query);

    DelRealisasiSem($id_kasbon, "KBN");

    if ($hasil) {
        setcookie('pesan', 'Kasbon berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=" . $_POST['url'] . "");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->