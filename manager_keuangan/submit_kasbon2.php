<?php  

session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['submit'])) {
		$id = $_POST['id_item'];		        
        
        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");		
		
		echo "<p> Yang Anda Pilih : </p>";
		foreach($id as $nilai){		
			$queryD = mysqli_query($koneksi, "UPDATE kasbon
										  SET status_kasbon = '3' , app_mgr_ga = '$tanggal'
                                          WHERE id ='$nilai' ");
                    
            $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Manager GA Menyetujui Pengajuan Kasbon id: $nilai');
                                    ";
		    mysqli_query($koneksi, $queryLog);                                    
		}			

		if($query1) {
			header("location:index.php?p=verifikasi_kasbon");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}
