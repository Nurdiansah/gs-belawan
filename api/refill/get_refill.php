<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT *
                                   FROM refill_funds
                                   WHERE id_refill = '$id'");
echo json_encode($row = mysqli_fetch_assoc($query));
