<div class="table-responsive datatab">
    <table class="table text-center table table-striped table-hover" id="">
        <tr style="background-color :#B0C4DE;">
            <th rowspan="2">No</th>
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">Nama Barang</th>
            <th rowspan="2">Keterangan</th>
            <th rowspan="2">Kode Anggaran</th>
            <th rowspan="2">Alasan Ditolak</th>
            <th rowspan="2">Total</th>
            <th rowspan="2">Aksi</th>
        </tr>
        <!-- <tr> -->
        <tbody>

            <?php
            $no = 1;
            if (mysqli_num_rows($querySO)) {
                while ($row = mysqli_fetch_assoc($querySO)) :

            ?>
                    <td> <?= $no; ?> </td>
                    <td> <?= formatTanggal($row['created_at']); ?> </td>
                    <td> <?= $row['nm_barang']; ?> </td>
                    <td> <?= $row['keterangan']; ?> </td>
                    <td> <?= $row['kd_anggaran'] . " - " . $row['nm_item']; ?> </td>
                    <td> <?= $row['komentar']; ?> </td>
                    <td> <?= formatRupiah($row['grand_total']); ?> </td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSr-<?= $row['id_so']; ?>"><i class="fa fa-undo"></i> Submit Kembali</button>
                        <a href="index.php?p=detail_so&id=<?= enkripRambo($row['id_so']); ?>&pg=<?= enkripRambo("ditolak_sr&sp=ditolak_so"); ?>"><span data-placement='top' data-toggle='tooltip' title='Detail'><button class="btn btn-success"> <i class="fa fa-file-text-o"></i> Detail</button></span></a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectSr-<?= $row['id_so']; ?>"><i class="fa fa-reply"></i> Reject</button>
                    </td>
                    </tr>

                    <!-- Modal release -->
                    <div id="approveSr-<?= $row['id_so']; ?>" class="modal fade" role="dialog">
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
                                        <form method="post" name="form" enctype="multipart/form-data" action="submit_kembali_sr.php" class="form-horizontal">
                                            <div class="box-body">
                                                <input type="hidden" name="id" value="<?= $row['id_so']; ?>">
                                                <input type="hidden" name="id_user" value="<?= $row['id_user']; ?> ">
                                                <input type="hidden" name="id_manager" value="<?= $row['id_manager']; ?> ">
                                                <input type="hidden" name="doc_penawaran" value="<?= $row['doc_penawaran']; ?> ">
                                                <input type="hidden" name="doc_quotation" value="<?= $row['doc_quotation']; ?> ">
                                                <input type="hidden" name="url" id="url" value="ditolak_sr&sp=ditolak_so">
                                                <h4>Apakah anda yakin ingin mensubmit kembali service request <b><?= $row['nm_barang']; ?> ? </b></h4>
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
                    <div id="rejectSr-<?= $row['id_so']; ?>" class="modal fade" role="dialog">
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
                                    <form method="post" enctype="multipart/form-data" action="tolak_so.php" class="form-horizontal">
                                        <div class="box-body">
                                            <input type="hidden" name="id_so" id="id_so" value="<?= $row['id_so']; ?>">
                                            <input type="hidden" name="url" id="url" value="ditolak_sr&sp=ditolak_so">
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
            <?php
                    $no++;
                endwhile;
            } ?>
        </tbody>
    </table>
</div>