<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id = $_POST['id'];

    $update = mysqli_query($koneksi, "UPDATE kasbon SET status_kasbon = NULL, komentar = NULL WHERE id_kasbon = '$id'");

    if ($update) {

        setcookie('pesan', 'Kasbon SR berhasil di Submit kembali!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Kasbon SR gagal di Submit kembali!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}
