<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $total = $_POST['total'];
    $keterangan     = $_POST['keterangan'];
    $id_anggaran = $_POST['id_anggaran'];
    $id_supplier = $_POST['id_supplier'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $nilai_ppn = $_POST['nilai_ppn'];
    $nilai_pph = $_POST['nilai_pph'];
    $id_pph = $_POST['id_pph'];
    $pengembalian = $_POST['pengembalian'];
    $id_anggaran = $_POST['id_anggaran'];
    $qty = $_POST['qty'];

    $DPP = $nilai_barang + $nilai_jasa;

    if ($id_pph == '') {
        $id_pph = '4';
    }

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");
    $tgl_bkk = date("Y-m-d ");

    // query user
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $id_user = $rowUser['id_user'];
    $nama = $rowUser['nama'];

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
										('$tanggal', '$nama', 'Menyetujui LPJ Kasbon id : $id_kasbon');

										";
    mysqli_query($koneksi, $queryLog);

    //deklarasi tanggal
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


    //query di kualifikasikan ke bkk final
    $queryBkkfinal = "INSERT INTO bkk_final (id_jenispengajuan, pengajuan, id_kdtransaksi, created_on_bkk, id_anggaran, id_supplier, nilai_barang, nilai_jasa, nilai_ppn, nilai_pph, id_pph, pengembalian, nominal, keterangan, status_bkk) VALUES
													('1', 'KASBON', '$id_kasbon', '$tanggal', '$id_anggaran','$id_supplier', '$nilai_barang','$nilai_jasa', '$nilai_ppn', '$nilai_pph', '$id_pph','$pengembalian','$total', '$keterangan', '1')
										";
    $hasil1 =  mysqli_query($koneksi, $queryBkkfinal);

    //query realisasi anggaran
    $fieldRealisasi = fieldRealisasi($bulan);
    $queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas  from anggaran WHERE id_anggaran = '$id_anggaran' ");
    $rowJA = mysqli_fetch_assoc($queryJumlahAwal);
    $jml_akhir = $rowJA['bulan'] + $DPP;
    $jumlah_realisasi = $rowJA['jumlah_realisasi'] + $DPP;
    $qty_akhir = $rowJA['realisasi_kuantitas'] + $qty;


    $queryRealisasi = "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir' , jumlah_realisasi = $jumlah_realisasi ,realisasi_kuantitas = $qty_akhir
												WHERE id_anggaran ='$id_anggaran' ";
    $hasil2 = mysqli_query($koneksi, $queryRealisasi);

    // print_r($queryRealisasi);
    // die;

    $query = "UPDATE kasbon SET status_kasbon = '8'
							WHERE id_kasbon ='$id_kasbon' ";

    $hasil3 = mysqli_query($koneksi, $query);

    if ($hasil1 && $hasil2 && $hasil3) {
        header("location:index.php?p=verifikasi_kasbonlpj&sp=vlk_sr");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->