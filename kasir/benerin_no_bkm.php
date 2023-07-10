<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm
                                    WHERE YEAR(tgl_bkm) = '2023'
                                    AND MONTH(tgl_bkm) = '04'");

$totalBKM = mysqli_num_rows($queryBKM);

if ($totalBKM > 0) {
    while ($dataBKM = mysqli_fetch_assoc($queryBKM)) {
        mysqli_begin_transaction($koneksi);

        $id_bkm = $dataBKM['id_bkm'];
        $tgl_bkm = $dataBKM['tgl_bkm'];

        $no_bkm = no_bkm($tgl_bkm);
        $no_awal = substr($no_bkm, 0, 3);

        $updBKM = mysqli_query($koneksi, "UPDATE bkm SET nomor = '$no_awal', no_bkm = '$no_bkm' WHERE id_bkm = '$id_bkm'");

        if ($updBKM) {
            echo "updated BKM " . $no_bkm . " done..<br>";
            mysqli_commit($koneksi);
        } else {
            echo "updated BKM " . $no_bkm . " failed..<br>Karena : " . mysqli_error($koneksi) . "<br>";
            mysqli_rollback($koneksi);
        }
    }
    echo $totalBKM . " rows affected";
} else {
    echo "Data kosong cuiii..";
}
