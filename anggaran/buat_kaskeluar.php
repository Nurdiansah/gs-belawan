<?php



include "../fungsi/koneksi.php";

$queryUser =  mysqli_query($koneksi, "SELECT area from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];


$tanggalCargo = date("Y-m-d");
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=lihat_kaskeluar" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">PENGAJUAN KAS KELUAR</h3>
                </div>
                <form method="post" name="form" action="add_kaskeluar.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_vendor" class="col-sm-offset-1 col-sm-3 control-label">Dibayarkan Kepada</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control is-valid" name="nm_vendor" placeholder="Input Nama Vendor">
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="tgl_bkk" class="col-sm-offset-1 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control tanggal" name="tgl_pengajuan" value="<?= $tanggalCargo ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="keterangan" class="col-sm-offset-1 col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="keterangan">
                            </div>
                        </div>
                        <div class="perhitungan">
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control " name="nilai_bkk" id="nilai" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">PPN</span>
                                        <input type="text" required class="form-control " name="nilai_ppn" value=0 id="ppn" />
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Biaya Lain lain</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control " name="bll_bkk" id="bll_bkk" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Jumlah </label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" name="jml_bkk" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="bank_tujuan" class="col-sm-offset-1 col-sm-3 control-label">Bank Tujuan</label>
                                <div class="col-sm-4">
                                    <input type="text" required class="form-control" name="bank_tujuan">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="norek_tujuan" class="col-sm-offset-1 col-sm-3 control-label">No Rekening</label>
                                <div class="col-sm-4">
                                    <input type="text" required class="form-control" name="norek_tujuan">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="invoice" class="col-sm-offset-1 col-sm-3 control-label">Invoice</label>
                                <div class="col-sm-4">
                                    <div class="input-group input-file" name="invoice">
                                        <input type="text" class="form-control" required />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-4 " value="Buat">
                                &nbsp;
                                <input type="reset" class="btn btn-danger" value="Batal">
                            </div>
                        </div>
                </form>

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

    $(".perhitungan").keyup(function() {
        var nilai = parseInt($("#nilai").val())
        var nilaia = tandaPemisahTitik(nilai);
        $("#nilai").attr("value", nilaia)
        var ppn = parseInt($("#ppn").val())
        var bll = parseInt($("#bll_bkk").val())
        var ppna = nilai * ppn / 100;
        var jml = nilai + ppna + bll;
        var jmlb = Math.floor(jml);
        var jmla = tandaPemisahTitik(jmlb);
        $("#jml").attr("value", jmla);
        document.form.jml_bkk.value = jmla;
    });


    // onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" 

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
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