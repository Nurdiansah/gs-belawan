<?php  
	session_start();
	include "../fungsi/koneksi.php";
	 
	if(isset($_POST['submit'])) {		
        $id = $_POST['id'];        
        $kd_transaksi = $_POST['kd_transaksi'];
        $id_anggaran = $_POST['id_anggaran'];		
        		
		date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");
		

        $query = "UPDATE detail_biayaops SET id_anggaran = '$id_anggaran'  
                                            WHERE id ='$id' ";
                                                  
		
        $hasil = mysqli_query($koneksi, $query);
        

		if ($hasil) {
			header("location:index.php?p=verifikasi_dmr&id=$kd_transaksi");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>
<!-- pindah -->
<!--  -->