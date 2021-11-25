<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id=" ">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>Kode </th>
                <th>Tanggal</th>
                <th>Divisi</th>
                <th>Deskripsi</th>
                <th>Alasan Ditolak</th>
                <th>Total</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                while ($row = mysqli_fetch_assoc($querySR)) :

                ?>
                    <td> <?= $no; ?> </td>
                    <td> <?= $row['id_kasbon']; ?> </td>
                    <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                    <td> <?= $row['nm_divisi']; ?> </td>
                    <td> <?= $row['nm_barang']; ?> </td>
                    <td> <?= $row['k_komentar']; ?> </td>
                    <td> <span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
                    <td>
                        <a href="?p=dtl_ditolak_srk&id=<?= enkripRambo($row['sr_id']); ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                    </td>
            </tr>
        <?php
                    $no++;
                endwhile;
        ?>
        </tbody>
    </table>
</div>