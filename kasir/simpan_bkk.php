
<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['simpan'])) {
    $id_bkk = $_POST['id_bkk'];
    $nominal = str_replace(".", "", $_POST['nominal']);
    $pengembalian = str_replace(".", "", $_POST['pengembalian']);
    $keterangan = $_POST['keterangan'];
    $remarks = $_POST['remarks'];
    $total = $nominal - $pengembalian;

    $cek_file = $_FILES['doc_pendukung']['name'];
    if ($cek_file == "") {
        $nm_file = $_POST['doc_pendukung_lama'];
    } else {
        $del_file = $_POST['doc_pendukung_lama'];
        unlink("../file/doc_pendukung/$del_file");
        $path = $_FILES['doc_pendukung']['tmp_name'];
        $nm_baru = $_FILES['doc_pendukung']['name'];
        $ekstensi = pathinfo($nm_baru, PATHINFO_EXTENSION);
        $nm_file = time() . "-doc-pendukung-bk." . $ekstensi;
        move_uploaded_file($path, "../file/doc_pendukung/" . $nm_file);
    }

    $query = mysqli_query($koneksi, "UPDATE bkk_final SET nilai_barang = '$nominal',
                                                nominal = '$total',
                                                pengembalian = '$pengembalian',
                                                keterangan = '$keterangan',
                                                remarks = '$remarks',
                                                doc_pendukung = '$nm_file'
                                            WHERE id = '$id_bkk'
                        ");

    if ($query) {
        setcookie('pesan', 'Berhasil di simpan !', time() + (3), '/');

        header("location:index.php?p=dtl_bkkditolak&id=$id_bkk");
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}
