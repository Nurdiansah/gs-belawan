<?php

session_start();
session_destroy();
include "fungsi/koneksi.php";
// log
$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$nama = $rowUser['nama'];

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");

$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
								('$tanggal', '$nama', 'logout');

								";
mysqli_query($koneksi, $queryLog);

// unset($_SESSION);
unset($_COOKIE);
unset($_SESSION['login_blw']);
unset($_SESSION['username_blw']);
unset($_SESSION['level_blw']);

header("location:index.php");
