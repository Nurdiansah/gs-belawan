<?php
$query = mysqli_query($koneksi, "SELECT * 
                                        FROM kasbon k
                                        JOIN biaya_ops bo
                                        ON k.kd_transaksi = bo.kd_transaksi
                                        JOIN detail_biayaops dbo
                                        ON k.id_dbo = dbo.id
                                        JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi
                                        WHERE k.status_kasbon = '4'
                                        ORDER BY k.id_kasbon DESC");
?>
<form method="post" enctype="multipart/form-data" action="setuju_kasbon2.php" class="form-horizontal">
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
                            <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                            <td> <?= $row['nm_divisi']; ?> </td>
                            <td> <?= $row['nm_barang']; ?> </td>
                            <td> <span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
                            <td>
                                <a href="?p=verifikasi_kasbon&aksi=lihat&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
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