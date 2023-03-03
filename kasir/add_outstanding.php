<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id_po = $_POST['id_po'];
	$tgl_tempo2 = $_POST['tgl_tempo1'];
	$persentase_pembayaran2 = 100 - $_POST['persentase_pembayaran1'];
	$nominal_pembayaran2 = str_replace(".", "", $_POST['nominal_pembayaran2']);
	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	// pertambahan tanggal
	$date = date_create("$tanggal");
	$tgl_tempo = date_format(date_add($date, date_interval_create_from_date_string("$tgl_tempo2 days")), "Y-m-d");

	// query user 

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	// query log
	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
											('$tanggal', '$nama', 'Verifikasi Outstanding PO : $id_po' );

											";
	mysqli_query($koneksi, $queryLog);


	// query update po
	$query = "UPDATE po SET regulasi_tempo2 = '$tgl_tempo2', tgl_tempo2 = '$tgl_tempo', 
			 						persentase_pembayaran2 = '$persentase_pembayaran2' , nominal_pembayaran2 = '$nominal_pembayaran2',
									status_po = '12'
								  WHERE id_po ='$id_po' ";

	$hasil = mysqli_query($koneksi, $query);

	if ($hasil) {
		header("location:index.php?p=outstanding_po");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->