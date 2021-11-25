<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_POST['kirim'])) {
    $id = $_POST['id'];

    $bulan    = date('n');

    // buat ngambil data anggaran dll
    $querySO = mysqli_query($koneksi, "SELECT * FROM so WHERE id_so = '$id'");
    $dataSO = mysqli_fetch_assoc($querySO);
    $id_anggaran = $dataSO['id_anggaran'];
    $grand_total = $dataSO['grand_total'];
    $DPP = $dataSO['nilai_barang'] + $dataSO['nilai_jasa'];

    // input file
    $doc_pembayaran = ($_FILES['doc_pembayaran']['name']);
    $path_doc_pembayaran = ($_FILES['doc_pembayaran']['tmp_name']);
    $ekstensi = pathinfo($doc_pembayaran, PATHINFO_EXTENSION);
    $namabaru = enkripRambo($id) . "-" . time() . "-doc-pembayaran-so." . $ekstensi;

    // Jika file yang di upload bukan pdf
    if ($ekstensi != 'pdf') {
        setcookie('pesan', 'File yang anda upload bukan berbentuk pdf, silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("Location: index.php?p=" . $_POST['url'] . "");
    } else {
        // Upload Document
        move_uploaded_file($path_doc_pembayaran, "../file/bukti_pembayaran/" . $namabaru);

        mysqli_begin_transaction($koneksi);

        // update table so
        $update = mysqli_query($koneksi, "UPDATE so SET status = '6', doc_pembayaran = '$namabaru' WHERE id_so = '$id'");

        // insert ke table bkk
        $insertBKK = mysqli_query($koneksi, "INSERT INTO bkk_final (id_jenispengajuan, pengajuan, created_on_bkk, id_anggaran, id_supplier, nilai_barang, nilai_ppn, nominal, keterangan, bukti_pembayaran, status_bkk)
                                            SELECT '2', 'SERVICE REQUEST', NOW(), id_anggaran, id_supplier, total,  nilai_ppn, grand_total, keterangan, doc_pembayaran, '1'
                                            FROM so
                                            WHERE id_so = '$id'
        						");


        //query realisasi anggaran
        $fieldRealisasi = fieldRealisasi($bulan);
        $queryRealisasi = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas  from anggaran WHERE id_anggaran = '$id_anggaran' ");
        $dataRealisasi = mysqli_fetch_assoc($queryRealisasi);
        $bulan_realisasi = $dataRealisasi['bulan'] + $DPP;
        $jumlah_realisasi = $dataRealisasi['jumlah_realisasi'] + $DPP;

        $updateRealisasi = mysqli_query($koneksi, "UPDATE anggaran SET $fieldRealisasi = '$bulan_realisasi', jumlah_realisasi = $jumlah_realisasi
												    WHERE id_anggaran = '$id_anggaran' ");

        if ($update && $insertBKK && $updateRealisasi) {
            mysqli_commit($koneksi);

            setcookie('pesan', 'Service Order berhasil dipayment!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');
        } else {
            mysqli_rollback($koneksi);

            setcookie('pesan', 'Service Order gagal dipayment!', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');
        }
        header("Location: index.php?p=" . $_POST['url'] . "");
    }
}
