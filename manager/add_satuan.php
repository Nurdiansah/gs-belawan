<?php  
	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {

		$nm_satuan = $_POST['nm_satuan'];   
		
		$query = "INSERT INTO satuan ( nm_satuan) VALUES 
										( '$nm_satuan');
			";
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=satuan");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>