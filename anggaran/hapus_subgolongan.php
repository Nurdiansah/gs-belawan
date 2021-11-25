<?php
	include "../fungsi/koneksi.php";

	if(isset($_GET['id'])){
		$id=$_GET['id'];

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $nama=$rowUser['nama'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");

        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menghapus Sub golongan no id $id' );

									";
		mysqli_query($koneksi, $queryLog);
		
	    $query = mysqli_query($koneksi,"DELETE FROM sub_golongan WHERE id_subgolongan=$id");
	    if ($query) {
	    	header("location:index.php?p=sub_golongan");
	    } else {
	    	echo 'gagal';
	    }
	
	}
?>