<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {

	$kode_otomatis = nomorMR();

	$tgl_pengajuan = $_POST['tgl_pengajuan'];


	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$id_divisi = $rowUser['id_divisi'];
	$id_manager = $rowUser['id_manager'];
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	// query log
	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Pembuatan Biaya OPS $kode_otomatis');

									";
	mysqli_query($koneksi, $queryLog);

	$queryDetail = "UPDATE detail_biayaops SET status = '1', kd_transaksi = '$kode_otomatis'  
                                            WHERE id_divisi ='$id_divisi' AND status = '0' ";
	mysqli_query($koneksi, $queryDetail);

	// query insert 
	$query = "INSERT INTO biaya_ops ( kd_transaksi, id_divisi, tgl_pengajuan, id_manager, created_by, created_on) VALUES 
								( '$kode_otomatis','$id_divisi',  '$tgl_pengajuan', '$id_manager', '$nama', '$tanggal');
			";

	$hasil = mysqli_query($koneksi, $query);
	if ($hasil) {
		header("location:index.php?p=proses_mr");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->