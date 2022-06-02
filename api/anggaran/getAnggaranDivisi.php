<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];


$query = mysqli_query($koneksi, "SELECT *
                                   FROM cost_center cc
                                   JOIN program_kerja pk
                                   ON pk.costcenter_id = cc.id_costcenter
                                   WHERE cc.divisi_id = '$id'");

$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_programkerja'] = $row['id_programkerja'];
    $data[$no]['nm_programkerja'] = $row['nm_programkerja'];

    $no++;
}


echo json_encode($data);
