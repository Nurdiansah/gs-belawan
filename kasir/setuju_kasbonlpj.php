<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id_kasbon = $_POST['id_kasbon'];
	$total = $_POST['total'];
	$keterangan = $_POST['keterangan'];
	$id_anggaran = $_POST['id_anggaran'];
	$id_supplier = $_POST['id_supplier'];
	$nilai_barang = $_POST['nilai_barang'];
	$nilai_jasa = $_POST['nilai_jasa'];
	$nilai_ppn = $_POST['nilai_ppn'];
	$nilai_pph = $_POST['nilai_pph'];
	$id_pph = $_POST['id_pph'];
	$pengembalian = $_POST['pengembalian'];
	$id_anggaran = $_POST['id_anggaran'];
	$qty = $_POST['qty'];
	$waktu_penerima_dana = $_POST['waktu_penerima_dana'];
	$id_divisi = $_POST['id_divisi'];
	$id_manager = $_POST['id_manager'];
	$doc_lpj = $_POST['doc_lpj'];
	$tgl_bkk = datetimeHtml($_POST['tgl_bkk'] . ":00");

	// $DPP = ($nilai_barang + $nilai_jasa) - $nilai_pph;
	$DPP = $nilai_barang + $nilai_jasa;


	mysqli_begin_transaction($koneksi);

	if ($id_pph == '') {
		$id_pph = '4';
	}

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	// query user
	$queryUser = mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];

	// cek apakah pengajuan tsb udh jadi BKK/PETTY atau belum, klo udh jadi BKK/PETTY lewatin
	$totalBF = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bkk_final WHERE id_kdtransaksi = '$id_kasbon' AND pengajuan = 'KASBON'"));

	// validasi supaya ngga double
	if ($totalBF > 0) {
		$hasil = "Berhasil";
		$realsiasi = "Berhasil";
		$update = "Berhasil";
		$updRealSem = "Berhasil";
	} else {
		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
										('$tanggal', '$nama', 'Menyetujui LPJ Kasbon id : $id_kasbon');

										";

		//deklarasi tanggal
		// $bulan    = date('n');
		// $tahun     = date('Y');
		$bulan = date("n", strtotime($tgl_bkk));
		$romawi = getRomawi($bulan);
		$tahun = date("Y", strtotime($tgl_bkk));
		$nomor = "/GS-GK/" . $romawi . "/" . $tahun;

		mysqli_query($koneksi, $queryLog);

		if ($total <= 100000) {
			# pettycash		
			// KODE OTOMATIS
			$kode_otomatis = nomorPettycash();

			$query = "INSERT INTO transaksi_pettycash (kd_pettycash, pengajuan, id_kdtransaksi, id_anggaran, keterangan_pettycash, total_pettycash, doc_lpj_pettycash, id_divisi, id_manager, status_pettycash, created_pettycash_on) VALUES 
										  ( '$kode_otomatis', 'KASBON', '$id_kasbon', '$id_anggaran', '$keterangan', '$total', '$doc_lpj', '$id_divisi', '$id_manager', '5' , '$tanggal');
				";

			$hasil = mysqli_query($koneksi, $query);
		} else {
			# bkk		

			$nomorBkk = nomorBkkNew($tgl_bkk);
			$nomorAwal = nomorAwal($nomorBkk);

			//query di kualifikasikan ke bkk final
			$queryBkkfinal = "INSERT INTO bkk_final (nomor, no_bkk, release_on_bkk,id_jenispengajuan, pengajuan, id_kdtransaksi, created_on_bkk, id_anggaran, id_supplier, nilai_barang, nilai_jasa, nilai_ppn, nilai_pph, id_pph, pengembalian, nominal, keterangan, status_bkk) VALUES
												('$nomorAwal', '$nomorBkk', '$tgl_bkk','1', 'KASBON', '$id_kasbon', '$waktu_penerima_dana', '$id_anggaran','$id_supplier', '$nilai_barang','$nilai_jasa', '$nilai_ppn', '$nilai_pph', '$id_pph','$pengembalian','$total', '$keterangan', '1')
										";
			$hasil = mysqli_query($koneksi, $queryBkkfinal);
		}


		//query realisasi anggaran
		$fieldRealisasi = fieldRealisasi($bulan);
		$queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas  from anggaran WHERE id_anggaran = '$id_anggaran' ");
		$rowJA = mysqli_fetch_assoc($queryJumlahAwal);
		$jml_akhir = $rowJA['bulan'] + $DPP;
		$jumlah_realisasi = $rowJA['jumlah_realisasi'] + $DPP;
		$qty_akhir = $rowJA['realisasi_kuantitas'] + $qty;


		$realsiasi = mysqli_query($koneksi, "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir' , jumlah_realisasi = $jumlah_realisasi ,realisasi_kuantitas = $qty_akhir
												WHERE id_anggaran ='$id_anggaran' ");

		$update = mysqli_query($koneksi, "UPDATE kasbon SET status_kasbon = '10'
											WHERE id_kasbon ='$id_kasbon' ");

		$updRealSem = UpdRealisasiSem($id_kasbon, 'KBN', $DPP, "1");
	}

	if ($hasil && $realsiasi && $update && $updRealSem) {
		mysqli_commit($koneksi);

		setcookie('pesan', 'Kasbon Berhasil di Selesaikan!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');

		header("location:index.php?p=" . $_POST['url']);
	} else {

		mysqli_rollback($koneksi);

		setcookie('pesan', 'Gagal!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');

		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->