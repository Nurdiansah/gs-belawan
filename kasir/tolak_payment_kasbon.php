<?php

include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $komentar = $_POST['komentar'];

    $query = mysqli_query($koneksi, "UPDATE kasbon SET status_kasbon = '606', komentar = '$komentar'
                                        WHERE id_kasbon = '$id_kasbon'");

    if ($query) {
        header("Location: index.php?p=" . $_POST['url'] . "");
    } else {
        echo "Kayanya ada yg error " . mysqli_error($koneksi);
    }
}
