<?php

include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id = $_POST['id'];
    $id_kasbon = $_POST['id_kasbon'];
    $kd_transaksi = $_POST['kd_transaksi'];
    $komentar = $_POST['komentar'];

    $queryTolak = mysqli_multi_query($koneksi, "UPDATE kasbon SET status_kasbon = '0', komentar = '$komentar'
                                                WHERE id_kasbon = '$id_kasbon';

                                                -- UPDATE sub_dbo SET sub_unitprice = NULL, total_price = NULL
                                                -- WHERE id_dbo = '$id';

                                                -- DELETE FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon';

                                                -- DELETE FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon';
    ");

    if ($queryTolak) {
        header('Location: index.php?p=' . $_POST['url'] . '');
    } else {
        echo "Ada yg salah "  . mysqli_error($koneksi);
    }
}
