<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsianggaran.php";

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm
                                    WHERE status_bkm = '5'
                                    AND YEAR(tgl_bkm) = '2022'");

$totalBKM = mysqli_num_rows($queryBKM);

if ($totalBKM > 0) {
    while ($dataBKM = mysqli_fetch_assoc($queryBKM)) {

        mysqli_begin_transaction($koneksi);

        $id_bkm = $dataBKM['id_bkm'];
        $id_anggaran = $dataBKM['id_anggaran'];
        $qty = "1";
        $nominal = $dataBKM['grand_total'];
        $bulan = date("n", strtotime($dataBKM['tgl_bkm']));

        $no_bkm = nomorBKM($dataBKM['tgl_bkm']);
        $no_awal = nomorAwal($no_bkm);

        $updateBKM = mysqli_query($koneksi, "UPDATE bkm SET nomor = '$no_awal',
                                                    no_bkm = '$no_bkm',
                                                    release_bkm = tgl_bkm,
                                                    v_kasir = NOW(),
                                                    app_mgr_fin = NOW(),
                                                    app_direktur = NOW(),
                                                    status_bkm = '7'
                                                WHERE id_bkm = '$id_bkm'
                                ");

        $updRealisasi = updateRealisasi($id_anggaran, $qty, $nominal, $bulan);

        if ($updateBKM && $updRealisasi) {
            echo "updated BKM " . $id_bkm . " done..<br>";
            mysqli_commit($koneksi);
        } else {
            echo "updated BKM " . $id_bkm . " failed..<br>Karena : " . mysqli_error($koneksi) . "<br>";
            mysqli_rollback($koneksi);
        }
    }
    echo $totalBKM . " rows affected";
} else {
    echo "Data kosong cuiii..";
}
