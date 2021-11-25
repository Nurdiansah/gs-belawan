<?php

session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {

	$id_bkk = $_POST['id_bkk'];
	$komentar = $_POST['komentar'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
								('$tanggal', '$nama', 'Menolak Pengajuan Biaya Non OPS id: $id_bkk');

								";
	mysqli_query($koneksi, $queryLog);

	// start cek 
	$cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_bkk WHERE id_bkk = '$id_bkk'");
	$dataCek = mysqli_fetch_assoc($cekReapp);

	if ($dataCek['alasan_reapprove_mgr'] == NULL) {
		mysqli_query($koneksi, "DELETE FROM reapprove_bkk WHERE id_bkk = '$id_bkk'");
	}
	// ngapus ditabel reapprove_bkk klo di mgr null

	$query = mysqli_query($koneksi, "UPDATE bkk SET komentar = '$komentar', 
													tgl_verifikasimanager = NULL ,
													status_bkk = '101'
                                     WHERE id_bkk ='$id_bkk' ");
	if ($query) {
		setcookie('pesan', 'Berhasil di Reject!', time() + (3), '/');
		setcookie('warna', 'alert-warning', time() + (3), '/');

		header("location:index.php?p=approval_biayanonops");
	} else {
		echo 'error' . mysqli_error($koneksi);
	}
}
