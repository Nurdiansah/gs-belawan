<?php

include "../fungsi/koneksi.php";

if (isset($_GET['id'])) {

    $kd_transaksi = $_GET['id'];

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
								('$tanggal', '$nama', 'Merevisi MR dan mengajukan kembali MR id: $kd_transaksi');

								";
    mysqli_query($koneksi, $queryLog);


    $query = mysqli_query($koneksi, "UPDATE biaya_ops 
									 SET status_biayaops=1, komentar = NULL
									 WHERE kd_transaksi ='$kd_transaksi' ");

    mysqli_query($koneksi, $query);

    if ($query) {
        header("location:index.php?p=proses_mr");
    } else {
        echo 'error' . mysqli_error($koneksi);
    }
}
