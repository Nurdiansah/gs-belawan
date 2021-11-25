<?php
include "../../fungsi/koneksi.php";
include "../../fungsi/fungsi.php";

$id = dekripRambo($_POST['id']);

$query = mysqli_query($koneksi, "SELECT *
                                   FROM tagihan_po tp
                                   JOIN po p
                                   ON p.id_po = tp.po_id
                                   JOIN detail_biayaops dbo
                                   ON dbo.id = p.id_dbo
                                   WHERE tp.id_tagihan = '$id'");
echo json_encode($row = mysqli_fetch_assoc($query));
