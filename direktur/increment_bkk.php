<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

// tanggal
date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");

$queryB = mysqli_query($koneksi, "SELECT * FROM bkk_final WHERE month(created_on_bkk)='5' AND status_bkk='4' ORDER BY created_on_bkk ASC ");
// $data = mysqli_fetch_assoc($queryB);

$no = 0001;

if (mysqli_num_rows($queryB)) {
    while ($data = mysqli_fetch_assoc($queryB)) :

        $id = $data['id'];
        $nomorAwal = "" . str_pad($no, 4, "0", STR_PAD_LEFT);
        // print_r($id);
        // die;

        // BEGIN/START TRANSACTION        
        // mysqli_begin_transaction($koneksi);


        $nomorBkk = $nomorAwal . '/GS/V/2021';

        // UPDATE BKK
        $query = mysqli_query($koneksi, "UPDATE bkk_final
        									SET nomor = '$no', no_bkk = '$nomorBkk'												
        									WHERE id = '$id' ");

        if ($query) {

            echo '</br> success....';
        } else {
            echo '</br> id bkk : ' . $id . ' Gagal di update';
            echo 'karena ' . mysqli_error($koneksi);
        }

        $no++;
    endwhile;
}
die;



if ($query1) {
    # jika semua query berhasil di jalankan
    mysqli_commit($koneksi);

    setcookie('pesan', 'BKK berhasil di Approved!', time() + (3), '/');
    setcookie('warna', 'alert-success', time() + (3), '/');
} else {
    #jika ada query yang gagal
    mysqli_rollback($koneksi);

    setcookie('pesan', 'BKK gagal di Approved!', time() + (3), '/');
    setcookie('warna', 'alert-danger', time() + (3), '/');
}
header("location:index.php?p=verifikasi_bkk");
