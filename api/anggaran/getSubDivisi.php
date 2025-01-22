<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];
$prj = $_POST['prj'];

$query = mysqli_query($koneksi, "SELECT * FROM divisi
                                    JOIN parent_divisi
                                        ON id_parent = parent_id
                                    JOIN cost_center
                                        ON id_divisi = divisi_id
                                    WHERE parent_id = '$id'
                                    AND pt_id = '$prj'
                                    AND id_divisi <> '0'
                                    ORDER BY nm_divisi ASC
                        ");


$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_divisi'] = $row['id_divisi'];
    $data[$no]['nm_divisi'] = $row['nm_divisi'];

    $no++;
}

echo json_encode($data);
