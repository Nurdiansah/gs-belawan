<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
} else {
    $id = dekripRambo($_GET['id']);
}

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    JOIN divisi c
                                        ON b.id_divisi = c.id_divisi
                                    WHERE id_bkm = '$id'");

$dataBKM = mysqli_fetch_assoc($queryBKM);

if (isset($_POST['submit'])) {
    $id_bkm = $_POST['id_bkm'];

    // buat ngambil data anggaran
    // $cekBKM = mysqli_query($koneksi, "SELECT * FROM bkm WHERE id_bkm = '$id_bkm'");
    // $cekData = mysqli_fetch_assoc($cekBKM);
    // $nominal = $cekData['nominal'];
    // $tgl_bkm = $cekData['tgl_bkm'];
    // $id_anggaran = $cekData['id_anggaran'];

    mysqli_begin_transaction($koneksi);

    $submit = mysqli_query($koneksi, "UPDATE bkm SET status_bkm = '2', app_pajak = NOW()
                                            WHERE id_bkm = '$id_bkm'
                        ");


    if ($submit) {
        mysqli_commit($koneksi);
    } else {
        mysqli_rollback($koneksi);
        echo mysqli_error($koneksi);
    }
    header("Location: index.php?p=verifikasi_bkm");
}

if (isset($_POST['tolak'])) {
    $id_bkm = $_POST['id_bkm'];
    $komentar = "@" . $Nama . " : " . $_POST['komentar'];

    $reject = mysqli_query($koneksi, "UPDATE bkm SET status_bkm = '101', komentar_pajak = '$komentar'
                                        WHERE id_bkm = '$id_bkm'
                ");

    if ($reject) {
        header("Location: index.php?p=verifikasi_bkm");
    }
}

if (isset($_POST['verifikasi'])) {
    $id_bkm = $_POST['id_bkm'];
    $nominal = $_POST['nominal'];
    $ppn_nilai = penghilangTitik($_POST['ppn_nilai']);
    $id_pph = $_POST['id_pph'];
    $pph_nilai = $_POST['pph_nilai'] > 0 ? penghilangTitik($_POST['pph_nilai']) : $_POST['pph_nilai2'];
    $potongan = $_POST['potongan'];
    $biaya_lain = $_POST['biaya_lain'];
    $total = penghilangTitik($_POST['total']);

    $updateBKM = mysqli_query($koneksi, "UPDATE bkm SET nominal = '$nominal',
                                            nilai_ppn = '$ppn_nilai',
                                            id_pph = '$id_pph',
                                            nilai_pph = '$pph_nilai',
                                            potongan = '$potongan',
                                            biaya_lain = '$biaya_lain',
                                            grand_total = '$total'
                                        WHERE id_bkm = '$id_bkm'
                            ");

    if ($updateBKM) {
        header("Location: index.php?p=verifikasi_dbkm&id=" . enkripRambo($id_bkm));
    }
}

if ($dataBKM['id_pph'] == 0 || $dataBKM['id_pph'] == 1) {
    $pph_persen = 0;
} else {
    $pph_persen = round($dataBKM['nilai_pph'] / $dataBKM['nominal'] * 100, 2);
}

?>


<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="col-sm-offset-11">
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buat"><i class="fa fa-edit"></i> Buat </button></span></a> -->
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Bukti Kas Masuk</h3>
                </div>
                <div class="box-body">
                    <form class="form-horizontal" enctype="multipart/form-data" action="" method="POST">
                        <div class="perhitungan">
                            <div class="box-body">
                                <div class="col-sm-5">
                                    <input type="hidden" name="id_bkm" value="<?= $id; ?>" id="me_id_bkm">
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Tanggal</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?= $dataBKM['tgl_bkm']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?= $dataBKM['kd_anggaran']  . " [" . $dataBKM['nm_item']; ?>]" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Nominal</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " value="<?= formatRupiah2($dataBKM['nominal']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Divisi</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " value="<?= $dataBKM['nm_divisi']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="validationTextarea" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                            <div class="col-sm-8">
                                                <textarea rows="5" class="form-control is-invalid" placeholder="Deskripsi" readonly><?= $dataBKM['keterangan']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <!-- <h3 class="text-center">Document BKM</h3> -->
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="../file/bkm/<?= $dataBKM['doc_bkm']; ?>"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-4 control-label">Nominal</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" value="<?= $dataBKM['nominal']; ?>" id="dpp" min="1">
                                    </div>
                                    <i><span id="dpp_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah">PPN
                                    <select name="ppn_persen" id="ppn_persen">
                                        <option value="0.12">12%</option>
                                        <option value="0.11">11%</option>
                                        <option value="0.1">10%</option>
                                    </select>
                                </label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                </div> -->
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-4 control-label">PPN</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control " name="ppn_nilai" id="ppn_nilai" value="<?= $dataBKM['nilai_ppn']; ?>" autocomplete="off">
                                    </div>
                                    <i><span id="ppn_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-4 control-label">Jenis PPh</label>
                                <div class="col-sm-3">
                                    <select name="id_pph" class="form-control" id="id_pph">
                                        <option value="0">--Jenis PPh--</option>
                                        <?php
                                        $queryPph = mysqli_query($koneksi, "SELECT * FROM pph ORDER BY nm_pph ASC");
                                        while ($rowPph = mysqli_fetch_assoc($queryPph)) { ?>
                                            <option value="<?= $rowPph['id_pph']; ?>" data-id="<?= $rowPph['jenis']; ?>" <?= $rowPph['id_pph'] == $dataBKM['id_pph'] ? "selected" : ""; ?>><?= $rowPph['nm_pph'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="fixed">
                                <div class="form-group">
                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah"></label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">PPh</span>
                                            <input type="text" required class="form-control " name="pph_persen" value="<?= $pph_persen; ?>" id="pph_persen" autocomplete="off">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah"></label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" readonly class="form-control " name="pph_nilai" value="<?= $dataBKM['id_pph'] != 1 ? formatRupiah2($dataBKM['nilai_pph']) : 0; ?>" id="pph_nilai" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="progresive">
                                <div class="form-group">
                                    <label id="tes" for="pph_nilai2" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah"></label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control " name="pph_nilai2" value="<?= $dataBKM['id_pph'] == 1 ? formatRupiah2($dataBKM['nilai_pph']) : 0; ?>" id="pph_nilai2" autocomplete="off">
                                        </div>
                                        <i><span id="pph_ui"></span></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="potongan" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah">Potongan</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" name="potongan" value="<?= $dataBKM['potongan']; ?>" id="potongan">
                                    </div>
                                    <i><span id="np_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="biaya_lain" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah">Biaya Lain</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" name="biaya_lain" value="<?= $dataBKM['biaya_lain']; ?>" id="biaya_lain" autocomplete="off">
                                    </div>
                                    <i><span id="bl_ui"></span></i>
                                    <i><span class="text-danger">*Biaya Materai/lain</span></i>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="total" class="col-sm-offset-1 col-sm-4 control-label">Total</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" id="total" name="total" min='1' readonly value="<?= formatRupiah2($dataBKM['grand_total']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <button type="button" class="btn btn-warning col-sm-offset-5" data-toggle="modal" data-target="#submit"><i class="fa fa-rocket"></i> Submit</button>
                                <button type="submit" class="btn btn-success" name="verifikasi"><i class="fa fa-save"></i> Simpan</button></span></a>
                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#reject"><i class="fa fa-close"></i> Reject</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal approve -->
    <div id="submit" class="modal fade" role="dialog">
        <div class="modal-dialog ">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Submit Bukti Kas Masuk</h4>
                </div>
                <!-- body modal -->
                <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                    <div class="modal-body">
                        <div class="perhitungan">
                            <div class="box-body">
                                <div class="form-group">
                                    <input type="hidden" value="<?= $id; ?>" class="form-control" name="id_bkm">
                                    <h4 class="text-center">Apakah anda yakin ingin memsubmit pengajuan <b><?= $dataBKM['keterangan']; ?>?</b></h4>
                                    <p class="text-center">Mohon dipastikan kembali apakah pengajuan sudah diverifikasi</p>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">submit</button></span>
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal approve -->

    <!-- Modal reject -->
    <div id="reject" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alasan Penolakan</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $dataBKM['id_bkm']; ?>" class="form-control" name="id_bkm">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="validationTextarea">Komentar</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required></textarea>
                                <div class="invalid-feedback">
                                    Please enter a message in the textarea.
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal reject -->
</section>

<script>
    let host = "<?= host() ?>";

    $(".perhitungan").keyup(function() {
        hitungYuk()
    });

    // $('#ppn_persen').on('change', function() {
    //     hitungYuk();
    // });

    function hitungYuk() {

        let dpp = parseInt($("#dpp").val())
        let dpp_ui = tandaPemisahTitik(dpp);
        $('#dpp_ui').text('Rp.' + dpp_ui);

        // var checkBox = document.getElementById("myCheck");
        // if (checkBox.checked == true) {
        // ppnNilai = dpp * ppnPersen();
        // $('#ppn_nilai').val(formatRibuan(Math.round(ppnNilai)));
        // } else {
        //     ppnNilai = 0
        // }

        let ppnNilai = parseInt($("#ppn_nilai").val())
        let ppn_ui = tandaPemisahTitik(Math.round(ppnNilai))
        $('#ppn_ui').text('Rp.' + ppn_ui)

        let pph_nilai2 = parseInt($("#pph_nilai2").val())
        let pph_ui = tandaPemisahTitik(Math.round(pph_nilai2))
        $('#pph_ui').text('Rp.' + pph_ui);

        var pph_persen = parseFloat($("#pph_persen").val())
        var pph_nilai = Math.floor(dpp * pph_persen / 100)
        $('#pph_nilai').val(formatRibuan(Math.round(pph_nilai)))

        let potongan = parseInt($("#potongan").val())
        let np_ui = tandaPemisahTitik(Math.round(potongan))
        $('#np_ui').text('Rp.' + np_ui);

        let biaya_lain = parseInt($("#biaya_lain").val())
        let bl_ui = tandaPemisahTitik(biaya_lain)
        $('#bl_ui').text('Rp.' + bl_ui);


        let total = (dpp + ppnNilai + biaya_lain) - (potongan + pph_nilai + pph_nilai2) //+ biaya_lain) - (potongan + pph_nilai + pph_nilai2);
        $('#total').val(formatRibuan(Math.round(total)));
    }

    let id_pph = $("#id_pph").val()
    if (id_pph == '1') {
        $("#fixed").hide();
        $("#progresive").show();
    } else if (id_pph == '2' || id_pph == '3') {
        $("#fixed").show();
        $("#progresive").hide();
    } else {
        $("#fixed").hide();
        $("#progresive").hide();
    }

    $('#id_pph').on('change', function() {
        let id_pph = this.value;
        let jenis = $(this).find(':selected').data('id')

        boxPph(jenis)
    });


    function boxPph(data) {

        if (data == 'progresive') {
            $('#pph_persen').val(0);
            $('#pph_nilai').val(0);

            $("#fixed").hide();
            $("#progresive").show();

            hitungYuk()

        } else if (data == 'fixed') {
            $('#pph_nilai2').val(0);

            $("#fixed").show();
            $("#progresive").hide();
            hitungYuk()

        } else {
            $('#pph_persen').val(0);
            $('#pph_nilai').val(0);
            $('#pph_nilai2').val(0);
            $("#fixed").hide();
            $("#progresive").hide();
            hitungYuk()
        }
    }


    // function checkBox() {
    //     var checkBox = document.getElementById("myCheck");
    //     if (checkBox.checked == true) {

    //         hitungYuk();


    //     } else if (checkBox.checked == false) {

    //         $('#ppn_nilai').val(0);

    //         hitungYuk();
    //     }
    // }

    function formatRibuan(angka) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');

        return ribuan;
    }

    function hilangkanTitik(data) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value))))); //input ke dalam angka tanpa titik

        return angka;
    }
</script>