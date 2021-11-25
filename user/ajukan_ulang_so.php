<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['kirim'])) {
    $id = $_POST['id'];

    $query = mysqli_query($koneksi, "UPDATE so SET status = NULL, komentar = NULL
                                        WHERE id_so = '$id'");

    if ($query) {
        setcookie('pesan', 'SO berhasil di ajukan kembali!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=ditolak_sr");
    }
}
