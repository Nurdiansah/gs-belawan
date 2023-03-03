<?php

session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
    $id = $_POST['id'];

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menyetujui Pengajuan Pettycash id: $id');

									";
    mysqli_query($koneksi, $queryLog);

    $query1 = mysqli_query($koneksi, "UPDATE transaksi_pettycash SET status_pettycash=2, app_mgr ='$tanggal' WHERE id_pettycash='$id' ");



    if ($query1) {
        header("location:index.php?p=approval_pettycash");
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
}
