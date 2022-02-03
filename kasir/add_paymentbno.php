<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/koneksipusat.php";
include "../fungsi/fungsi.php";

if (isset($_POST['simpan'])) {

	$id = $_POST['id_bkk'];
	$id_anggaran = $_POST['id_anggaran'];
	$kd_transaksi = $_POST['kd_transaksi'];
	$metode_pembayaran = $_POST['metode_pembayaran'];
	$tgl_bkk = date("Y-m-d");
	$keterangan = $_POST['keterangan'];
	$jml_bkk = $_POST['jml_bkk'];

	$nilai_barang = $_POST['nilai_barang'];
	$nilai_jasa = $_POST['nilai_jasa'];
	$nilai_ppn = $_POST['nilai_ppn'];
	$id_pph = $_POST['id_pph'];
	$nilai_pph = $_POST['nilai_pph'];
	$nominal = $_POST['nominal'];

	$jenis = $_POST['jenis'];

	$DPP = $nilai_barang + $nilai_barang;

	if ($metode_pembayaran === 'transfer') {
		$nocek_bkk = $_POST['nocek_bkk'];
		$dari_bank = $_POST['dari_bank'];
		$dari_rekening = $_POST['dari_rekening'];
	} else {
		$nocek_bkk = null;
		$dari_bank = null;
		$dari_rekening = null;
	}

	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);


	if ($metode_pembayaran == 'tunai') {
		$tgl_bkk_release = $_POST['tgl_bkk_release'];

		$lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
		$doc_lpj = ($_FILES['doc_lpj']['name']);

		$ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);

		$namabaru = $kd_transaksi . "-bukti-pembayaran-biaya-umum." . $ekstensi;

		//deklarasi tanggal
		$bulan = date("n", strtotime($tgl_bkk_release));

		$nomorBkk = nomorBkkNew($tgl_bkk_release); // getNomorBkk($bulan);

		$nomor = nomorAwal($nomorBkk);

		//query di kualifikasikan ke bkk final
		$return = mysqli_query($koneksi, "INSERT INTO bkk_final (nomor, no_bkk, release_on_bkk, id_jenispengajuan, pengajuan, id_kdtransaksi, created_on_bkk, id_anggaran, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, bukti_pembayaran,status_bkk) VALUES
																('$nomor', '$nomorBkk', '$tgl_bkk_release','1', 'BIAYA UMUM','$kd_transaksi', '$tgl_bkk', '$id_anggaran', '$nilai_barang', '$nilai_jasa', '$nilai_ppn','$id_pph', '$nilai_pph', '$nominal', '$keterangan', '$namabaru', '1');
									");

		//query realisasi anggaran
		$fieldRealisasi = fieldRealisasi($bulan);

		$queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan, jumlah_realisasi, realisasi_kuantitas from anggaran WHERE id_anggaran = '$id_anggaran' ");
		$rowJA = mysqli_fetch_assoc($queryJumlahAwal);
		$jml_akhir = $rowJA['bulan'] + $DPP;
		$jml_realisasi = $rowJA['jumlah_realisasi'] + $DPP;
		$jml_kuantitas = $rowJA['realisasi_kuantitas'] + 1;

		$realisasi = mysqli_query($koneksi, "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir', jumlah_realisasi = '$jml_realisasi', realisasi_kuantitas = '$jml_kuantitas'
										WHERE id_anggaran ='$id_anggaran' ");


		// query update biaya umum
		$hasil = mysqli_query($koneksi, "UPDATE bkk SET tgl_bkk = '$tgl_bkk', nocek_bkk = '$nocek_bkk' , dari_bank = '$dari_bank' , 
					dari_rekening = '$dari_rekening', keterangan = '$keterangan',  doc_lpj = '$namabaru', status_bkk = '10' 
					WHERE id_bkk ='$id' ");
		// 

	} else if ($metode_pembayaran == 'transfer') {
		//query di kualifikasikan ke bkk final
		$return = mysqli_query($koneksi, "INSERT INTO bkk_ke_pusat (id_jenispengajuan, pengajuan, id_kdtransaksi, created_on_bkk,  id_anggaran, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, status_bkk) VALUES
												('1', 'BIAYA UMUM','$kd_transaksi', '$tgl_bkk', '$id_anggaran', '$nilai_barang', '$nilai_jasa', '$nilai_ppn','$id_pph', '$nilai_pph', '$nominal', '$keterangan',  '1');
									");

		$realisasi = 'Berhasil';

		// query update biaya umum
		$hasil = mysqli_query($koneksi, "UPDATE bkk SET tgl_bkk = '$tgl_bkk',keterangan = '$keterangan',  doc_lpj = '$namabaru', status_bkk = '17' 
			WHERE id_bkk ='$id' ");
		// 
		$bkkPusat = mysqli_query($koneksiPusat, "INSERT INTO bkk_final (id_jenispengajuan, pengajuan, id_kdtransaksi, created_on_bkk,  id_anggaran, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, id_area ,status_bkk) VALUES
																		('1', 'BIAYA UMUM','$kd_transaksi', '$tgl_bkk', '$id_anggaran', '$nilai_barang', '$nilai_jasa', '$nilai_ppn','$id_pph', '$nilai_pph', '$nominal', '$keterangan', '2', '1');
									");
	}


	if ($hasil && $realisasi && $return) {
		// $folder_ptw="file/$Doc_ptw";
		move_uploaded_file($lokasi_doc_lpj, "../file/bukti_pembayaran/" . $namabaru);

		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'BKK berhasil di Payment!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'BKK gagal di Payment!', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=payment_kaskeluar");
}

?>
<!-- pindah -->
<!--  -->