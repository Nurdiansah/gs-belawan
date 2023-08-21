<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id=" ">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>Kode </th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Verifikasi Pajak</th>
                <th>Alasan Ditolak</th>
                <!-- <th>Status</th> -->
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($queryUser)) {
                while ($row = mysqli_fetch_assoc($queryUser)) {

            ?>
                    <tr>
                        <td> <?= $no; ?> </td>
                        <td> <?= $row['id_kasbon']; ?> </td>
                        <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                        <td> <?= $row['keterangan']; ?> </td>
                        <?php if ($row['vrf_pajak'] == "bp") { ?>
                            <td>Sebelum Pembayaran</td>
                        <?php } else { ?>
                            <td>Setelah LPJ</td>
                        <?php } ?>
                        <td> <?= $row['komentar']; ?>&#13;&#10;<?= $row['komentar_mgr_ga']; ?>&#13;&#10;<?= $row['komentar_mgr_fin']; ?>&#13;&#10;<?= $row['komentar_pajak']; ?>&#13;&#10;<?= $row['komentar_mgr_finjkt']; ?><?= $row['komentar_direktur']; ?></td>
                        <!-- <?php if ($row['status_kasbon'] == '101') { ?>
                            <td><span class="label label-danger">Ditolak Manager</span></td>
                        <?php } elseif ($row['status_kasbon'] == '202') { ?>
                            <td><span class="label label-danger">Ditolak Costcontrol</span></td>
                        <?php } elseif ($row['status_kasbon'] == '404') { ?>
                            <td><span class="label label-danger">Ditolak Pajak</span></td>
                        <?php } elseif ($row['status_kasbon'] == '505') { ?>
                            <td><span class="label label-danger">Ditolak Direksi</span></td>
                        <?php } elseif ($row['status_kasbon'] == '707') { ?>
                            <td><span class="label label-danger">Ditolak Kasir</span></td>
                        <?php } ?> -->
                        <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
                        <td>
                            <?php if ($row['status_kasbon'] == '707') { ?>
                                <button type="button" class="btn btn-primary modalLPJ" data-toggle="modal" data-target="#LPJ" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-send"></i> LPJ Ulang </button></span>
                            <?php } elseif ($row['status_kasbon'] == '101' || $row['status_kasbon'] == '202') { ?>
                                <button type="button" class="btn btn-warning modalRelease" data-toggle="modal" data-target="#releaseKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-rocket"></i> Release</button>
                                <button type="button" class="btn btn-success modalEdit" data-toggle="modal" data-target="#editKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#hapusKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-trash"></i> Delete</button>
                            <?php } ?>
                        </td>
                    </tr>

            <?php
                    $no++;
                    $vrf_pajak = $row['vrf_pajak'];
                    $idPK = $row['programkerja_id'];
                }
            } ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah  -->
<div id="tambahKasbon" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Kasbon</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="create_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="id_divisi" value="<?= $idDivisi ?>">
                        <div class="form-group ">
                            <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-5">
                                <select class="form-control select2" name="id_anggaran" required>
                                    <option value="">--Kode Anggaran--</option>
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$idDivisi' AND tahun = '$tahun' ORDER BY nm_item ASC");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['nm_item'] . ' ' . $rowAnggaran['kd_anggaran']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>LPJ
                        lpj
                        lpj
                        <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon ">Rp.</span>
                                <input type="text" class="form-control" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="doc" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                        <div class="col-sm-5    ">
                            <div class="input-group input-file" name="doc">
                                <input type="text" class="form-control" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-choose" type="button">Browse</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="tes" for="keterangan" class="control-label">Keterangan: </label>
                        <!-- <div class="col-sm-8"> -->
                        <textarea rows="7" type="textarea" required class="form-control" name="keterangan" placeholder="Keterangan Kebutuhan"></textarea>
                        <!-- </div> -->
                    </div>
                    <div class=" modal-footer">
                        <input type="submit" name="submit" class="btn btn-primary col-sm-offset-1 " value="Tambah">
                        &nbsp;
                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- Akhir Modal Tambah Kasbon  -->


<!--  -->
<div id="LPJ" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Konfirmasi Laporan Pertanggung Jawaban </h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="ulang_lpj_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" name="harga" id="me_harga_akhir">
                                <input type="hidden" name="id_kasbon" id="me_id_kasbon">
                                <input type="hidden" name="nilai_barang" id="me_nilai_barang">
                                <input type="hidden" name="nilai_jasa" id="me_nilai_jasa">
                                <input type="hidden" name="vrf_pajak" id="me_vrf_pajak">
                                <input type="hidden" name="doc_lpj_lama" id="me_doc_lpj_lama">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal_pengembalian" class="col-sm-offset- col-sm-3 control-label">Pengembalian/Penambahan</label>
                            <div class="col-sm-offset-1 col-sm-5">
                                <select name="aksi" id="aksi" class="form-control">
                                    <option value="">--- Tidak Ada ---</option>
                                    <option value="pengembalian">Pengembalian</option>
                                    <option value="penambahan">Penambahan</option>
                                </select>
                            </div>
                        </div>
                        <div id="nml">
                            <div class="form-group ">
                                <label for="nominal_pengembalian" class="col-sm-offset-1 col-sm-3 control-label">Nominal</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="nominal_pengembalian" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="doc_lpj" class="col-sm-offset-1 col-sm-3 control-label">Document </label>
                            <div class="col-sm-5">
                                <div class="input-group input-file" name="doc_lpj" required>
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <p style="color: red;"><i>*Kosongkan jika tidak dirubah</i></p>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-success" type="submit" name="kirim">Kirim</button></span></a>
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


