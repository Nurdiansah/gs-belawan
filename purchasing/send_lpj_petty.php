<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {

    $tanggal = dateNow();

    $id_pettycash = $_POST['id_pettycash'];
    $harga = $_POST['nominal_pengajuan'];
    $aksi = $_POST['aksi'];
    $nominal = penghilangTitik($_POST['nominal']);


    // Upload Document
    $lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
    $doc_lpj = ($_FILES['doc_lpj']['name']);
    $ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);

    // 
    $nama_doc = $id_pettycash . "-lpj-pettycash." . $ekstensi;
    move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);
    // e

    if ($aksi == 'penambahan') {
        $hargaAkhir = $harga + $nominal;

        $query = "UPDATE transaksi_pettycash SET  doc_lpj_pettycash = '$nama_doc' , $aksi = '$nominal',
									status_pettycash = '4', lpj_user = '$tanggal' , total_pettycash = '$hargaAkhir', komentar_pettycash = NULL
                                    WHERE id_pettycash ='$id_pettycash' ";
    } elseif ($aksi == 'pengembalian') {
        $hargaAkhir = $harga - $nominal;

        $query = "UPDATE transaksi_pettycash SET  doc_lpj_pettycash = '$nama_doc' , $aksi = '$nominal',
                            status_pettycash = '4', lpj_user = '$tanggal' , total_pettycash = '$hargaAkhir', komentar_pettycash = NULL
        WHERE id_pettycash ='$id_pettycash' ";
    } else {
        $query = "UPDATE transaksi_pettycash SET  doc_lpj_pettycash = '$nama_doc' , 
									status_pettycash = '4', lpj_user = '$tanggal' , total_pettycash = '$harga', komentar_pettycash = NULL
                                    WHERE id_pettycash ='$id_pettycash' ";
    }


    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {
        header("location:index.php?p=lpj_petty");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->