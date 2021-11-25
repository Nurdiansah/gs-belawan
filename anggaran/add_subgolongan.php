<?php  
    ob_start();
    session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {

        $nm_subgolongan = $_POST['nm_subgolongan'];   
        $id_golongan = $_POST['id_golongan'];   

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $nama=$rowUser['nama'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");

        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Tambah Sub Golongan $nm_subgolongan' );

									";
		mysqli_query($koneksi, $queryLog);
		
		$query = "INSERT INTO sub_golongan ( id_golongan, nm_subgolongan) VALUES 
										( '$id_golongan', '$nm_subgolongan');
			";
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=sub_golongan");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>