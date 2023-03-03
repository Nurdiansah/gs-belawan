<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
	$id = $_POST['id_bkk'];
	$free_approve = $_POST['free_approve'];

	$tanggal = date('Y-m-d');

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	$tanggal = dateNow();

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menyetujui Pengajuan Biaya Non OPS id: $id');

									";
	mysqli_query($koneksi, $queryLog);

	if ($free_approve == '1') {
		// jika pengajuan jenis free approve langsung ke kasir 
		$query1 = mysqli_query($koneksi, "UPDATE bkk SET status_bkk=9, app_managerga = '$tanggal', tgl_verifikasimanagerkeuangan = '$tanggal', tgl_verifikasidireksi = '$tanggal', tgl_verifikasidireksi2 = '$tanggal' WHERE id_bkk='$id' ");
	} else {
		# code...
		$query1 = mysqli_query($koneksi, "UPDATE bkk SET status_bkk=7, app_managerga = '$tanggal' WHERE id_bkk='$id' ");
	}



	if ($query1) {

		mysqli_commit($koneksi);

		setcookie('pesan', 'Biaya Umum berhasil di Approve!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'Biaya Umum gagal di Approve!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}

	header("location:index.php?p=approval_bno");
}
