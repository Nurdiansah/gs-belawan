<?php
session_start();
include "../fungsi/koneksi.php";

if (isset($_POST['edit'])) {
    $id_pettycash = $_POST['id'];
    $kd_pettycash = $_POST['kd_pettycash'];
    $id_anggaran = $_POST['id_anggaran'];
    $keterangan_pettycash = $_POST['keterangan'];
    $total_pettycash = str_replace(".", "", $_POST['nominal']);

    if ($total_pettycash > 100000) {      // jika diatas 100rb gabisa
        setcookie('pesan', 'Nominal Pettycash harus lebih kecil dari Rp. 100.000 !', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');

        header("location:index.php?p=buat_petty");
    } else {

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

        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date("Y-m-d H:i:s");

        // UPDATE
        $query = "UPDATE transaksi_pettycash SET id_anggaran = '$id_anggaran' , keterangan_pettycash = '$keterangan_pettycash', 
                                                total_pettycash = '$total_pettycash', last_modified_pettycash_on = '$tanggal' ,
                                                doc_lpj_pettycash = '$namabaru'
                                                WHERE id_pettycash ='$id_pettycash' ";
        $hasil = mysqli_query($koneksi, $query);
    }

    if ($hasil) {
        header("location:index.php?p=buat_petty");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>
<!-- pindah -->
<!--  -->