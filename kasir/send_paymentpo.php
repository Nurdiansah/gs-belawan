<?php

session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_po = $_POST['id_po'];
    $id_bkk = $_POST['id_bkk'];
    $id_anggaran = $_POST['id_anggaran'];
    $persen = $_POST['total_persen'];
    $dari_bank = $_POST['dari_bank'];
    $dari_rekening = $_POST['dari_rekening'];
    $qty = $_POST['qty'];
    $nominal = $_POST['nominal'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_pph = $_POST['nilai_pph'];

    $dpp = $nilai_barang + $nilai_jasa;

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    if ($persen == 100) {
        $status_po = 10;
    } else {
        $status_po = 9;
    }

    $lokasi_bukti_pembayaran = ($_FILES['bukti_pembayaran']['tmp_name']);
    $bukti_pembayaran = ($_FILES['bukti_pembayaran']['name']);
    // 
    $nama_doc = $id_bkk . "-bukti_pembayaran_bkk";
    move_uploaded_file($lokasi_bukti_pembayaran, "../file/bukti_pembayaran/" . $nama_doc);

    //penomoran bkk
    $bulan    = date('n');
    $romawi    = getRomawi($bulan);
    $tahun     = date('Y');
    $nomor     = "/GS/" . $romawi . "/" . $tahun;

    $queryNomor = mysqli_query($koneksi, "SELECT MAX(nomor) from bkk_final WHERE month(release_on_bkk)='$bulan' ");

    $nomorMax = mysqli_fetch_array($queryNomor);
    if ($nomorMax) {

        $nilaikode = substr($nomorMax[0], 2);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $nomorAkhir = "" . str_pad($kode, 4, "0", STR_PAD_LEFT);
    } else {
        $nomorAkhir = "0001";
    }

    $nomorBkk = $nomorAkhir . $nomor;


    // log
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $id_user = $rowUser['id_user'];
    $nama = $rowUser['nama'];

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
                                        ('$tanggal', '$nama', 'Melakukan Pembayaran BKK : $id_bkk');

                                        ";
    mysqli_query($koneksi, $queryLog);

    // query update po
    $querypo = "UPDATE po
                        SET status_po= '$status_po' , persentase = '$persen'
                        WHERE id_po = '$id_po' ";
    $hasil = mysqli_query($koneksi, $querypo);



    // query update bkk
    $querybkk = "UPDATE bkk_final
                                    SET status_bkk= '4' , bukti_pembayaran = '$nama_doc',
                                    nomor = '$nomorAkhir', no_bkk = '$nomorBkk',
                                    dari_bank = '$dari_bank', dari_rekening = '$dari_rekening', release_on_bkk = '$tanggal'
                                    WHERE id = '$id_bkk' ";
    mysqli_query($koneksi, $querybkk);


    // query realisasi
    $fieldRealisasi = fieldRealisasi($bulan);

    $queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan, jumlah_realisasi, realisasi_kuantitas from anggaran WHERE id_anggaran = '$id_anggaran' ");
    $rowJA = mysqli_fetch_assoc($queryJumlahAwal);
    $jml_akhir = $rowJA['bulan'] + $dpp;
    $jumlah_realisasi = $rowJA['jumlah_realisasi'] + $dpp;
    $realisasi_kuantitas = $rowJA['realisasi_kuantitas'] + $qty;

    $queryRealisasi = "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir', jumlah_realisasi = '$jumlah_realisasi', realisasi_kuantitas = '$realisasi_kuantitas'
                                                WHERE id_anggaran ='$id_anggaran' ";
    mysqli_query($koneksi, $queryRealisasi);

    if ($hasil) {
        header("location:index.php?p=payment_po");
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
}
