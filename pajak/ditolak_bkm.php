<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    JOIN divisi c
                                        ON b.id_divisi = c.id_divisi
                                    WHERE status_bkm IN ('202')
                                    ORDER BY id_bkm DESC");

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
        header("Location: index.php?p=verifikasi_bkm");
    }
}

$no = 1;

$totalBKM = mysqli_num_rows($queryBKM);

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="col-sm-offset-11">
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buat"><i class="fa fa-edit"></i> Buat </button></span></a> -->
                </div>
                <h3 class="text-center">Ditolak Bukti Kas Masuk</h3>
                <div class="box-body">
                    <form action="" method="POST" enctype="multipart/form-data" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?= $totalBKM > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Nominal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($dataBKM = mysqli_fetch_assoc($queryBKM)) { ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= formatTanggal($dataBKM['tgl_bkm']); ?></td>
                                            <td><?= batasiKata($dataBKM['keterangan']); ?></td>
                                            <td><?= kodeAnggaran($dataBKM['id_anggaran']); ?>]</td>
                                            <td><?= formatRupiah($dataBKM['grand_total']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#submit_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-rocket" title="Submit" data-ggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-success modalVerifikasi" data-toggle="modal" data-target="#modalVerifikasi" data-id="<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-edit" title="Verifikasi" data-toggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#reject_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-close" title="Reject" data-toggle="tooltip"></i></button>
                                            </td>
                                        </tr>

                                        <!-- Modal approve -->
                                        <div id="submit_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
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
                                                                        <input type="hidden" value="<?= $dataBKM['id_bkm']; ?>" class="form-control" name="id_bkm">
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
                                        <div id="reject_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
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
                                    <?php $no++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi -->
    <div id="modalVerifikasi" class="modal fade" role="dialog">
        <div class="modal-dialog " style="width: 90%;">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Verifikasi Bukti Kas Masuk</h4>
                </div>
                <!-- body modal -->
                <form class="form-horizontal" enctype="multipart/form-data" action="" method="POST">
                    <div class="modal-body">
                        <div class="perhitungan">
                            <div class="box-body">
                                <div class="col-sm-5">
                                    <input type="hidden" name="id_bkm" value="" id="me_id_bkm">
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Tanggal</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="" id="me_tgl_bkm" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="" id="me_nm_item" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Nominal</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " value="" id="me_nominal" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Divisi</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control " value="" id="me_nm_divisi" readonly>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="validationTextarea" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                            <div class="col-sm-8">
                                                <textarea rows="5" class="form-control is-invalid" placeholder="Deskripsi" id="me_keterangan" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="validationTextarea" class="col-sm-offset- col-sm-3 control-label">Keterangan Ditolak</label>
                                            <div class="col-sm-8">
                                                <textarea rows="5" class="form-control is-invalid" placeholder="Deskripsi" id="me_ditolak" readonly></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <!-- <h3 class="text-center">Document BKM</h3> -->
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="" id="me_doc"></iframe>
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
                                        <input type="text" class="form-control" name="nominal" value="0" id="dpp" min="1">
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
                                        <input type="text" class="form-control " name="ppn_nilai" id="ppn_nilai" value="0" autocomplete="off">
                                    </div>
                                    <i><span id="ppn_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-4 control-label">Jenis PPh</label>
                                <div class="col-sm-3">
                                    <select name="id_pph" class="form-control" id="id_pph" value="<?= $row2['id_pph'] ?>">
                                        <option value="0">--Jenis PPh--</option>
                                        <?php
                                        $queryPph = mysqli_query($koneksi, "SELECT * FROM pph ORDER BY nm_pph ASC");
                                        if (mysqli_num_rows($queryPph)) {
                                            while ($rowPph = mysqli_fetch_assoc($queryPph)) :
                                        ?>
                                                <option value="<?= $rowPph['id_pph']; ?>" data-id="<?= $rowPph['jenis']; ?>" type="checkbox"><?= $rowPph['nm_pph'] ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="fixed">
                                <div class="form-group">
                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah"></label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">PPh</span>
                                            <input type="text" required class="form-control " name="pph_persen" value="0" id="pph_persen" autocomplete="off">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah"></label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" readonly class="form-control " name="pph_nilai" value="0" id="pph_nilai" autocomplete="off">
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
                                            <input type="text" class="form-control " name="pph_nilai2" value="0" id="pph_nilai2" autocomplete="off">
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
                                        <input type="text" class="form-control" name="potongan" value="0" id="potongan">
                                    </div>
                                    <i><span id="np_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="biaya_lain" class="col-sm-offset-1 col-sm-4 control-label" id="rupiah">Biaya Lain</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" class="form-control" name="biaya_lain" value="0" id="biaya_lain" autocomplete="off">
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
                                        <input type="text" class="form-control" id="total" name="total" min='1' readonly value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-success" type="submit" name="verifikasi">Simpan</button></span></a>
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <!-- Akhir modal lihat -->

</section>

<script>
    let host = "<?= host() ?>";

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    $(function() {
        $('.modalVerifikasi').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/bkm/getvrfbkm.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_bkm').val(data.id_bkm);
                    $('#me_tgl_bkm').val(data.tgl_bkm);
                    $('#me_nm_item').val(data.nm_item);
                    $('#me_nominal').val(formatRibuan(Math.round(data.nominal)));
                    $('#me_nm_divisi').val(data.nm_divisi);
                    $('#me_keterangan').val(data.keterangan);
                    $('#me_ditolak').val(data.komentar_kasir);
                    $('#dpp').val(Math.round(data.nominal));
                    $('#ppn_nilai').val(Math.round(data.nilai_ppn));
                    $('#id_pph').val(data.id_pph);
                    $('#pph_nilai').val(formatRibuan(Math.round(data.nilai_pph)));
                    $('#potongan').val(Math.round(data.potongan));
                    $('#biaya_lain').val(Math.round(data.biaya_lain));
                    $('#total').val(formatRibuan(Math.round(data.grand_total)));

                    let doc_bkm = '../file/bkm/' + data.doc_bkm;
                    $("#me_doc").attr("src", doc_bkm);


                    // if (data.nilai_ppn != 0) {

                    //     var persentase = data.nilai_ppn / data.nominal * 100
                    //     var nilai_persentase = persentase / 100

                    //     if (nilai_persentase > 0) {
                    // $('#myCheck').prop('checked', true);
                    //         $("#ppn_persen").val(nilai_persentase);
                    //     } else {
                    //         $("#ppn_persen").val("0.11");
                    //     }
                    // }


                    if (data.id_pph == '1') {
                        // console.log(data.pph);
                        $("#fixed").hide();
                        $("#progresive").show();

                        $('#pph_nilai2').val(Math.round(data.nilai_pph));
                    } else if (data.id_pph == '2' || data.id_pph == '3') {
                        $("#fixed").show();
                        $("#progresive").hide();

                        $('#pph_nilai').val(formatRibuan(Math.round(data.nilai_pph)));

                        if (data.nilai_pph != 0) {
                            let pphPersen = Math.round((data.nilai_pph / data.nominal) * 100);
                            $('#pph_persen').val(pphPersen);
                        }
                    } else {
                        $("#fixed").hide();
                        $("#progresive").hide();
                    }
                }
            });
        });
    });

    // function ppnPersen() {
    //     return $('#ppn_persen').val();
    // }

    // function checkBox() {
    //     var checkBox = document.getElementById("myCheck");
    //     if (checkBox.checked == true) {
    //         let dpp = $("#dpp").val()
    //         let ppnPersen = $("#ppn_persen").val()

    //     } else if (checkBox.checked == false) {
    //         $('#ppn_nilai').val(0);
    //     }
    // }

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