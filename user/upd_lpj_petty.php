<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id_pettycash = $_POST['id_pettycash'];
	$harga = $_POST['hargaawal'];
	$nominal_pengembalian = str_replace(".", "", $_POST['nominal_pengembalian']);

	$hargaAkhir = $harga - $nominal_pengembalian;

	$lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
	$doc_lpj = ($_FILES['doc_lpj']['name']);

	// 
	$nama_doc = $id_pettycash . "-lpj-pettycash";
	move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Melakukan LPJ Pettycash id : $id_pettycash');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "UPDATE transaksi_pettycash SET nominal_pengembalian_pettycash = '$nominal_pengembalian' , doc_lpj_pettycash = '$nama_doc' , 
									status_pettycash = '4', lpj_user = '$tanggal' , grand_total_pettycash = '$hargaAkhir'
                                    WHERE id_pettycash ='$id_pettycash' ";

	$hasil = mysqli_query($koneksi, $query);

	if ($hasil) {
		header("location:index.php?p=buat_petty");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->