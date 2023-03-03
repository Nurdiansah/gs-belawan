<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Divisi = $rowUser['id_divisi'];

$tanggalCargo = date("Y-m-d");
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
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=dashboard" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Create Biaya Umum Cabang </h3>
                </div>

                <form method="post" name="form" action="add_biayanonops.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_vendor" class="col-sm-offset-1 col-sm-3 control-label">Dibayarkan Kepada</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control is-valid" name="nm_vendor" placeholder="Input Nama Vendor">
                            </div>

                        </div>
                        <div class="form-group">
                            <label id="tes" for="keterangan" class="col-sm-offset-1 col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-4">
                                <textarea type="text" required class="form-control" name="keterangan"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset-1 col-sm-3 control-label">Divisi</label>
                            <div class="col-sm-4">
                                <select class="form-control select2 divisi_id" name="id_divisi" required>
                                    <option value="">--Divisi--</option>
                                    <?php

                                    $queryDivsi = mysqli_query($koneksi, "SELECT * FROM divisi
                                                                            WHERE id_divisi <> '0'
                                                                            ORDER BY nm_divisi ASC
                                                                                ");
                                    if (mysqli_num_rows($queryDivsi)) {
                                        while ($rowPK = mysqli_fetch_assoc($queryDivsi)) :
                                    ?>
                                            <option value="<?= $rowPK['id_divisi']; ?>" type="checkbox"><?= $rowPK['nm_divisi']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <div class="kotakProgramKerja">
                            <div class="form-group">
                                <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                <div class="col-sm-4">
                                    <select class="form-control select2 programkerja_id" name="id_programkerja" id="id_programkerja" required>
                                        <option>--Kode Anggaran--</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="kotakAnggaran">
                            <div class="form-group">
                                <label id="tes" for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                <div class="col-sm-4">
                                    <select class="form-control select2 id_anggaran" name="id_anggaran" id="id_anggaran" required>
                                        <option>--Kode Anggaran--</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="perhitungan">
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" value="0" name="nilai_barang" id="nilai_barang" />
                                    </div>
                                    <i><span id="nb_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" value="0" name="nilai_jasa" id="nilai_jasa" />
                                    </div>
                                    <i><span id="nj_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">PPN</span>
                                        <input type="text" required min="0" max="10" class="form-control " name="ppn_persen" value=0 id="ppn_persen" />
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control " name="ppn_nilai" id="ppn_nilai" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">PPh</span>
                                        <input type="text" required class="form-control " name="pph_persen" value=0 id="pph_persen" />
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control " name="pph_nilai" id="pph_nilai" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Jumlah</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="hidden" required class="form-control" name="jml_bu" />
                                            <input type="text" required class="form-control" name="jml_bkk" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="jenis" class="col-sm-offset-1 col-sm-3 control-label">Jenis</label>
                                <div class="col-sm-4">
                                    <select class="form-control jenis" name="jenis" id="jenis" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="umum"> Umum </option>
                                        <option value="kontrak"> Kontrak</option>
                                    </select>
                                </div>
                            </div>
                            <div id="ktk">
                                <div class="form-group">
                                    <label id="tes" for="tgl_pengajuan" class="col-sm-offset-1 col-sm-3 control-label">Tanggal Pengajuan</label>
                                    <div class="col-sm-4">
                                        <input type="hidden" required class="form-control" id='tgl_pengajuan' name="tgl_pengajuan" readonly>
                                        <input type="text" required class="form-control" id='tgl_pengajuan_ui' readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="tgl_tempo" class="col-sm-offset-1 col-sm-3 control-label">Tanggal Tempo</label>
                                    <div class="col-sm-4">
                                        <input type="hidden" required class="form-control" id="tgl_tempo" name="tgl_tempo" readonly>
                                        <input type="text" required class="form-control" id="tgl_tempo_ui" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="tgl_payment" class="col-sm-offset-1 col-sm-3 control-label">Tanggal Pembayaran Kasir</label>
                                    <div class="col-sm-4">
                                        <input type="hidden" required class="form-control" id='tgl_payment' name="tgl_payment" readonly>
                                        <input type="text" required class="form-control" id='tgl_payment_ui' readonly>
                                        <span> <i>* Pembayaran akan di lakukan di hari kamis</i> </span><br>
                                        <span style="color: red;"> <i>* Jika jatuh tempo di hari selasa, rabu dan kamis maka pembayaran akan di lakukan di hari kamis minggu depannya</i> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="pembayaran" class="col-sm-offset-1 col-sm-3 control-label">Metode Pembayaran</label>
                                <div class="col-sm-4">
                                    <select class="form-control pembayaran" name="pembayaran" id="pembayaran" required>
                                        <option value="">-- Metode Pembayaran --</option>
                                        <option value="tunai"> Tunai </option>
                                        <option value="transfer"> Transfer </option>
                                    </select>
                                </div>
                            </div>
                            <div id="tf">
                                <div class="form-group">
                                    <label id="tes" for="bank_tujuan" class="col-sm-offset-1 col-sm-3 control-label">Bank Tujuan</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="bank_tujuan">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="norek_tujuan" class="col-sm-offset-1 col-sm-3 control-label">No Rekening</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="norek_tujuan">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="penerima_tujuan" class="col-sm-offset-1 col-sm-3 control-label">Nama Penerima</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" name="penerima_tujuan">
                                    </div>
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
                                <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-5" value="Simpan">
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



    // script anggaran 
    $('.kotakProgramKerja').hide();
    $('.kotakAnggaran').hide();

    $('.divisi_id').on('change', function() {
        let divisiId = this.value;

        $('.kotakProgramKerja').show();
        $('.kotakAnggaran').hide();

        $.ajax({
            url: host + 'api/anggaran/getAnggaranDivisi.php',
            data: {
                id: divisiId
            },
            method: 'post',
            dataType: 'json',
            success: function(data) {


                $('#id_programkerja').empty();
                $('#id_programkerja').append($('<option>').text('--- Pilih Program Kerja ---').attr('value', ''));
                $.each(data, function(i, value) {
                    $('#id_programkerja').append($('<option>').text(value.nm_programkerja).attr('value', value.id_programkerja));
                });

            }
        });

    });

    $('.programkerja_id').on('change', function() {
        let programKerjaId = this.value;

        if (programKerjaId == '') {

            $('.kotakAnggaran').hide();

        } else {

            $('.kotakAnggaran').show();

            $.ajax({
                url: host + 'api/anggaran/getAnggaranPK.php',
                data: {
                    id: programKerjaId
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {


                    $('#id_anggaran').empty();
                    $.each(data, function(i, value) {
                        $('#id_anggaran').append($('<option>').text(value.nm_item).attr('value', value.id_anggaran));
                    });

                }
            });

        }

    });

    $('.id_anggaran').on('change', function() {
        let anggaranId = this.value;
    });




    function getHari(hari) {
        var day = hari;
        if (day < 10) {
            day = "0" + day;
        }
        return day;
    }

    function getBulan(bulan) {
        var month = bulan;
        if (month < 10) {
            month = "0" + month;
        }
        return month;
    }

    function formatTanggal(tahun, bulan, hari) {
        return tahun + '-' + bulan + '-' + hari;
    }

    function formatTanggalIndo(tahun, bulan, hari) {
        return hari + '/' + bulan + '/' + tahun;
    }


    $("#tf").hide();
    $("#ktk").hide();

    $('.jenis').on('change', function() {
        let jenis = this.value;

        if (jenis == 'kontrak') {
            $("#ktk").show();

            var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            var date = new Date();


            var today = formatTanggal(date.getFullYear(), getBulan(date.getMonth() + 1), getHari(date.getDate()));
            var today_ui = myDays[date.getDay()] + ', ' + formatTanggalIndo(date.getFullYear(), getBulan(date.getMonth() + 1), getHari(date.getDate()));

            date.setDate(date.getDate() + 30);

            var dateTempo = formatTanggal(date.getFullYear(), getBulan(date.getMonth() + 1), getHari(date.getDate()));
            var dateTempoUi = myDays[date.getDay()] + ', ' + formatTanggalIndo(date.getFullYear(), getBulan(date.getMonth() + 1), getHari(date.getDate()));

            // Untuk menentukan pembayaran kasir
            var hariTempo = date.getDay();

            // Jika hari tempo 2 selasa 3 rabu 4 kamis akan di buatkan tanggal pembayaran kasir di kamis minggu berikut nya           

            if (hariTempo == 2) {
                date.setDate(date.getDate() + 9);
            } else if (hariTempo == 3) {
                date.setDate(date.getDate() + 8);
            } else if (hariTempo == 4) {
                date.setDate(date.getDate() + 7);
            } else {

                for (let i = 1; i <= 7; i++) {
                    date.setDate(date.getDate() + 1);

                    if (date.getDay() == '4') {
                        break;
                    }
                }
            }
            var datePayment = formatTanggal(date.getFullYear(), getBulan(date.getMonth() + 1), getHari(date.getDate()));
            var datePaymentUi = myDays[date.getDay()] + ', ' + formatTanggalIndo(date.getFullYear(), getBulan(date.getMonth() + 1), getHari(date.getDate()));

            $("#tgl_pengajuan").val(today);
            $("#tgl_tempo").val(dateTempo);
            $("#tgl_payment").val(datePayment);

            //untuk ui
            $("#tgl_pengajuan_ui").val(today_ui);
            $("#tgl_tempo_ui").val(dateTempoUi);
            $("#tgl_payment_ui").val(datePaymentUi);

        } else {
            $("#ktk").hide();
        }
    });


    $('.pembayaran').on('change', function() {
        let pembayaran = this.value;

        if (pembayaran == 'transfer') {
            $("#tf").show();
        } else {
            $("#tf").hide();
        }
    });

    $(".perhitungan").keyup(function() {


        var nilaiJasa = parseInt($("#nilai_jasa").val())
        $("#nj_ui").text('Rp.' + tandaPemisahTitik(nilaiJasa));

        var pph_persen = parseFloat($("#pph_persen").val())
        var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);
        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;


        var nilaiBarang = parseInt($("#nilai_barang").val())
        $("#nb_ui").text('Rp.' + tandaPemisahTitik(nilaiBarang));


        var ppn_persen = parseInt($("#ppn_persen").val())
        var ppn_nilai = Math.floor((nilaiJasa + nilaiBarang) * ppn_persen / 100);
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);
        document.form.jml_bu.value = jml;

    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' accept='application/pdf' style='visibility:hidden; height:0'>");
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