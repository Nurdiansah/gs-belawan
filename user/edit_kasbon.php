<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['edit'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $id_dbo = $_POST['id_dbo'];
    $id_anggaran = $_POST['id_anggaran'];
    $keterangan = $_POST['keterangan'];
    $harga_akhir = penghilangTitik($_POST['nominal']);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $cek_doc = $_FILES['doc_pendukung']['name'];
    if ($cek_doc == "") {
        $nm_baru = $_POST['doc_pendukung_lama'];
    } else {
        $doc_lama = $_POST['doc_pendukung_lama'];
        unlink("../file/doc_pendukung/" . $doc_lama);
        $path = $_FILES['doc_pendukung']['tmp_name'];
        $ekstensi = pathinfo($_FILES['doc_pendukung']['name'], PATHINFO_EXTENSION);
        $nm_baru = $id_kasbon . "-doc-pendukung_REVISI." . $ekstensi;

        move_uploaded_file($path, "../file/doc_pendukung/" . $nm_baru);
    }

    // UPDATE DBO
    $queryDbo = "UPDATE detail_biayaops SET id_anggaran = '$id_anggaran', keterangan = '$keterangan'
                                WHERE id = '$id_dbo' ";
    mysqli_query($koneksi, $queryDbo);

    // UPDATE
    $query = "UPDATE kasbon SET harga_akhir = '$harga_akhir', doc_pendukung = '$nm_baru'
                                WHERE id_kasbon = '$id_kasbon' ";
    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        setcookie('pesan', 'Kasbon Berhasil di edit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("Location: index.php?p=kasbon_detail&id=$id_kasbon");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->