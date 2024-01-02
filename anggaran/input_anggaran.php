<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT area from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

date_default_timezone_set('Asia/Jakarta');
$waktuSekarang = date('d-m-Y H:i:s');
$tahunAyeuna = date("Y");

?>

<section class="content">
    <div class="row">
        <form method="POST" name="form" action="add_anggaran.php" enctype="multipart/form-data" class="form-horizontal">
            <div class="col-sm-6 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Input Anggaran</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="tahun" class="col-sm-offset-1 col-sm-3 control-label">Anggaran Tahun</label>
                            <div class="col-sm-5">
                                <select name="tahun" id="tahun" class="form-control" required>
                                    <?php foreach (range(2021, $tahunAyeuna + 1) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahunAyeuna ? "selected=selected" : ''; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-3 control-label">Divisi</label>
                            <div class="col-sm-5">
                                <select name="id_divisi" id="id_divisi" class="form-control id_divisi" required>
                                    <option value="">-- Pilih Divisi --</option>
                                    <?php
                                    $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi <> '0' ORDER BY nm_divisi ASC");
                                    if (mysqli_num_rows($queryDivisi)) {
                                        while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                                    ?>
                                            <option value="<?= $rowDivisi['id_divisi']; ?>" type="checkbox"><?= $rowDivisi['nm_divisi']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="ktkPK">
                            <div class="form-group">
                                <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                <div class="col-sm-5">
                                    <select name="program_kerja" id="id_programkerja" class="form-control" required>
                                        <option>-- Pilih Program Kerja --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="tahun" class="col-sm-offset-1 col-sm-3 control-label">Segmen/Job Code</label>
                            <div class="col-sm-5">
                                <select name="segmen" class="form-control">
                                    <?php $querySegmen = mysqli_query($koneksi, "SELECT * FROM segmen ORDER BY nm_segmen ASC");
                                    while ($dataSegmen = mysqli_fetch_assoc($querySegmen)) {
                                    ?>
                                        <option value="<?= $dataSegmen['id_segmen']; ?>"><?= $dataSegmen['nm_segmen']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="header" class="col-sm-offset-1 col-sm-3 control-label">Header</label>
                            <div class="col-sm-5">
                                <select name="id_header" id="id_header" class="form-control header_id" required>
                                    <option value="">-- Pilih Header --</option>
                                    <?php
                                    $queryHeader = mysqli_query($koneksi, "SELECT * FROM header ORDER BY nm_header ASC");
                                    while ($dataHeader = mysqli_fetch_assoc($queryHeader)) {
                                    ?>
                                        <option value="<?= $dataHeader['id_header']; ?>" type="checkbox"><?= $dataHeader['nm_header']; ?></option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="sub_header" class="col-sm-offset-1 col-sm-3 control-label">Sub Header</label>
                            <div class="col-sm-5">
                                <select name="sub_header" id="sub_header" class="form-control">
                                    <option>-- Pilih Sub Header --</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="waktu" value="<?php echo $waktuSekarang; ?>">
                        <div class="form-group">
                            <label id="tes" for="no_coa" class="col-sm-offset-1 col-sm-3 control-label">Nomor Coa</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="no_coa" id="no_coa">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nm_coa" class="col-sm-offset-1 col-sm-3 control-label">Nama Coa</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nm_coa">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_golongan" class="col-sm-offset-1 col-sm-3 control-label">Tipe Anggaran</label>
                            <div class="col-sm-5">
                                <select name="tipe_anggaran" class="form-control">
                                    <option value="OPEX">OPEX</option>
                                    <option value="CAPEX">CAPEX</option>
                                    <option value="HUTANG PAJAK">HUTANG PAJAK</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_subgolongan" class=" col-sm-offset-1 col-sm-3 control-label">Jenis Anggaran</label>
                            <div class="col-sm-5">
                                <select name="jenis_anggaran" class="form-control">
                                    <option value="BIAYA">BIAYA</option>
                                    <option value="PENDAPATAN">PENDAPATAN</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="perhitungan"> -->
                        <div class="form-group">
                            <label id="tes" for="deskripsi" class="col-sm-offset-1 col-sm-3 control-label">Deskripsi Anggaran</label>
                            <div class="col-sm-5">
                                <!-- <input type="text" required class="form-control" name="deskripsi"> -->
                                <textarea name="deskripsi" id="deskripsi" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="kd_anggaran" id="kd_anggaran">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label"></label>
                            <div class="col-sm-5">
                                <input type="checkbox" name="perdin" id="perdin" value="1">&nbsp;<label for="perdin">SPJ/Perjalanan Dinas</label>
                            </div>

                            <label id="tes" for="kd_anggaran" class="col-sm-offset-1 col-sm-3 control-label"></label>
                            <div class="col-sm-5">
                                <input type="checkbox" name="unlock" id="unlock" value="1">&nbsp;<label for="unlock">Unlock Anggaran</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Nominal</h3>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nominal_januari" class="col-sm-offset- col-sm-4 control-label">Januari </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" class="form-control" value="0" name="nominal_januari" id="nominal_januari" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                                <input type="checkbox" name="all" id="myCheck" onclick="checkBox()"><label for="myCheck">&nbsp;&nbsp;Semua Bulan</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_februari" class="col-sm-offset- col-sm-4 control-label">Februari</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_februari" id="nominal_februari" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_maret" class="col-sm-offset- col-sm-4 control-label">Maret</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_maret" id="nominal_maret" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_april" class="col-sm-offset- col-sm-4 control-label">April </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_april" id="nominal_april" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_mei" class="col-sm-offset- col-sm-4 control-label">Mei</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_mei" id="nominal_mei" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_juni" class="col-sm-offset- col-sm-4 control-label">Juni</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_juni" id="nominal_juni" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_juli" class="col-sm-offset- col-sm-4 control-label">Juli</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_juli" id="nominal_juli" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_agustus" class="col-sm-offset- col-sm-4 control-label">Agustus </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_agustus" id="nominal_agustus" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_september" class="col-sm-offset- col-sm-4 control-label">September</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_september" id="nominal_september" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_oktober" class="col-sm-offset- col-sm-4 control-label">Oktober</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_oktober" id="nominal_oktober" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_november" class="col-sm-offset- col-sm-4 control-label">November</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_november" id="nominal_november" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nominal_desember" class="col-sm-offset- col-sm-4 control-label">Desember </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input type="text" required class="form-control" value="0" name="nominal_desember" id="nominal_desember" onkeydown="return numbersonly(this, event);" onkeyup="jumlah_nominal();" />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-auto">
                            <div class="form-group">
                                <label id="tes" for="jml_bkk" class="col-sm-offset- col-sm-4 control-label">Jumlah Nominal </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" value="0" name="nominal_jumlah" id="nominal_jumlah" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-5" value="Tambah">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div>
                        <!-- </div> -->

                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    var host = '<?= host() ?>';

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

    function getNumber(data) {
        return eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value)))));
    }

    function jumlah_nominal() {
        //  Math.round(document.getElementById('nominal_januari').value);
        var nominal_januari = getNumber('nominal_januari');
        var nominal_februari = getNumber('nominal_februari');
        var nominal_maret = getNumber('nominal_maret');
        var nominal_april = getNumber('nominal_april');
        var nominal_mei = getNumber('nominal_mei');
        var nominal_juni = getNumber('nominal_juni');
        var nominal_juli = getNumber('nominal_juli');
        var nominal_agustus = getNumber('nominal_agustus');
        var nominal_september = getNumber('nominal_september');
        var nominal_oktober = getNumber('nominal_oktober');
        var nominal_november = getNumber('nominal_november');
        var nominal_desember = getNumber('nominal_desember');
        var nominal_hasil = parseInt(nominal_januari) + parseInt(nominal_februari) + parseInt(nominal_maret) + parseInt(nominal_april) + parseInt(nominal_mei) + parseInt(nominal_juni) + parseInt(nominal_juli) + parseInt(nominal_agustus) + parseInt(nominal_september) + parseInt(nominal_oktober) + parseInt(nominal_november) + parseInt(nominal_desember);

        // console.log(nominal_hasil);
        if (!isNaN(nominal_hasil)) {
            document.getElementById('nominal_jumlah').value = tandaPemisahTitik(nominal_hasil);
        }
    }

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var nominal_januari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_januari').value)))));
            var jumlah = nominal_januari * 12;

            document.form.nominal_februari.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_maret.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_april.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_mei.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_juni.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_juli.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_agustus.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_september.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_oktober.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_november.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_desember.value = tandaPemisahTitik(nominal_januari);
            document.form.nominal_jumlah.value = tandaPemisahTitik(jumlah);

        } else {
            document.form.nominal_februari.value = "0";
            document.form.nominal_maret.value = "0";
            document.form.nominal_april.value = "0";
            document.form.nominal_mei.value = "0";
            document.form.nominal_juni.value = "0";
            document.form.nominal_juli.value = "0";
            document.form.nominal_agustus.value = "0";
            document.form.nominal_september.value = "0";
            document.form.nominal_oktober.value = "0";
            document.form.nominal_november.value = "0";
            document.form.nominal_desember.value = "0";
            document.form.nominal_jumlah.value = "0";
            // text.style.display = "none";
        }
    }

    // nomor coa dengan kd anggaran sama sekarnag
    const no_coa = document.getElementById('no_coa');
    const kd_anggaran = document.getElementById('kd_anggaran');

    no_coa.addEventListener('keyup', function() {
        document.form.kd_anggaran.value = no_coa.value;
    });


    $('.header_id').on('change', function() {
        let headerId = this.value;

        // console.log(headerId);
        $.ajax({
            url: host + 'api/anggaran/getSubHeader.php',
            data: {
                id: headerId
            },
            method: 'post',
            dataType: 'json',
            success: function(data) {
                // console.log(data);

                $('#sub_header').empty()
                $.each(data, function(i, value) {
                    $('#sub_header').append($('<option>').text(value.nm_subheader).attr('value', value.id_subheader));
                });
            }
        });
        // }
    });
    $('.divisi').on('change', function() {
        let divisi = this.value;
    });

    // $(".perhitungan").keyup(function() {

    //     //ambil inputan harga
    //     // var harga = parseInt($("#harga_nominal").val())

    //     var harga = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('harga_nominal').value))))); //input ke dalam angka tanpa titik
    //     var nominal_januari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_januari').value))))); //input ke dalam angka tanpa titik
    //     var nominal_februari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_februari').value))))); //input ke dalam angka tanpa titik
    //     var nominal_maret = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_maret').value))))); //input ke dalam angka tanpa titik
    //     var nominal_april = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_april').value))))); //input ke dalam angka tanpa titik
    //     var nominal_mei = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_mei').value))))); //input ke dalam angka tanpa titik
    //     var nominal_juni = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_juni').value))))); //input ke dalam angka tanpa titik
    //     var nominal_juli = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_juli').value))))); //input ke dalam angka tanpa titik
    //     var nominal_agustus = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_agustus').value))))); //input ke dalam angka tanpa titik
    //     var nominal_september = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_september').value))))); //input ke dalam angka tanpa titik
    //     var nominal_oktober = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_oktober').value))))); //input ke dalam angka tanpa titik
    //     var nominal_november = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_november').value))))); //input ke dalam angka tanpa titik
    //     var nominal_desember = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_desember').value))))); //input ke dalam angka tanpa titik

    //     var nominal_januari = parseInt($("#januari_kuantitas").val())
    //     var fk = parseInt($("#februari_kuantitas").val())
    //     var mk = parseInt($("#maret_kuantitas").val())
    //     var apk = parseInt($("#april_kuantitas").val())
    //     var mek = parseInt($("#mei_kuantitas").val())
    //     var junk = parseInt($("#juni_kuantitas").val())
    //     var julk = parseInt($("#juli_kuantitas").val())
    //     var agk = parseInt($("#agustus_kuantitas").val())
    //     var sepk = parseInt($("#september_kuantitas").val())
    //     var oktk = parseInt($("#oktober_kuantitas").val())
    //     var novk = parseInt($("#november_kuantitas").val())
    //     var desk = parseInt($("#desember_kuantitas").val())

    //     // jumlah nominal
    //     var jmlKuantitas = nominal_januari + fk + mk + apk + mek + junk + julk + agk + sepk + oktk + novk + desk;
    //     $("#jml_kuantitas").attr("value", jmlKuantitas);
    //     document.form.jml_kuantitas.value = jmlKuantitas;

    //     // jumlah nominal
    //     var jml_nominal = nominal_januari + nominal_februari + nominal_maret + nominal_april + nominal_mei + nominal_juni + nominal_juli + nominal_agustus + nominal_september + nominal_oktober + nominal_november + nominal_desember;
    //     var jml_nominala = tandaPemisahTitik(jml_nominal);
    //     document.form.jml_nominal.value = jml_nominala;

    // });


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