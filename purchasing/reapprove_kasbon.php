<?php
include "../fungsi/koneksi.php";

if (isset($_POST['kirim'])) {
    $id_dbo = $_POST['id_dbo'];
    $id_kasbon = $_POST['id_kasbon'];
    $harga = $_POST['harga'];
    $id_supplier = $_POST['id_supplier'];
    $kd_transaksi = $_POST['kd_transaksi'];
    $doc_penawaran = $_FILES['doc_penawaran']['name'];
    $komentar = $_POST['komentar'];
    $status = $_POST['status'];

    if ($status == "202") {
        // Cek detail biaya ops
        // $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
        // $totalReapp = mysqli_num_rows($cekReapp);

        // cek tabel tolak_kasbon, jika dikolom tolak mgr ga dan pajak NULL maka diapus
        // $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'");
        // $dataTolak = mysqli_fetch_assoc($cekTolak);

        // if ($dataTolak['alasan_tolak_mgrga'] == NULL && $dataTolak['alasan_tolak_mgrfin'] == NULL && $dataTolak['alasan_tolak_direktur'] == NULL) {
        //     $aksi_tolak = "DELETE FROM tolak_kasbon WHERE kasbon_id = '$id_kasbon'";
        // } else {
        //     $aksi_tolak = "UPDATE tolak_kasbon SET alasan_tolak_pajak = NULL, waktu_tolak_pajak = NULL
        //                 WHERE kasbon_id = '$id_kasbon'";
        // }
        // selesai cek tabel tolak_kasbon


        // cek ditabel reapprove_kasbon, jika udh ada isinya dari pengajuan tsb maka hanya update saja
        // $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id_kasbon'");
        // $totalReapp = mysqli_num_rows($cekReapp);

        // if ($totalReapp == 0) {
        //     $aksi_reapp = "INSERT INTO reapprove_kasbon (kasbon_id, alasan_reapprove_purchasing, waktu_reapprove_purchasing) VALUES
        //                 ('$id_kasbon', '$komentar', NOW());";
        // } else {
        //     $aksi_reapp = "UPDATE reapprove_kasbon SET alasan_reapprove_purchasing = '$komentar', waktu_reapprove_purchasing = NOW()
        //                 WHERE kasbon_id = '$id_kasbon';";
        // }
        // end


        // Jika tidak ada perubahan document penawaran
        if (!empty($doc_penawaran)) {
            $del_invoice = $_POST['doc_penawaran_lama'];
            if (isset($del_invoice)) {
                unlink("../file/doc_penawaran/$del_invoice");
            }
            $lokasi_invoice = ($_FILES['doc_penawaran']['tmp_name']);
            $doc_penawaran = ($_FILES['doc_penawaran']['name']);
            $ekstensi = pathinfo($doc_penawaran, PATHINFO_EXTENSION);
            $namabaru = $id_dbo . "-doc_penawaran." . $ekstensi;
            move_uploaded_file($lokasi_invoice, "../file/doc_penawaran/" . $namabaru);
        } else {
            $namabaru = $_POST['doc_penawaran_lama'];
        }


        $reapprove = mysqli_multi_query($koneksi, "UPDATE kasbon SET harga_akhir = '$harga', status_kasbon = '3', komentar_mgr_fin = NULL
                                                WHERE id_kasbon = '$id_kasbon';

                                                UPDATE detail_biayaops SET harga_estimasi = '$harga',
                                                        id_supplier = '$id_supplier',
                                                        doc_penawaran = '$namabaru'
                                                WHERE id = '$id_dbo';
                                                
                                            -- $aksi_tolak;   
                                            -- $aksi_reapp; 
                                    ");
    } else {
        $reapprove = mysqli_query($koneksi, "UPDATE kasbon SET status_kasbon = '9' WHERE id_kasbon = '$id_kasbon'");
    }

    if ($reapprove) {
        header('Location: index.php?p=' . $_POST['url'] . '');
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
    // END AKSI JALANIN DATANYA
}
