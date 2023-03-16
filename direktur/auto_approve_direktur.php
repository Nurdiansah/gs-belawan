<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

date_default_timezone_set("Asia/Jakarta");
$jam = date("H");

if ($jam != '08' && $jam != '09') {
    $update = mysqli_multi_query($koneksi, "UPDATE bkk SET status_bkk = '9', id_direktur2 = '68', tgl_verifikasidireksi2 = NOW()
                                            WHERE status_bkk = '8'
                                            AND tgl_verifikasimanagerkeuangan IS NOT NULL;
    
                                            UPDATE kasbon SET status_kasbon = '7', id_direktur2 = '68', app_direktur2 = NOW()
                                            WHERE status_kasbon = '6'
                                            AND app_mgr_finance IS NOT NULL;
    
                                            UPDATE po SET status_po = '6', id_direktur2 = '68', app_direksi2 = NOW()
                                            WHERE status_po = '5'
                                            AND app_mgr_finance IS NOT NULL;

                                            -- UPDATE so SET `status` = '5', direktur2 = '68', app_direktur2 = NOW()
                                            -- WHERE `status` = '4'
                                            -- AND direktur1 IS NOT NULL;
    
                ");

    unlink("../../../../../home/enc3/auto_approve_direktur.php");

    if ($update) {
        mysqli_close($koneksi);
        // echo "Berhasil";
    }
} else {
    echo "Gagal. Sekarang jam $jam";
}
