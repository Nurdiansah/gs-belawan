<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['delete'])) {

    $id_dsr = $_POST['id'];
    $sr_id = enkripRambo($_POST['sr_id']);

    // UPDATE
    $query = "DELETE FROM detail_sr  WHERE id_dsr ='$id_dsr' ";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {

        setcookie('pesan2', 'Data berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=" . $_POST['url'] . "&id=" . $sr_id);
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->