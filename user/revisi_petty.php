<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['id_divisi'];

$queryDetail =  mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp
                                                JOIN anggaran a
                                                ON a.id_anggaran = tp.id_anggaran
                                                WHERE tp.id_pettycash = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$idAnggaran = $data['id_anggaran'];
$idPK = $data['programkerja_id'];

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id ");

$tanggalCargo = date("Y-m-d");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=lihat_detailanggaran&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_sdboedit&id=$id");
    }
}

?>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Revisi Pettycash</h3>
                </div>
                <form method="post" name="form" action="upd_revisi_petty.php" enctype="multipart/form-data" class="form-horizontal">
                    <input name="id" type="hidden" class="form-control" value="<?= $data['id_pettycash']; ?>" readonly>
                    <input name="doc_lpj_lama" type="hidden" class="form-control" value="<?= $data['doc_lpj_pettycash']; ?>" readonly>
                    <input name="status_petty" type="hidden" class="form-control" value="<?= $data['status_pettycash']; ?>" readonly>
                    <div class="box-body">
                        <?php if ($_GET['aksi'] == "proses_petty") { ?>
                            <div class="form-group">
                                <div class="mb-3">
                                    <label for="validationTextarea" class="col-sm-offset-1 col-sm-1 control-label">Alasan Penolakan: </label>
                                    <div class="col-sm-8">
                                        <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" readonly><?= $data['komentar_pettycash']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        <?php } ?>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label">ID Pettycash </label>
                            <div class="col-sm-3">
                                <input name="kd_pettycash" type="text" class="form-control" value="<?= $data['kd_pettycash']; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label">Nominal </label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon ">Rp.</span>
                                    <input type="text" class="form-control" value="<?= formatRupiah2(round($data['total_pettycash'])); ?>" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                </div>
                            </div>
                            <label id="tes" for="id_programkerja" class="col-sm-offset- col-sm-2 control-label">Program Kerja</label>
                            <div class="col-sm-3">
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
                            <br><br>
                            <div class="kotakAnggaran_edit">
                                <label for="id_anggaran" class="col-sm-offset-5 col-sm-2 control-label">Kode Anggaran</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2 id_anggaran_edit" name="id_anggaran" id="id_anggaran_edit" required>
                                        <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
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
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox" <?= $rowAnggaran['id_anggaran'] == $data['id_anggaran'] ? 'selected=selected' : ''; ?>><?= $rowAnggaran['nm_item'] . ' - [' . $rowAnggaran['program_kerja']; ?>]</option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="validationTextarea" class="col-sm-offset-1 col-sm-1 control-label">Deskripsi : </label>
                                <div class="col-sm-8">
                                    <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Deskripsi"><?= $data['keterangan_pettycash']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="doc_lpj" class="col-sm-offset-1 col-sm-1 control-label">Document Pendukung </label>
                            <div class="col-sm-8">
                                <div class="input-group input-file" name="doc_lpj">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <span class="text-danger"> <i>*Kosongkan jika tidak dirubah</i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="simpan" class="btn btn-success col-sm-offset-5"><i class="fa fa-save"></i> Simpan</button>
                            &nbsp;
                            <?php if ($_GET['aksi'] == "proses_petty") { ?>
                                <input type="submit" name="revisi" class="btn btn-primary col-sm-offset-" value="Submit">
                                &nbsp;
                            <?php } ?>
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div>
                        <div class="form-group">
                            <h3 class="text-center">Document Pendukung </h3>
                            <div class="col-sm-12">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/doc_lpj/<?= $data['doc_lpj_pettycash']; ?>" id="me_doc"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <br>
                <hr>
                <br>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });

    $(document).ready(function() {
        $('.datatab').DataTable();
    });

    // batas script baru

    // Browse
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