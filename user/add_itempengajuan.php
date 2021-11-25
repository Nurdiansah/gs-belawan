<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id_divisi = $_POST['id_divisi'];
	$nm_barang = $_POST['nm_barang'];
	$nm_barang = $_POST['nm_barang'];
	$id_anggaran = $_POST['id_anggaran'];
	$merk = $_POST['merk'];
	$type = $_POST['type'];
	$spesifikasi = $_POST['spesifikasi'];
	$jumlah = $_POST['jumlah'];
	$satuan = $_POST['satuan'];
	$keterangan = $_POST['keterangan'];

	// 
	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");
	$tanggal2 = date("Y-m-d H.i.s");


	//baca lokasi file sementara dan nama file dari form (doc_ptw)		
	$lokasi_foto = ($_FILES['foto']['tmp_name']);
	$foto = ($_FILES['foto']['name']);
	$ekstensi = pathinfo($foto, PATHINFO_EXTENSION);
	$namabaru = $id_divisi . "-" . time() . "-foto-barang." . $ekstensi;

	// Jika file yang di upload bukan pdf
	if ($ekstensi != 'pdf') {
		setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');

		header("location:index.php?p=buat_mr");
	} else {

		move_uploaded_file($lokasi_foto, "../file/foto/" . $namabaru);
		// Insert ke detail biaya ops

		$query = "INSERT INTO detail_biayaops ( id_divisi, nm_barang, id_anggaran,merk, type, spesifikasi, jumlah, satuan, keterangan, foto_item) VALUES 
						( '$id_divisi', '$nm_barang', '$id_anggaran', '$merk', '$type', '$spesifikasi', '$jumlah', '$satuan', '$keterangan', '$namabaru' );
						";
		mysqli_query($koneksi, $query);


		$cekId = mysqli_query($koneksi, "SELECT MAX(id) AS id from detail_biayaops ");
		$rowDbo = mysqli_fetch_array($cekId);
		$id_dbo = $rowDbo['id'];

		// 
		$querySub = "INSERT INTO sub_dbo ( id_dbo, sub_deskripsi, sub_qty, sub_unit) VALUES 
									( '$id_dbo','$nm_barang',  '$jumlah', '$satuan');
									";

		$hasil =  mysqli_query($koneksi, $querySub);


		// $hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			setcookie('pesan', 'Item Berhasil di buat!', time() + (3), '/');
			setcookie('warna', 'alert-success', time() + (3), '/');

			header("location:index.php?p=buat_mr");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}
	}
}

?>
<!-- pindah -->
<!--  -->