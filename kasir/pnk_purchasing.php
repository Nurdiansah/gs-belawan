<div class="table-responsive">
    <table class="table text-center table table-striped table-hover">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th style="vertical-align: middle;" rowspan="2">No</th>
                <th style="vertical-align: middle;" rowspan='2'>Kode </th>
                <th style="vertical-align: middle;" colspan="2">Tanggal</th>
                <th style="vertical-align: middle;" rowspan='2'>Divisi</th>
                <th style="vertical-align: middle;" rowspan='2'>Deskripsi</th>
                <th style="vertical-align: middle;" rowspan='2'>Total</th>
                <th style="vertical-align: middle;" rowspan='2'>Pending</th>
                <th style="vertical-align: middle;" rowspan='2'>Detail</th>
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
                        <td>
                            <h4><span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></h4>
                        </td>
                        <td>
                            <span class="label label-<?= $notifPending; ?>">
                                <?= $pendingLpj; ?>
                            </span>
                        </td>
                        <td>
                            <a href="?p=pending_kasbon&aksi=lihat&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                        </td>
            </tr>
    <?php
                        $no++;
                    endwhile;
                }

                $jumlahData  = mysqli_num_rows($query);
                if ($jumlahData == 0) {
                    echo
                    "<tr>
                        <td colspan='8'> Tidak Ada Data</td>
                    </tr>
                    ";
                }
    ?>
        </tbody>
    </table>
</div>

<script>

</script>