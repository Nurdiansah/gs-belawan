<?php
include "../../fungsi/koneksi.php";
include "../../fungsi/fungsi.php";

$id = dekripRambo($_POST['id']);

$query = mysqli_query($koneksi, "SELECT *
                                   FROM bkk
                                   WHERE id_bkk = '$id'");
echo json_encode($row = mysqli_fetch_assoc($query));
