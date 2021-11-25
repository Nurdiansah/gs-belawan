<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT *
                                   FROM bkk_final bf
                                   JOIN tagihan_po tp
                                   ON tp.id_tagihan = bf.id_tagihan
                                   WHERE id = '$id'");
echo json_encode($row = mysqli_fetch_assoc($query));
