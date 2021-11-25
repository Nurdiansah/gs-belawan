<?php
include "../fungsi/koneksi.php";

if (isset($_POST['kirim'])) {
    $id_po = $_POST['id_po'];
    $id_dbo = $_POST['id_dbo'];
    $harga = $_POST['harga'];
    $id_supplier = $_POST['id_supplier'];
    $kd_transaksi = $_POST['kd_transaksi'];
    $doc_penawaran = $_FILES['doc_penawaran']['name'];
    $komentar = $_POST['komentar'];

    // cek tabel tolak_po, jika dikolom tolak mgr ga dan pajak NULL maka diapus
    $cekTolak = mysqli_query($koneksi, "SELECT * FROM tolak_po WHERE po_id = '$id_po'");
    $dataTolak = mysqli_fetch_assoc($cekTolak);

    if ($dataTolak['alasan_tolak_mgrga'] == NULL && $dataTolak['alasan_tolak_mgrfin'] == NULL && $dataTolak['alasan_tolak_direktur'] == NULL) {
        $aksi_tolak = "DELETE FROM tolak_po WHERE po_id = '$id_po'";
    } else {
        $aksi_tolak = "UPDATE tolak_po SET alasan_tolak_pajak = NULL, waktu_tolak_pajak = NULL
                        WHERE po_id = '$id_po'";
    }
    // selesai cek tabel tolak_po


    // cek ditabel reapprove_po, jika udh ada isinya dari pengajuan tsb maka hanya update saja
    $cekReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_po WHERE po_id = '$id_po'");
    $totalReapp = mysqli_num_rows($cekReapp);

    if ($totalReapp == 0) {
        $aksi_reapp = "INSERT INTO reapprove_po (po_id, alasan_reapprove_purchasing, waktu_reapprove_purchasing) VALUES
                        ('$id_po', '$komentar', NOW())";
    } else {
        $aksi_reapp = "UPDATE reapprove_po SET alasan_reapprove_purchasing = '$komentar', waktu_reapprove_purchasing = NOW()
                        WHERE po_id = '$id_po'";
    }
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

    // $query = "UPDATE detail_biayaops SET harga_estimasi = '$harga' , id_supplier = '$id_supplier' , doc_penawaran = '$namabaru'
    // WHERE id ='$id' ";
    $tolak = mysqli_multi_query($koneksi, "UPDATE po SET sub_totalpo = '$harga', status_po = '1'
                                                WHERE id_po = '$id_po';

                                            UPDATE detail_biayaops SET harga_estimasi = '$harga', id_supplier = '$id_supplier', doc_penawaran = '$namabaru'
                                                WHERE id = '$id_dbo';

                                            $aksi_tolak;
                                            $aksi_reapp;
                                            ");


    if ($tolak) {
        header('Location: index.php?p=' . $_POST['url'] . '');
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
    // END AKSI JALANIN DATANYA
}
