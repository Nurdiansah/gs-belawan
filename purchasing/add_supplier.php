<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {

	$nm_supplier = $_POST['nm_supplier'];
	$pic_supplier = $_POST['pic_supplier'];
	$alamat_supplier = $_POST['alamat_supplier'];
	$no_telponsupplier = $_POST['no_telponsupplier'];
	$no_faxsupplier = $_POST['no_faxsupplier'];
	$email_supplier = $_POST['email_supplier'];
	$kategori_supplier = $_POST['kategori_supplier'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	// query log
	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Penambahan Supplier ');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "INSERT INTO supplier ( nm_supplier, pic_supplier, alamat_supplier , no_telponsupplier, no_faxsupplier, email_supplier, kategori_supplier) VALUES 
										( '$nm_supplier', '$pic_supplier', '$alamat_supplier', '$no_telponsupplier', '$no_faxsupplier', '$email_supplier', '$kategori_supplier');
			";

	$hasil = mysqli_query($koneksi, $query);
	if ($hasil) {
		header("location:index.php?p=supplier");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->