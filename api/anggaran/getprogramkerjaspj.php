<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];
$tahun = date("Y");

$query = mysqli_query($koneksi, "SELECT id_programkerja, nm_programkerja
                                    FROM cost_center cc
                                    JOIN program_kerja pk
                                        ON pk.costcenter_id = cc.id_costcenter
                                    JOIN anggaran agg
                                        ON id_programkerja = programkerja_id
                                    WHERE cc.divisi_id = '$id'
                                    AND spj = '1'
                                    AND pk.tahun = '$tahun'
                                    GROUP BY id_programkerja");

$no = 0;
while ($row = mysqli_fetch_array($query)) {
    $data[$no]['id_programkerja'] = $row['id_programkerja'];
    $data[$no]['nm_programkerja'] = $row['nm_programkerja'];

    $no++;
}


echo json_encode($data);
