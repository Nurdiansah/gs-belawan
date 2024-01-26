<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['id'])) {
    $id = dekripRambo($_GET['id']);

    $cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM detail_biayaops WHERE id_supplier = '$id'"));

    if ($cek > 0) {
        echo "<script>window.alert('Supplier gagal dihapus karena ada " . $cek . " transaksi menggunakan supplier tersebut!');
                    location=' index.php?p=supplier'
                </script>";
    } else {

        $delSupplier = mysqli_query($koneksi, "DELETE FROM supplier WHERE id_supplier = '$id'");
    }

    if ($delSupplier) {
        header('Location: index.php?p=supplier');
    }
}
