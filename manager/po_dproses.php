<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBo =  mysqli_query($koneksi, "SELECT * FROM po p
                                            JOIN biaya_ops bo
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON dbo.id = p.id_dbo
                                            JOIN anggaran a
                                            ON a.id_anggaran = dbo.id_anggaran
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            JOIN supplier s
                                            ON s.id_supplier = dbo.id_supplier
                                            WHERE p.id_po ='$id' ");


$query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            JOIN po p
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON p.id_dbo = dbo.id
                                            JOIN anggaran a
                                            ON dbo.id_anggaran = a.id_anggaran
                                            WHERE p.id_po ='$id' ");
$data2 = mysqli_fetch_assoc($query);

$id_supplier = $data2['id_supplier'];
$id_anggaran = $data2['id_anggaran'];
$totalPengajuan = $data2['total_po'];

// total anggaran yang ada di anggaran
$queryTotal = mysqli_query($koneksi, " SELECT sum(jumlah_nominal) as total_anggaran 
                                                FROM anggaran
                                                WHERE id_anggaran='$id_anggaran' ");
$rowTotal = mysqli_fetch_assoc($queryTotal);
$totalAnggaran = $rowTotal['total_anggaran'];

// realisasi anggaran
$queryRealisasi = mysqli_query($koneksi, " SELECT *
                                                FROM anggaran
                                                WHERE id_divisi='$id_anggaran' ");
$rowR = mysqli_fetch_assoc($queryRealisasi);
$totalRealisasi = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">


                <!-- Detail Job Order -->
                <div class="box-header with-border">
                    <h3 class="text-center">Proses PO</h3>
                </div>

                <br>
                <div id="my-timeline"></div>
                <br>

                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['tgl_pengajuan']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">PO Number</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['po_number']; ?>">
                            </div>
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
                            <th>Merk</th>
                            <th>Supplier/Vendor</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($queryBo)) {
                                        while ($row = mysqli_fetch_assoc($queryBo)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['nm_supplier']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['jumlah']; ?> </td>
                                            <td> <b> Rp. <?= number_format($row['harga_estimasi'], 0, ",", "."); ?> </b></td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                            <!-- </tr>
                                <tr>
                                <td colspan="7"><b>Total Harga</b></td>
                                <td><b> </b></td>                                
                                </tr> -->
                    </table>
                </div>
                <br>
                <?php
                $foto = $data2['foto_item'];
                if ($foto === '0') { ?>
                    <h3 class="text-center">Foto Barang</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-offset-">
                            <h5 class="text-center">Tidak Ada Foto</h5>
                        </div>
                    </div>
                <?php } else { ?>
                    <h3 class="text-center">Document Pendukung</h3>
                    <!-- pdf baru -->
                    <iframe src="../file/pdfjs/web/viewer.html?file=../../foto/<?php echo $data2['foto_item']; ?> " frameborder="0" width="100%" height="550"></iframe>
                    <!-- pdf lama -->
                    <!-- <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/foto/<?= $data2['foto_item']; ?>"></iframe>
                    </div> -->

                <?php } ?>
                <br>
                <!--  -->
                <!--  -->
            </div>
            <br>
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
        var app_mgr = "<?php print($data2['app_mgr']); ?>";
        var content_mgr = '<b>Manager</b><small>sudah memverifikasi</small>';
        if (app_mgr === "0000-00-00 00:00:00") {
            app_mgr = "";
            var content_mgr = "<b>Manager </b><small>Waiting....</small>";
        }

        // purchasing
        var app_purchasing = "<?php print($data2['tgl_po']); ?>";
        var content_purchasing = '<b>purchasing</b><small> sudah melakukan bidding</small>'
        if (app_purchasing === "0000-00-00 00:00:00") {
            app_purchasing = " ";
            content_purchasing = "<b>purchasing </b><small>Waiting....</small>";
        }

        // pajak
        var app_pajak = "<?php print($data2['app_pajak']); ?>";
        var content_pajak = '<b>Pajak</b><small> sudah memverifikasi</small>'
        if (app_pajak === "0000-00-00 00:00:00") {
            app_pajak = " ";
            content_pajak = "<b>Pajak </b><small>Waiting....</small>";
        }

        // manager ga        
        var app_manager_ga = "<?php print($data2['app_mgr_ga']); ?>";
        var content_manager_ga = '<b>Manager GA</b><small> sudah memverifikasi</small>'
        if (app_manager_ga === "0000-00-00 00:00:00") {
            app_manager_ga = " ";
            content_manager_ga = "<b>Manager GA</b><small>Waiting....</small>";
        }

        // manager finance
        var app_manager_finance = "<?php print($data2['app_mgr_finance']); ?>";
        var content_manager_finance = '<b>Manager GA</b><small>sudah memverifikasi</small>'
        if (app_manager_finance === "0000-00-00 00:00:00") {
            app_manager_finance = " ";
            content_manager_finance = "<b>Manager Finance</b><small>Waiting....</small>";
        }

        // direktur
        var app_direktur = "<?php print($data2['app_direksi']); ?>";
        var content_direktur = '<b>Direktur</b><small> sudah memverifikasi</small>'
        if (app_direktur === "0000-00-00 00:00:00") {
            app_direktur = " ";
            content_direktur = "<b>Direktur </b><small>Waiting....</small>";
        }

        var status = "<?php print($data2['status_po']); ?>";

        if (status == '1') {
            var content_pajak = "<b>Pajak </b><font color= blue ><small>Verifikasi Pajak </small></font>";
        } else if (status == '2') {
            var content_manager_ga = "<b>Manager GA </b><font color= blue ><small>Verifikasi Manager GA </small></font>";
        } else if (status == '3') {
            var content_manager_finance = "<b>Manager Finance</b><font color= blue ><small>Verifikasi Manager Finance </small></font>";
        } else if (status == '4') {
            var content_direktur = "<b>Direktur</b><font color= blue ><small>Verifikasi Direktur </small></font>";
        }

        var events = [{
                date: '<?= date("d M Y H:i", strtotime($data2['tgl_po'])); ?>',
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
                date: app_pajak,
                content: content_pajak
            },
            {
                date: app_manager_ga,
                content: content_manager_ga
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