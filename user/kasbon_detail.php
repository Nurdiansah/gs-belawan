<?php


include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsianggaran.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}
$tahun = date("Y");

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     FROM en_fin.user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$idDivisi = $rowUser['id_divisi'];

$queryDetail =  mysqli_query($koneksi, "SELECT *, a.id_divisi AS id_dvs_spj -- , CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item
                                        FROM kasbon k
                                        JOIN detail_biayaops db 
                                            ON k.id_dbo = db.id
                                        JOIN anggaran a
                                            ON db.id_anggaran = a.id_anggaran
                                        LEFT JOIN divisi dvs
                                            ON db.id_divisi = dvs.id_divisi
                                        JOIN program_kerja
                                            ON id_programkerja = a.programkerja_id
                                        WHERE k.id_kasbon = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);

$id_supplier = $data['id_supplier'];
$id_anggaran = $data['id_anggaran'];
$totalPengajuan = $data['harga_akhir'];
$id_dbo = $data['id'];
$id_divisi = $data['id_divisi'];
$id_dvs_spj = $data['id_dvs_spj'];
$vrf_pajak = $data['vrf_pajak'];
$idPk = $data['programkerja_id'];

$totalAnggaran = totalProgramKerja($data['programkerja_id']); // $data['jumlah_nominal'];
$totalRealisasi = $data['jumlah_realisasi'];

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id_anggaran'");

$dataCC = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM program_kerja
                                                        JOIN cost_center
                                                            ON costcenter_id = id_costcenter
                                                        WHERE id_programkerja = '$idPk'"));

