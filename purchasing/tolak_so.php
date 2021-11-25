<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['update'])) {
    $id_so = $_POST['id_so'];
    $komentar = $_POST['komentar'];

    $query = "UPDATE so SET komentar = '$komentar', app_mgr = NULL, status = '202'
                            WHERE id_so ='$id_so' ";
    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        header("location:index.php?p=" . $_POST['url'] . "");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}
