<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id="">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Kasbon</th>
                <th>Nama Barang</th>
                <th>Keterangan</th>
                <th>Tanggal Pengajuan</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($dataKasbon = mysqli_fetch_assoc($queryKasbon)) { ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $dataKasbon['id_kasbon']; ?></td>
                    <td><?= $dataKasbon['nm_barang']; ?></td>
                    <td><?= $dataKasbon['keterangan']; ?></td>
                    <td><?= $dataKasbon['tgl_kasbon']; ?></td>
                    <td><?= formatRupiah($dataKasbon['harga_akhir']); ?></td>
                    <td>
                        <?php if ($dataKasbon['status_kasbon'] == '202') {
                            echo $dataKasbon['komentar_mgr_fin'];
                        } elseif ($dataKasbon['status_kasbon'] == '606') {
                            echo $dataKasbon['komentar'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($dataKasbon['status_kasbon'] == '202') { ?>
                            <a href="index.php?p=dtl_kasbonditolak&id_kasbon=<?= $dataKasbon['id_kasbon']; ?>" class="btn btn-primary " title="Lihat"> Lihat</a>
                        <?php } elseif ($dataKasbon['status_kasbon'] == '606') { ?>
                            <button type="button" class="btn btn-primary modalLPJ" data-toggle="modal" data-target="#LPJ_<?= $dataKasbon['id_kasbon']; ?>" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-send"></i> Ajukan Kembali</button></span>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak_<?= $dataKasbon['id_kasbon']; ?>"> Reject </button></span></a>
                        <?php } ?>
                        <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approve_<?= $dataKasbon['id_kasbon']; ?>" title="Setuju"><i class="fa fa-check"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#tolak_<?= $dataKasbon['id_kasbon']; ?>" title="Tolak"><i class="fa fa-close"></i></button> -->
                    </td>
                </tr>
                <!-- MODAL TOLAK REMBO YG IKUT NGELOOPING -->
                <div id="tolak_<?= $dataKasbon['id_kasbon']; ?>" class="modal fade" role="dialog">
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
                                <form method="POST" enctype="multipart/form-data" action="tolakpurchasing_kasbon.php" class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group ">
                                            <div class="col-sm-4">
                                                <input type="hidden" value="<?= $dataKasbon['id_kasbon']; ?>" class="form-control" name="id_kasbon">
                                                <input type="hidden" value="ditolak_mr&sp=ditolak_kasbon" class="form-control" name="url">
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

                <!--  -->
                <div id="LPJ_<?= $dataKasbon['id_kasbon']; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Ajukan Kembali Pengajuan</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="reapprove_kasbon.php" class="form-horizontal">
                                    <div class="box-body">
                                        <div class="form-group ">
                                            <div class="col-sm-4">
                                                <input type="hidden" name="harga" value="<?= $dataKasbon['harga_akhir']; ?>">
                                                <input type="hidden" name="doc_lpj_lama" value="<?= $dataKasbon['doc_lpj']; ?>">
                                                <input type="hidden" name="id_kasbon" value="<?= $dataKasbon['id_kasbon']; ?>">
                                                <input type="hidden" required class="form-control is-valid" name="status" value="<?= $dataKasbon['status_kasbon']; ?>">
                                                <input type="hidden" name="url" value="ditolak_mr&sp=ditolak_kasbon">
                                            </div>
                                        </div>
                                        <h4>Yakin ingin mengajukan kembali kasbon <b><?= $dataKasbon['nm_barang']; ?></b>?</h4>
                                        <!-- <div class="form-group ">
                                            <label for="nominal_pengembalian" class="col-sm-offset- col-sm-3 control-label">Pengembalian/Penambahan</label>
                                            <div class="col-sm-offset-1 col-sm-5">
                                                <select name="aksi" id="aksi" class="form-control">
                                                    <option value="">--- Tidak Ada ---</option>
                                                    <option value="pengembalian">Pengembalian</option>
                                                    <option value="penambahan">Penambahan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br><br>
                                        <div id="nml">
                                            <div class="form-group ">
                                                <label for="nominal_pengembalian" class="col-sm-offset-1 col-sm-3 control-label">Nominal</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" name="nominal_pengembalian" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                                    <br>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!-- <div class="form-group ">
                                            <label for="doc_lpj" class="col-sm-offset-1 col-sm-3 control-label">Document </label>
                                            <div class="col-sm-5"> -->
                                        <!-- <div class="input-group input-file" name="doc_lpj" required> -->
                                        <!-- <input type="text" class="form-control" />
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                    </span> -->
                                        <!-- <input type="file" class="form-control" name="doc_lpj" accept="application/pdf"> -->
                                        <!-- </div> -->
                                        <!-- <p style="color: red;"><i>*Kosongkan jika tidak dirubah</i></p> -->
                                        <!-- </div>
                                        </div> -->
                                    </div>
                                    <div class=" modal-footer">
                                        <button class="btn btn-success" type="submit" name="kirim">Kirim</button></span></a>
                                        &nbsp;
                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
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
<script>
    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost'  accept='application/pdf' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function() {
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function() {
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function() {
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor", "pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }

    $(function() {
        bs_input_file();
    });
</script>