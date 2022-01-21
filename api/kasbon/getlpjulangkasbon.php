<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT * 
                                FROM kasbon k                                            
                                JOIN detail_biayaops dbo
                                    ON k.id_dbo = dbo.id
                                JOIN divisi d
                                    ON d.id_divisi = dbo.id_divisi                                            
                                WHERE k.status_kasbon = '606'
                                -- AND from_user = '1'
                                AND k.id_kasbon = '$id'");
echo json_encode($row = mysqli_fetch_assoc($query));
