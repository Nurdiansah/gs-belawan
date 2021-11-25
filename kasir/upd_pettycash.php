<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id_pettycash = $_POST['id_pettycash'];
	$penerima_dana = $_POST['penerima_dana'];
	$nominal = $_POST['nominal'];
	$id_anggaran = $_POST['id_anggaran'];
	$from = $_POST['from'];
	$total_pettycash = $_POST['total_pettycash'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");
	$bulan    = date('n');

	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);


	if ($from == 'user') {
		$query1 = mysqli_query($koneksi, "UPDATE transaksi_pettycash
										  SET status_pettycash= 5 , pym_ksr = '$tanggal', vrf_ksr = '$tanggal', penerima_dana = '$penerima_dana' 
										  WHERE id_pettycash = '$id_pettycash' ");

		// query realisasi
		$fieldRealisasi = fieldRealisasi($bulan);

		$queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas from anggaran WHERE id_anggaran = '$id_anggaran' ");
		$rowJA = mysqli_fetch_assoc($queryJumlahAwal);
		$jml_akhir = $rowJA['bulan'] + $nominal;
		$jumlah_realisasi = $rowJA['jumlah_realisasi'] + $nominal;
		$qty = $rowJA['realisasi_kuantitas'] + 1;

		$queryRealisasi = "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir' , jumlah_realisasi = '$jumlah_realisasi' , realisasi_kuantitas = '$qty'
										WHERE id_anggaran ='$id_anggaran' ";
		$realisasi = mysqli_query($koneksi, $queryRealisasi);
	} else {
		$query1 = mysqli_query($koneksi, "UPDATE transaksi_pettycash
										  SET status_pettycash= 3 , pym_ksr = '$tanggal', vrf_ksr = '$tanggal', penerima_dana = '$penerima_dana', nominal_pengajuan = '$total_pettycash' 
										  WHERE id_pettycash = '$id_pettycash' ");

		$realisasi = 'Berhasil';
	}

	if ($query1 && $realisasi) {
		mysqli_commit($koneksi);

		header("location:index.php?p=payment_pettycash");
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		echo "ada yang salah" . mysqli_error($koneksi);
	}
}
