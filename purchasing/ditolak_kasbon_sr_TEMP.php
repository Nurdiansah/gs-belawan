<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id="">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>ID Kasbon</th>
                <th>Tanggal</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Kode Anggaran</th>
                <th>Alasan Ditolak</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($dataKasbon = mysqli_fetch_assoc($queryKasbon)) { ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $dataKasbon['id_kasbon']; ?></td>
                    <td><?= $dataKasbon['tgl_kasbon']; ?></td>
                    <td><?= $dataKasbon['nm_barang']; ?></td>
                    <td><?= $dataKasbon['keterangan']; ?></td>
                    <td> <?= $dataKasbon['kd_anggaran'] . " - " . $dataKasbon['nm_item']; ?> </td>
                    <td> <?= $dataKasbon['k_komentar']; ?> </td>
                    <td> <?= formatRupiah($dataKasbon['harga_akhir']); ?> </td>
                    <td>
                        <?php if ($dataKasbon['status_kasbon'] == '0') { ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSr-<?= $dataKasbon['id_kasbon']; ?>"><i class="fa fa-undo"></i> Submit Kembali</button>
                            <a href="index.php?p=detail_sr&id=<?= enkripRambo($dataKasbon['id_sr']); ?>&pg=<?= enkripRambo("ditolak_sr&sp=ditolak_kasbon_sr"); ?>" class="btn btn-success" title="Detail"> <i class="fa fa-file-text-o"></i> Detail</a>
                            <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectSr-<?= $dataKasbon['id_kasbon']; ?>"><i class="fa fa-reply"></i> Reject</button> -->
                        <?php } else { ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSr-<?= $dataKasbon['id_kasbon']; ?>"><i class="fa fa-undo"></i> Submit Kembali</button>
                            <a href="index.php?p=detail_sr&id=<?= enkripRambo($dataKasbon['id_sr']); ?>&pg=<?= enkripRambo("ditolak_sr&sp=ditolak_kasbon_sr"); ?>" class="btn btn-success" title="Detail"> <i class="fa fa-file-text-o"></i> Detail</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectSr-<?= $dataKasbon['id_kasbon']; ?>"><i class="fa fa-reply"></i> Reject</button>
                        <?php } ?>
                    </td>
                </tr>

                <!-- Modal release -->
                <div id="approveSr-<?= $dataKasbon['id_kasbon']; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Konfirmasi</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <div class="perhitungan">
                                    <form method="post" name="form" enctype="multipart/form-data" action="submit_kembali_ksr.php" class="form-horizontal">
                                        <div class="box-body">
                                            <input type="hidden" name="id" value="<?= $dataKasbon['id_kasbon']; ?>">
                                            <input type="hidden" name="url" id="url" value="ditolak_sr&sp=ditolak_kasbon_sr">
                                            <input type="hidden" name="status" id="status" value="<?= $dataKasbon['status_kasbon'] ?>">
                                            <h4>Apakah anda yakin ingin mensubmit kembali service request <b><?= $dataKasbon['nm_barang']; ?> ? </b></h4>
                                            <div class=" modal-footer">
                                                <button class="btn btn-primary" type="submit" name="approve">Ya, Saya yakin</button></span></a>
                                                &nbsp;
                                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                            </div>
                                        </div>
                                    </form>
                                    <!-- div perhitungan -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End release -->

                <!-- Modal Reject -->
                <div id="rejectSr-<?= $dataKasbon['id_kasbon']; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Alasan Penolakan </h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="tolak_kasbon_sr.php" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id" id="id" value="<?= $dataKasbon['id_kasbon']; ?>">
                                        <input type="hidden" name="url" id="url" value="ditolak_sr&sp=ditolak_kasbon_sr">
                                        <div class="mb-3">
                                            <label for="validationTextarea">Komentar</label>
                                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
                                            <div class="invalid-feedback">
                                                Please enter a message in the textarea.
                                            </div>
                                        </div>
                                        <div class=" modal-footer">
                                            <button class="btn btn-success" type="submit" name="update">Kirim</button></span></a>
                                            &nbsp;
                                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
            <?php $no++;
            } ?>
        </tbody>
    </table>
</div>