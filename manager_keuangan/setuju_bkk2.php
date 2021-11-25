<?php  

session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['submit'])) {
		$id_po = $_POST['id_item'];		 		

        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
		$rowUser=mysqli_fetch_assoc($queryUser);	
		$nama=$rowUser['nama'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal= date("Y-m-d H:i:s");		

		foreach($id_po as $nilai){		
			$queryD = mysqli_query($koneksi, "UPDATE bkk_final
										  SET status_bkk = '2' , v_mgr_finance = '$tanggal'
                                          WHERE id ='$nilai' ");                                                               
        }			
        
        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Manager Finance Menyetujui BKK' );
                                    ";
		mysqli_query($koneksi, $queryLog); 

		if($queryLog) {
			header("location:index.php?p=verifikasi_bkk");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}


?>