?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <!-- <a href="index.php?p=buat_kasbon" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> -->
                    </div>
                    <br><br>
                </div>
                <form class="form-horizontal" method="POST" action="edit_kasbon.php" enctype="multipart/form-data">
                    <input type="hidden" name="doc_pendukung_lama" value="<?= $data['doc_pendukung']; ?>">
                    <input type="hidden" name="id_dbo" value="<?= $data['id_dbo']; ?>">
                    <div class="row">
                        <br><br>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="nominal" class="col-sm-offset- col-sm-3 control-label">Kode Kasbon </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="id_kasbon" value="<?= $id; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nominal" class="col-sm-offset- col-sm-3 control-label">Tanggal Dibuat</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="tgl_kasbon" value="<?= formatTanggal($data['tgl_kasbon']); ?>" readonly>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="nominal" class="col-sm-offset- col-sm-3 control-label">Divisi </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="divisi" value="<?= $data['nm_divisi']; ?>" readonly>
                                </div>
                            </div> -->
                            <!-- JIKA DIA ANGGARN SPJ -->
                            <?php if ($data['spj'] == "1") { ?>

                                <div class="form-group">
                                    <label id="tes" for="pengajuan" class="col-sm-offset- col-sm-3 control-label"></label>
                                    <div class="col-sm-8">
                                        <input type="checkbox" name="spj" id="mySPJ" checked onclick="checkBox()" disabled><label for="mySPJ">&nbsp;&nbsp;Pengajuan SPJ</label>
                                    </div>
                                </div>

                                <div class="kotakSPJ_edit">
                                    <div class="form-group">
                                        <input type="hidden" name="id_divisi" value="<?= $idDivisi ?>">
                                        <label id="tes" for="divisi" class="col-sm-offset- col-sm-3 control-label">Divisi</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 divisi_id_edit_spj" name="id_divisi_spj">
                                                <option value="">--Divisi--</option>
                                                <?php
                                                $queryDivsi = mysqli_query($koneksi, "SELECT *
                                                        FROM divisi
                                                        WHERE id_divisi <> '0'
                                                        ORDER BY nm_divisi ASC
                                                    ");
                                                if (mysqli_num_rows($queryDivsi)) {
                                                    while ($rowPK = mysqli_fetch_assoc($queryDivsi)) :
                                                ?>
                                                        <option value="<?= $rowPK['id_divisi']; ?>" <?= $data['id_dvs_spj'] == $rowPK['id_divisi'] ? "selected" : ""; ?>><?= $rowPK['nm_divisi']; ?></option>
                                                <?php endwhile;
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="kotakPkSPJ_edit">
                                        <div class="form-group">
                                            <label id="tes" for="id_programkerja" class="col-sm-offset- col-sm-3 control-label">Program Kerja</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2 programkerja_id_edit" name="id_programkerja" id="id_programkerja_edit" required>
                                                    <!-- <option value="">--Program Kerja--</option> -->
                                                    <?php

                                                    $queryProgramKerja = mysqli_query($koneksi, "SELECT DISTINCT id_programkerja, kd_programkerja, nm_programkerja
                                                                    FROM program_kerja pk
                                                                    JOIN cost_center
                                                                        ON id_costcenter = costcenter_id
                                                                    JOIN anggaran agg
                                                                        ON id_programkerja = programkerja_id
                                                                    WHERE divisi_id = '$id_dvs_spj'
                                                                    AND spj = '1'
                                                                    AND agg.tahun = '$tahun'
                                                                    ORDER BY nm_programkerja ASC
                                                        ");
                                                    if (mysqli_num_rows($queryProgramKerja)) {
                                                        while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                                    ?>
                                                            <option value="<?= $rowPK['id_programkerja']; ?>" <?= $rowPK['id_programkerja'] == $idPk ? 'selected' : ''; ?>><?= $rowPK['kd_programkerja'] . " [" . $rowPK['nm_programkerja']; ?>]</option>
                                                    <?php endwhile;
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END ANGGARAN SPJ -->
                            <?php } else { ?>
                                <div class="kotakPK_edit">
                                    <div class="form-group"><label id="tes" for="id_programkerja" class="col-sm-offset- col-sm-3 control-label">Program Kerja</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2 programkerja_id_edit" name="id_programkerja" id="id_programkerja_edit" required>
                                                <!-- <option value="">--Program Kerja--</option> -->
                                                <?php

                                                $queryProgramKerja = mysqli_query($koneksi, "SELECT * FROM program_kerja
                                                                                                JOIN cost_center
                                                                                                    ON id_costcenter = costcenter_id
                                                                                                WHERE divisi_id = '$idDivisi'
                                                                                                AND tahun = '$tahun'
                                                                                                ORDER BY nm_programkerja ASC
                                                        ");
                                                if (mysqli_num_rows($queryProgramKerja)) {
                                                    while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                                ?>
                                                        <option value="<?= $rowPK['id_programkerja']; ?>" <?= $rowPK['id_programkerja'] == $idPk ? 'selected' : ''; ?>><?= $rowPK['kd_programkerja'] . " [" . $rowPK['nm_programkerja']; ?>]</option>
                                                <?php endwhile;
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="kotakAnggaran_edit">
                                <div class="form-group">
                                    <label id="tes" for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                                    <div class="col-sm-8">
                                        <select class="form-control select2 id_anggaran_edit" name="id_anggaran" id="id_anggaran_edit" required>
                                            <?php
                                            if ($data['spj'] == "1") {
                                                $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran 
                                                                                        WHERE programkerja_id = '$idPk'
                                                                                        AND tahun = '$tahun'
                                                                                        AND spj = '1'
                                                                                        ORDER BY nm_item ASC
                                                    ");
                                            } else {
                                                $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran 
                                                                                    WHERE programkerja_id = '$idPk'
                                                                                    AND tahun = '$tahun'
                                                                                    ORDER BY nm_item ASC
                                                ");
                                            }
                                            if (mysqli_num_rows($queryAnggaran)) {
                                                while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                            ?>
                                                    <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox" <?php if ($rowAnggaran['id_anggaran'] == $data['id_anggaran']) {
                                                                                                                            echo "selected=selected";
                                                                                                                        } ?>><?= $rowAnggaran['kd_anggaran'] . ' [' . $rowAnggaran['nm_item']; ?>]</option>
                                            <?php endwhile;
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nominal" class="col-sm-offset- col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" value="<?= formatRupiah2(round($data['harga_akhir'], 2)); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="doc" class="col-sm-offset- col-sm-3 control-label">Document Pendukung </label>
                                <div class="col-sm-8">
                                    <div class="input-group input-file" name="doc_pendukung">
                                        <input type="text" class="form-control" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                    <i class="text-danger">Kosongkan jika tidak dirubah</i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nominal" class="col-sm-offset- col-sm-3 control-label">Keterangan </label>
                                <div class="col-sm-8">
                                    <textarea rows="7" type="textarea" name="keterangan" class="form-control"><?= $data['keterangan']; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="col-sm-offset-5 btn btn-primary" name="edit" value="Simpan">
                                <input type="reset" class="btn btn-danger" name="batal" value="Batal">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <!-- <div class="form-group "> -->
                            <div class="box-header with-border">
                                <h3 class="text-center">Document Pendukung </h3>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_pendukung']; ?>" id="ml_doc"></iframe>
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="box-header with-border">
                    <!-- <div class="form-group">   -->
                    <h4 class="text-left"><b>Total Program Kerja <?= '<font color="blue">' . $dataCC['kd_programkerja'] . " [" . $dataCC['nm_programkerja'] . "]" . '</font>' . ' Setahun : ' . formatRupiah($totalAnggaran); ?> &nbsp;</b></b></h4>
                    <?php
                    // pengajuan di bandingkan dengan total Anggaran divisi  
                    if ($totalAnggaran == 0) {
                        $totalAnggaran = 0.1;
                    }

                    $selisihAnggaran = round(@($totalPengajuan / $totalAnggaran * 100), 1);
                    $selisihRealisasi = round(@($totalRealisasi / $totalAnggaran * 100), 1);
                    $persentaseProgress = $selisihRealisasi + $selisihAnggaran;

                    $sisaBudget = $totalAnggaran - ($totalRealisasi + $totalPengajuan);
                    $persentaseSisaBudget = round(@($sisaBudget / $totalAnggaran * 100), 1);


                    if ($sisaBudget < 0) {
                        $warnaRealisasi = 'warning';

                        echo "
                                <div class='col-sm-offset-1 col-sm-9'> 
                                <div class='alert alert-danger' role='alert'><h3> PERINGATAN  <b> <i class ='fa fa-exclamation'></i><br> </h3> Pengajuan tidak bisa menggunakan kode anggaran ini, di karenakan realisasi pada program kerja anggaran tersebut sudah terlimit, silahkan ajukan pemindahan budget ke program kerja ini. </b></div>
                                </div>
                                ";
                    } else {
                        $warnaRealisasi = 'success';
                    }

                    // print_r($selisihAnggaran);

                    // die;
                    ?>
                    <div class="col-sm-offset-1 col-sm-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-<?= $warnaRealisasi ?>" style="width: <?= $selisihRealisasi; ?>%">
                                <!-- <span><?= $selisihRealisasi; ?> %</span> -->
                            </div>
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: <?= $selisihAnggaran; ?>%">
                                <!-- <span ><b><?= "  (" . $selisihAnggaran . "%)"; ?></b></span> -->
                            </div>
                            <label for=""> &nbsp;<b>(<?= $persentaseProgress ?> %)</label>
                        </div>
                    </div>
                    <!-- </div>                                                 -->
                    <div class="col-sm-offset-1 col-sm-3 ">
                        <button type="button" class="btn btn-<?= $warnaRealisasi ?>"></button> <b> (<?= $selisihRealisasi ?> %)</b>
                        <h5><b>Realisasi : <?= 'Rp. ' . number_format($totalRealisasi, 0, ",", ".") ?> </b></h5>
                    </div>
                    <div class="col-sm-offset-1 col-sm-3">
                        <button type="button" class="btn btn-primary"></button> <b> (<?= $selisihAnggaran ?> %)</b>
                        <h5><b> Pengajuan : <?= 'Rp. ' . number_format($totalPengajuan, 0, ",", ".") ?> </b></h5>
                    </div>
                    <div class="col-sm-offset-1 col-sm-3">
                        <button type="button" class="btn btn-dark" style="background-color :#708090;"></button> <b> (<?= $persentaseSisaBudget ?> %)</b>
                        <h5><b> Sisa Budget : <?= 'Rp. ' . number_format($sisaBudget, 0, ",", ".") ?> </b></h5>
                    </div>
                </div>

            </div>
            </form>
        </div>
    </div>
    <br>

    </div>
    <!-- </div> -->
</section>

<!--  LPJ -->
<div id="konfirmasi_lama" class="modal fade" role="dialog">
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
                <form method="post" enctype="multipart/form-data" action="add_lpj_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $totalPengajuan; ?>" class="form-control" name="harga" readonly>
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon" readonly>
                                <input type="hidden" value="<?= $data['vrf_pajak']; ?>" class="form-control" name="vrf_pajak" readonly>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal_pengembalian" class="col-sm-offset-1 col-sm-3 control-label">Nominal Pengembalian</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nominal_pengembalian" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="penambahan" class="col-sm-offset-1 col-sm-3 control-label">Nominal Penambahan</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="penambahan" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
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
                <form method="post" enctype="multipart/form-data" action="add_lpj_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $totalPengajuan; ?>" class="form-control" name="harga" readonly>
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon" readonly>
                                <input type="hidden" value="<?= $data['vrf_pajak']; ?>" class="form-control" name="vrf_pajak" readonly>
                                <input type="hidden" value="<?= round($data['nilai_barang']); ?>" class="form-control" name="nilai_barang" readonly>
                                <input type="hidden" value="<?= round($data['nilai_jasa']); ?>" class="form-control" name="nilai_jasa" readonly>
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
                                    <input type="text" class="form-control" name="nominal_pengembalian" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
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

<!-- End LPJ -->
<script>
    var host = '<?= host(); ?>'

    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file'  accept='application/pdf' class='input-ghost' style='visibility:hidden; height:0'>");
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