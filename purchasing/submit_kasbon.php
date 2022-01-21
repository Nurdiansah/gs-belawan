<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryKasbon = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                        JOIN detail_biayaops dbo
                                            ON id_dbo = id
                                        INNER JOIN divisi dvs
                                            ON dvs.id_divisi = dbo.id_divisi
                                        WHERE status_kasbon IS NULL
                                        AND from_user = 0");
$totalKasbon = mysqli_num_rows($queryKasbon);

$no = 1;
?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Submit Kembali Kasbon</h3>
                </div>
                <div class="table-responsive">
                    <table class="table text-center table table-striped table-hover" id="">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Kasbon</th>
                                <th>Divisi</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Jenis Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($dataKasbon = mysqli_fetch_assoc($queryKasbon)) { ?>
                                <tr>
                                    <td><?= $no; ?></td>
                                    <td><?= $dataKasbon['id_kasbon']; ?></td>
                                    <td><?= $dataKasbon['nm_divisi']; ?></td>
                                    <td><?= $dataKasbon['tgl_kasbon']; ?></td>
                                    <td><?= formatRupiah($dataKasbon['harga_akhir']); ?></td>
                                    <?php if ($dataKasbon['sr_id'] == NULL) { ?>
                                        <td><span class="label label-success">Material Request</span></td>
                                    <?php } else { ?>
                                        <td><span class="label label-warning">Service Request</span></td>
                                    <?php } ?>
                                    <td>
                                        <?php if ($dataKasbon['sr_id'] == NULL) { ?>
                                            <a href="index.php?p=dtl_submitkasbon&id_kasbon=<?= $dataKasbon['id_kasbon']; ?>" class="btn btn-success " title="Lihat"><i class="fa fa-search"></i> Lihat</a>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#approve_<?= $dataKasbon['id_kasbon']; ?>" title="Submit Kembali"><i class="fa fa-rocket"></i></button>
                                            <a href="index.php?p=dtl_submitsr&id=<?= enkripRambo($dataKasbon['sr_id']); ?>&pg=<?= enkripRambo("submit_kasbon"); ?>" class="btn btn-primary " title="Detail"><i class="fa fa-search-plus"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <!-- MODAL BUAT SETUJU -->
                                <div id="approve_<?= $dataKasbon['id_kasbon']; ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- konten modal-->
                                        <div class="modal-content">
                                            <!-- heading modal -->
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"> Konfirmasi </h4>
                                            </div>
                                            <!-- body modal -->
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data" action="submit_kembali_ksr.php" class="form-horizontal">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <h4 class="text-center">Apakah anda yakin ingin submit kembali kasbon ini?</h4>
                                                        </div>
                                                        <input type="hidden" name="id" value="<?= $dataKasbon['id_kasbon']; ?>">
                                                        <input type="hidden" name="url" value="submit_kasbon">
                                                        <br>
                                                        <div class=" modal-footer">
                                                            <button class="btn btn-primary" type="submit" name="approve">Ya, Saya yakin</button></span>
                                                            &nbsp;
                                                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END SETUJU -->
                            <?php $no++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>