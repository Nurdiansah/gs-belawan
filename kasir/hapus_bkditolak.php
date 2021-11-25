<?php

include "../fungsi/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $hapusBK = mysqli_multi_query($koneksi, "DELETE FROM bkk_final WHERE id = '$id';
                                             DELETE FROM tolak_bkk_final WHERE id_bkk_final = '$id';
                                             DELETE FROM reapprove_bkk_final WHERE id_bkk_final = '$id';
    ");

    if ($hapusBK) {
        header("Location: index.php?p=" . $_GET['pg'] . "");
    }
}
