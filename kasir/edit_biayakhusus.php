<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

// edit perubahan data
if (isset($_POST['edit'])) {
    // cek jika inputan file tidak ada maka memakai file lama
    $cek_lpj = ($_FILES['doc_pendukung']['name']);
    if ($cek_lpj == '') {

        $nama_doc = $_POST['doc_pendukung_lama'];
    } else {

        // Haspus dulu dokument lama nya
        $doc_lama = $_POST['doc_pendukung_lama'];
        unlink("../file/doc_pendukung/" . $doc_lama);

        // Upload document pendukung yang baru
        $lokasi_doc_pendukung = ($_FILES['doc_pendukung']['tmp_name']);
        $doc_pendukung = ($_FILES['doc_pendukung']['name']);
        $ekstensi = pathinfo($doc_pendukung, PATHINFO_EXTENSION);
        $nama_doc = "doc-pendukung-biaya-khusus-" . time() . "." . $ekstensi;
        move_uploaded_file($lokasi_doc_pendukung, "../file/doc_pendukung/" . $nama_doc);
    }
    // Akhir upload document pendukung

    $id = $_POST['id'];
    $id_anggaran = $_POST['id_anggaran'];
    $nominal = str_replace(".", "", $_POST['nominal']);
    $keterangan = $_POST['keterangan'];
    $remarks = $_POST['remarks'];

    $updateBK = mysqli_query($koneksi, "UPDATE bkk_final SET nominal = '$nominal',
                                                            keterangan = '$keterangan',
                                                            remarks = '$remarks',
                                                            doc_pendukung = '$nama_doc'
                                                            WHERE id = '$id'
                                                            ");

    if ($updateBK) {
        setcookie('pesan', 'Berhasil di edit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header('Location: index.php?p=biaya_khusus&id=' . $id . '');
    }
}
// end edit perubahan data