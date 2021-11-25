<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $id_user = $_POST['id_user'];
    $id_manager = $_POST['id_manager'];

    $tanggal = dateNow();

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    // Update approval SO
    $query = "UPDATE so SET status = '2', app_mgr_ga = NOW(), komentar = NULL
                WHERE id_so ='$id' ";
    $update = mysqli_query($koneksi, $query);

    if ($update) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'SR berhasil di Approve kembali!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'SR gagal di Approve kembali!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=ditolak_so");
}

?>
<!--  -->