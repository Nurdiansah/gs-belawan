<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id = $_POST['id'];
	$kd_transaksi = $_POST['kd_transaksi'];
	$nm_barang = $_POST['nm_barang'];
	$nm_barang = $_POST['nm_barang'];
	$id_anggaran = $_POST['id_anggaran'];
	$merk = $_POST['merk'];
	$type = $_POST['type'];
	$spesifikasi = $_POST['spesifikasi'];
	$jumlah = $_POST['jumlah'];
	$satuan = $_POST['satuan'];
	$keterangan = $_POST['keterangan'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	if ($_FILES['doc_pendukung']['name'] == '') {
		$doc_pendukung = $_POST['doc_pendukung_lama'];
	} else {
		$doc_pendukung_lama =  $_POST['doc_pendukung_lama'];
		unlink("../file/foto/$doc_pendukung_lama");
		$path = $_FILES['doc_pendukung']['tmp_name'];
		$ekstensi = pathinfo($_FILES['doc_pendukung']['name'], PATHINFO_EXTENSION);
		$doc_pendukung = $id . "-" . time() . "-foto-barang." . $ekstensi;
		move_uploaded_file($path, "../file/foto/" . $doc_pendukung);
	}

	$query = "UPDATE detail_biayaops SET nm_barang = '$nm_barang' , id_anggaran = '$id_anggaran' , merk = '$merk', 
                                        type = '$type', spesifikasi = '$spesifikasi', jumlah = '$jumlah', satuan = '$satuan', 
                                        keterangan = '$keterangan', foto_item = '$doc_pendukung'
                WHERE id ='$id' ";


	// move_uploaded_file($tmp,"file/pjsm/$Doc_pjsm");
	$hasil = mysqli_query($koneksi, $query);


	if ($hasil) {
		header("location:index.php?p=detail_tolakmr&id=$kd_transaksi");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->