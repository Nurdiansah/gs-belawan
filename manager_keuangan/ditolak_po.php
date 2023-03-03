<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                                FROM po p
                                                JOIN biaya_ops bo
                                                ON p.kd_transaksi = bo.kd_transaksi
                                                JOIN detail_biayaops dbo
                                                ON p.id_dbo = dbo.id
                                                JOIN divisi d
                                                ON d.id_divisi = bo.id_divisi                                            
                                                WHERE status_po = '202'
                                                ORDER BY p.kd_transaksi DESC   ");

$jumlahData  = mysqli_num_rows($query);
?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">PO Ditolak</h3>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_po2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Po Number</th>
                                        <th>Tanggal</th>
                                        <th>Divisi</th>
                                        <th>Deskripsi</th>
                                        <th>Alasan Ditolak</th>
                                        <th>Total</th>
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
                                                <td> <?= $row['po_number']; ?> </td>
                                                <td> <?= formatTanggalWaktu($row['tgl_po']); ?> </td>
                                                <td> <?= $row['nm_divisi']; ?> </td>
                                                <td> <?= $row['nm_barang']; ?> </td>
                                                <td><?= $row['komentar_mgr_ga']; ?></td>
                                                <td> <span class="label label-success"><?= formatRupiah($row['grand_totalpo']) ?> </span></td>
                                                <td>
                                                    <a href="index.php?p=dtl_ditolakpo&id=<?= $row['id_po']; ?>" class="btn btn-info">Lihat</a>
                                                </td>
                                    </tr>
                            <?php
                                                $no++;
                                            endwhile;
                                        }

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
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>