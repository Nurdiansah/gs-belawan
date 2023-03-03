<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['revisi']) || isset($_POST['simpan'])) {

    $id_pettycash = $_POST['id'];
    $kd_pettycash = $_POST['kd_pettycash'];
    $id_anggaran = $_POST['id_anggaran'];
    $keterangan_pettycash = $_POST['keterangan'];
    $total_pettycash = str_replace(".", "", $_POST['nominal']);
    $status_petty = $_POST['status_petty'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // Query User
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $id_user = $rowUser['id_user'];
    $nama = $rowUser['nama'];
    $id_divisi = $rowUser['id_divisi'];
    $id_manager = $rowUser['id_manager'];

    // Cek dulu apakah ada submitan ubah lpj
    $cek_lpj = ($_FILES['doc_lpj']['name']);
    if ($cek_lpj == '') {
        $namabaru = $_POST['doc_lpj_lama'];
    } else {
        $del_lpj = $_POST['doc_lpj_lama'];
        if (isset($del_lpj)) {
            unlink("../file/doc_lpj/$del_lpj");
        }
        $lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
        $doc_lpj = ($_FILES['doc_lpj']['name']);
        $ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);
        $namabaru = $kd_pettycash . "-lpj-pettycash-rev-" . time() . "." . $ekstensi;
        move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $namabaru);
    }

    if ($status_petty == "10") {
        $status = "1";
    } elseif ($status_petty == "202") {
        $status = "2";
    }

    // Kalo tombol simpan
    if (isset($_POST['simpan'])) {
        $query = "UPDATE transaksi_pettycash SET id_anggaran = '$id_anggaran' , keterangan_pettycash = '$keterangan_pettycash'
                                                total_pettycash = '$total_pettycash', last_modified_pettycash_on = '$tanggal', last_modified_pettycash_by = '$nama',doc_lpj_pettycash = '$namabaru'
                                                WHERE id_pettycash ='$id_pettycash' ";
        $hasil = mysqli_query($koneksi, $query);

        if ($hasil) {
            setcookie('pesan', 'Berhasil tersimpan !', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');

            header("location:index.php?p=revisi_petty&id=$id_pettycash");
        } else {
            die("ada kesalahan : " . mysqli_error($koneksi));
        }
    } else if (isset($_POST['revisi'])) { // Kalo tombol yang di klik tombol revisi
        $query = "UPDATE transaksi_pettycash SET id_anggaran = '$id_anggaran' , keterangan_pettycash = '$keterangan_pettycash', komentar_pettycash = NULL,
                                                total_pettycash = '$total_pettycash', last_modified_pettycash_on = '$tanggal', last_modified_pettycash_by = '$nama', status_pettycash = '$status', doc_lpj_pettycash = '$namabaru'  
                                                WHERE id_pettycash ='$id_pettycash' ";
        $hasil = mysqli_query($koneksi, $query);
        if ($hasil) {
            setcookie('pesan', 'Berhasil terkirim !', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');

            header("location:index.php?p=proses_petty");
        } else {
            die("ada kesalahan : " . mysqli_error($koneksi));
        }
    }

    // LOG
    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
                                                ('$tanggal', '$nama', 'Merevisi Pengajuan Petty Cash id = $id_pettycash');

                                                ";
    mysqli_query($koneksi, $queryLog);

    // UPDATE    



}

?>
<!-- pindah -->
<!--  -->