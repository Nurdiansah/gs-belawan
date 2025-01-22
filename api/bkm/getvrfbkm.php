<?php
include "../../fungsi/koneksi.php";
$id = $_POST['id'];

$query = mysqli_query($koneksi, "SELECT * FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    JOIN divisi c
                                        ON b.id_divisi = c.id_divisi
                                    WHERE id_bkm = '$id'");

echo json_encode($data = mysqli_fetch_assoc($query));
