<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    // Reject KASBON
    $query = "UPDATE kasbon SET komentar = '$komentar', status_kasbon = '101'
                            WHERE id_kasbon ='$id_kasbon' ";
    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        header("location: index.php?p=approval_kasbon");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}
