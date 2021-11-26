<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['simpan'])) {

	$id = $_POST['id_bkk'];
	$id_anggaran = $_POST['id_anggaran'];
	$kd_transaksi = $_POST['kd_transaksi'];
	$metode_pembayaran = $_POST['metode_pembayaran'];
	$tgl_bkk = date("Y-m-d");
	$keterangan	 = $_POST['keterangan'];
	$jml_bkk = $_POST['jml_bkk'];

	$nilai_barang = $_POST['nilai_barang'];
	$nilai_jasa = $_POST['nilai_jasa'];
	$nilai_ppn = $_POST['nilai_ppn'];
	$id_pph = $_POST['id_pph'];
	$nilai_pph = $_POST['nilai_pph'];
	$nominal = $_POST['nominal'];

	$jenis = $_POST['jenis'];

	$DPP = $nilai_barang + $nilai_barang;

	if ($metode_pembayaran  === 'transfer') {
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


	if ($jenis == 'umum') {
		$lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
		$doc_lpj = ($_FILES['doc_lpj']['name']);

		$ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);

		$namabaru = $kd_transaksi . "-bukti-pembayaran-biaya-umum." . $ekstensi;

		// $folder_ptw="file/$Doc_ptw";
		move_uploaded_file($lokasi_doc_lpj, "../file/bukti_pembayaran/" . $namabaru);

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


		//query di kualifikasikan ke bkk final
		$queryBkkfinal = "INSERT INTO bkk_final (id_jenispengajuan, pengajuan, id_kdtransaksi, created_on_bkk, id_anggaran, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, bukti_pembayaran,status_bkk) VALUES
											('1', 'BIAYA UMUM','$kd_transaksi', '$tgl_bkk', '$id_anggaran', '$nilai_barang', '$nilai_jasa', '$nilai_ppn','$id_pph', '$nilai_pph', '$nominal', '$keterangan', '$namabaru', '1');
									";
		$return = mysqli_query($koneksi, $queryBkkfinal);

		//query realisasi anggaran
		$fieldRealisasi = fieldRealisasi($bulan);

		$queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan, jumlah_realisasi, realisasi_kuantitas from anggaran WHERE id_anggaran = '$id_anggaran' ");
		$rowJA = mysqli_fetch_assoc($queryJumlahAwal);
		$jml_akhir = $rowJA['bulan'] + $DPP;
		$jml_realisasi = $rowJA['jumlah_realisasi'] + $DPP;
		$jml_kuantitas = $rowJA['realisasi_kuantitas'] + 1;

		$queryRealisasi = "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir', jumlah_realisasi = '$jml_realisasi', realisasi_kuantitas = '$jml_kuantitas'
										WHERE id_anggaran ='$id_anggaran' ";

		$realisasi = mysqli_query($koneksi, $queryRealisasi);
	} else if ($jenis == 'kontrak') {
		//query di kualifikasikan ke bkk final
		$queryBkkfinal = "INSERT INTO bkk_final (id_jenispengajuan, pengajuan, id_kdtransaksi, created_on_bkk,  id_anggaran, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, status_bkk) VALUES
												('1', 'BIAYA UMUM','$kd_transaksi', '$tgl_bkk', '$id_anggaran', '$nilai_barang', '$nilai_jasa', '$nilai_ppn','$id_pph', '$nilai_pph', '$nominal', '$keterangan',  '1');
									";
		$return = mysqli_query($koneksi, $queryBkkfinal);

		$realisasi = 'Berhasil';
	}


	// query update bkk
	$query = "UPDATE bkk SET tgl_bkk = '$tgl_bkk', nocek_bkk = '$nocek_bkk' , dari_bank = '$dari_bank' , 
					dari_rekening = '$dari_rekening', keterangan = '$keterangan',  doc_lpj = '$namabaru', status_bkk = '10' 
					WHERE id_bkk ='$id' ";


	$hasil = mysqli_query($koneksi, $query);


	if ($hasil && $realisasi && $return) {
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