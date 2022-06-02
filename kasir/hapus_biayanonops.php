<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = dekripRambo($_GET['id']);
    $inv = dekripRambo($_GET['inv']);

    $hapusBNO = mysqli_query($koneksi, "DELETE FROM bkk WHERE id_bkk = '$id'");
    unlink("../file/$inv");

    if ($hapusBNO) {
        header('Location: index.php?p=' . dekripRambo($_GET['pg']) . '');
    }
}
