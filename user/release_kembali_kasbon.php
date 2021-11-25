<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['release'])) {
    $id_kasbon = $_POST['id'];
    $id_dbo = $_POST['id_dbo'];

    // UPDATE KASBON
    $hasil = mysqli_multi_query($koneksi, "UPDATE kasbon SET status_kasbon = '1', komentar = NULL
                                            WHERE id_kasbon ='$id_kasbon';
                                            
                                            DELETE FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon';
                                            ");


    if ($hasil) {
        setcookie('pesan', 'Kasbon berhasil di release!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location: index.php?p=ditolak_kasbon&sp=tolak_user");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!--  -->