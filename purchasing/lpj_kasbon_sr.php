<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id_kasbon = $_POST['id_kasbon'];
    $harga = $_POST['harga'];
    $nominal_pengembalian = str_replace(".", "", $_POST['nominal_pengembalian']);

    $hargaAkhir = $harga - $nominal_pengembalian;

    $lokasi_doc_lpj = ($_FILES['doc_lpj']['tmp_name']);
    $doc_lpj = ($_FILES['doc_lpj']['name']);

    // 
    $nama_doc = $id_kasbon . "-doc-lpj-kasbon.pdf";
    move_uploaded_file($lokasi_doc_lpj, "../file/doc_lpj/" . $nama_doc);

    $tanggal = dateNow();

    $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
    $rowUser = mysqli_fetch_assoc($queryUser);
    $id_user = $rowUser['id_user'];
    $nama = $rowUser['nama'];

    $query = "UPDATE kasbon SET pengembalian = '$nominal_pengembalian' , doc_lpj = '$nama_doc' , 
									status_kasbon = '7', waktu_lpj = '$tanggal' , harga_akhir = '$hargaAkhir'
                                    WHERE id_kasbon ='$id_kasbon' ";

    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {

        setcookie('pesan', 'LPJ Berhasil di kirim!<br>' . mysqli_error($koneksi) . '', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
        header("location:index.php?p=lpj_kasbon&sp=lpj_ksr");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->