<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['simpan'])) {

	// function Terbilang($nilai)
	// {
	// 	$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	// 	if ($nilai == 0) {
	// 		return "";
	// 	} elseif ($nilai < 12 & $nilai != 0) {
	// 		return "" . $huruf[$nilai];
	// 	} elseif ($nilai < 20) {
	// 		return Terbilang($nilai - 10) . " Belas ";
	// 	} elseif ($nilai < 100) {
	// 		return Terbilang($nilai / 10) . " Puluh " . Terbilang($nilai % 10);
	// 	} elseif ($nilai < 200) {
	// 		return " Seratus " . Terbilang($nilai - 100);
	// 	} elseif ($nilai < 1000) {
	// 		return Terbilang($nilai / 100) . " Ratus " . Terbilang($nilai % 100);
	// 	} elseif ($nilai < 2000) {
	// 		return " Seribu " . Terbilang($nilai - 1000);
	// 	} elseif ($nilai < 1000000) {
	// 		return Terbilang($nilai / 1000) . " Ribu " . Terbilang($nilai % 1000);
	// 	} elseif ($nilai < 1000000000) {
	// 		return Terbilang($nilai / 1000000) . " Juta " . Terbilang($nilai % 1000000);
	// 	} elseif ($nilai < 1000000000000) {
	// 		return Terbilang($nilai / 1000000000) . " Milyar " . Terbilang($nilai % 1000000000);
	// 	} elseif ($nilai < 100000000000000) {
	// 		return Terbilang($nilai / 1000000000000) . " Trilyun " . Terbilang($nilai % 1000000000000);
	// 	} elseif ($nilai <= 100000000000000) {
	// 		return "Maaf Tidak Dapat di Prose Karena Jumlah nilai Terlalu Besar ";
	// 	}
	// }

	$query = mysqli_query($koneksi, "SELECT MAX(kd_transaksi) from bkk ");

	$id_joborder = mysqli_fetch_array($query);
	if ($id_joborder) {

		$nilaikode = substr($id_joborder[0], 2);
		$kode = (int) $nilaikode;

		//setiap kode ditambah 1
		$kode = $kode + 1;
		$kode_otomatis = "A" . str_pad($kode, 5, "0", STR_PAD_LEFT);
	} else {
		$kode_otomatis = "A00001";
	}

	$nm_vendor = $_POST['nm_vendor'];
	$tgl_bkk = $tanggalCargo = date("Y-m-d");
	$keterangan = $_POST['keterangan'];
	$nilai_barang = $_POST['nilai_barang'];
	$nilai_jasa = $_POST['nilai_jasa'];
	$ppn_persen = $_POST['ppn_persen'];
	$ppn_nilaia = $_POST['ppn_nilai'];
	$ppn_nilai = str_replace(".", "", $ppn_nilaia);
	$pph_persen = $_POST['pph_persen'];
	$pph_nilaia = $_POST['pph_nilai'];
	$pph_nilai = str_replace(".", "", $pph_nilaia);
	$jml_bkka = $_POST['jml_bkk'];
	$jml_bkk = str_replace(".", "", $jml_bkka);
	$terbilang_bkk = Terbilang($jml_bkk);
	$bank_tujuan = $_POST['bank_tujuan'];
	$norek_tujuan = $_POST['norek_tujuan'];
	$penerima_tujuan = $_POST['penerima_tujuan'];
	$id_anggaran = $_POST['id_anggaran'];
	$jenis = $_POST['jenis'];
	$metode_pembayaran = $_POST['pembayaran'];
	$tgl_tempo = $_POST['tgl_tempo'];
	$tgl_payment = $_POST['tgl_payment'];

	//baca lokasi file sementara dan nama file dari form (doc_ptw)\

	$lokasi_invoice = ($_FILES['invoice']['tmp_name']);
	$invoice = ($_FILES['invoice']['name']);
	$ekstensi = pathinfo($invoice, PATHINFO_EXTENSION);
	$namabaru = $kode_otomatis . "-inv-biaya-non-ops." . $ekstensi;
	// $folder_ptw="file/$Doc_ptw";		
	move_uploaded_file($lokasi_invoice, "../file/" . $namabaru);

	$queryUser =  mysqli_query($koneksi, "SELECT * from user u
											JOIN divisi d
												ON u.id_divisi = d.id_divisi
											WHERE username  = '$_SESSION[username]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];
	$id_divisi = $rowUser['id_divisi'];
	$id_manager = $rowUser['id_manager'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");


	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Pengajuan Biaya Non OPS ');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "INSERT INTO bkk (nm_vendor, kd_transaksi, tgl_pengajuan, terbilang_bkk, id_anggaran, keterangan, nilai_barang, nilai_jasa, ppn_persen, ppn_nilai, pph_persen, pph_nilai, jml_bkk, jenis, metode_pembayaran, tgl_tempo, tgl_payment,bank_tujuan, norek_tujuan, penerima_tujuan, id_pemohon, id_divisi, id_manager,invoice, status_bkk) VALUES 
							('$nm_vendor', '$kode_otomatis', '$tgl_bkk',  '$terbilang_bkk', '$id_anggaran', '$keterangan', '$nilai_barang', '$nilai_jasa', '$ppn_persen',' $ppn_nilai', '$pph_persen','$pph_nilai','$jml_bkk', '$jenis', '$metode_pembayaran', '$tgl_tempo', '$tgl_payment', '$bank_tujuan', '$norek_tujuan', '$penerima_tujuan','$id_user', '$id_divisi', '$id_manager','$namabaru', '0');
			";

	// move_uploaded_file($tmp,"file/pjsm/$Doc_pjsm");
	$hasil = mysqli_query($koneksi, $query);

	if ($hasil) {
		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'Biaya Umum berhasil dibuat!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		mysqli_rollback($koneksi);

		setcookie('pesan', 'Biaya Umum Gagal dibuat!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=proses_biayanonops");
}

?>
<!-- pindah -->
<!--  -->