<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['kirim'])) {
    $id = $_POST['id'];

    $query = mysqli_query($koneksi, "UPDATE sr SET status = '1',
                                        komentar = NULL,
                                        updated_at = NOW()
                                    WHERE id_sr = '$id'");

    if ($query) {
        setcookie('pesan', 'SR berhasil di ajukan kembali!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=ditolak_sr");
    }
}
