<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['simpan'])) {
    $id_supplier = $_POST['id_supplier'];
    $nm_supplier = $_POST['nm_supplier'];
    $pic_supplier = $_POST['pic_supplier'];
    $no_telponsupplier = $_POST['no_telponsupplier'];
    $no_faxsupplier = $_POST['no_faxsupplier'];
    $email_supplier = $_POST['email_supplier'];
    $alamat_supplier = $_POST['alamat_supplier'];
    $kategori_supplier = $_POST['kategori_supplier'];

    $updSupplier = mysqli_query($koneksi, "UPDATE supplier SET nm_supplier = '$nm_supplier',
                                                                pic_supplier = '$pic_supplier',
                                                                no_telponsupplier = '$no_telponsupplier',
                                                                no_faxsupplier = '$no_faxsupplier',
                                                                email_supplier = '$email_supplier',
                                                                alamat_supplier = '$alamat_supplier',
                                                                kategori_supplier = '$kategori_supplier'
                                            WHERE id_supplier = '$id_supplier'
                        ");

    if ($updSupplier) {
        header('Location: index.php?p=supplier');
    }
}
