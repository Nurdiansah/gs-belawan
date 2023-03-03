<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$total = str_replace(".", "", $_POST['nominal']);
	$nilai_barang	 = penghilangTitik($_POST['nilai_barang']);
	$nilai_jasa	 = penghilangTitik($_POST['nilai_jasa']);
	$nilai_ppn	 = penghilangTitik($_POST['nilai_ppn']);
	$nilai_pph	 = penghilangTitik($_POST['nilai_pph']);
	$id_pph	 = penghilangTitik($_POST['id_pph']);

	if ($nilai_barang == '0' && $nilai_jasa == '0') {
		$nilai_barang = $total;
	}

	$keterangan	 = $_POST['keterangan'];
	$remarks	 = $_POST['remarks'];
	$id_anggaran = $_POST['id_anggaran'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	// query user
	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];

	$lokasi_doc = ($_FILES['doc_pendukung']['tmp_name']);
	$doc_pendukung = ($_FILES['doc_pendukung']['name']);
	$ekstensi = pathinfo($doc_pendukung, PATHINFO_EXTENSION);

	//deklarasi tanggal
	$bulan    = date('n');
	$romawi    = getRomawi($bulan);
	$tahun     = date('Y');
	$nomor     = "/GS/" . $romawi . "/" . $tahun;

	$queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE month(created_on_bkk)='$bulan' ");

	$nomorMax = mysqli_fetch_array($queryNomor);
	if ($nomorMax) {

		$nilaikode = substr($nomorMax[0], 2);
		$kode = (int) $nilaikode;

		//setiap kode ditambah 1
		$kode = $kode + 1;
		$nomorAkhir = "" . str_pad($kode, 4, "0", STR_PAD_LEFT);
	} else {
		$nomorAkhir = "0001";
	}

	$nomorBkk = $nomorAkhir . $nomor;

	// Jika ada file yang di upload
	if (!empty($doc_pendukung)) {

		// Jika file yang di upload bukan pdf
		if ($ekstensi != 'pdf') {
			# code...
			setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
			setcookie('warna', 'alert-danger', time() + (3), '/');

			header("location:index.php?p=biaya_khusus");
		} else {
			# code...
			$namabaru = time() . "-doc-pendukung-bk." . $ekstensi;
			move_uploaded_file($lokasi_doc, "../file/doc_pendukung/" . $namabaru);
			//query di kualifikasikan ke bkk final
			$queryBkkfinal = "INSERT INTO bkk_final (id_anggaran, pengajuan, created_on_bkk, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, remarks, doc_pendukung, status_bkk) VALUES
													('$id_anggaran', 'BIAYA KHUSUS', '$tanggal', '$nilai_barang', '$nilai_jasa','$nilai_ppn', '$id_pph','$nilai_pph', '$nilai_barang', '$keterangan', '$remarks', '$namabaru', '0');
													";
			$hasil = mysqli_query($koneksi, $queryBkkfinal);

			if ($hasil) {
				header("location:index.php?p=biaya_khusus");
			} else {
				die("ada kesalahan : " . mysqli_error($koneksi));
			}
		}
	} else {
		//query di kualifikasikan ke bkk final
		$queryBkkfinal = "INSERT INTO bkk_final (id_anggaran, pengajuan, created_on_bkk, nilai_barang, nominal, keterangan, remarks,status_bkk) VALUES
										('$id_anggaran', 'BIAYA KHUSUS', '$tanggal', '$nilai_barang', '$nilai_barang', '$keterangan', '$remarks','0');
										";

		$hasil = mysqli_query($koneksi, $queryBkkfinal);

		if ($hasil) {
			header("location:index.php?p=biaya_khusus");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}
	}
}

?>
<!-- pindah -->
<!--  -->