<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$query =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                    JOIN sr sr
                                        ON id_sr = sr_id
                                    JOIN divisi d
                                        ON divisi_id = d.id_divisi
                                    WHERE id_kasbon = '$id'
            ");
$data2 = mysqli_fetch_assoc($query);
$id_sr = $data2['id_sr'];

$querySR =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                    JOIN sr sr
                                        ON id_sr = sr_id
                                    JOIN divisi d
                                        ON divisi_id = d.id_divisi
                                    JOIN anggaran a
                                        ON a.id_anggaran = sr.id_anggaran
                                    JOIN supplier sp
                                        ON sp.id_supplier = sr.id_supplier
                                    WHERE id_kasbon = '$id'

                ");

$queryDSR = mysqli_query($koneksi, "SELECT * FROM detail_sr WHERE sr_id = '$id_sr'");

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=list_order" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <?php if ($data2['status_kasbon'] == '101' || $data2['status_kasbon'] == '202' || $data2['status_kasbon'] == '303' || $data2['status_kasbon'] == '404') { ?>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="mb-2">
                                <label for="validationTextarea">Alasan Ditolak</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" disabled><?= $data2['komentar']; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <br>
                    <div id="my-timeline"></div>
                    <br>
                <?php } ?>
                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Detail SR</h3>
                </div>
                <form method="post" name="form" action="#" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['tgl_kasbon']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">Kode </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['id_kasbon']; ?>">
                            </div>
                            <div class="col-sm-offset-5 col-sm-3">
                                <?php if ($data2['status_kasbon'] >= 5) { ?>
                                    <a target="_blank" onclick="window.open('cetak_pengambilandana_sr.php?id=<?= enkripRambo($id); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> Laporan Pengambilan Dana </a>
                                <?php } ?>
                                <!-- <a target="_blank" href="cetak_kasbon.php?id=<?= $id; ?>" class="btn btn-success"><i class="fa fa-print"></i> Kasbon </a> -->
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="validationTextarea">Keterangan :</label>
                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" disabled><?= $data2['keterangan']; ?></textarea>
                        </div>
                        <br>
                    </div>
                </form>

                <!--  -->
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Anggaran</th>
                            <th>Supplier/Vendor</th>
                            <th>Nominal</th>
                            <th>Diskon</th>
                            <th>PPN</th>
                            <th>Grand Total</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($querySR)) {
                                        while ($row = mysqli_fetch_assoc($querySR)) :
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                            <td> <?= $row['nm_supplier']; ?> </td>
                                            <td> <?= number_format($row['nominal'], 0, ",", "."); ?> </td>
                                            <td> <?= number_format($row['diskon'], 0, ",", "."); ?> </td>
                                            <td> <?= number_format($row['nilai_ppn'], 0, ",", "."); ?> </td>
                                            <td>Rp. <?= number_format($row['grand_total'], 0, ",", "."); ?> </td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                        </tr>
                    </table>
                </div>
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Rincian Barang</h3>
                </div>
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Deskripsi</th>
                            <th>Merk</th>
                            <th>Type</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th>Sub Total</th>
                            <th>QTY</th>
                            <th>Total Price</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    if (mysqli_num_rows($queryDSR)) {
                                        while ($row = mysqli_fetch_assoc($queryDSR)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['deskripsi']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['type']; ?> </td>
                                            <td> <?= $row['spesifikasi']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= formatRupiah($row['sub_total']); ?> </td>
                                            <td> <?= $row['qty']; ?> </td>
                                            <td><?= formatRupiah($row['total']); ?></td>
                                </tr>
                        <?php
                                            $total += $row['total'];
                                            $no++;
                                        endwhile;
                                    } ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="8"><b>Total</b></td>
                            <td><b><?= formatRupiah($total); ?></b></td>
                        </tr>
                            </tbody>
                    </table>
                </div>
                <br>
                <!--  -->
                <?php
                $foto = $data2['doc_ba'];
                if ($foto === '0') { ?>
                    <h3 class="text-center">BA/Foto</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-offset-">
                            <h5 class="text-center">Tidak Ada Foto</h5>
                        </div>
                    </div>
                <?php } else { ?>
                    <h3 class="text-center">BA/Foto</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data2['doc_ba']; ?> "></iframe>
                    </div>
                    <!-- <div class="row ">
                        <div class="col-sm-offset-2">
                            <img src="../file/foto/<?= $data2['foto_item']; ?>" width="80%" alt="...">
                        </div>
                    </div> -->
                <?php } ?>

                <div class="box-header with-border">
                    <h3 class="text-center">Document Penawaran</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?= $data2['doc_penawaran']; ?> "></iframe>
                    </div>
                </div>
                <!--  -->
            </div>
        </div>
    </div>
    </div>

    <div id="tolak" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alasan Penolakan </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="#" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['kd_transaksi']; ?>" class="form-control" name="kd_transaksi" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="validationTextarea">Komentar</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
                                <div class="invalid-feedback">
                                    Please enter a message in the textarea.
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                </div>
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

        // app mgr
        var app_mgr = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr']))); ?>";
        var content_mgr = '<b>Manager</b><small>sudah memverifikasi</small>';
        if (app_mgr == "01 Jan 1970 01:00") {
            app_mgr = "";
            var content_mgr = "<b>Manager </b><small>Waiting....</small>";
        }

        // purchasing
        var app_purchasing = "<?php print(date("d M Y H:i", strtotime($data2['app_purchasing']))); ?>";
        var content_purchasing = '<b>purchasing</b><small> sudah melakukan bidding</small>'
        if (app_purchasing == "01 Jan 1970 01:00") {
            app_purchasing = " ";
            content_purchasing = "<b>purchasing </b><small>Waiting....</small>";
        }

        // pajak
        var app_pajak = "<?php print(date("d M Y H:i", strtotime($data2['app_pajak']))); ?>";
        var content_pajak = '<b>Pajak</b><small> sudah memverifikasi</small>'

        if (app_pajak == "01 Jan 1970 01:00") {
            app_pajak = " ";
            content_pajak = "<b>Pajak </b><small>Waiting....</small>";
        }

        // manager ga
        var app_manager_ga = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr_ga']))); ?>";
        var content_manager_ga = '<b>Manager GA</b><small> sudah memverifikasi</small>'
        if (app_manager_ga == "01 Jan 1970 01:00") {
            app_manager_ga = " ";
            content_manager_ga = "<b>Manager GA</b><small>Waiting....</small>";
        }

        // manager finance
        var app_manager_finance = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr_finance']))); ?>";
        var content_manager_finance = '<b>Manager Finance</b><small>sudah memverifikasi</small>'
        if (app_manager_finance == "01 Jan 1970 01:00") {
            app_manager_finance = " ";
            content_manager_finance = "<b>Manager Finance</b><small>Waiting....</small>";
        }

        // direktur
        var app_direktur = "<?php print(date("d M Y H:i", strtotime($data2['app_direktur2']))); ?>";
        var content_direktur = '<b>Direktur</b><small> sudah memverifikasi</small>'
        if (app_direktur == "01 Jan 1970 01:00") {
            app_direktur = " ";
            content_direktur = "<b>Direktur </b><small>Waiting....</small>";
        }

        var status = "<?php print($data2['status_kasbon']); ?>";

        if (status == '1') {
            var content_pajak = "<b>Manager GA </b><font color= blue ><small>Verifikasi Manager GA </small></font>";
        } else if (status == '2') {
            var content_manager_ga = "<b>Pajak </b><font color= blue ><small>Verifikasi Pajak </small></font>";
        } else if (status == '3') {
            var content_manager_finance = "<b>Manager Finance</b><font color= blue ><small>Verifikasi Manager Finance </small></font>";
        } else if (status == '4') {
            var content_direktur = "<b>Direktur</b><font color= blue ><small>Verifikasi Direktur </small></font>";
        }

        var events = [{
                date: '<?= date("d M Y H:i", strtotime($data2['created_at'])); ?>',
                content: '<b>User</b><small>membuat pengajuan</small>'
            },
            {
                date: app_mgr,
                content: content_mgr
            },
            {
                date: app_purchasing,
                content: content_purchasing
            },
            {
                date: app_manager_ga,
                content: content_manager_ga
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
            }
        ];

        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 7,
            slide: 1,
            prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
            nextArrow: '<i class="material-icons">keyboard_arrow_right</i>'
        });
    });
</script>