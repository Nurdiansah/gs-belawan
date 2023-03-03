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
                                            LEFT JOIN detail_biayaops
                                                ON id = id_dbo
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
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">LPJ Pettycash</h3>
                </div>
                <form method="post" name="form" action="upd_revisi_petty.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <br>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label">Kode Pettycash </label>
                            <div class="col-sm-3">
                                <input name="id" type="text" class="form-control" value="<?= $data['kd_pettycash']; ?>" readonly>
                            </div>
                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label"> </label>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#konfirmasi"><i class="fa fa-send"></i> LPJ </button></span></a>
                            </div>

                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label" readonly>Nominal </label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon ">Rp.</span>
                                    <input type="text" class="form-control" value="<?= formatRupiah2($data['total_pettycash']); ?>" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" readonly>
                                </div>
                            </div>
                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran" disabled>
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
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data['keterangan_pettycash']; ?></textarea>
                            </div>
                            <label for="nm_barang" class="col-sm-offset- col-sm-2 control-label">Nama Barang</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="nm_barang" disabled class="form-control "> <?= $data['nm_barang']; ?></textarea>
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

<!--  -->
<div id="konfirmasi" class="modal fade" role="dialog">
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
                <form method="post" enctype="multipart/form-data" action="send_lpj_petty.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['total_pettycash']; ?>" class="form-control" name="nominal_pengajuan" readonly>
                                <input type="hidden" value="<?= $data['id_pettycash']; ?>" class="form-control" name="id_pettycash" readonly>
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
                                    <input type="text" class="form-control" name="nominal" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="doc_lpj" class="col-sm-offset-1 col-sm-3 control-label">Document </label>
                            <div class="col-sm-5">
                                <div class="input-group input-file" name="doc_lpj" required>
                                    <input type="text" class="form-control" required />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-success" type="submit" name="submit">Kirim</button></span></a>
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

    // batas script baru

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