<?php  

	include "../fungsi/koneksi.php";

	if(isset($_POST['simpan'])) {
        $id = $_POST['id_bkk'];
        $no_bkk = $_POST['no_bkk'];
        $tgl_bkk = $_POST['tgl_bkk'];
        $nocek_bkk = $_POST['nocek_bkk'];
        $dari_bank = $_POST['dari_bank'];
        $dari_rekening = $_POST['dari_rekening'];
		$tanggal = date('Y-m-d');
		
		$query = "UPDATE bkk SET no_bkk = '$no_bkk' , tgl_bkk = '$tgl_bkk' , nocek_bkk = '$nocek_bkk' , dari_bank = '$dari_bank' , 
                dari_rekening = '$dari_rekening' WHERE id_bkk ='$id' ";		

		$hasil = mysqli_query($koneksi, $query);
		if($hasil) {
			header("location:index.php?p=payment_kaskeluar");
		} else {
			echo "ada yang salah" . mysqli_error($koneksi);
		}
	}


?>