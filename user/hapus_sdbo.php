<?php
include "../fungsi/koneksi.php";

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$id_subdbo = $_GET['id_subdbo'];

	// $querySbo =  mysqli_query($koneksi, "SELECT * 
	//                                                     FROM sub_dbo
	//                                                     WHERE id_subdbo=$id ");
	// $data = mysqli_fetch_assoc($querySbo);
	// $id_dbo = $data['id_dbo'];

	$query = mysqli_query($koneksi, "DELETE FROM sub_dbo WHERE id_subdbo = $id_subdbo");

	if ($query) {
		header("location:index.php?p=" . $_GET['url'] . "&id=$id");
	} else {
		echo 'gagal cuii' . mysqli_error($koneksi);
	}
}
