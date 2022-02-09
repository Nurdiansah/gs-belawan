<?php
$query = mysqli_query($koneksi, "SELECT * 
                                FROM kasbon k
                                LEFT JOIN biaya_ops bo
                                ON k.kd_transaksi = bo.kd_transaksi
                                LEFT JOIN detail_biayaops dbo
                                ON k.id_dbo = dbo.id
                                LEFT JOIN divisi d
                                ON d.id_divisi = bo.id_divisi                                            
                                WHERE k.status_kasbon IN ('8', '707') AND k.from_user = '0' AND sr_id IS NULL
                                ORDER BY k.id_kasbon DESC   ");
?>
<form method="post" enctype="multipart/form-data" action="setuju_kasbon2.php" class="form-horizontal">
    <div class="table-responsive">
        <table class="table text-center table table-striped table-hover" id=" ">
            <thead>
                <tr style="background-color :#B0C4DE;">
                    <th style="vertical-align: middle;" rowspan="2">No</th>
                    <th style="vertical-align: middle;" rowspan="2">Kode </th>
                    <th style="vertical-align: middle;" colspan="2">Tanggal</th>
                    <th style="vertical-align: middle;" rowspan="2">Divisi</th>
                    <th style="vertical-align: middle;" rowspan="2">Deskripsi</th>
                    <th style="vertical-align: middle;" rowspan="2">Total</th>
                    <th style="vertical-align: middle;" rowspan='2'>Pending</th>
                    <th style="vertical-align: middle;" rowspan="2">Detail</th>
                </tr>
                <tr style="background-color :#B0C4DE;">
                    <th>Buat</th>
                    <th>Penyerahan Dana</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($query)) {
                        while ($row = mysqli_fetch_assoc($query)) :

                            // Memnghitung waktu tempuh dari penyerahan dana
                            $payment  = strtotime($row['waktu_penerima_dana']);
                            $today  = strtotime(date("Y-m-d H:i:s"));
                            $dif = $today - $payment;
                            $pendingLpj = detikToString($dif);

                            if ($dif > 172800) {
                                $notifPending = 'danger';
                            } else {
                                $notifPending = 'warning';
                            }
                    ?>
                            <td> <?= $no; ?> </td>
                            <td> <?= $row['id_kasbon']; ?> </td>
                            <td> <?= formatTanggal($row['tgl_pengajuan']); ?> </td>
                            <td> <?= formatTanggal($row['waktu_penerima_dana']); ?> </td>
                            <td> <?= $row['nm_divisi']; ?> </td>
                            <td> <?= $row['nm_barang']; ?> </td>
                            <td> <span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
                            <?php if ($row['status_kasbon'] == "707") { ?>
                                <td><span class="label label-danger">Ditolak Kasir</span></td>
                            <?php } else { ?>
                                <td><span class="label label-<?= $notifPending; ?>"><?= $pendingLpj; ?></span></td>
                            <?php } ?>
                            </td>
                            <td>
                                <a href="?p=lpj_dkasbon&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                            </td>
                </tr>
        <?php
                            $no++;
                        endwhile;
                    } ?>
            </tbody>
        </table>
    </div>
</form>