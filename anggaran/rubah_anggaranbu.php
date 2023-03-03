<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['rubah'])) {
	$kd_transaksi = $_POST['kd_transaksi'];
	$id_anggaran = $_POST['id_anggaran'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Melakukan Perubahan Kode anggaran: $kd_transaksi');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "UPDATE bkk SET id_anggaran = '$id_anggaran'  
                                            WHERE kd_transaksi ='$kd_transaksi' ";


	$hasil = mysqli_query($koneksi, $query);


	if ($hasil) {
		header("location:index.php?p=transaksi_bu");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->