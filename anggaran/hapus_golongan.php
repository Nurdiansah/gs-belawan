<?php
	include "../fungsi/koneksi.php";

	if(isset($_GET['id'])){
		$id=$_GET['id'];

		$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $nama=$rowUser['nama'];

        date_default_timezone_set('Asia/Jakarta');
        $tanggal= date("Y-m-d H:i:s");

        $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menghapus golongan no id $id' );

									";
		mysqli_query($koneksi, $queryLog);
		
	    $query = mysqli_query($koneksi,"DELETE FROM golongan WHERE id_golongan=$id");
	    if ($query) {
	    	header("location:index.php?p=golongan");
	    } else {
	    	echo 'gagal';
	    }
	
	}
