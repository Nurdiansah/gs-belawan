<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    $query = "UPDATE kasbon SET komentar = '$komentar', status_kasbon = '505', app_pajak = NULL, app_mgr_ga = NULL
                WHERE id_kasbon ='$id' ";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {

        setcookie('pesan', 'Kasbon SR berhasil di Reject!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    } else {
        setcookie('pesan', 'Kasbon SR gagal di Reject!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}
