<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
	$id = dekripRambo($_GET['id']);

	$query = mysqli_query($koneksi, "DELETE FROM anggaran WHERE id_anggaran = '$id'");

	if ($query) {
		header("Location: index.php?p=anggaran&tahun=" . $_GET['thn'] . "&divisi=" . $_GET['dvs'] . "");
	} else {
		echo 'gagal cuii : ' . mysqli_error($koneksi);
		die;
	}
}
