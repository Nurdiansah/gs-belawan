<?php

include "../fungsi/koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query =  mysqli_query($koneksi, "SELECT * 
                                        FROM detail_biayaops db
                                        JOIN anggaran a
                                            ON a.id_anggaran = db.id_anggaran
                                        JOIN kasbon ks
                                            ON db.kd_transaksi = ks.kd_transaksi
                                        WHERE id_kasbon = '$id'");

    $data = mysqli_fetch_assoc($query);

    $id_dbo = $data['id'];
    $id_kasbon = $data['id_kasbon'];
    $kd_transaksi = $data['kd_transaksi'];

    $ajukan = mysqli_multi_query($koneksi, "UPDATE detail_biayaops SET alasan_penolakan = NULL
                                            WHERE id = '$id_dbo';

                                            UPDATE kasbon SET status_kasbon = NULL, komentar = NULL
                                            WHERE id_kasbon = '$id_kasbon';
    
    ");

    if ($ajukan) {
        header("Location: index.php?p=ditolak_kasbon&sp=tolak_purchasing");
    } else {
        echo "Ada error cuii " . mysqli_error($koneksi);
    }
}
