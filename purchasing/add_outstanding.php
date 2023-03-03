<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
	$id_po = $_POST['id_po'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	$rowUser = mysqli_fetch_assoc($queryUser);
	$id_user = $rowUser['id_user'];
	$nama = $rowUser['nama'];

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Mensubmit Pelunasan PO id : $id_po');

									";
	mysqli_query($koneksi, $queryLog);

	$query = "UPDATE po SET  status_po = '11'
                            WHERE id_po ='$id_po' ";

	$hasil = mysqli_query($koneksi, $query);

	if ($hasil) {
		header("location:index.php?p=po_outstanding");
	} else {
		die("ada kesalahan : " . mysqli_error($koneksi));
	}
}

?>
<!-- pindah -->
<!--  -->