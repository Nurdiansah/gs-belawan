<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
    $id_dbo = $_POST['id_dbo'];
    $sub_deskripsi = $_POST['sub_deskripsi'];
    $sub_qty = $_POST['sub_qty'];
    $sub_unit = $_POST['sub_unit'];

    // 		
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];


    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $query = "INSERT INTO sub_dbo ( id_dbo, sub_deskripsi, sub_qty, sub_unit) VALUES 
								( '$id_dbo','$sub_deskripsi',  '$sub_qty', '$sub_unit');
			";

    $hasil = mysqli_query($koneksi, $query);



    // $hasil = mysqli_query($koneksi, $query);
    if ($hasil) {
        header("location:index.php?p=edit_item_tolak&id=$id_dbo");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->