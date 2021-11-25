<?php
$query = mysqli_query($koneksi, "SELECT * 
                                        FROM kasbon k                                        
                                        LEFT JOIN detail_biayaops dbo
                                        ON k.id_dbo = dbo.id
                                        LEFT JOIN divisi d
                                        ON d.id_divisi = dbo.id_divisi                                            
                                        WHERE k.status_kasbon != '0'
                                        AND k.from_user = '1'
                                        ORDER BY k.id_kasbon DESC   ");

?>
<div class="box-body">
    <div class="row">
        <br><br>
    </div>
    <div class="table-responsive">
        <table class="table text-center table table-striped table-hover" id=" ">
            <thead>
                <tr style="background-color :#B0C4DE;">
                    <th>No</th>
                    <th>Kode </th>
                    <th>Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Divisi</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($query)) {
                        while ($row = mysqli_fetch_assoc($query)) :

                    ?>
                            <td> <?= $no; ?> </td>
                            <td> <?= $row['id_kasbon']; ?> </td>
                            <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                            <td> <?= $row['keterangan']; ?> </td>
                            <td> <?= $row['nm_divisi']; ?> </td>
                            <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
                            <td>
                                <?php if ($row['status_kasbon'] == 1) { ?>
                                    <span class="label label-primary">Verifikasi Manager</span>
                                <?php  } else if ($row['status_kasbon'] == 2) { ?>
                                    <span class="label label-primary">Verifikasi Pajak </span>
                                <?php  } else if ($row['status_kasbon'] == 3) { ?>
                                    <span class="label label-success">Approval Manager Finance </span>
                                <?php  } else if ($row['status_kasbon'] == 4) { ?>
                                    <span class="label label-success">Approval Direktur </span>
                                <?php  } else if ($row['status_kasbon'] == 5) { ?>
                                    <span class="label label-warning">Dana sudah bisa diambil </span>
                                <?php  } else if ($row['status_kasbon'] == 6) { ?>
                                    <span class="label label-info">Pengajuan sedang di belikan </span>
                                <?php  } else if ($row['status_kasbon'] == 7) { ?>
                                    <span class="label label-info">Verifikasi LPJ </span>
                                <?php  } else if ($row['status_kasbon'] == 8) { ?>
                                    <span class="label label-info">BKK</span>
                                <?php  } elseif ($row['status_kasbon'] == 101) {  ?>
                                    <span class="label label-danger">Ditolak Manager</span>
                                <?php  } elseif ($row['status_kasbon'] == 202) {  ?>
                                    <span class="label label-danger">Ditolak Pajak</span>
                                <?php  } elseif ($row['status_kasbon'] == 303) {  ?>
                                    <span class="label label-danger">Ditolak Manager Finance</span>
                                <?php  } elseif ($row['status_kasbon'] == 404) {  ?>
                                    <span class="label label-danger">Ditolak Direktur</span>
                                <?php  } elseif ($row['status_kasbon'] == 505) {  ?>
                                    <span class="label label-danger">Ditolak Kasir (LPJ Ulang)</span>
                                <?php } ?>
                            </td>
                </tr>
        <?php
                            $no++;
                        endwhile;
                    } ?>
            </tbody>
        </table>
    </div>
</div>