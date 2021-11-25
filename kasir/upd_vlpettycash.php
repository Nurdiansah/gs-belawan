<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $nominal = $_POST['nominal'];
    $id_anggaran = $_POST['id_anggaran'];

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");
    $bulan    = date('n');

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menyetujui LPJ Pettycash id: $id');

									";
    mysqli_query($koneksi, $queryLog);

    $query1 = mysqli_query($koneksi, "UPDATE transaksi_pettycash SET status_pettycash=5, vrf_ksr ='$tanggal' WHERE id_pettycash='$id' ");

    // query realisasi
    $fieldRealisasi = fieldRealisasi($bulan);

    $queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas from anggaran WHERE id_anggaran = '$id_anggaran' ");
    $rowJA = mysqli_fetch_assoc($queryJumlahAwal);
    $jml_akhir = $rowJA['bulan'] + $nominal;
    $jumlah_realisasi = $rowJA['jumlah_realisasi'] + $nominal;
    $qty = $rowJA['realisasi_kuantitas'] + 1;
    // print_r($id_anggaran);
    // die;
    $queryRealisasi = "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir' , jumlah_realisasi = '$jumlah_realisasi' , realisasi_kuantitas = '$qty'
                                       WHERE id_anggaran ='$id_anggaran' ";
    mysqli_query($koneksi, $queryRealisasi);



    if ($query1) {
        header("location:index.php?p=verifikasi_pettylpj");
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
}
