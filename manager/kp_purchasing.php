<?php

?>
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
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
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
                        <td> <?= $row['nm_barang']; ?> </td>
                        <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
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
                                <span class="label label-info">Dana sudah bisa di ambil </span>
                            <?php  } else if ($row['status_kasbon'] == 8) { ?>
                                <span class="label label-info">Proses Pembelian purchasing</span>
                            <?php  } else if ($row['status_kasbon'] == 9) { ?>
                                <span class="label label-info">Verifikasi LPJ kasir</span>
                            <?php  } else if ($row['status_kasbon'] == 202) { ?>
                                <span class="label label-danger">Ditolak Costcontrol</span>
                            <?php  } else if ($row['status_kasbon'] == 606) { ?>
                                <span class="label label-danger">Ditolak Kasir</span>
                            <?php  } elseif (is_null($row['status_kasbon'])) { ?>
                                <span class="label label-info">Submit Kembali Purchasing</span>
                            <?php }  ?>
                        </td>
                        <td>
                            <a href="index.php?p=proses_dkasbon&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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