<!--  LPJ -->
<div id="LPJ_LAMA" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Konfirmasi Ulang LPJ</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="ulang_lpj_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="harga" id="me_harga_akhir">
                        <input type="hidden" name="id_kasbon" id="me_id_kasbon">
                        <input type="hidden" name="vrf_pajak" id="me_vrf_pajak">
                        <input type="hidden" name="doc_lpj_lama" id="me_doc_lpj_lama">
                        <div class="form-group ">
                            <label for="nominal_pengembalian" class="col-sm-offset-1 col-sm-3 control-label">Nominal Pengembalian</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nominal_pengembalian" id="me_pengembalian" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="penambahan" class="col-sm-offset-1 col-sm-3 control-label">Nominal Penambahan</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="penambahan" id="me_penambahan" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="doc_lpj" class="col-sm-offset-1 col-sm-3 control-label">Document</label>
                            <div class="col-sm-5">
                                <div class="input-group input-file" name="doc_lpj">
                                    <!-- <input type="file" class="form-control" name="doc_lpj" accept="application/pdf"> -->
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <p style="color: red;"><i>*Kosongkan jika tidak dirubah</i></p>
                            </div>
                        </div>


                        <br><br>
                        <div class=" modal-footer">
                            <input type="submit" class="btn btn-success" name="kirim" value="Kirim">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End LPJ -->

