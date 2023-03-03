<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['rubah'])) {
	$id_dbo = $_POST['id_dbo'];
	$id_anggaran = $_POST['id_anggaran'];

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$nama = $rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Melakukan Perubahan Kode anggaran: $id_dbo');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "UPDATE detail_biayaops SET id_anggaran = '$id_anggaran'  
                                            WHERE id ='$id_dbo' ";


	$hasil = mysqli_query($koneksi, $query);


	if ($hasil) {
		header("location:index.php?p=transaksi_kasbon");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->