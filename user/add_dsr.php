<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['create'])) {
    $sr_id = $_POST['sr_id'];
    $deskripsi = $_POST['deskripsi'];
    $merk = $_POST['merk'];
    $type = $_POST['type'];
    $spesifikasi = $_POST['spesifikasi'];
    $qty = $_POST['qty'];
    $satuan = $_POST['satuan'];
    $keterangan = $_POST['keterangan'];
    // Insert ke detail biaya ops

    $query = "INSERT INTO detail_sr ( sr_id, deskripsi, merk, type, spesifikasi, qty, satuan, keterangan) VALUES 
						            ( '$sr_id', '$deskripsi', '$merk', '$type', '$spesifikasi', '$qty', '$satuan', '$keterangan');
						";
    $hasil  = mysqli_query($koneksi, $query);

    if ($hasil) {
        setcookie('pesan2', 'Data berhasil di buat!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:index.php?p=" . $_POST['url'] . "&id=" . enkripRambo($sr_id));
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->