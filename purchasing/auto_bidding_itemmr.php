<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id = $_POST['id'];
	$kd_transaksi = $_POST['kd_transaksi'];
	$harga = $_POST['harga'];
	$id_supplier = $_POST['id_supplier'];
	$kdTransaksi = $_POST['kd_transaksi'];
	$nm_barang = $_POST['nm_barang'];

	$lokasi_doc_penawaran = ($_FILES['doc_penawaran']['tmp_name']);
	$invoice = ($_FILES['doc_penawaran']['name']);
	$ekstensi = pathinfo($invoice, PATHINFO_EXTENSION);

	$namabaru = $id . "-doc-penawaran." . $ekstensi;

	// Jika file yang di upload bukan pdf
	if ($ekstensi != 'pdf') {
		setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');

		header("location:index.php?p=bidding_itemmr&id=$id");
	} else {
		// Upload pdf
		move_uploaded_file($lokasi_doc_penawaran, "../file/doc_penawaran/" . $namabaru);

		date_default_timezone_set('Asia/Jakarta');
		$tanggal = date("Y-m-d H:i:s");


		$query = "UPDATE detail_biayaops SET harga_estimasi = '$harga' , id_supplier = '$id_supplier' , doc_penawaran = '$namabaru'
                                            WHERE id ='$id' ";


		$hasil = mysqli_query($koneksi, $query);

		if ($hasil) {
			setcookie('pesan', 'Document Penawaran berhasil di upload!', time() + (3), '/');
			setcookie('warna', 'alert-success', time() + (3), '/');

			header("location:index.php?p=verifikasi_dmr&id=$kd_transaksi");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}
	}
}
