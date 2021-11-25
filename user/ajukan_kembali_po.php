<?php

include "../fungsi/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query =  mysqli_query($koneksi, "SELECT * 
                                        FROM detail_biayaops db
                                        JOIN anggaran a
                                            ON a.id_anggaran = db.id_anggaran
                                        JOIN po po
                                            ON db.kd_transaksi = po.kd_transaksi
                                        WHERE id = '$id'");

    $data = mysqli_fetch_assoc($query);

    $id_dbo = $data['id'];
    $id_po = $data['id_po'];
    $kd_transaksi = $data['kd_transaksi'];

    $ajukan = mysqli_multi_query($koneksi, "UPDATE detail_biayaops SET alasan_penolakan = NULL
                                            WHERE id = '$id_dbo';

                                            UPDATE po SET status_po = NULL
                                            WHERE id_po = '$id_po';
    
    ");

    if ($ajukan) {
        header("Location: index.php?p=ditolak_po");
    } else {
        echo "Ada error cuii " . mysqli_error($koneksi);
    }
}
