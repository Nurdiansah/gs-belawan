<?php  
	ob_start();
    session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {

		$nm_golongan = $_POST['nm_golongan'];   

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $nama=$rowUser['nama'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");

        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Tambah golongan $nm_golongan' );

									";
		mysqli_query($koneksi, $queryLog);
		
		$query = "INSERT INTO golongan ( nm_golongan) VALUES 
										( '$nm_golongan');
			";
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=golongan");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}
