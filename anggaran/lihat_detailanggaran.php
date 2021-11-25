<?php

include "../fungsi/koneksi.php";

// if(!isset($_GET['id'])){
//     header("location:index.php");
//   }

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT area from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryAnggaran =  mysqli_query($koneksi, "SELECT * 
                                              from anggaran a
                                              JOIN divisi d
                                              ON a.id_divisi=d.id_divisi                                              
                                              JOIN golongan g
                                              ON a.id_golongan = g.id_golongan
                                              JOIN sub_golongan sg
                                              ON a.id_subgolongan = sg.id_subgolongan
                                              WHERE id_anggaran = '$id'");
$rowAnggaran = mysqli_fetch_assoc($queryAnggaran);
$harga = number_format($rowAnggaran['harga'], 0, ",", ".");
$januari = number_format($rowAnggaran['januari_nominal'], 0, ",", ".");
$februari = number_format($rowAnggaran['februari_nominal'], 0, ",", ".");
$maret = number_format($rowAnggaran['maret_nominal'], 0, ",", ".");
$april = number_format($rowAnggaran['april_nominal'], 0, ",", ".");
$mei = number_format($rowAnggaran['mei_nominal'], 0, ",", ".");
$juni = number_format($rowAnggaran['juni_nominal'], 0, ",", ".");
$juli = number_format($rowAnggaran['juli_nominal'], 0, ",", ".");
$agustus = number_format($rowAnggaran['agustus_nominal'], 0, ",", ".");
$september = number_format($rowAnggaran['september_nominal'], 0, ",", ".");
$oktober = number_format($rowAnggaran['oktober_nominal'], 0, ",", ".");
$november = number_format($rowAnggaran['november_nominal'], 0, ",", ".");
$desember = number_format($rowAnggaran['desember_nominal'], 0, ",", ".");
$jumlah_kuantitas = number_format($rowAnggaran['jumlah_kuantitas'], 0, ",", ".");
$jumlah_nominal = number_format($rowAnggaran['jumlah_nominal'], 0, ",", ".");

date_default_timezone_set('Asia/Jakarta');
$waktuSekarang = date('d-m-Y H:i:s');
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <br>
                <div class="row">
                    <input type="hidden" name="id_divisi" value="<?= $rowAnggaran['id_divisi'] ?>">
                    <div class="col-md-2">
                        <a href="index.php?p=anggaran&divisi=<?= $_GET['divisi'] ?>&tahun=<?= $_GET['tahun']; ?>" class="btn btn-primary">Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Anggaran</h3>
                </div>
                <form method="post" name="form" action="add_anggaran.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control" name="id_divisi" value="<?= $rowAnggaran['nm_divisi']; ?>">
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes" for="tahun" class="col-sm-2 control-label">Anggaran Tahun</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control" name="id_tahun" value="<?= $rowAnggaran['tahun']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="no_coa" class="col-sm-2 control-label">Nomor Coa</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control" name="no_coa" value="<?= $rowAnggaran['no_coa']; ?>">
                            </div>
                            <!-- </div>
                        <div class="form-group"> -->
                            <label id="tes" for="kd_anggaran" class="col-sm-2 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control" name="kd_anggaran" value="<?= $rowAnggaran['kd_anggaran']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_golongan" class="col-sm-offset-1 col-sm-1 control-label">Golongan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control" name="id_golongan" value="<?= $rowAnggaran['nm_golongan']; ?>">
                            </div>
                            <!-- </div>
                        <div class="form-group"> -->
                            <label id="tes" for="id_subgolongan" class="col-sm-offset-0 col-sm-2 control-label">Sub Golongan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control" name="id_subgolongan" value="<?= $rowAnggaran['nm_subgolongan']; ?>">
                            </div>
                        </div>
                        <div class="perhitungan">
                            <div class="form-group">
                                <label id="tes" for="nm_item" class="col-sm-offset-1 col-sm-1 control-label">Deskripsi</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control" name="nm_item" value="<?= $rowAnggaran['nm_item']; ?>">
                                </div>
                                <!-- </div>
                        <div class="form-group"> -->
                                <label id="tes" for="harga" class="col-sm-offset-1 col-sm-1 control-label" id="hargal">Harga</label>
                                <div class="col-sm-3">

                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" disabled class="form-control " name="harga" id="harga_nominal" value="<?= $harga ?>" />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="Quantity" class="col-sm-offset-1 col-sm-3 control-label">Quantity</label>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_januari" class="col-sm-offset-1 col-sm-4 control-label">Nominal</label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="januari_kuantitas" class="col-sm-offset- col-sm-2 control-label">Januari </label>
                                <div class="col-sm-3">
                                    <input type="number" disabled class="form-control" name="januari_kuantitas" id="januari_kuantitas" value="<?= $rowAnggaran['januari_kuantitas']; ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_januari" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" disabled class="form-control" name="nominal_januari" value="<?= $januari; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="februari_kuantitas" class="col-sm-offset- col-sm-2 control-label">Februari </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['februari_kuantitas']; ?>" min="0" name="februari_kuantitas" id="februari_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_februari" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_februari" readonly value="<?= $februari; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="maret_kuantitas" class="col-sm-offset- col-sm-2 control-label">Maret </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['maret_kuantitas']; ?>" min="0" name="maret_kuantitas" id="maret_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_maret" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_maret" readonly value="<?= $maret; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="april_kuantitas" class="col-sm-offset- col-sm-2 control-label">April </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['april_kuantitas']; ?>" min="0" name="april_kuantitas" id="april_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_april" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_april" readonly value="<?= $april; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="mei_kuantitas" class="col-sm-offset- col-sm-2 control-label">Mei </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['mei_kuantitas']; ?>" min="0" name="mei_kuantitas" id="mei_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_mei" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_mei" readonly value="<?= $mei; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="juni_kuantitas" class="col-sm-offset- col-sm-2 control-label">Juni </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['juni_kuantitas']; ?>" min="0" name="juni_kuantitas" id="juni_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_juni" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_juni" readonly value="<?= $juni; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="juli_kuantitas" class="col-sm-offset- col-sm-2 control-label">Juli </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['juli_kuantitas']; ?>" min="0" name="juli_kuantitas" id="juli_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_juli" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_juli" readonly value="<?= $juli; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="agustus_kuantitas" class="col-sm-offset- col-sm-2 control-label">Agustus </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['agustus_kuantitas']; ?>" min="0" name="agustus_kuantitas" id="agustus_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_agustus" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_agustus" readonly value="<?= $agustus; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="september_kuantitas" class="col-sm-offset- col-sm-2 control-label">September </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['september_kuantitas']; ?>" min="0" name="september_kuantitas" id="september_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_september" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_september" readonly value="<?= $september; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="oktober_kuantitas" class="col-sm-offset- col-sm-2 control-label">Oktober </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['oktober_kuantitas']; ?>" min="0" name="oktober_kuantitas" id="oktober_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_oktober" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_oktober" readonly value="<?= $oktober; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="november_kuantitas" class="col-sm-offset- col-sm-2 control-label">November </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['november_kuantitas']; ?>" min="0" name="november_kuantitas" id="november_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_november" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_november" readonly value="<?= $november; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="desember_kuantitas" class="col-sm-offset- col-sm-2 control-label">Desember </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" disabled value="<?= $rowAnggaran['desember_kuantitas']; ?>" min="0" name="desember_kuantitas" id="desember_kuantitas">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes" for="nominal_desember" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nominal_desember" readonly value="<?= $desember; ?>" />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-auto">
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-offset- col-sm-2 control-label">Jumlah Kuantitas</label>
                                    <div class="col-sm-3">
                                        <input type="text" required class="form-control" name="jml_kuantitas" disabled value="<?= $jumlah_kuantitas; ?>" />
                                    </div>
                                    <!-- </div>
                            <div class="form-group"> -->
                                    <label id="tes" for="jml_bkk" class="col-sm-offset- col-sm-2 control-label">Jumlah Nominal </label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" name="jml_nominal" disabled value="<?= $jumlah_nominal; ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
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