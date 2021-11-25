<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT *
                                   FROM bkk_final
                                   WHERE id = '$id'");
echo json_encode($row = mysqli_fetch_assoc($query));