<!-- Modal Edit  -->
<div id="editKasbon" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Kasbon</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="edit_kasbon_ditolak.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="id" id="me_id">
                        <input type="hidden" name="id_dbo" id="me_id_dbo">
                        <input type="hidden" name="doc_pendukung_lama" id="me_doc_pendukung_lama">
                        <div class="form-group">
                            <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                            <div class="col-sm-5">
                                <select class="form-control select2 programkerja_id_edit" name="id_programkerja" id="id_programkerja_edit" required>
                                    <!-- <option value="">--Program Kerja--</option> -->
                                    <?php

                                    $queryProgramKerja = mysqli_query($koneksi, "SELECT id_programkerja, id_costcenter, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi) AS cost_center, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_programkerja
                                                                                                    FROM cost_center
                                                                                                    JOIN pt
                                                                                                        ON id_pt = pt_id
                                                                                                    JOIN divisi
                                                                                                        ON id_divisi = divisi_id
                                                                                                    JOIN parent_divisi
                                                                                                        ON id_parent = parent_id
                                                                                                    JOIN program_kerja
                                                                                                        ON id_costcenter = costcenter_id
                                                                                                    WHERE divisi_id = '$idDivisi'
                                                                                                    AND tahun = '$tahun'
                                                                                                    ORDER BY program_kerja ASC
                                                                                ");
                                    if (mysqli_num_rows($queryProgramKerja)) {
                                        while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                    ?>
                                            <option value="<?= $rowPK['id_programkerja']; ?>" <?= $rowPK['id_programkerja'] == $idPK ? 'selected' : ''; ?>><?= $rowPK['program_kerja'] . " [" . $rowPK['nm_programkerja']; ?>]</option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="kotakAnggaran_edit">
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2 id_anggaran_edit" name="id_anggaran" id="id_anggaran_edit" required>
                                        <option value="">--Kode Anggaran--</option>
                                        <?php
                                        $queryAnggaran = mysqli_query($koneksi, "SELECT id_anggaran, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item
                                                                                FROM anggaran agg
                                                                                JOIN program_kerja
                                                                                    ON programkerja_id = id_programkerja
                                                                                JOIN cost_center cc
                                                                                    ON costcenter_id = id_costcenter
                                                                                JOIN pt pt
                                                                                    ON pt_id = id_pt
                                                                                JOIN divisi dvs
                                                                                    ON divisi_id = dvs.id_divisi
                                                                                JOIN parent_divisi pd
                                                                                    ON parent_id = id_parent
                                                                                JOIN segmen sg
                                                                                    ON sg.id_segmen = agg.id_segmen
                                                                                WHERE programkerja_id = '$idPK'
                                                                                ORDER BY nm_item ASC
                                                                            ");
                                        if (mysqli_num_rows($queryAnggaran)) {
                                            while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                        ?>
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['nm_item'] . ' - [' . $rowAnggaran['program_kerja']; ?>]</option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon ">Rp.</span>
                                    <input type="text" class="form-control" id="me_nominal" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="doc_pendukung" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                            <div class="col-sm-5    ">
                                <div class="input-group input-file" name="doc_pendukung">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <span class="text-danger"><i>*Kosongkan jika tidak dirubah</i></span>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="vrf_pajak" class="col-sm-offset-1 col-sm-3 control-label">Verifikasi Pajak</label>
                            <div class="col-sm-5">
                                <select class="form-control select2" name="vrf_pajak" id="me_vrf_pajak" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="bp" <?php if ($vrf_pajak == "bp") {
                                                            echo "selected=selected";
                                                        } ?>> Sebelum Pembayaran</option>
                                    <option value="as" <?php if ($vrf_pajak == "as") {
                                                            echo "selected=selected";
                                                        } ?>> Setelah LPJ</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="keterangan" class="control-label col-sm-offset-1 col-sm-3">Keterangan: </label>
                            <div class="col-sm-5">
                                <textarea rows="7" type="textarea" required class="form-control" name="keterangan" id="me_keterangan" placeholder="Keterangan Kebutuhan"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <h3 class="text-center">Document Pendukung </h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="" id="me_doc"></iframe>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <input type="submit" name="edit" class="btn btn-primary col-sm-offset-1 " value="Edit">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Edit Kasbon  -->

<!-- Modal hapus -->
<div id="hapusKasbon" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="delete_kasbon.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="md_id_kasbon">
                            <input type="hidden" name="id_dbo" value="" id="md_id_dbo">
                            <input type="hidden" name="url" value="ditolak_kasbon&sp=tolak_user">

                            <h4>Apakah anda yakin ingin menghapus Kasbon<b><span id="md_keterangan"></b></span></h4>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="delete">Delete</button></span></a>
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
<!-- End hapus -->

<!-- Modal release -->
<div id="releaseKasbon" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="release_kembali_kasbon.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="mr_id_kasbon">
                            <input type="hidden" name="id_dbo" value="" id="mr_id_dbo">

                            <h4>Apakah anda yakin ingin merelease kembali Kasbon <b><span id="mr_keterangan"></b></span> ini ?</h4>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="release">Kirim</button></span></a>
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

<?php
$host = host();
?>

<script>
    var host = '<?= $host ?>';
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    console.log(host);

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

    // Modal Lihat
    $(function() {
        $('.modalLihat').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/pettycash/getdatapetty.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#ml_kd_anggaran').val(data.kd_anggaran);
                    $('#ml_nominal').val(formatRibuan(data.total_pettycash));
                    $('#ml_keterangan').val(data.keterangan_pettycash);
                }
            });
        });
    });

    // Modal LPJ Ulang
    $(function() {
        $('.modalLPJ').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/kasbon/getlpjulangkasbon.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#me_id_kasbon').val(data.id_kasbon);
                    $('#me_nilai_barang').val(data.nilai_barang);
                    $('#me_nilai_jasa').val(data.nilai_jasa);
                    $('#me_harga_akhir').val(data.harga_akhir);
                    $('#me_vrf_pajak').val(data.vrf_pajak);
                    $('#me_doc_lpj_lama').val(data.doc_lpj);
                    $('#me_pengembalian').val(data.pengembalian);
                    $('#me_penambahan').val(formatRibuan(data.penambahan));

                }
            });
        });
    });

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/kasbon/getkasbonuser.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#me_id').val(data.id_kasbon);
                    $('#me_id_dbo').val(data.id_dbo);
                    $('#me_doc_pendukung_lama').val(data.doc_pendukung);
                    $('#id_anggaran_edit').val(data.id_anggaran);
                    $('#me_nominal').val(tandaPemisahTitik(data.harga_akhir.substring(0, data.harga_akhir.length - 3)));
                    $('#me_keterangan').val(data.keterangan);
                    $('#me_vrf_pajak').val(data.vrf_pajak);
                    $('#me_doc').attr('src', '../file/doc_pendukung/' + data.doc_pendukung);

                }
            });
        });
    });

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/kasbon/getkasbonuser.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#md_id_kasbon').val(data.id_kasbon);
                    $('#md_id_dbo').val(data.id_dbo);
                    $('#md_keterangan').text(data.keterangan);
                }
            });
        });
    });

    // Modal Release
    $(function() {
        $('.modalRelease').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/kasbon/getkasbonuser.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#mr_id_kasbon').val(data.id_kasbon);
                    $('#mr_id_dbo').val(data.id_dbo);
                    $('#mr_keterangan').text(data.keterangan);
                }
            });
        });
    });


    function formatRibuan(angka) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');

        return ribuan;
    }

    // sembunyikan nominal
    $("#nml").hide();

    $('#aksi').on('change', function() {
        let aksi = this.value;

        if (aksi == 'pengembalian' || aksi == 'penambahan') {
            $("#nml").show();
        } else {
            $("#nml").hide();
        }
    });
</script>