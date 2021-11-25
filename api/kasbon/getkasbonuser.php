<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT id_kasbon, id_anggaran, tgl_kasbon, keterangan, harga_akhir, id_dbo, vrf_pajak, doc_pendukung
                                    FROM kasbon k                                            
                                    JOIN detail_biayaops dbo
                                    ON k.id_dbo = dbo.id
                                    JOIN divisi d
                                    ON d.id_divisi = dbo.id_divisi                                            
                                    WHERE k.id_kasbon = '$id'");
echo json_encode($row = mysqli_fetch_assoc($query));
