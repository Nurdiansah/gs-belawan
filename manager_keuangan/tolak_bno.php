<?php

session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {

	$id_bkk = $_POST['id_bkk'];
	$id_manager = $_POST['id_manager'];
	$komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];
	$id_user = $rowUser['id_user'];

	if ($id_user == $id_manager) {
		$status_bkk  = '101';
	} else {
		$status_bkk  = '303';
	}


	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
								('$tanggal', '$nama', 'Menolak Pengajuan Biaya Non OPS id: $id_bkk');

								";
	mysqli_query($koneksi, $queryLog);


	$query = mysqli_query($koneksi, "UPDATE bkk SET komentar_mgrfin = '$komentar', 
													tgl_verifikasimanagerkeuangan = NULL,
													status_bkk = '$status_bkk'
                                     WHERE id_bkk ='$id_bkk'");
	if ($query) {
		setcookie('pesan', 'Berhasil di Reject !', time() + (3), '/');
		setcookie('warna', 'alert-warning', time() + (3), '/');

		header("location:index.php?p=" . $_POST['url'] . "");
	} else {
		echo 'error' . mysqli_error($koneksi);
	}
}
