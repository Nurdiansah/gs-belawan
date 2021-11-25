<?php  

	include "../fungsi/koneksi.php";

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$tanggal = date('Y-m-d');
		
		$query1 = mysqli_query($koneksi, "UPDATE bkk SET status_bkk=3 WHERE id_bkk='$id' ");		

		

		if($query1) {
			header("location:index.php?p=lihat_kaskeluar");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}


?>