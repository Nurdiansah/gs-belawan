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
                                    LEFT JOIN tolak_po
                                        ON p.id_po = po_id
                                    WHERE p.id_po ='$id' ");
$data2 = mysqli_fetch_assoc($query);

$id_supplier = $data2['id_supplier'];
$id_anggaran = $data2['id_anggaran'];
$totalPengajuan = $data2['total_po'];

$id_dbo = $data2['id_dbo'];


$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");

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
// $totalRealisasi = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];        

$queryTagihan =  mysqli_query($koneksi, "SELECT *, tp.persentase AS tppersentase
                                            FROM tagihan_po tp
                                            JOIN po p
                                            ON p.id_po = tp.po_id
                                            WHERE tp.po_id ='$id' ");


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">


                <!-- Detail Job Order -->
                <div class="box-header with-border">
                    <!-- <a target="_blank" href="cetak_po.php?id=<?= $id; ?>" class="btn btn-success col-sm-offset-11"><i class="fa fa-print"></i> PO </a>                                                                                                 -->
                    <h3 class="text-center">Detail PO Ready To Pay</h3>
                </div>
                <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="status" class="col-sm-offset- col-sm-1 control-label">Status</label>
                            <div class="col-sm-3">
                                <!-- <input type="text" disabled class="form-control is-valid" name="status" value="<?= $data2['po_number']; ?>"> -->
                                <?php if ($data2['status_po'] == 6) { ?>
                                    <button class="btn btn-danger">Kasir-Verifikasi Term Payment</button>
                                <?php  } else if ($data2['status_po'] == 7) { ?>
                                    <button class="btn btn-info">Kasir-List Tempo</button>
                                <?php  } else if ($data2['status_po'] == 8) { ?>
                                    <button class="btn btn-warning">Proses Pengajuan BKK</button>
                                <?php  } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-1 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['tgl_pengajuan']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-1 col-sm-3 control-label">PO Number</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['po_number']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Termin</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['note_po']; ?></textarea>
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
                                            <td> <b> Rp. <?= number_format($row['grand_totalpo'], 0, ",", "."); ?> </b></td>
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
                <!--  -->
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="box-header with-border">
                            <h4 class="text-center">Rincian Barang</h4>
                        </div>
                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-dark table-hover ">
                                <thead style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Deskripsi</th>
                                    <th>QTY</th>
                                    <th>Unit</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                </thead>
                                <tr>
                                    <tbody>
                                        <tr>
                                            <?php
                                            $no = 1;
                                            $total = 0;
                                            if (mysqli_num_rows($querySbo)) {
                                                while ($row = mysqli_fetch_assoc($querySbo)) :

                                            ?>
                                                    <td> <?= $no; ?> </td>
                                                    <td> <?= $row['sub_deskripsi']; ?> </td>
                                                    <td> <?= $row['sub_qty']; ?> </td>
                                                    <td> <?= $row['sub_unit']; ?> </td>
                                                    <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
                                                    <td style="text-align: right;"><?= formatRupiah($row['total_price']); ?></td>
                                        </tr>
                                <?php
                                                    $total += $row['total_price'];
                                                    $no++;
                                                endwhile;
                                            } ?>
                                <tr style="background-color :#B0C4DE;">
                                    <td colspan="5" style="text-align: right;"><b>Sub Total</b></td>
                                    <td style="text-align: right;"><b> <?= formatRupiah($data2['sub_totalpo']); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right;"><b>Diskon </b></td>
                                    <td style="text-align: right;"><b> <?= formatRupiah($data2['diskon_po']); ?></b></td>
                                </tr>
                                <?php
                                $total = $data2['sub_totalpo'] - $data2['diskon_po'];
                                $grandTotal = $total + $data2['nilai_ppn'];
                                ?>
                                <tr style="background-color :#B0C4DE;">
                                    <td colspan="5" style="text-align: right;"><b>Total </b></td>
                                    <td style="text-align: right;"><b> <?= formatRupiah($data2['total_po']); ?></b></td>
                                </tr>
                                <?php
                                $persentase = ($data2['nilai_ppn'] / $data2['total_po']) * 100;

                                ?>
                                <tr>
                                    <td colspan="5" style="text-align: right;"><b> PPN <?= round($persentase) ?>% </b></td>
                                    <td style="text-align: right;"><b> <?= formatRupiah($data2['nilai_ppn']); ?></b></td>
                                </tr>
                                <tr style="background-color :#B0C4DE;">
                                    <td colspan="5" style="text-align: right;"><b> Grand Total </b></td>
                                    <td style="text-align: right;"><b> <?= formatRupiah($data2['grand_totalpo']); ?></b></td>
                                </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="box-header with-border">
                            <h4 class="text-center">Tagihan Pembayaran</h4>
                        </div>
                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-dark table-hover ">
                                <thead style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Tgl Invoice</th>
                                    <th>Tgl Tempo</th>
                                    <th>Nominal</th>
                                    <th>%</th>
                                    <th>Status</th>
                                    <th>Invoice</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    if (mysqli_num_rows($queryTagihan)) {
                                        while ($row = mysqli_fetch_assoc($queryTagihan)) :

                                    ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= formatTanggal($row['tgl_buat']); ?></td>
                                                <td><?= formatTanggal($row['tgl_tempo']); ?></td>
                                                <td><?= formatRupiah(round($row['nominal'])); ?></td>
                                                <td><?= $row['tppersentase']; ?></td>
                                                <td>
                                                    <?php
                                                    if ($row['status_tagihan'] == '1') {
                                                        echo "<span class='label label-danger'>Menunggu Tempo/Invoice</span>";
                                                    } else if ($row['status_tagihan'] == '2') {
                                                        echo "<span class='label label-warning'>Approval Manager Finance</span>";
                                                    } else if ($row['status_tagihan'] == '3') {
                                                        echo "<span class='label label-warning'>Approval Direksi</span>";
                                                    } else if ($row['status_tagihan'] == '4') {
                                                        echo "<span class='label label-warning'>Dana sudah bisa di ambil</span>";
                                                    } else if ($row['status_tagihan'] == '5') {
                                                        echo "<span class='label label-success'>Terbayar</span>";
                                                    }

                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#lihat_<?= $row['id_tagihan']; ?>"><i class="fa fa-folder-open" title="Lihat" data-toggle="tooltip"></i></button>
                                                </td>
                                            </tr>

                                            <!-- Modal Lihat -->
                                            <div id="lihat_<?= $row['id_tagihan']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Invoice PO [<?= $row['po_number']; ?>], pembayaran ke-<?= $no . " (" .  $row['tppersentase']; ?>%)</h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <form class="form-horizontal">
                                                            <div class="modal-body">
                                                                <div class="perhitungan">
                                                                    <div class="box-body">
                                                                        <div class="form-group">
                                                                            <?php if (file_exists("../file/invoice/" . $row['doc_faktur']) && !empty($row['doc_faktur'])) { ?>
                                                                                <div class="embed-responsive embed-responsive-16by9">
                                                                                    <iframe class="embed-responsive-item" src="../file/invoice/<?= $row['doc_faktur']; ?>"></iframe>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <h4 class="text-center">Document tidak ada</h4>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class=" modal-footer">
                                                                        <input type="reset" value="Close" data-dismiss="modal" class="btn btn-default">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir modal lihat -->
                                    <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <br>
                <!--  -->
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
                    <h3 class="text-center">Foto Barang/BA</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-12">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="../file/foto/<?= $data2['foto_item']; ?>"></iframe>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <br>
                <!-- Embed Document -->
                <div class="box-header with-border">
                    <h3 class="text-center">Document Penawaran</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?php echo $data2['doc_penawaran']; ?> "></iframe>
                    </div>
                </div>
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Document Quotation</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_quotation/<?php echo $data2['doc_quotation']; ?> "></iframe>
                    </div>
                </div>
                <br>
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
        if (app_pajak === null) {
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
        var content_manager_finance = '<b>Manager Finance</b><small>sudah memverifikasi</small>'
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

        if (status == '2') {
            var content_manager_ga = "<b>Manager GA </b><font color= blue ><small>Verifikasi Manager GA </small></font>";
        } else if (status == '3') {
            var content_pajak = "<b>Pajak </b><font color= blue ><small>Verifikasi Pajak </small></font>";
        } else if (status == '4') {
            var content_manager_finance = "<b>Manager Finance</b><font color= blue ><small>Verifikasi Manager Finance </small></font>";
        } else if (status == '5') {
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

    function divide() {
        var txt;
        txt = document.getElementById('a').value;
        var text = txt.split(".");
        var str = text.join('.</br>');
        document.write(str);
    }
</script>