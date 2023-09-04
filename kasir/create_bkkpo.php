<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/koneksipusat.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
	$id_po = $_POST['id_po'];
	$id_tagihan = $_POST['id_tagihan'];

	$total = str_replace(".", "", $_POST['nominal']);

	$keterangan	 = $_POST['keterangan'];
	$id_anggaran = $_POST['id_anggaran'];
	$id_supplier = $_POST['id_supplier'];
	$metode_pembayaran = $_POST['metode_pembayaran'];

	$persen = $_POST['persen'];
	$nilai_barang = ($persen / 100) * $_POST['nilai_barang'];
	$nilai_jasa = ($persen / 100) * $_POST['nilai_jasa'];
	$nilai_ppn = ($persen / 100) * $_POST['nilai_ppn'];
	$id_pph = $_POST['id_pph'];
	$nilai_pph = ($persen / 100) * $_POST['nilai_pph'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");
	$tgl_bkk = date("Y-m-d ");

	//baca lokasi file sementara dan nama file dari form (doc_ptw)		
	$lokasi_doc = ($_FILES['doc_faktur']['tmp_name']);
	$doc = ($_FILES['doc_faktur']['name']);
	$ekstensi = pathinfo($doc, PATHINFO_EXTENSION);
	$namadoc = md5($id_tagihan) . "faktur-belawan." . $ekstensi;

	// BEGIN/START TRANSACTION        
	mysqli_begin_transaction($koneksi);

	if ($metode_pembayaran == 'Tunai') {

		// Tunai
		$insertBkk = mysqli_query($koneksi, "INSERT INTO bkk_final (id_anggaran, id_supplier, id_jenispengajuan, pengajuan, id_kdtransaksi, id_tagihan, created_on_bkk, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, status_bkk) VALUES
																 ('$id_anggaran', '$id_supplier' , '4', 'PO', '$id_po', '$id_tagihan','$tanggal', '$nilai_barang', '$nilai_jasa', '$nilai_ppn', '$id_pph', '$nilai_pph', '$total', '$keterangan', '0'); ");

		$queryMaxBKK = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(id) as max_bkk FROM bkk_final"));
		$dataMaxBKK = $queryMaxBKK['max_bkk'];

		$updateTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET status_tagihan = '2', bkk_id = '$dataMaxBKK', doc_faktur = '$namadoc'
												 WHERE id_tagihan ='$id_tagihan' ");
	} else {

		// Jika file yang di upload bukan pdf
		if ($ekstensi != 'pdf') {
			setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
			setcookie('warna', 'alert-danger', time() + (3), '/');

			header("location:index.php?p=list_dpo&id=$id_tagihan");
		} else {

			// insert bkk pusat
			$insertBkk = mysqli_query($koneksiPusat, "INSERT INTO bkk_final (id_anggaran, id_supplier, id_jenispengajuan, pengajuan, id_kdtransaksi, id_tagihan, created_on_bkk, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, id_area, status_bkk) VALUES
																			('$id_anggaran', '$id_supplier' , '4', 'PO', '$id_po', '$id_tagihan','$tanggal', '$nilai_barang', '$nilai_jasa', '$nilai_ppn', '$id_pph', '$nilai_pph', '$total', '$keterangan', '2','0'); ");

			// insert bkk belawan
			$insertBkk = mysqli_query($koneksi, "INSERT INTO bkk_ke_pusat (id_anggaran, id_supplier, id_jenispengajuan, pengajuan, id_kdtransaksi, id_tagihan, created_on_bkk, nilai_barang, nilai_jasa, nilai_ppn, id_pph, nilai_pph, nominal, keterangan, status_bkk) VALUES
																			('$id_anggaran', '$id_supplier' , '4', 'PO', '$id_po', '$id_tagihan','$tanggal', '$nilai_barang', '$nilai_jasa', '$nilai_ppn', '$id_pph', '$nilai_pph', '$total', '$keterangan', '0'); ");


			$queryMaxBKK = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT MAX(id) as max_bkk FROM bkk_ke_pusat"));
			$dataMaxBKK = $queryMaxBKK['max_bkk'];

			$updateTagihan = mysqli_query($koneksi, "UPDATE tagihan_po SET status_tagihan = '2', doc_faktur = '$namadoc', bkk_id = '$dataMaxBKK'
		  												 WHERE id_tagihan ='$id_tagihan' ");
		}
	}

	$updatePO = mysqli_query($koneksi, "UPDATE po SET status_po = '8'
	WHERE id_po ='$id_po' ");

	if ($insertBkk && $updatePO  && $updateTagihan) {

		move_uploaded_file($lokasi_doc, "../file/invoice/" . $namadoc);

		# jika semua query berhasil di jalankan
		mysqli_commit($koneksi);

		setcookie('pesan', 'Invoice berhasil di submit!', time() + (3), '/');
		setcookie('warna', 'alert-success', time() + (3), '/');
	} else {
		#jika ada query yang gagal
		print_r(mysqli_error($koneksi));
		die;

		mysqli_rollback($koneksi);

		setcookie('pesan', 'Invoice gagal di submit!', time() + (3), '/');
		setcookie('warna', 'alert-danger', time() + (3), '/');
	}
	header("location:index.php?p=list_po");
}

?>
<!-- pindah -->
<!--  -->