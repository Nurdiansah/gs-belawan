<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBkk = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.id_bkk = '$id' ");

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <br><br>
                </div>

                <!-- <div id="my-timeline"></div> -->

                <!-- Detail Job Order -->
                <div class="box-header with-border">
                    <h3 class="text-center">Biaya Umum Non OPS</h3>
                </div>
                <?php
                if (mysqli_num_rows($queryBkk)) {
                    while ($row2 = mysqli_fetch_assoc($queryBkk)) :
                        // query Total_cargo
                        $nilai_barang = number_format($row2['nilai_barang'], 0, ",", ".");
                        $nilai_jasa = number_format($row2['nilai_jasa'], 0, ",", ".");
                        $ppn_nilai = number_format($row2['ppn_nilai'], 0, ",", ".");
                        $pph_nilai = number_format($row2['pph_nilai'], 0, ",", ".");
                        $jml_bkk = number_format($row2['jml_bkk'], 0, ",", ".");
                        $bll_bkk = number_format($row2['bll_bkk'], 0, ",", ".");

                ?>



                        <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">
                            <div class="box-body">

                                <div class="form-group ">
                                    <label for="id_joborder" class=" col-sm-2 control-label">Kode Transaksi</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['kd_transaksi']; ?>" disabled class="form-control" name="id_bkk">
                                    </div>
                                    <!-- </div>
                    <div class="form-group "> -->
                                    <label id="tes" for="tgl_bkk" class=" col-sm-2 control-label">Tanggal Pengajuan</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['tgl_pengajuan']; ?>" disabled class="form-control" name="tgl_bkk">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nm_vendor" class=" col-sm-2 control-label">Nama Vendor</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['nm_vendor']; ?>" disabled class="form-control" name="nm_vendor">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label for="kd_transaksi" class="col-sm-2 control-label">Kode Anggaran</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['kd_anggaran']; ?>" class="form-control " name="kd_transaksi" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-3">
                                        <!-- <input type="text" value="<?= $row2['keterangan']; ?>" class="form-control " name="keterangan" readonly> -->
                                        <textarea rows="5" name="keterangan" class="form-control" readonly id="" cols="30" rows="10"><?= $row2['keterangan']; ?></textarea>
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label for="terbilang_bkk" class=" col-sm-2 control-label">Terbilang</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['terbilang_bkk'] . ' Rupiah'; ?>" disabled class="form-control tanggal" name="terbilang_bkk">
                                    </div>
                                </div>
                                <?php if ($row2['status_bkk'] == "404" || $row2['status_bkk'] == "303" || $row2['status_bkk'] == "202" || $row2['status_bkk'] == "101") { ?>
                                    <div class="form-group">
                                        <label for="alasan_ditolak" class="col-sm-2 control-label">Alasan Ditolak</label>
                                        <div class="col-sm-3">
                                            <textarea rows="5" name="alasan_ditolak" class="form-control" readonly id="" cols="30" rows="10"><?= $row2['komentar_direktur']; ?>&#13;&#10;<?= $row2['komentar_mgrfin']; ?>&#13;&#10;<?= $row2['komentar']; ?></textarea>
                                        </div>
                                    </div>
                                <?php } ?>
                                <hr>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai Barang</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $nilai_barang; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai Jasa</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $nilai_jasa; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">PPN</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['ppn_persen'];  ?> %" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">PPh</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['pph_persen'];  ?> %" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai PPN</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $ppn_nilai; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai PPh</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $pph_nilai; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-4 control-label">Jumlah</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $jml_bkk; ?>" readonly class="form-control" name="jml_bkk">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </form>


                        <!-- Embed Document               -->
                        <div class="box-header with-border">
                            <h3 class="text-center">Invoice </h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="../file/<?php echo $row2['invoice']; ?> "></iframe>
                            </div>

                            <!-- Embed Document               -->
                            <!-- <?php
                                    if ($row2['doc_lpj'] != 0) { ?>
                        <div class="box-header with-border">
                            <h3 class="text-center">LPJ</h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="../file/lpj/<?php echo $row2['doc_lpj']; ?> "></iframe>
                        </div>
                                                                        
                            <br>
                            <br>
                <?php    } ?> -->


                            <!-- </div> -->
                        </div>
            </div>
        </div>
    </div>


<?php endwhile;
                } ?>
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        var status = "<?php print($data['status_kasbon']); ?>";
        var vrf_pajak = "<?php print($data['vrf_pajak']); ?>";

        console.log(vrf_pajak);

        // app mgr
        var app_mgr = "<?php print(date("d M Y H:i", strtotime($data['app_manager']))); ?>";
        var content_mgr = '<b>Manager</b><small>sudah memverifikasi</small>';
        if (app_mgr == "01 Jan 1970 01:00") {
            app_mgr = "";
            var content_mgr = "<b>Manager </b><small>Waiting....</small>";
        }


        // pajak
        var app_pajak = "<?php print(date("d M Y H:i", strtotime($data['app_pajak']))); ?>";
        var content_pajak = '<b>Pajak</b><small> sudah memverifikasi</small>'
        if (app_pajak == "01 Jan 1970 01:00") {
            app_pajak = " ";
            content_pajak = "<b>Pajak </b><small>Waiting....</small>";
        }

        // manager finance
        var app_manager_finance = "<?php print(date("d M Y H:i", strtotime($data['app_mgr_finance']))); ?>";
        var content_manager_finance = '<b>Manager Finance</b><small>sudah memverifikasi</small>'
        if (app_manager_finance == "01 Jan 1970 01:00") {
            app_manager_finance = " ";
            content_manager_finance = "<b>Manager Finance</b><small>Waiting....</small>";
        }

        // direktur
        var app_direktur = "<?php print(date("d M Y H:i", strtotime($data['app_direktur']))); ?>";
        var content_direktur = '<b>Direktur</b><small> sudah memverifikasi</small>'
        if (app_direktur == "01 Jan 1970 01:00") {
            app_direktur = " ";
            content_direktur = "<b>Direktur </b><small>Waiting....</small>";
        }

        // direktur2
        var app_direktur2 = "<?php print(date("d M Y H:i", strtotime($data['app_direktur2']))); ?>";
        var content_direktur2 = '<b>Direktur </b><small> sudah memverifikasi</small>'
        if (app_direktur2 == "01 Jan 1970 01:00") {
            app_direktur2 = " ";
            content_direktur = "<b>Direktur </b><small>Waiting....</small>";
        }

        // kasir
        var app_kasir = "<?php print(date("d M Y H:i", strtotime($data['waktu_penerima_dana']))); ?>";
        var content_kasir = '<b>Kasir </b><small> sudah melakukan penyerahan dana</small>'
        if (app_kasir == "01 Jan 1970 01:00") {
            app_kasir = " ";
            content_kasir = "<b>Kasir </b><small>Waiting....</small>";
        }

        // User
        var app_pembelian = "<?php print(date("d M Y H:i", strtotime($data['waktu_lpj']))); ?>";
        var content_pembelian = '<b>User </b><small> sudah melakukan LPJ</small>'
        if (app_pembelian == "01 Jan 1970 01:00") {
            app_pembelian = " ";
            content_pembelian = "<b>User </b><small>Waiting....</small>";
        }

        // Kasir    
        if (status != '7') {
            app_v_lpj = " ";
            content_v_lpj = "<b>Kasir </b><small>Waiting....</small>";
        }

        if (status == '1') {
            var content_mgr = "<b>Manager </b><font color= blue ><small>Verifikasi Manager </small></font> ";
        } else if (status == '2') {
            var content_pajak = "<b>Pajak </b><font color= blue ><small>Verifikasi Pajak </small></font> ";
        } else if (status == '3') {
            var content_manager_finance = "<b>Manager Finance</b><font color= blue ><small>Verifikasi Manager Finance </small></font> ";
        } else if (status == '4') {
            var content_direktur = "<b>Direktur</b><font color= blue ><small>Verifikasi Direktur </small></font> ";
            var content_direktur2 = "<b>Direktur</b><font color= blue ><small>Verifikasi Direktur </small></font> ";
        } else if (status == '5') {
            var content_kasir = "<b>Kasir</b><font color= blue ><small>Dana Sudah Bisa di ambil </small></font> ";
        } else if (status = '6') {
            var content_pembelian = "<b>User</b><font color= blue ><small>Setelah pembelian silahkan di lakukan LPJ </small></font> ";
        } else if (status = '7') {
            var content_v_lpj = "<b>Kasir</b><font color= blue ><small>Verifikasi LPJ </small></font> ";
        }

        if (vrf_pajak == 'bp') {
            var events = [{
                    date: '<?= date("d M Y H:i", strtotime($data['created_on'])); ?>',
                    content: '<b>User</b><small>membuat pengajuan</small>'
                },
                {
                    date: app_mgr,
                    content: content_mgr
                },
                {
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
                }, {
                    date: app_direktur,
                    content: content_direktur
                }, {
                    date: app_kasir,
                    content: content_kasir
                }, {
                    date: app_pembelian,
                    content: content_pembelian
                }, {
                    date: app_v_lpj,
                    content: content_v_lpj
                }
            ];
        } else {
            var events = [{
                    date: '<?= date("d M Y H:i", strtotime($data['created_on'])); ?>',
                    content: '<b>User</b><small>membuat pengajuan</small>'
                },
                {
                    date: app_mgr,
                    content: content_mgr
                },
                {
                    date: app_manager_finance,
                    content: content_manager_finance
                },
                {
                    date: app_direktur,
                    content: content_direktur
                }, {
                    date: app_direktur,
                    content: content_direktur
                }, {
                    date: app_kasir,
                    content: content_kasir
                }, {
                    date: app_pembelian,
                    content: content_pembelian
                },
                {
                    date: app_pajak,
                    content: content_pajak
                }, {
                    date: app_v_lpj,
                    content: content_v_lpj
                }
            ];
        }


        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 9,
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