<?php  
	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {

		$nm_kegiatan = $_POST['nm_kegiatan'];   
        $id_jenis = $_POST['id_jenis']; 
        $id_client = $_POST['id_client']; 
        $nominal_tarif = $_POST['nominal_tarif']; 
        $id_satuan = $_POST['id_satuan']; 
        
		$query = "INSERT INTO tarif_shorebase ( nm_kegiatan, id_jenis, id_client, nominal_tarif, id_satuan) VALUES 
										( '$nm_kegiatan', '$id_jenis', '$id_client', '$nominal_tarif', '$id_satuan');
			";
		
		
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=tarif");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>