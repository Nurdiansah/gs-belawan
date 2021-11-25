<?php  

session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['submit'])) {
		$id = $_POST['id_item'];		
		$kd_transaksi = $_POST['kd_transaksi'];
		
		if (!isset($id)) {
			setcookie('pesan', 'Anda belum melakukan ceklis item pengajuan !', time() + (3), '/');
	
			header("location:index.php?p=app_dmr&id=$kd_transaksi");
		} else {
		
		echo "<p> Yang Anda Pilih : </p>";
		foreach($id as $nilai){		
			$queryD = mysqli_query($koneksi, "UPDATE detail_biayaops 
										  SET status='2' 
										  WHERE id ='$nilai' ");
		}
					
		
		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");		

		$query1 = mysqli_query($koneksi, "UPDATE biaya_ops 
										  SET status_biayaops=2 , app_mgr = '$tanggal' 
										  WHERE kd_transaksi='$kd_transaksi' ");		

		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menyetujui Pengajuan MR id: $id');

									";
		mysqli_query($koneksi, $queryLog);
		
		

		// 

		mysqli_query($koneksi, $query1);

		if($query1) {
			header("location:index.php?p=approval_mr");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}

	}
	}


?>