<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['delete'])) {

    $id_dsr = $_POST['id'];
    $sr_id = $_POST['sr_id'];

    mysqli_begin_transaction($koneksi);

    // UPDATE
    $delete = mysqli_query($koneksi, "DELETE FROM detail_sr WHERE id_dsr ='$id_dsr' ");

    // buat ngambil SUM dari tbl detail_sr
    $querySUM = mysqli_query($koneksi, "SELECT SUM(total) as sum FROM detail_sr WHERE sr_id = '$sr_id'");
    $dataSUM = mysqli_fetch_assoc($querySUM);

    // ngecek nilainya dari table sr dulu buat dijumlahin
    $queryCekSR = mysqli_query($koneksi, "SELECT * FROM sr WHERE id_sr = '$sr_id'");
    $dataCekSR = mysqli_fetch_assoc($queryCekSR);

    $nominal = $dataSUM['sum'];
    $total_sr = $nominal - $dataCekSR['diskon'];
    $grand_total = $total_sr + $dataCekSR['nilai_ppn'];

    // update table sr
    $updateSr = mysqli_query($koneksi, "UPDATE sr SET nominal = '$nominal', total = '$total_sr', grand_total = '$grand_total' WHERE id_sr = '$sr_id';");

    if ($delete & $updateSr) {
        mysqli_commit($koneksi);

        setcookie('pesan2', 'Rincian SR berhasil dihapus!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        mysqli_rollback($koneksi);

        setcookie('pesan2', 'Rincian SR gagal dihapus!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}

?>
<!-- pindah -->
<!--  -->