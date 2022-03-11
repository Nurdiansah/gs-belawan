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
    $pengajuan = $_POST['pengajuan'];
    $id_dbo = $_POST['id_dbo'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    // Query User
    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $id_user = $rowUser['id_user'];
    $nama = $rowUser['nama'];
    $id_divisi = $rowUser['id_divisi'];
    $id_manager = $rowUser['id_manager'];


    $cek_penawaran = ($_FILES['doc_penawaran']['name']);
    if ($cek_penawaran == '') {
        $namabaru = $_POST['doc_penawaran_lama'];
    } else {
        $del_penawaran = $_POST['doc_penawaran_lama'];
        // if (isset($del_penawaran)) {
        unlink("../file/doc_penawaran/$del_penawaran");
        // }
        $lokasi_doc_penawaran = ($_FILES['doc_penawaran']['tmp_name']);
        $namabaru = ($_FILES['doc_penawaran']['name']);
        $ekstensi = pathinfo($namabaru, PATHINFO_EXTENSION);
        $namabaru = $id_dbo . "-doc-penawaran-rev." . $ekstensi;
        move_uploaded_file($lokasi_doc_penawaran, "../file/doc_penawaran/" . $namabaru);

        $updateDBO = mysqli_query($koneksi, "UPDATE detail_biayaops SET doc_penawaran = '$namabaru' WHERE id = '$id_dbo'");
    }

    if ($status_petty == "10") {
        $status = "1";
    } elseif ($status_petty == "202" || ($pengajuan == "mr" && $status_petty == "10")) {
        $status = "2";
    }

    // Kalo tombol simpan
    if (isset($_POST['simpan'])) {
        $query = "UPDATE transaksi_pettycash SET id_anggaran = '$id_anggaran' , keterangan_pettycash = '$keterangan_pettycash',
                                                total_pettycash = '$total_pettycash', last_modified_pettycash_on = '$tanggal', last_modified_pettycash_by = '$nama'
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
                                                total_pettycash = '$total_pettycash', last_modified_pettycash_on = '$tanggal', last_modified_pettycash_by = '$nama', status_pettycash = '$status'
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