<?php
$query = mysqli_query($koneksi, "SELECT id_kasbon, tgl_kasbon,nm_divisi, nm_barang , harga_akhir, sr_id
                                        FROM kasbon k
                                        JOIN sr s
                                        ON k.sr_id = s.id_sr
                                        JOIN divisi d
                                        ON d.id_divisi = k.divisi_id
                                        WHERE k.status_kasbon = '6'
                                        ORDER BY k.id_kasbon DESC");
?>
<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id=" ">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>Kode </th>
                <th>Tanggal</th>
                <th>Divisi</th>
                <th>Deskripsi</th>
                <th>Total</th>
                <th>Detail</th>
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
                        <td> <?= $row['nm_divisi']; ?> </td>
                        <td> <?= $row['nm_barang']; ?> </td>
                        <td> <button class="btn btn-success"><?= formatRupiah($row['harga_akhir']) ?> </button></td>
                        <td>
                            <a href="?p=detail_srk&id=<?= enkripRambo($row['sr_id']); ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                        </td>
            </tr>
    <?php
                        $no++;
                    endwhile;
                } ?>
        </tbody>
    </table>
</div>