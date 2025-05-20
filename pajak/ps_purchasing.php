<?php
$query = mysqli_query($koneksi, "SELECT * 
                                FROM kasbon k
                                JOIN biaya_ops bo
                                ON k.kd_transaksi = bo.kd_transaksi
                                JOIN detail_biayaops dbo
                                ON k.id_dbo = dbo.id
                                JOIN divisi d
                                ON d.id_divisi = bo.id_divisi
                                WHERE k.status_kasbon IN ('2', '3', '4', '5', '6', '7', '8')
                                AND from_user = '0'
                                ORDER BY k.id_kasbon DESC   ");
?>
<br>
<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id="material">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>Kode </th>
                <th>Tanggal</th>
                <th>Divisi</th>
                <th>Deskripsi</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($query)) {
                while ($row = mysqli_fetch_assoc($query)) :

            ?>
                    <tr>
                        <td> <?= $no; ?> </td>
                        <td> <?= $row['id_kasbon']; ?> </td>
                        <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                        <td> <?= $row['nm_divisi']; ?> </td>
                        <td> <?= $row['nm_barang']; ?> </td>
                        <td> <span class="btn btn-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
                        <td>
                            <?php if ($row['status_kasbon'] == 2) { ?>
                                <span class="label label-primary">Verifikasi Pajak</span>
                            <?php  } else if ($row['status_kasbon'] == 3) { ?>
                                <span class="label label-primary">Verifikasi Cost Control</span>
                            <?php  } else if ($row['status_kasbon'] == 4) { ?>
                                <span class="label label-warning">Approval Manager</span>
                            <?php  } else if ($row['status_kasbon'] == 5) { ?>
                                <span class="label label-warning">Approval GM Finance</span>
                            <?php  } else if ($row['status_kasbon'] == 6) { ?>
                                <span class="label label-warning">Approval Direksi</span>
                            <?php  } else if ($row['status_kasbon'] == 7) { ?>
                                <span class="label label-success">Proses Pengambilan Dana </span>
                            <?php  } else if ($row['status_kasbon'] == 8) { ?>
                                <span class="label label-info">Proses Pembelian Purchasing</span>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="index.php?p=detail_proseskasbon&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
                        </td>
                    </tr>
            <?php
                    $no++;
                endwhile;
            }
            ?>

        </tbody>
    </table>
</div>