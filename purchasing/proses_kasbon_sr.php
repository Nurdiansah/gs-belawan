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
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                $no = 1;
                if (mysqli_num_rows($querySR)) {
                    while ($row = mysqli_fetch_assoc($querySR)) :

                ?>
                        <td> <?= $no; ?> </td>
                        <td> <?= $row['id_kasbon']; ?> </td>
                        <td> <?= tanggal_indo($row['tgl_kasbon']); ?> </td>
                        <td> <?= $row['nm_divisi']; ?> </td>
                        <td> <?= $row['nm_barang']; ?> </td>
                        <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
                        <td>
                            <?php if ($row['status_kasbon'] == 1) { ?>
                                <span class="label label-primary">Approval Manager GA</span>
                            <?php  } else if ($row['status_kasbon'] == 2) { ?>
                                <span class="label label-primary">Verifikasi Pajak</span>
                            <?php  } else if ($row['status_kasbon'] == 3) { ?>
                                <span class="label label-warning">Approval Manager Finance </span>
                            <?php  } else if ($row['status_kasbon'] == 4) { ?>
                                <span class="label label-warning">Approval Direktur </span>
                            <?php  } else if ($row['status_kasbon'] == 5) { ?>
                                <span class="label label-success">Dana sudah bisa diambil </span>
                            <?php  } else if ($row['status_kasbon'] == 303) { ?>
                                <span class="label label-danger">Ditolak Manager GA</span>
                            <?php  } else if ($row['status_kasbon'] == 404) { ?>
                                <span class="label label-danger">Ditolak Pajak</span>
                            <?php  } else if ($row['status_kasbon'] == 505) { ?>
                                <span class="label label-danger">Ditolak Manager Finance</span>
                            <?php  } else if ($row['status_kasbon'] == 606) { ?>
                                <span class="label label-danger">Ditolak Direktur</span>
                            <?php  } else {
                                echo "-";
                            }  ?>
                        </td>
                        <td>
                            <a href="index.php?p=kasbon_dproses_sr&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
                        </td>
            </tr>
    <?php
                        $no++;
                    endwhile;
                } ?>
        </tbody>
    </table>
</div>