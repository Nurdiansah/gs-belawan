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
									('$tanggal', '$nama', 'Verifikasi Pengajuan MR id: $id');

									";
		mysqli_query($koneksi, $queryLog);

		$query1 = mysqli_query($koneksi, "UPDATE biaya_ops 
                                          SET status_biayaops=3 , app_anggaran = '$tanggal' , id_jenispengajuan = '1'
                                          WHERE kd_transaksi='$id' ");		

        mysqli_query($koneksi, $query1);

		if($query1) {
			header("location:index.php?p=verifikasi_mr");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}
