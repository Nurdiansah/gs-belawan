<?php  

session_start();
	include "../fungsi/koneksi.php";

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		$tanggal = date('Y-m-d');
		
		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");

		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Verifikasi Kode Transaksi Biaya Non OPS id: $id');

									";
		mysqli_query($koneksi, $queryLog);

		$query1 = mysqli_query($koneksi, "UPDATE bkk SET status_bkk=3 WHERE id_bkk='$id' ");		

		

		if($query1) {
			header("location:index.php?p=verifikasi_biayanonops");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}
