<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
	$id = $_GET['id'];


	$queryBkk = mysqli_query($koneksi, "SELECT * from bkk_final WHERE id=$id");
	$dataBkk = mysqli_fetch_assoc($queryBkk);

	$bulanCOB = $dataBkk['created_on_bkk'];


	$bulan    = date('n', strtotime($bulanCOB));
	$bulanSekarang    = date('n');

	if ($bulan == $bulanSekarang) {
		$tanggal = dateNow();
	} else {
		$tanggal = date('Y-m-t', strtotime($bulanCOB));
	}

	//deklarasi tanggal	
	$romawi    = getRomawi($bulan);
	$tahun     = date('Y');
	$nomor     = "/GS-GK/" . $romawi . "/" . $tahun;

	$queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE month(created_on_bkk)='$bulan' OR month(release_on_bkk)='$bulan'");

	$nomorMax = mysqli_fetch_array($queryNomor);
	if ($nomorMax) {

		$nilaikode = substr($nomorMax[0], 0);
		$kode = (int) $nilaikode;

		//setiap kode ditambah 1
		$kode = $kode + 1;
		$nomorAkhir = "" . str_pad($kode, 3, "0", STR_PAD_LEFT);
	} else {
		$nomorAkhir = "001";
	}

	$nomorBkk = $nomorAkhir . $nomor;

	// log
	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
                                        ('$tanggal', '$nama', 'Melakukan Pembayaran BKK : $id');

                                        ";
	mysqli_query($koneksi, $queryLog);


	$id_anggaran = $dataBkk['id_anggaran'];
	$DPP = $dataBkk['nominal'];

	//query realisasi anggaran
	$fieldRealisasi = fieldRealisasi($bulan);

	$queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas  from anggaran WHERE id_anggaran = '$id_anggaran' ");
	$rowJA = mysqli_fetch_assoc($queryJumlahAwal);
	$jml_akhir = $rowJA['bulan'] + $DPP;
	$jumlah_realisasi = $rowJA['jumlah_realisasi'] + $DPP;
	$qty_akhir = $rowJA['realisasi_kuantitas'] + 1;


	$queryRealisasi = "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir' , jumlah_realisasi = $jumlah_realisasi ,realisasi_kuantitas = $qty_akhir
												WHERE id_anggaran ='$id_anggaran' ";
	mysqli_query($koneksi, $queryRealisasi);

	$query = mysqli_query($koneksi, "UPDATE bkk_final SET status_bkk='4', nomor = '$nomorAkhir', no_bkk = '$nomorBkk', release_on_bkk = '$tanggal' WHERE id=$id");

	if ($query) {
		header("location:index.php?p=biaya_khusus");
	} else {
		echo 'gagal';
	}
}
