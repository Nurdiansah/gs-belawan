<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id_po = $_POST['id_po'];
	$tgl_tempo1 = $_POST['tgl_tempo1'];
	$persentase_pembayaran1 = $_POST['persentase_pembayaran1'];
	$nominal_pembayaran1 = str_replace(".", "", $_POST['nominal_pembayaran1']);
	$metode_pembayaran = $_POST['metode_pembayaran'];
	$tanggal = dateNow();

	// pertambahan tanggal
	$date = date_create("$tanggal");
	$tgl_tempo = date_format(date_add($date, date_interval_create_from_date_string("$tgl_tempo1 days")), "Y-m-d");

	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);

	// query update po
	$updatePo = mysqli_query($koneksi, "UPDATE po SET app_kasir = '$tanggal' , regulasi_tempo1 = '$tgl_tempo1', tgl_tempo1 = '$tgl_tempo', 
			 			persentase_pembayaran1 = '$persentase_pembayaran1' , nominal_pembayaran1 = '$nominal_pembayaran1',
						status_po = '7'
						WHERE id_po ='$id_po' ");

	// query log
	$insertTagihan = mysqli_query($koneksi, "INSERT INTO tagihan_po (po_id, regulasi_tempo, persentase , nominal, 
																	tgl_buat, tgl_tempo, metode_pembayaran,status_tagihan) VALUES
																	('$id_po', '$tgl_tempo1', '$persentase_pembayaran1' , '$nominal_pembayaran1', 
																	'$tanggal', '$tgl_tempo', '$metode_pembayaran' ,'1' ); ");

	if ($updatePo && $insertTagihan) {
		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'PO berhasil di verifikasi!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'PO gagal di verifikasi!', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=verifikasi_po");
}

?>
<!-- pindah -->
<!--  -->