<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['submit'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $harga = $_POST['harga'];
    $vrf_pajak = $_POST['vrf_pajak'];
    $nominal_pengembalian = str_replace(".", "", $_POST['nominal_pengembalian']);
    $aksi = $_POST['aksi'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];

    if ($aksi == "pengembalian") {
        $hargaAkhir = $harga - $nominal_pengembalian;
        $field = $aksi . " = '" . $nominal_pengembalian . "', ";

        if ($nilai_barang > 0) {
            // Nilai Barang
            $nilai_barang = $nilai_barang - $nominal_pengembalian;
        } else {
            if ($nilai_jasa > 0) {
                // Nilai Jasa
                $nilai_jasa = $nilai_jasa - $nominal_pengembalian;
            }
        }
    } elseif ($aksi == "penambahan") {
        $hargaAkhir = $harga + $nominal_pengembalian;
        $field = $aksi . " = '" . $nominal_pengembalian . "', ";

        if ($nilai_barang > 0) {
            // Nilai Barang
            $nilai_barang = $nilai_barang + $nominal_pengembalian;
        } else {
            if ($nilai_jasa > 0) {
                // Nilai Jasa
                $nilai_jasa = $nilai_jasa + $nominal_pengembalian;
            }
        }
    } else {
        $hargaAkhir = $harga;
        $field = "";
    }

    $lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
    $doc_lpj = ($_FILES['doc_lpj']['name']);
    $ekstensi = pathinfo($doc_lpj, PATHINFO_EXTENSION);

    // Jika file yang di upload bukan pdf
    if ($ekstensi != 'pdf') {
        setcookie('pesan', 'File LPJ yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=kasbon_proses&sp=kp_user");
    } else {

        $nama_doc = $id_kasbon . "-doc-lpj-kasbon." . $ekstensi;
        move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);

        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date("Y-m-d H:i:s");

        if ($vrf_pajak == 'bp') {
            $query = "UPDATE kasbon SET doc_lpj = '$nama_doc', $field   -- antara nominal pengembalian atau penambahan
                                status_kasbon = '9', waktu_lpj = '$tanggal' , harga_akhir = '$hargaAkhir'
                                , nilai_barang = '$nilai_barang', nilai_jasa = '$nilai_jasa'
                                WHERE id_kasbon ='$id_kasbon' ";

            $hasil = mysqli_query($koneksi, $query);
        } else if ($vrf_pajak == 'as') {
            $query = "UPDATE kasbon SET doc_lpj = '$nama_doc',  $field     -- antara nominal pengembalian atau penambahan
                                status_kasbon = '2', waktu_lpj = '$tanggal' , harga_akhir = '$hargaAkhir'
                                , nilai_barang = '$nilai_barang', nilai_jasa = '$nilai_jasa'
                                WHERE id_kasbon ='$id_kasbon' ";

            $hasil = mysqli_query($koneksi, $query);
        }

        if ($hasil) {
            setcookie('pesan', 'Kasbon berhasil di LPJ!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');

            header("location:index.php?p=kasbon_proses&sp=kp_user");
        } else {
            die("ada kesalahan : " . mysqli_error($koneksi));
        }
    }
}

?>
<!-- pindah -->
<!--  -->