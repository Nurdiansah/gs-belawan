<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT *
                                   FROM detail_sr
                                   WHERE id_dsr = '$id'");
echo json_encode($data = mysqli_fetch_assoc($query));
