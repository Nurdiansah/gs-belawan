<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['update'])) {


	$id_subdbo = $_POST['id_subdbo'];
	$sub_unitprice = str_replace(",", ".", $_POST['sub_unitprice']);
	$sub_qty = $_POST['sub_qty'];
	$id_dbo = $_POST['id_dbo'];

	$total_price = $sub_qty * $sub_unitprice;

	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("Y-m-d H:i:s");

	$query = mysqli_query($koneksi, "UPDATE sub_dbo
                                        SET sub_unitprice = '$sub_unitprice', total_price = '$total_price'                                         
                                        WHERE id_subdbo='$id_subdbo' ");

	if ($query) {
		header("location:index.php?p=bidding_itemmr&id=$id_dbo");
	} else {
		echo "ada yang salah" . mysqli_error($koneksi);
	}
}
