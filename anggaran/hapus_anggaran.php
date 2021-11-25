<?php
	include "../fungsi/koneksi.php";

	if(isset($_GET['id'])){
		$id=$_GET['id'];
		
	    $query = mysqli_query($koneksi,"DELETE FROM anggaran WHERE id_anggaran=$id");
	    if ($query) {
	    	header("location:index.php?p=anggaran&divisi=". $_GET['divisi'] . "&tahun=" . $_GET['tahun'] . "");
	    } else {
	    	echo 'gagal';
	    }
	
	}
