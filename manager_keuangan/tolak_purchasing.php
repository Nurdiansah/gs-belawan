<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id="">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Kasbon</th>
                <th>Kode Transaksi</th>
                <th>Tanggal Pengajuan</th>
                <th>Alasan Ditolak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($dataPurchasing = mysqli_fetch_assoc($queryPurchasing)) { ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $dataPurchasing['id_kasbon']; ?></td>
                    <td><?= $dataPurchasing['kd_transaksi']; ?></td>
                    <td><?= formatTanggalWaktu($dataPurchasing['tgl_kasbon']); ?></td>
                    <td><?= $dataPurchasing['komentar_mgr_ga']; ?></td>
                    <td>
                        <a href="index.php?p=dtl_kasbonditolak&aksi=lihat&id=<?= $dataPurchasing['id_kasbon']; ?>" class="btn btn-primary " title="Lihat"> Lihat</a>
                        <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approve_<?= $dataPurchasing['id_kasbon']; ?>" title="Setuju"><i class="fa fa-check"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#tolak_<?= $dataPurchasing['id_kasbon']; ?>" title="Tolak"><i class="fa fa-close"></i></button> -->
                    </td>
                </tr>
                <!-- MODAL TOLAK REMBO YG IKUT NGELOOPING -->
                <div id="tolak_<?= $dataPurchasing['id_kasbon']; ?>" class="modal fade" role="dialog">
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
                                <form method="POST" enctype="multipart/form-data" action="tolakmgrfin_kasbon.php" class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group ">
                                            <div class="col-sm-4">
                                                <input type="hidden" value="<?= $dataPurchasing['id_kasbon']; ?>" class="form-control" name="id_kasbon">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="validationTextarea">Komentar</label>
                                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required autocomplete>@ <?php echo $Nama ?> : </textarea>
                                            <div class="invalid-feedback">
                                                Please enter a message in the textarea.
                                            </div>
                                        </div>
                                        <div class=" modal-footer">
                                            <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>
                                            &nbsp;
                                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END MODAL TOLAK -->

                <!-- MODAL BUAT SETUJU -->
                <div id="approve_<?= $dataPurchasing['id_kasbon']; ?>" class="modal fade" role="dialog">
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
                                <form method="post" enctype="multipart/form-data" action="setuju_kasbon.php" class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <h4 class="text-center">Apakah anda yakin ingin menyetujui kasbon ini?</h4>
                                        </div>
                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                        <br>
                                        <div class=" modal-footer">
                                            <button class="btn btn-primary" type="submit" name="submit">Yes</button></span>
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

<script>
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });
</script>