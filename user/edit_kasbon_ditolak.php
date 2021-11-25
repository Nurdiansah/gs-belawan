<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['edit'])) {
    $id_kasbon = $_POST['id'];
    $id_dbo = $_POST['id_dbo'];
    $id_anggaran = $_POST['id_anggaran'];
    $keterangan = $_POST['keterangan'];
    $vrf_pajak = $_POST['vrf_pajak'];
    $harga_akhir = str_replace(".", "", $_POST['nominal']);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // cek jika inputan file tidak ada maka memakai file lama
    $cek_lpj = ($_FILES['doc_pendukung']['name']);
    if ($cek_lpj == '') {
        $nama_doc = $_POST['doc_pendukung_lama'];
    } else {
        $lpj_lama = $_POST['doc_pendukung_lama'];
        unlink("../file/doc_pendukung/" . $lpj_lama);
        $lokasi_doc_pendukung = ($_FILES['doc_pendukung']['tmp_name']);
        $doc_pendukung = ($_FILES['doc_pendukung']['name']);
        $ekstensi = pathinfo($doc_pendukung, PATHINFO_EXTENSION);
        $nama_doc = $id_kasbon . "-doc-lpj-kasbon-" . time() . "." . $ekstensi;
        move_uploaded_file($lokasi_doc_pendukung, "../file/doc_pendukung/" . $nama_doc);
    }

    // UPDATE DBO
    $queryDbo = "UPDATE detail_biayaops SET id_anggaran = '$id_anggaran', keterangan = '$keterangan'
                                WHERE id ='$id_dbo' ";
    mysqli_query($koneksi, $queryDbo);

    // UPDATE
    $query = "UPDATE kasbon SET harga_akhir = '$harga_akhir', vrf_pajak = '$vrf_pajak', doc_pendukung = '$nama_doc'
                                WHERE id_kasbon ='$id_kasbon' ";
    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        setcookie('pesan', 'Kasbon Berhasil di edit!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location: index.php?p=ditolak_kasbon&sp=tolak_user");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->