<?php  

session_start();
	include "../fungsi/koneksi.php";

	if(isset($_GET['id'])) {
        $kd_transaksi = $_GET['id'];	
            

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");		

		$query1 = mysqli_query($koneksi, "UPDATE biaya_ops 
										  SET status_biayaops=5 , app_manager_ga = '$tanggal' 
										  WHERE kd_transaksi='$kd_transaksi' ");		

		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Selesai melakukan verifikasi MR id: $kd_transaksi');

									";
		mysqli_query($koneksi, $queryLog);
		

		if($query1) {
			header("location:index.php?p=verifikasi_kasbon");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}
