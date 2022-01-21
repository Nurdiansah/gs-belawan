<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['edit'])) {

    $id_bkk = $_POST['id_bkk'];
    $tgl_tempo = $_POST['tgl_tempo'];
    $tgl_payment = $_POST['tgl_payment'];

    $tanggal = dateNow();

    mysqli_begin_transaction($koneksi);

    $return = mysqli_query($koneksi,  "UPDATE bkk SET tgl_tempo = '$tgl_tempo' , tgl_payment = '$tgl_payment' , jenis = 'kontrak'
                            WHERE id_bkk ='$id_bkk' ");

    if ($return) {
        // mysql commit transaction
        mysqli_commit($koneksi);

        setcookie('pesan', 'Biaya Umum berhasil di pindahkan ke Tempo', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        // mysql rollback transaction
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Biaya Umum gagal di pindahkan!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=payment_kaskeluar");
}

?>
<!-- pindah -->
<!--  -->