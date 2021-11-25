<?php  
	session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {

        $id = $_POST['id_bkk'];
		$lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
		$doc_lpj=($_FILES['doc_lpj']['name']);
		
		// $folder_ptw="file/$Doc_ptw";
		move_uploaded_file($lokasi_doc_lpj,"../file/lpj/$doc_lpj");

        $query = "UPDATE bkk SET doc_lpj = '$doc_lpj', status_bkk = '7'  WHERE id_bkk ='$id' ";	
		
		// $query = "INSERT INTO bkk (  doc_lpj, status_bkk) VALUES 
		// 								(  '$doc_lpj', '7');
		// 	";
		
		// move_uploaded_file($tmp,"file/pjsm/$Doc_pjsm");
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=lihat_kaskeluar");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>
<!-- pindah -->
<!--  -->