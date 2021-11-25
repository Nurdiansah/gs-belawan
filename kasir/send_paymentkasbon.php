<?php

session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id_kasbon = $_POST['id_kasbon'];
	$penerima_dana = $_POST['penerima_dana'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$query1 = mysqli_query($koneksi, "UPDATE kasbon
										SET status_kasbon = 6 , waktu_penerima_dana = '$tanggal', penerima_dana = '$penerima_dana', nilai_pengajuan = harga_akhir
										WHERE id_kasbon = '$id_kasbon' ");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan pembayaran kasbon id: $id_kasbon');

									";
	mysqli_query($koneksi, $queryLog);


	if ($query1) {
		header("location:index.php?p=payment_kasbon&sp=pk_user");
	} else {
		echo "ada yang salah" . mysqli_error($koneksi);
	}
}
