<?php

session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {

	$kd_transaksi = $_POST['kd_transaksi'];
	$penerima_dana = $_POST['penerima_dana'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
								('$tanggal', '$nama', 'Menolak MR id: $kd_transaksi');

								";
	mysqli_query($koneksi, $queryLog);


	$query = mysqli_query($koneksi, "UPDATE biaya_ops 
									 SET penerima_dana='$penerima_dana', status_biayaops=9, waktu_penerima_dana = '$tanggal'
									 WHERE kd_transaksi ='$kd_transaksi' ");

	mysqli_query($koneksi, $query);

	if ($query) {
		header("location:index.php?p=payment_kasbon");
	} else {
		echo 'error' . mysqli_error($koneksi);
	}
}
