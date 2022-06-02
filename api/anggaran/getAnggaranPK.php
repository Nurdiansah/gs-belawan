<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];


$query = mysqli_query($koneksi, "SELECT *
                                   FROM anggaran
                                   WHERE programkerja_id = '$id'");

$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_anggaran'] = $row['id_anggaran'];
    $data[$no]['nm_item'] = $row['nm_item'];

    $no++;
}


echo json_encode($data);
