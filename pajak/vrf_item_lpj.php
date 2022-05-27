<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_kasbon = $_POST['id_kasbon'];

    // str_replace(".", "", $_POST['harga']);

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $biaya_lain = $_POST['biaya_lain'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $nilai_pph = str_replace(".", "", $_POST['pph_nilai']);
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml']);

    $query = mysqli_query($koneksi, "UPDATE kasbon SET nilai_barang = '$nilai_barang',
                                            nilai_jasa = '$nilai_jasa', 
                                            biaya_lain = '$biaya_lain', 
                                            nilai_ppn = '$nilai_ppn',
                                            nilai_pph = '$nilai_pph', 
                                            id_pph = '$id_pph',
                                            harga_akhir = '$harga',
                                            status_kasbon = '7',
                                            komentar = NULL
                                        WHERE id_kasbon ='$id_kasbon' ");

    if ($query) {
        setcookie('pesan', 'LPJ berhasil di Verifikasi!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        setcookie('pesan', 'LPJ gagal di Verifikasi!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_lpj");
} else if (isset($_POST['simpan'])) {
    $id_kasbon = $_POST['id_kasbon'];

    // str_replace(".", "", $_POST['harga']);

    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $biaya_lain = $_POST['biaya_lain'];
    $nilai_ppn = str_replace(".", "", $_POST['ppn_nilai']);
    $nilai_pph = str_replace(".", "", $_POST['pph_nilai']);
    $id_pph = $_POST['id_pph'];
    $harga = str_replace(".", "", $_POST['jml']);

    $query = mysqli_query($koneksi, "UPDATE kasbon SET nilai_barang = '$nilai_barang',
                                            nilai_jasa = '$nilai_jasa', 
                                            biaya_lain = '$biaya_lain', 
                                            nilai_ppn = '$nilai_ppn',
                                            nilai_pph = '$nilai_pph', 
                                            id_pph = '$id_pph',
                                            harga_akhir = '$harga'
                                        WHERE id_kasbon ='$id_kasbon' ");

    if ($query) {
        setcookie('pesan', 'LPJ berhasil di Simpan!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        setcookie('pesan', 'LPJ gagal di Simpan!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=verifikasi_lpj&id=" . enkripRambo($id_kasbon));
}
