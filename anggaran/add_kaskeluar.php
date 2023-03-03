<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['simpan'])) {

	function Terbilang($nilai)
	{
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		if ($nilai == 0) {
			return "";
		} elseif ($nilai < 12 & $nilai != 0) {
			return "" . $huruf[$nilai];
		} elseif ($nilai < 20) {
			return Terbilang($nilai - 10) . " Belas ";
		} elseif ($nilai < 100) {
			return Terbilang($nilai / 10) . " Puluh " . Terbilang($nilai % 10);
		} elseif ($nilai < 200) {
			return " Seratus " . Terbilang($nilai - 100);
		} elseif ($nilai < 1000) {
			return Terbilang($nilai / 100) . " Ratus " . Terbilang($nilai % 100);
		} elseif ($nilai < 2000) {
			return " Seribu " . Terbilang($nilai - 1000);
		} elseif ($nilai < 1000000) {
			return Terbilang($nilai / 1000) . " Ribu " . Terbilang($nilai % 1000);
		} elseif ($nilai < 1000000000) {
			return Terbilang($nilai / 1000000) . " Juta " . Terbilang($nilai % 1000000);
		} elseif ($nilai < 1000000000000) {
			return Terbilang($nilai / 1000000000) . " Milyar " . Terbilang($nilai % 1000000000);
		} elseif ($nilai < 100000000000000) {
			return Terbilang($nilai / 1000000000000) . " Trilyun " . Terbilang($nilai % 1000000000000);
		} elseif ($nilai <= 100000000000000) {
			return "Maaf Tidak Dapat di Prose Karena Jumlah nilai Terlalu Besar ";
		}
	}


	$nm_vendor = $_POST['nm_vendor'];
	$tgl_bkk = $_POST['tgl_pengajuan'];
	$keterangan = $_POST['keterangan'];
	$nilai_bkk = $_POST['nilai_bkk'];
	$nilai_ppn = $_POST['nilai_ppn'];
	$bll_bkk = $_POST['bll_bkk'];
	$jml_bkka = $_POST['jml_bkk'];
	$jml_bkk = str_replace(".", "", $jml_bkka);
	$terbilang_bkk = Terbilang($jml_bkk);
	$bank_tujuan = $_POST['bank_tujuan'];
	$norek_tujuan = $_POST['norek_tujuan'];

	//baca lokasi file sementara dan nama file dari form (doc_ptw)\

	$lokasi_invoice = ($_FILES['invoice']['tmp_name']);
	$invoice = ($_FILES['invoice']['name']);

	// $folder_ptw="file/$Doc_ptw";

	move_uploaded_file($lokasi_invoice, "../file/$invoice");

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$id_divisi = $rowUser['id_divisi'];

	$query = "INSERT INTO bkk ( nm_vendor, tgl_pengajuan, terbilang_bkk, keterangan, nilai_bkk, bll_bkk, ppn_bkk, jml_bkk, bank_tujuan, norek_tujuan, id_pemohon, id_divisi, invoice, status_bkk) VALUES 
										( '$nm_vendor', '$tgl_bkk',  '$terbilang_bkk', '$keterangan', '$nilai_bkk', '$bll_bkk','$nilai_ppn','$jml_bkk', '$bank_tujuan', '$norek_tujuan', '$id_user', '$id_divisi', '$invoice', '1');
			";

	// move_uploaded_file($tmp,"file/pjsm/$Doc_pjsm");
	$hasil = mysqli_query($koneksi, $query);
	if ($hasil) {
		header("location:index.php?p=dashboard");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->