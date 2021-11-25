<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id = $_POST['id'];

    $query = "UPDATE so SET  status = '1', app_purchasing = NOW(), komentar = NULL
                WHERE id_so ='$id' ";
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
