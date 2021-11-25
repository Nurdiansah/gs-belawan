<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_POST['id'];

$udpate = mysqli_query($koneksi, "UPDATE kasbon
                                    SET status_kasbon = 4, app_mgr_finance = NOW(), komentar = NULL
                                    WHERE id_kasbon = '$id' ");

if ($queue && $query1) {

    setcookie('pesan', 'Kasbon berhasil di Approve kembali!', time() + (3), '/');
    setcookie('warna', 'alert-success', time() + (3), '/');
} else {
    setcookie('pesan', 'Kasbon gagal di Approve kembali!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
    setcookie('warna', 'alert-danger', time() + (3), '/');
}
header("location:index.php?p=ditolak_kasbon&sp=tolak_sr");
