<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['simpan'])) {
    $remarks = $_POST['remarks'];
    $id_bkk = $_POST['id_bkk'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    //Update remarks
    $query = mysqli_query($koneksi, "UPDATE bkk_final
									 SET remarks = '$remarks'
                                     WHERE id ='$id_bkk' ");

    if ($query) {
        setcookie('pesan', 'Remarks berhasil di simpan !', time() + (3), '/');

        header("location:index.php?p=verifikasi_dbkk&id=$id_bkk");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->