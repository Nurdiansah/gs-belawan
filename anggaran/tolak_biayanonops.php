<?php  

session_start();
include "../fungsi/koneksi.php";

if(isset($_POST['tolak'])) {
	
	$id_bkk = $_POST['id_bkk'];
	$komentar = $_POST['komentar'];
	
	$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
	$rowUser=mysqli_fetch_assoc($queryUser);	
	$nama=$rowUser['nama'];

	date_default_timezone_set('Asia/Jakarta');
	$tanggal= date("Y-m-d H:i:s");

	$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
								('$tanggal', '$nama', 'Menolak Pengajuan Biaya Non OPS id: $id_bkk');

								";
	mysqli_query($koneksi, $queryLog);
    

	$query = mysqli_query($koneksi, "UPDATE bkk SET komentar='$komentar', status_bkk=0
                                     WHERE id_bkk ='$id_bkk' ");
	if ($query) {
		header("location:index.php?p=verifikasi_biayanonops");
	} else {
		echo 'error' . mysqli_error($koneksi);
	}

}
