<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['update'])) {
    $id_so = $_POST['id_so'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    $query = "UPDATE so SET komentar = '$komentar', app_mgr_fin = NULL, status = '606'
                            WHERE id_so ='$id_so' ";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {

        setcookie('pesan', 'SR berhasil di Reject!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    } else {
        setcookie('pesan', 'SR gagal di Reject!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}
