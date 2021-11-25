<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $komentar = $_POST['komentar'];

    // Reject KASBON
    $query = "UPDATE kasbon SET komentar = '$komentar', status_kasbon = '101'
                            WHERE id_kasbon ='$id_kasbon' ";
    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        header("location:index.php?p=" . $_POST['url'] . "");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!--  -->