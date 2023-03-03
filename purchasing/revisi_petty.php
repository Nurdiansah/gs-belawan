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

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id ");

$tanggalCargo = date("Y-m-d");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

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
                    <input name="id_dbo" type="hidden" class="form-control" value="<?= $data['id_dbo']; ?>" readonly>
                    <input name="doc_penawaran_lama" type="hidden" class="form-control" value="<?= $data['doc_penawaran_lama']; ?>" readonly>
                    <input name="status_petty" type="hidden" class="form-control" value="<?= $data['status_pettycash']; ?>" readonly>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="mb-3">
                                <label for="validationTextarea" class="col-sm-offset-1 col-sm-1 control-label">Alasan Penolakan: </label>
                                <div class="col-sm-8">
                                    <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" readonly><?= $data['komentar_pettycash']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label">ID Pettycash </label>
                            <div class="col-sm-3">
                                <input name="kd_pettycash" type="text" class="form-control" value="<?= $data['kd_pettycash']; ?>" readonly>
                            </div>

                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran">
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran != '$idAnggaran' ORDER BY nm_item ASC");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label">Nominal </label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon ">Rp.</span>
                                    <input type="text" class="form-control" value="<?= str_replace(",00", "", formatRupiah2($data['total_pettycash'])); ?>" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                </div>
                            </div>

                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Document Pendukung</label>
                            <div class="col-sm-3">
                                <div class="input-group input-file" name="doc_penawaran">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <p style="size: 10px; color: red;"><i>Kosongkan jika tidak dirubah</i></p>
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
                        <!-- <div class="form-group ">
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
                        </div> -->
                        <div class="form-group">
                            <?php if ($data['status_pettycash'] == 101) { ?>
                                <button type="submit" name="simpan" class="btn btn-success col-sm-offset-5"><i class="fa fa-save"></i> Simpan</button>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" value="Batal">
                            <?php } else { ?>
                                <button type="submit" name="simpan" class="btn btn-success col-sm-offset-5"><i class="fa fa-save"></i> Simpan</button>
                                &nbsp;
                                <input type="submit" name="revisi" class="btn btn-primary col-sm-offset-" value="Update">
                                &nbsp;
                                <input type="reset" class="btn btn-danger" value="Batal">
                            <?php } ?>
                        </div>
                        <!-- <div class="form-group">
                            <h3 class="text-center">Document Pendukung </h3>
                            <div class="col-sm-12">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/doc_lpj/<?= $data['doc_lpj_pettycash']; ?>" id="me_doc"></iframe>
                                </div>
                            </div>
                        </div> -->
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