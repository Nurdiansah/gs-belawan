<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT DISTINCT id_parent, nm_parent
                                    FROM parent_divisi
                                    RIGHT JOIN divisi
                                        ON id_parent = parent_id
                                    JOIN cost_center
                                        ON id_divisi = divisi_id
                                    WHERE pt_id = '$id'
                                    AND id_parent <> '0'
                                    ORDER BY nm_parent ASC
                        ");

$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_parent'] = $row['id_parent'];
    $data[$no]['nm_parent'] = $row['nm_parent'];

    $no++;
}

echo json_encode($data);
