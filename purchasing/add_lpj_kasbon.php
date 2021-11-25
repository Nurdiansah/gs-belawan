<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id_kasbon = $_POST['id_kasbon'];
	$harga = $_POST['harga'];
	$nominal_pengembalian = str_replace(".", "", $_POST['nominal_pengembalian']);
	$aksi = $_POST['aksi'];

	if ($aksi == "pengembalian") {
		$hargaAkhir = $harga - $nominal_pengembalian;
		$field = $aksi . " = '" . $nominal_pengembalian . "', ";
	} elseif ($aksi == "penambahan") {
		$hargaAkhir = $harga + $nominal_pengembalian;
		$field = $aksi . " = '" . $nominal_pengembalian . "', ";
	} else {
		$hargaAkhir = $harga;
		$field = "";
	}

	// echo $hargaAkhir . "<br>" . $field;
	// die;

	$lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
	$doc_lpj = ($_FILES['doc_lpj']['name']);
	$ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);

	$nama_doc = $id_kasbon . "-doc-lpj-kasbon." . $ekstensi;
	move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Melakukan LPJ Kasbon id : $id_kasbon');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "UPDATE kasbon SET doc_lpj = '$nama_doc', $field
									status_kasbon = '7', waktu_lpj = '$tanggal' , harga_akhir = '$hargaAkhir', komentar = NULL
                                    WHERE id_kasbon ='$id_kasbon' ";

	$hasil = mysqli_query($koneksi, $query);

	if ($hasil) {
		header("location:index.php?p=lpj_kasbon&sp=lpj_kmr");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->