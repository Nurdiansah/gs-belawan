<?php  
	session_start();
	include "../fungsi/koneksi.php";

	if(isset($_POST['submit'])) {		
		$id_divisi = $_POST['id_divisi'];
		$nm_barang = $_POST['nm_barang'];
		$nm_barang = $_POST['nm_barang'];
		$id_anggaran = $_POST['id_anggaran'];		
		$merk = $_POST['merk'];
		$type = $_POST['type'];
		$spesifikasi = $_POST['spesifikasi'];
		$jumlah = $_POST['jumlah'];
		$satuan = $_POST['satuan'];
		$keterangan = $_POST['keterangan'];		

		date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");

		mysqli_query($koneksi, $queryLog);
		
		$query = "INSERT INTO detail_biayaops ( id_divisi, nm_barang, id_anggaran,merk, type, spesifikasi, jumlah, satuan, keterangan) VALUES 
										( '$id_divisi', '$nm_barang', '$id_anggaran', '$merk', '$type', '$spesifikasi', '$jumlah', '$satuan', '$keterangan' );
			";
		
		// move_uploaded_file($tmp,"file/pjsm/$Doc_pjsm");
		$hasil = mysqli_query($koneksi, $query);
		if ($hasil) {
			header("location:index.php?p=buat_mr");
		} else {
			die("ada kesalahan : " . mysqli_error($koneksi));
		}

	}

?>
<!-- pindah -->
<!--  -->