<?php  
	ob_start();
    session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['edit'])) {
        $id_golongan = $_POST['id_golongan'];   
		$nm_golongan = $_POST['nm_golongan'];   

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $nama=$rowUser['nama'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");

        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Merubah golongan $nm_golongan' );

									";
		mysqli_query($koneksi, $queryLog);
		
		$query = "UPDATE golongan SET nm_golongan = '$nm_golongan' WHERE id_golongan ='$id_golongan' ";	
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {        
			header("location:index.php?p=golongan");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}
