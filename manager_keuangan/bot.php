<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_POST['submit'])) {


    $data = mysqli_query($koneksi, "SELECT * FROM bkk_final
										  WHERE status_bkk BETWEEN 1 AND 3 
                                          -- GROUP BY pengajuan 
                                          ");

    $jumlahData = mysqli_num_rows($data);


    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);

    foreach ($data as $key => $value) {
        $id = $value['id'];
        $id_anggaran = $value['id_anggaran'];
        $DPP = $value['nilai_barang'] + $value['nilai_jasa'];

        // Tanggal 
        $tanggal = dateNow();
        $bulan    = date('n');

        $tanggalDireksi =  manipulasiTanggal($tanggal, '2', 'hours');

        $nomorBkk = getNoBkk();

        $nomor = substr($nomorBkk, 0, 3);


        if ($value['pengajuan'] == 'BIAYA UMUM') {
            # code...

            $queryBU = mysqli_query($koneksi, "SELECT bf.id , b.jenis FROM bkk_final bf
                                          JOIN bkk b
                                          ON b.kd_transaksi = bf.id_kdtransaksi
										  WHERE id = '$id' 
                                          ");
            $dataBU = mysqli_fetch_assoc($queryBU);

            if ($dataBU['jenis'] == 'kontrak') {

                $query1 = mysqli_query($koneksi, "UPDATE bkk_final
                                                SET status_bkk = 17 , v_mgr_finance = '$tanggal', v_direktur = '$tanggalDireksi'
                                                WHERE id= '$id' ");
            } else {
                $update = mysqli_query($koneksi, "UPDATE bkk_final
											SET status_bkk = 4 , v_mgr_finance = '$tanggal', v_direktur = '$tanggalDireksi',
												nomor = '$nomor', no_bkk = '$nomorBkk', 
												release_on_bkk = '$tanggal'
											WHERE id= '$id' ");
            }

            // Realisasi
            $updateRealisasi = "Berhasil";

            // Kasbon
        } else if ($value['pengajuan'] == 'KASBON') {
            $update = mysqli_query($koneksi, "UPDATE bkk_final
											SET status_bkk = 4 ,  v_mgr_finance = '$tanggal', v_direktur = '$tanggalDireksi',
												nomor = '$nomor', no_bkk = '$nomorBkk', 
												release_on_bkk = '$tanggal'
											WHERE id= '$id' ");
            // Realisasi
            $updateRealisasi = "Berhasil";

            // Biaya Khusus
        } else if ($value['pengajuan'] == 'BIAYA KHUSUS') {
            $update = mysqli_query($koneksi, "UPDATE bkk_final
										  SET status_bkk = 4 , v_mgr_finance = '$tanggal', v_direktur = '$tanggalDireksi',
                                          nomor = '$nomor', no_bkk = '$nomorBkk', release_on_bkk = '$tanggal' 
										  WHERE id= '$id' ");

            //query realisasi anggaran
            $fieldRealisasi = fieldRealisasi($bulan);

            $queryJumlahAwal = mysqli_query($koneksi, "SELECT $fieldRealisasi as bulan , jumlah_realisasi, realisasi_kuantitas  
                                                    FROM anggaran WHERE id_anggaran = '$id_anggaran' ");
            $rowJA = mysqli_fetch_assoc($queryJumlahAwal);
            $jml_akhir = $rowJA['bulan'] + $DPP;
            $jumlah_realisasi = $rowJA['jumlah_realisasi'] + $DPP;
            $qty_akhir = $rowJA['realisasi_kuantitas'] + 1;

            // Realisasi
            $updateRealisasi = mysqli_query($koneksi, "UPDATE anggaran SET $fieldRealisasi = '$jml_akhir' , jumlah_realisasi = $jumlah_realisasi ,realisasi_kuantitas = $qty_akhir
                                WHERE id_anggaran ='$id_anggaran' ");
        }
    }

    if ($update && $updateRealisasi) {
        # jika semua query berhasil di jalankan
        echo $jumlahData . ' BKK Berhasil di Approved';
        mysqli_commit($koneksi);

        setcookie('pesan', 'BKK berhasil di Approved!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'BKK gagal di Approved!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOT Untuk Approval</title>
</head>

<body>
    <h2>Automatis Approval BKK</h2>
    <p>Automatis Approval BKK sampai </p>
    <form method="post" enctype="multipart/form-data" action="">
        <button type="submit" name="submit">Approve</button>
    </form>
</body>

</html>