<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['id_divisi'];

if (isset($_POST['simpan'])) {
    $id_bkk = $_POST['id_bkk'];
    $nm_vendor = $_POST['nm_vendor'];
    $kd_transaksi = $_POST['kd_transaksi'];
    // $tgl_pengajuan = $_POST['tgl_pengajuan'];
    $keterangan = $_POST['keterangan'];
    $id_anggaran = $_POST['id_anggaran'];
    $nilai_barang = str_replace(".", "", $_POST['nilai_barang']);
    $nilai_jasa = str_replace(".", "", $_POST['nilai_jasa']);
    $ppn_persen = $_POST['ppn_persen'];
    $ppn_nilai = str_replace(".", "", $_POST['ppn_nilai']);
    $pph_persen = $_POST['pph_persen'];
    $pph_nilai = str_replace(".", "", $_POST['pph_nilai']);
    $jml_bkk = str_replace(".", "", $_POST['jml_bkk']);
    $bank_tujuan = $_POST['bank_tujuan'];
    $norek_tujuan = $_POST['norek_tujuan'];
    $penerima_tujuan = $_POST['penerima_tujuan'];

    // buat ngapus invoice yg lama
    $cek_invoice = ($_FILES['invoice']['name']);
    if ($cek_invoice == '') {
        $namabaru = $_POST['invoice_lama'];
    } else {
        $del_invoice = $_POST['invoice_lama'];
        if (isset($del_invoice)) {
            unlink("../file/" . $del_invoice);
        }

        $lokasi_invoice = ($_FILES['invoice']['tmp_name']);
        $invoice = ($_FILES['invoice']['name']);
        $ekstensi = pathinfo($invoice, PATHINFO_EXTENSION);
        $namabaru =  $kd_transaksi . "-inv-biaya-non-ops-new." . $ekstensi;
        move_uploaded_file($lokasi_invoice, "../file/" . $namabaru);
    }

    $queryUpd = mysqli_query($koneksi, "UPDATE bkk SET nm_vendor = '$nm_vendor',
                                                        -- tgl_pengajuan = '$tgl_pengajuan',
                                                        keterangan = '$keterangan',
                                                        id_anggaran = '$id_anggaran',
                                                        nilai_barang = '$nilai_barang',
                                                        nilai_jasa = '$nilai_jasa',
                                                        ppn_persen = '$ppn_persen',
                                                        ppn_nilai = '$ppn_nilai',
                                                        pph_persen = '$pph_persen',
                                                        pph_nilai = '$pph_nilai',
                                                        jml_bkk = '$jml_bkk',
                                                        bank_tujuan = '$bank_tujuan',
                                                        norek_tujuan = '$norek_tujuan',
                                                        penerima_tujuan = '$penerima_tujuan',
                                                        invoice = '$namabaru'
                                        WHERE id_bkk = '$id_bkk'");

    if ($queryUpd) {
        header("Location: index.php?p=" . $_POST['url'] . "");
    }
}

if (isset($_GET['id'])) {
    $id_bkk = dekripRambo($_GET['id']);

    $queryBKK = mysqli_query($koneksi, "SELECT * FROM bkk WHERE id_bkk = '$id_bkk'");
    $dataBKK = mysqli_fetch_assoc($queryBKK);
}

$tanggalCargo = date("Y-m-d");
?>

<section class="content">
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
                    <h3 class="text-center">Rubah Biaya Umum <?= $dataBKK['keterangan']; ?></h3>
                </div>

                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" value="<?= $dataBKK['id_bkk']; ?>" name="id_bkk">
                    <input type="hidden" value="<?= $dataBKK['invoice']; ?>" name="invoice_lama">
                    <input type="hidden" value="<?= $dataBKK['kd_transaksi']; ?>" name="kd_transaksi">
                    <input type="hidden" value="<?= dekripRambo($_GET['pg']); ?>" name="url">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_vendor" class="col-sm-offset-1 col-sm-3 control-label">Dibayarkan Kepada</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control is-valid" name="nm_vendor" placeholder="Input Nama Vendor" value="<?= $dataBKK['nm_vendor']; ?>">
                            </div>

                        </div>
                        <!-- <div class="form-group">
                            <label for="tgl_bkk" class="col-sm-offset-1 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control tanggal" name="tgl_pengajuan" value="<?= $dataBKK['tgl_pengajuan']; ?>">
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label id="tes" for="keterangan" class="col-sm-offset-1 col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="keterangan" value="<?= $dataBKK['keterangan']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" name="id_anggaran">
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
                                                                                WHERE agg.id_divisi = '$Divisi'
                                                                                AND jenis_anggaran = 'BIAYA'
                                                                                AND tahun = '$tahun'
                                                                                ORDER BY nm_item ASC
                                                                            ");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox" <?php if ($rowAnggaran['id_anggaran'] == $dataBKK['id_anggaran']) {
                                                                                                                    echo "selected=selected";
                                                                                                                } ?>><?= $rowAnggaran['nm_item'] . ' - [' . $rowAnggaran['program_kerja']; ?>]</option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="perhitungan">
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nilai_barang" id="nilai_barang" value="<?= $dataBKK['nilai_barang']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" name="nilai_jasa" id="nilai_jasa" value="<?= $dataBKK['nilai_jasa']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">PPN</span>
                                        <input type="text" required min="0" max="10" class="form-control " name="ppn_persen" id="ppn_persen" value="<?= $dataBKK['ppn_persen']; ?>">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control " name="ppn_nilai" id="ppn_nilai" value="<?= $dataBKK['ppn_nilai']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">PPh</span>
                                        <input type="text" required class="form-control " name="pph_persen" id="pph_persen" value="<?= $dataBKK['pph_persen']; ?>">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control " name="pph_nilai" id="pph_nilai" value="<?= $dataBKK['pph_nilai']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Jumlah</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" name="jml_bkk" readonly value="<?= $dataBKK['jml_bkk']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="jenis" class="col-sm-offset-1 col-sm-3 control-label">Jenis</label>
                                <div class="col-sm-4">
                                    <select class="form-control jenis" name="jenis" id="jenis" required readonly>
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
                                        <option value="tunai" <?php if ($dataBKK['metode_pembayaran'] == "tunai") {
                                                                    echo "selected=selected";
                                                                } ?>> Tunai </option>
                                        <option value="transfer" <?php if ($dataBKK['metode_pembayaran'] == "transfer") {
                                                                        echo "selected=selected";
                                                                    } ?>> Transfer </option>
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
                                        <input type="text" class="form-control">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                    <i>Kosongkan jika tidak dirubah</i>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-5" value="Simpan">
                                &nbsp;
                                <input type="reset" class="btn btn-danger" value="Batal">
                            </div>
                            <!-- Embed Document               -->
                            <div class="box-header with-border">
                                <h3 class="text-center">Invoice </h3>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/<?php echo $dataBKK['invoice']; ?> "></iframe>
                                </div>
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

    var jenis = '<?= $dataBKK['jenis']; ?>';

    $("#jenis").val(jenis);


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
        } else {
            $("#ktk").hide();
        }
    });

    if (jenis == 'kontrak') {
        $("#ktk").show();

        var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        var date = new Date();

        var today = formatTanggal(date.getFullYear(), getBulan(date.getMonth()), getHari(date.getDate()));
        var today_ui = myDays[date.getDay()] + ', ' + formatTanggalIndo(date.getFullYear(), getBulan(date.getMonth()), getHari(date.getDate()));

        date.setDate(date.getDate() + 30);

        var dateTempo = formatTanggal(date.getFullYear(), getBulan(date.getMonth()), getHari(date.getDate()));
        var dateTempoUi = myDays[date.getDay()] + ', ' + formatTanggalIndo(date.getFullYear(), getBulan(date.getMonth()), getHari(date.getDate()));

        // untuk menentukan pembayaran kasir
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
                date.setDate(date.getDate() + i);
                if (date.getDay() == '4') {
                    break;
                }
            }
        }
        var datePayment = formatTanggal(date.getFullYear(), getBulan(date.getMonth()), getHari(date.getDate()));
        var datePaymentUi = myDays[date.getDay()] + ', ' + formatTanggalIndo(date.getFullYear(), getBulan(date.getMonth()), getHari(date.getDate()));

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
        var pph_persen = parseInt($("#pph_persen").val())
        var pph_nilai = nilaiJasa * pph_persen / 100;
        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;

        var nilaiBarang = parseInt($("#nilai_barang").val())
        var ppn_persen = parseInt($("#ppn_persen").val())
        var ppn_nilai = Math.round((nilaiJasa + nilaiBarang) * ppn_persen / 100);
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);
        document.form.jml_bkk.value = jml;

        // var nilaia = tandaPemisahTitik(nilai);
        // $("#nilai").attr("value",nilaia) 
        // var ppn = parseInt($("#ppn").val())
        // var bll = parseInt($("#bll_bkk").val())
        // var ppna =nilai*ppn/100; 
        // var jml = nilai + ppna + bll ;
        // var jmlb = Math.floor( jml);
        // var jmla = tandaPemisahTitik(jmlb);
        // $("#jml").attr("value",jmla);    
        // document.form.jml_bkk.value = jmla;        
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