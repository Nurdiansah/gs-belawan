<?php
session_start();
include "../fungsi/koneksi.php";

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");

if (isset($_POST['kirim'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $harga = $_POST['harga'];
    $nominal_pengembalian = str_replace(".", "", $_POST['nominal_pengembalian']);
    $aksi = $_POST['aksi'];

    if ($aksi == "pengembalian") {
        $hargaAkhir = $harga - $nominal_pengembalian;
        $field = $aksi . " = '" . $nominal_pengembalian . "', ";
    } elseif ($aksi == "penambahan") {
        $hargaAkhir = $harga + $nominal_pengembalian;
        $field = $aksi . " = '" . $nominal_pengembalian . "', ";
    } else {
        $hargaAkhir = $harga;
        $field = "";
    }

    // echo $hargaAkhir . "<br>" . $field;
    // die;

    // cek jika inputan file tidak ada maka memakai file lama
    $cek_lpj = ($_FILES['doc_lpj']['name']);
    if ($cek_lpj == '') {
        $nama_doc = $_POST['doc_lpj_lama'];
    } else {
        $lpj_lama = $_POST['doc_lpj_lama'];
        if (isset($lpj_lama)) {
            unlink("../file/doc_lpj/" . $lpj_lama);
        }
        $lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
        $doc_lpj = ($_FILES['doc_lpj']['name']);
        $ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);
        $nama_doc = $id_kasbon . "-doc-lpj-kasbon." . $ekstensi;
        move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);
    }

    $query = "UPDATE kasbon SET doc_lpj = '$nama_doc', $field
                                status_kasbon = '7', waktu_lpj = '$tanggal', harga_akhir = '$hargaAkhir', komentar = NULL
                            WHERE id_kasbon ='$id_kasbon' ";

    $hasil = mysqli_query($koneksi, $query);


    if ($hasil) {
        setcookie('pesan', 'LPJ berhasil dikonfirmasi ulang!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location: index.php?p=ditolak_mr&sp=ditolak_kasbon");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->