<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id = $_POST['id'];
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


	$query = "UPDATE detail_biayaops SET nm_barang = '$nm_barang' , id_anggaran = '$id_anggaran' , merk = '$merk', 
                                            type = '$type', spesifikasi = '$spesifikasi', jumlah = '$jumlah', satuan = '$satuan', 
                                            keterangan = '$keterangan'  
                                            WHERE id ='$id' ";


	// move_uploaded_file($tmp,"file/pjsm/$Doc_pjsm");
	$hasil = mysqli_query($koneksi, $query);

	if ($hasil) {
		header("location:index.php?p=" . $_POST['url'] . "");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->