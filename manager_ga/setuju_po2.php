<?php  

session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['submit'])) {
		$id_po = $_POST['id_item'];		        

        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");		

		foreach($id_po as $nilai){		
			$queryD = mysqli_query($koneksi, "UPDATE po
										  SET status_po = '3' , app_mgr_ga = '$tanggal'
                                          WHERE id_po ='$nilai' ");                                                               
        }			
        
        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Manager GA Menyetujui Pengajuan Po );
                                    ";
		mysqli_query($koneksi, $queryLog); 

		if($queryLog) {
			header("location:index.php?p=verifikasi_po");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}
