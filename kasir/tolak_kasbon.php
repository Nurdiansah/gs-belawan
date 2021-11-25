<?php

include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $komentar = "@" .  $_POST['Nama'] . " : " . $_POST['komentar'];

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $pengembalian = $_POST['pengembalian'];
    $penambahan = $_POST['penambahan'];

    if ($nilai_barang > 0) {
        // Nilai Barang
        $nilai_barang = ($nilai_barang +  $pengembalian) - $penambahan;
    } else if ($nilai_jasa > 0) {

        // Nilai Jasa
        $nilai_jasa = ($nilai_jasa +  $pengembalian) - $penambahan;
    }

    $query = mysqli_query($koneksi, "UPDATE kasbon SET status_kasbon = '505', komentar = '$komentar',
                                            harga_akhir = harga_akhir + pengembalian,   -- harga akhir ditambahn pengembalian
                                            harga_akhir = harga_akhir - penambahan,     -- harga akhir dikurang penambahan
                                            pengembalian = '0',
                                            penambahan = '0',
                                            nilai_barang = '$nilai_barang', 
                                            nilai_jasa = '$nilai_jasa'
                                        WHERE id_kasbon = '$id_kasbon'");

    if ($query) {
        header("Location: index.php?p=" . $_POST['url'] . "");
    } else {
        echo "Kayanya ada yg error " . mysqli_error($koneksi);
    }
}
