<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];
$tahun = date("Y");

// $query = mysqli_query($koneksi, "SELECT id_programkerja, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi) AS cost_center, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_programkerja
// FROM cost_center
// JOIN pt
//     ON id_pt = pt_id
// JOIN divisi
//     ON id_divisi = divisi_id
// JOIN parent_divisi
//     ON id_parent = parent_id
// JOIN program_kerja
//     ON id_costcenter = costcenter_id
// WHERE id_divisi = '$id'
//                                 ");

// $no = 0;
// while ($row = mysqli_fetch_array($query)) {
//     $data[$no]['id_programkerja'] = $row['id_programkerja'];
//     $data[$no]['nm_programkerja'] = $row['nm_programkerja'];

//     $no++;
// }
// 

$query = mysqli_query($koneksi, "SELECT id_programkerja, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi) AS cost_center, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_programkerja
                                      FROM cost_center
                                    JOIN pt
                                        ON id_pt = pt_id
                                    JOIN divisi
                                        ON id_divisi = divisi_id
                                    JOIN parent_divisi
                                        ON id_parent = parent_id
                                    JOIN program_kerja
                                        ON id_costcenter = costcenter_id
                                    WHERE id_divisi = '$id'
                                    AND tahun = '$tahun'");

$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_programkerja'] = $row['id_programkerja'];
    $data[$no]['program_kerja'] = $row['program_kerja'];
    $data[$no]['nm_programkerja'] = $row['nm_programkerja'];

    $no++;
}


echo json_encode($data);

// echo json_encode($id);
