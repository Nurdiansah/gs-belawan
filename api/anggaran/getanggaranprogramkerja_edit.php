<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];
$tahun = date("Y");

$query = mysqli_query($koneksi, "SELECT id_anggaran, kd_anggaran, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item
                                    FROM anggaran agg
                                    JOIN program_kerja
                                        ON programkerja_id = id_programkerja
                                    JOIN cost_center cc
                                        ON costcenter_id = id_costcenter
                                    JOIN pt pt
                                        ON pt_id = id_pt
                                    JOIN divisi dvs
                                        ON divisi_id = dvs.id_divisi
                                    JOIN parent_divisi pd
                                        ON parent_id = id_parent
                                    JOIN segmen sg
                                        ON sg.id_segmen = agg.id_segmen
                                    WHERE id_programkerja = '$id'
                                    AND agg.tahun = '$tahun'
                                    ORDER BY nm_item ASC");

$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_anggaran'] = $row['id_anggaran'];
    $data[$no]['kd_anggaran'] = $row['kd_anggaran'];
    $data[$no]['program_kerja'] = $row['program_kerja'];
    $data[$no]['nm_item'] = $row['nm_item'];

    $no++;
}


echo json_encode($data);
