<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['update'])) {
    $id_dsr = $_POST['id_dsr'];
    $sr_id = $_POST['sr_id'];
    $qty = $_POST['qty'];
    $sub_total = str_replace(".", "", $_POST['sub_total']);
    $total = $sub_total * $qty;

    mysqli_begin_transaction($koneksi);

    // UPDATE table detail_sr
    $updateDtl = mysqli_query($koneksi, "UPDATE detail_sr SET sub_total = '$sub_total', total = '$total' WHERE id_dsr = '$id_dsr'");

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

    if ($updateSr && $updateDtl) {
        mysqli_commit($koneksi);

        setcookie('pesan', 'Rincian SR berhasil diupdate!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Rincian SR gagal diupdate!<br>' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=detail_sr&id=" . $_POST['id'] . "&pg=" . $_POST['pg'] . "");
}
