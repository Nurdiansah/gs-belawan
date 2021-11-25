<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query2 = mysqli_query($koneksi, "SELECT tp.id_pettycash, tp.kd_pettycash,tp.id_anggaran, tp.total_pettycash, tp.keterangan_pettycash, a.kd_anggaran, tp.doc_lpj_pettycash
                                   FROM transaksi_pettycash tp
                                   JOIN anggaran a
                                   ON a.id_anggaran = tp.id_anggaran
                                   WHERE tp.id_pettycash = '$id'");
echo json_encode($row2 = mysqli_fetch_assoc($query2));
