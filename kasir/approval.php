<?php  

	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {
        $id = $_POST['id_bkk'];
        $keterangan = $_POST['keterangan'];
		$tanggal = date('Y-m-d');
		
		$query = "UPDATE bkk SET keterangan = '$keterangan', status_bkk = '4', tgl_pengajuankasir = '$tanggal' WHERE id_bkk ='$id' ";		
		// $query = "UPDATE job_order SET booking_cargo ='$Bc', status_jo=2 WHERE id_joborder='$idJoborder' ";

		$hasil = mysqli_query($koneksi, $query);
		if($hasil) {
			header("location:index.php?p=proses_kaskeluar");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}


?>