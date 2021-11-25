<?php  
	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {

        $nm_client = $_POST['nm_client'];
    

		
		$query = "INSERT INTO client ( nm_client) VALUES 
										( '$nm_client');
			";
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=client");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>