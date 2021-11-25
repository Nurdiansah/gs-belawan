<?php
	include "../fungsi/koneksi.php";

	if(isset($_GET['id'])){
        $id=$_GET['id'];
        
        $querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo
                                                        WHERE id_subdbo=$id ");
        $data=mysqli_fetch_assoc($querySbo);
        $id_dbo = $data['id_dbo'];        
		
	    $query = mysqli_query($koneksi,"DELETE FROM sub_dbo WHERE id_subdbo=$id");
	    if ($query) {
	    	header("location:index.php?p=edit_item_tolak&id=$id_dbo");
	    } else {
	    	echo 'gagal';
	    }
	
	}
