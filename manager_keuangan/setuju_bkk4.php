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

    $query1 = mysqli_query($koneksi, "UPDATE bkk_final
										  SET status_bkk = 2 , v_mgr_finance = '$tanggal' 
										  WHERE id= '$id' ");

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Manager Finance Menyetujui BKK: $id');

									";
    mysqli_query($koneksi, $queryLog);


    if ($query1) {
        header("location:index.php?p=verifikasi_bkk");
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
}
