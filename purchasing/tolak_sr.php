<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['update'])) {
    $id_sr = $_POST['id_sr'];
    $komentar = $_POST['komentar'];

    $query = "UPDATE sr SET komentar = '$komentar', app_mgr = NULL, status = '202'
                            WHERE id_sr ='$id_sr' ";
    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        header("location:index.php?p=" . $_POST['url'] . "");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}
