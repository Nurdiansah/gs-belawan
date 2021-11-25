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

    $query = "INSERT INTO detail_sr (sr_id, deskripsi, merk, type, spesifikasi, qty, satuan, keterangan) VALUES 
						            ('$sr_id', '$deskripsi', '$merk', '$type', '$spesifikasi', '$qty', '$satuan', '$keterangan');
						";
    $hasil  = mysqli_query($koneksi, $query);

    if ($hasil) {
        setcookie('pesan2', 'Rincian SR berhasil ditambah!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        setcookie('pesan2', 'Rincian SR gagal ditambah!<br>' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=" . $_POST['url'] . "");
}

?>
<!-- pindah -->
<!--  -->