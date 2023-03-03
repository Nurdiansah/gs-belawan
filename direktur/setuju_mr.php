<?php  

session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['submit'])) {
		$id = $_POST['id_item'];		
		$kd_transaksi = $_POST['kd_transaksi'];
		
		echo "<p> Yang Anda Pilih : </p>";
		foreach($id as $nilai){		
			$queryD = mysqli_query($koneksi, "UPDATE detail_biayaops 
										  SET status='4' 
										  WHERE id ='$nilai' ");
		}
					

		$tanggal = date('Y-m-d');
		
		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");		

		$query1 = mysqli_query($koneksi, "UPDATE biaya_ops 
										  SET status_biayaops=7 , app_direktur = '$tanggal' 
										  WHERE kd_transaksi='$kd_transaksi' ");		

		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menyetujui Pengajuan MR id: $id');

									";
		mysqli_query($koneksi, $queryLog);
		
		

		// 

		mysqli_query($koneksi, $query1);

		if($query1) {
			header("location:index.php?p=verifikasi_mr");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}
