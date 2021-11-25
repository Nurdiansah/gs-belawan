<?php

session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id = $_POST['id'];
    $komentar = $_POST['komentar'];

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]' ");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $nama = $rowUser['nama'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$nama', 'Menolak Pengajuan Pettycash id: $id');

									";
    mysqli_query($koneksi, $queryLog);

    $query1 = mysqli_query($koneksi, "UPDATE transaksi_pettycash SET status_pettycash = 101, komentar_pettycash = '$komentar',
                                            total_pettycash = total_pettycash + pengembalian,   -- harga akhir ditambahn pengembalian
                                            total_pettycash = total_pettycash - penambahan,  -- harga akhir dikurang penambahan
                                            pengembalian = 0, penambahan = 0
                                        WHERE id_pettycash='$id' ");


    if ($query1) {
        header("location:index.php?p=verifikasi_pettylpj");
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
}
