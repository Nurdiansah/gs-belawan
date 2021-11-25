<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id = $_POST['id'];

    if ($_POST['status'] == "0") {
        $nilai = "7";
    } else {
        $nilai = "1";
    }

    $query = "UPDATE kasbon SET  status_kasbon = '$nilai', komentar = NULL
                WHERE id_kasbon = '$id'";
    $update = mysqli_query($koneksi, $query);

    if ($update) {

        setcookie('pesan', 'SR berhasil di Submit kembali!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'SR gagal di Submit kembali!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}
