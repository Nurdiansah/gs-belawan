<?php

include "../fungsi/koneksi.php";

if (isset($_POST['tolak'])) {
    $id = $_POST['id'];
    $id_po = $_POST['id_po'];
    $kd_transaksi = $_POST['kd_transaksi'];
    $komentar = $_POST['komentar'];

    $queryTolak = mysqli_multi_query($koneksi, "UPDATE detail_biayaops SET id_supplier = '0',
                                                                        doc_penawaran = NULL,
                                                                        harga_estimasi = '0',
                                                                        alasan_penolakan = '$komentar'
                                                WHERE id = '$id';

                                                UPDATE po SET status_po = '0' WHERE id_po = '$id_po';

                                                UPDATE sub_dbo SET sub_unitprice = NULL, total_price = NULL
                                                WHERE id_dbo = '$id';

                                                DELETE FROM reapprove_po WHERE po_id = '$id_po';

                                                DELETE FROM tolak_po WHERE po_id = '$id_po';
    ");

    if ($queryTolak) {
        header('Location: index.php?p=' . $_POST['url'] . '');
    } else {
        echo "Ada yg salah "  . mysqli_error($koneksi);
    }
}
