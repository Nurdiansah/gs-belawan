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
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryDetail =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         JOIN divisi d
                                                         ON d.id_divisi = db.id_divisi
                                                         JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$id_supplier = $data['id_supplier'];
$id_anggaran = $data['id_anggaran'];
$totalPengajuan = $data['harga_akhir'];
$id_dbo = $data['id'];
$id_divisi = $data['id_divisi'];
$vrf_pajak = $data['vrf_pajak'];

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id_anggaran'");

?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=kasbon_transaksi&sp=kt_user" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <br>
                <div id="my-timeline"></div>
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Kasbon</h3>
                </div>
                <div class="perhitungan">
                    <form method="post" name="form" action="vrf_itemmr.php" enctype="multipart/form-data" class="form-horizontal">

                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="tanggal" class="col-sm-offset col-sm-2 control-label">Tanggal Pengajuan</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="tanggal" value="<?= formatTanggal($data['tgl_kasbon']); ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label for="satuan" class="col-sm-offset- col-sm-2 control-label">Divisi</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control " name="satuan" value="<?= $data['nm_divisi']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="nominal" for="nominal" class="col-sm-offset col-sm-2 control-label">Nominal</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="nominal" value="<?= formatRupiah($data['harga_akhir']); ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2" name="id_anggaran" disabled>
                                        <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                        <?php
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
                                <input type="hidden" required class="form-control is-valid" name="id_kasbon" value="<?= $data['id_kasbon']; ?>">
                                <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                                <input type="hidden" required class="form-control is-valid" name="from_user" value="<?= $data['from_user']; ?>">
                                <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <!-- <input type="text" readonly class="form-control is-valid" name="nm_barang"> -->
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->

                                <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data['keterangan']; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group ">
                                <?php if (!empty($data['doc_pendukung'])) { ?>
                                    <div class="box-header with-border">
                                        <h3 class="text-center">Document Pendukung </h3>
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_pendukung']; ?>" id="ml_doc"></iframe>
                                        </div>
                                    </div>
                                <?php }; ?>
                            </div>

                            <!-- Rincian Harga -->
                            <?php if ($vrf_pajak == 'bp') { ?>
                                <div class="col-sm-12">
                                    <h3 class="text-center">Rincian Harga</h3>
                                    <div class="table-responsive">
                                        <table class="table" border="2px">
                                            <tr style="background-color :#B0C4DE;">
                                                <td colspan="5"><b>Nilai Barang</b></td>
                                                <td><b><?= formatRupiah($data['nilai_barang']); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>Nilai Jasa</b></td>
                                                <td><b><?= formatRupiah($data['nilai_jasa']); ?></b></td>
                                            </tr>
                                            <tr style="background-color :#B0C4DE;">
                                                <td colspan="5"><b>PPN</b></td>
                                                <td><b><?= formatRupiah($data['nilai_ppn']); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>PPh</b></td>
                                                <td><b>(<?= formatRupiah($data['nilai_pph']); ?>)</b></td>
                                            </tr>
                                            <tr style="background-color :#B0C4DE;">
                                                <td colspan="5"><b>Grand Total</b></td>
                                                <td><b><?= formatRupiah($data['harga_akhir']); ?></b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            <br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>

    </div>
    <!-- </div> -->
</section>
<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        var status = "<?php print($data['status_kasbon']); ?>";
        var vrf_pajak = "<?php print($data['vrf_pajak']); ?>";
        var created_on = "<?php print(date("d M Y H:i", strtotime($data['tgl_kasbon']))); ?>";

        var jamDefault = "01 Jan 1970 01:00";
        var waiting = "<b>Manager </b><small>Waiting....</small>";

        // app mgr
        var app_spv = "<?php print(date("d M Y H:i", strtotime($data['app_supervisor']))); ?>";
        var content_spv = '<b>Supervisor</b><small>sudah memverifikasi</small>';
        if (app_spv == jamDefault) {
            app_spv = "";
            var content_spv = "<b>Supervisor </b><small>Waiting....</small>";
        }

        var app_cc = "<?php print(date("d M Y H:i", strtotime($data['app_costcontrol']))); ?>";
        var content_cc = '<b>Cost Control</b><small>sudah memverifikasi</small>';
        if (app_cc == jamDefault) {
            app_cc = "";
            var content_cc = "<b>Cost Control </b><small>Waiting....</small>";
        }

        var app_mgr_ga = "<?php print(date("d M Y H:i", strtotime($data['app_mgr_ga']))); ?>";
        var content_mgr_ga = '<b>Manager</b><small>sudah memverifikasi</small>';
        if (app_mgr_ga == jamDefault) {
            app_mgr_ga = "";
            var content_mgr_ga = "<b>Manager </b><small>Waiting....</small>";
        }


        // pajak
        var app_pajak = "<?php print(date("d M Y H:i", strtotime($data['app_pajak']))); ?>";
        var content_pajak = '<b>Pajak</b><small> sudah memverifikasi</small>'
        if (app_pajak == jamDefault) {
            app_pajak = " ";
            content_pajak = "<b>Pajak </b><small>Waiting....</small>";
        }

        // manager finance
        var app_manager_finance = "<?php print(date("d M Y H:i", strtotime($data['app_mgr_finance']))); ?>";
        var content_manager_finance = '<b>GM Finance</b><small>sudah memverifikasi</small>'
        if (app_manager_finance == jamDefault) {
            app_manager_finance = " ";
            content_manager_finance = "<b>GM Finance</b><small>Waiting....</small>";
        }

        // direktur
        var app_direktur = "<?php print(date("d M Y H:i", strtotime($data['app_direktur']))); ?>";
        var content_direktur = '<b>Direktur</b><small> sudah memverifikasi</small>'
        if (app_direktur == jamDefault) {
            app_direktur = " ";
            content_direktur = "<b>Direktur </b><small>Waiting....</small>";
        }


        // Kasir    
        if (status != '7') {
            app_v_lpj = " ";
            content_v_lpj = "<b>Kasir </b><small>Waiting....</small>";
        }

        if (status == '1') {
            var content_spv = "<b>Supervisor </b><font color= blue ><small>Verifikasi Supervisor </small></font> ";
        } else if (status == '2') {
            var content_pajak = "<b>Pajak </b><font color= blue ><small>Verifikasi Pajak </small></font> ";
        } else if (status == '3') {
            var content_cc = "<b>Cost Control</b><font color= blue ><small>Verifikasi Cost Control </small></font> ";
        } else if (status == '4') {
            var content_mgr_ga = "<b>Manager </b><font color= blue ><small>Verifikasi Manager </small></font> ";
        } else if (status == '5') {
            var content_manager_finance = "<b>GM Finance</b><font color= blue ><small>Verifikasi GM Finance </small></font> ";
        } else if (status = '6') {
            var content_direktur = "<b>Direksi</b><font color= blue ><small>Verifikasi Direksi </small></font> ";
        } else if (status = '7') {
            var content_v_lpj = "<b>Kasir</b><font color= blue ><small>Payment </small></font> ";
        }

        var events = [{
                date: created_on,
                content: '<b>User</b><small>membuat pengajuan</small>'
            },
            {
                date: app_spv,
                content: content_spv
            }, {
                date: app_cc,
                content: content_cc
            },
            {
                date: app_mgr_ga,
                content: content_mgr_ga
            }, {
                date: app_pajak,
                content: content_pajak
            },
            {
                date: app_manager_finance,
                content: content_manager_finance
            },
            {
                date: app_direktur,
                content: content_direktur
            }
        ];



        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 7,
            slide: 1,
            prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
            nextArrow: '<i class="material-icons">keyboard_arrow_right</i>'
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
</script>