<?php  
	ob_start();
    session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['edit'])) {
        $id_subgolongan = $_POST['id_subgolongan'];   
        $nm_subgolongan = $_POST['nm_subgolongan'];   
        $id_golongan = $_POST['id_golongan'];   

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $nama=$rowUser['nama'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");

        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Merubah sub golongan $nm_subgolongan' );

									";
		mysqli_query($koneksi, $queryLog);
		
		$query = "UPDATE sub_golongan SET nm_subgolongan = '$nm_subgolongan', id_golongan = '$id_golongan' WHERE id_subgolongan ='$id_subgolongan' ";	
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
                    
			header("location:index.php?p=sub_golongan");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}
