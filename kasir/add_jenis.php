<?php  
	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {

		$nm_jenis = $_POST['nm_jenis'];   
		
		$query = "INSERT INTO jenis_kegiatan ( nm_jenis) VALUES 
										( '$nm_jenis');
			";
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=jenis_kegiatan");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>