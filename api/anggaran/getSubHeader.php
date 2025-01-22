<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT * FROM sub_header WHERE id_header = '$id' ORDER BY nm_subheader ASC
                                ");

$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_subheader'] = $row['id_subheader'];
    $data[$no]['nm_subheader'] = $row['nm_subheader'];

    $no++;
}

echo json_encode($data);
