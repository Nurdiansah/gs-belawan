
<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal'];

    $query = mysqli_query($koneksi, "UPDATE bkk_final SET release_on_bkk = '$tanggal', status_bkk = '4' WHERE id = '$id'");

    if ($query) {
        header("location: index.php?p=outstanding_cek");
    } else {
        echo "Error cui " . mysqli_error($koneksi);
    }
}
