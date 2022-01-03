<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = dekripRambo($_GET['id']);

    $query =  mysqli_query($koneksi, "SELECT * FROM po WHERE id_po = '$id'");
    $data = mysqli_fetch_assoc($query);

    $id_po = $data['id_po'];
    $id_dbo = $data['id_dbo'];
    $kd_transaksi = $data['kd_transaksi'];

    echo $id_po . "<br>" . $id_dbo;

    $ajukan = mysqli_multi_query($koneksi, "UPDATE detail_biayaops SET alasan_penolakan = NULL
                                            WHERE id = '$id_dbo';

                                            UPDATE po SET status_po = NULL
                                            WHERE id_po = '$id_po';
    
    ");

    // echo mysqli_error($koneksi);
    // die;

    if ($ajukan) {
        header("Location: index.php?p=ditolak_po");
    } else {
        echo "Ada error cuii " . mysqli_error($koneksi);
    }
}
