<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
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


$query =  mysqli_query($koneksi, "SELECT *, dbo.keterangan AS dbo_keterangan
                                    FROM biaya_ops bo
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
                                    LEFT JOIN bkk_final
                                        ON id_po = id_kdtransaksi
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


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">


                <!-- Detail Job Order -->
                <div class="box-header with-border">
                    <!-- <a target="_blank" href="cetak_po.php?id=<?= $id; ?>" class="btn btn-success col-sm-offset-11"><i class="fa fa-print"></i> PO </a>                                                                                                 -->
                    <h3 class="text-center">Proses PO</h3>
                </div>
                <?php if ($data2['status_po'] == '0' || $data2['status_po'] == '101' || $data2['status_po'] == '202' || $data2['status_po'] == '303' || $data2['status_po'] == '404') { ?>
                    <form method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="alasan_ditolak" class="col-sm-offset- col-sm-1 control-label">Alasan Ditolak</label>
                                <div class="col-sm-10">
                                    <textarea rows="5" type="text" name="alasan_ditolak" disabled class="form-control "><?= $data2['alasan_tolak_mgrga']; ?>&#13;&#10;<?= $data2['alasan_tolak_pajak']; ?>&#13;&#10;<?= $data2['alasan_tolak_mgrfin']; ?>&#13;&#10;<?= $data2['alasan_tolak_direktur']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <br>
                    <div id="my-timeline"></div>
                    <br>
                <?php } ?>
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
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
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['dbo_keterangan']; ?></textarea>
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
                                    if (mysqli_num_rows($querySbo)) {
                                        while ($row = mysqli_fetch_assoc($querySbo)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['sub_deskripsi']; ?> </td>
                                            <td> <?= $row['sub_qty']; ?> </td>
                                            <td> <?= $row['sub_unit']; ?> </td>
                                            <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
                                            <td><?= formatRupiah($row['total_price']); ?></td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Sub Total</b></td>
                            <td><b> <?= formatRupiah($data2['sub_totalpo']); ?></b></td>
                        </tr>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Diskon </b></td>
                            <td><b> <?= formatRupiah($data2['diskon_po']); ?></b></td>
                        </tr>
                        <?php
                        $total = $data2['sub_totalpo'] - $data2['diskon_po'];
                        $grandTotal = $total + $data2['nilai_ppn'];
                        ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Total</b></td>
                            <td><b> <?= formatRupiah($total); ?>,00</b></td>
                        </tr>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b> PPN 10% </b></td>
                            <td><b> <?= formatRupiah($data2['nilai_ppn']); ?></b></td>
                        </tr>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b> Grand Total </b></td>
                            <td><b> <?= formatRupiah($data2['grand_totalpo']); ?></b></td>
                        </tr>

                            </tbody>
                    </table>
                </div>
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
        if (app_mgr === "") {
            app_mgr = "";
            var content_mgr = "<b>Manager </b><small>Waiting....</small>";
        }

        // purchasing
        var app_purchasing = "<?php print($data2['tgl_po']); ?>";
        var content_purchasing = '<b>purchasing</b><small> sudah melakukan bidding</small>'
        if (app_purchasing === "") {
            app_purchasing = " ";
            content_purchasing = "<b>purchasing </b><small>Waiting....</small>";
        }

        // Cost control       
        var app_cc = "<?php print($data2['app_cc']); ?>";
        var content_cc = '<b>Cost Control</b><small> sudah memverifikasi</small>'
        if (app_cc === "") {
            app_cc = " ";
            content_cc = "<b>Cost Control</b><small>Waiting....</small>";
        }

        // manager ga        
        var app_manager_ga = "<?php print($data2['app_mgr_ga']); ?>";
        var content_manager_ga = '<b>Manager</b><small> sudah memverifikasi</small>'
        if (app_manager_ga === "") {
            app_manager_ga = " ";
            content_manager_ga = "<b>Manager</b><small>Waiting....</small>";
        }

        // manager finance
        var app_manager_finance = "<?php print($data2['app_mgr_finance']); ?>";
        var content_manager_finance = '<b>Manager Finance</b><small>sudah memverifikasi</small>'
        if (app_manager_finance === "") {
            app_manager_finance = " ";
            content_manager_finance = "<b>Manager Finance</b><small>Waiting....</small>";
        }

        // direktur
        var app_direktur = "<?php print($data2['app_direksi']); ?>";
        var content_direktur = '<b>Direktur</b><small> sudah memverifikasi</small>'
        if (app_direktur === "") {
            app_direktur = " ";
            content_direktur = "<b>Direktur </b><small>Waiting....</small>";
        }

        // kasir
        var app_kasir = "<?php print($data2['app_kasir']); ?>";
        var content_kasir = '<b>Payment Kasir</b><small> Payment</small>'
        if (app_kasir === "") {
            app_kasir = " ";
            content_kasir = "<b>Payment Kasir</b><small>Waiting....</small>";
        }

        // pajak
        var app_pajak = "<?php print($data2['app_pajak']); ?>";
        var content_pajak = '<b>Pajak</b><small> verifikasi pajak</small>'
        if (app_pajak === "") {
            app_pajak = " ";
            content_pajak = "<b>Pajak</b><small>Waiting....</small>";
        }

        // pajak
        var app_kasir_pym = "<?php print($data2['v_direktur']); ?>";
        var content_kasir_pym = '<b>Payment Kasir</b><small> payment kasir</small>'
        if (app_kasir_pym === "") {
            app_kasir_pym = " ";
            content_kasir_pym = "<b>Payment Kasir</b><small>Waiting....</small>";
        }

        var status = "<?php print($data2['status_po']); ?>";

        if (status == '1') {
            var content_purchasing = "<b> Purchasing </b><font color= blue ><small>Submit Quatation Purchasing </small></font>";
        } else if (status == '2') {
            var content_cc = "<b>Costcontrol </b><font color= blue ><small>Verifikasi Cost Control </small></font>";
        } else if (status == '3') {
            var content_manager_finance = "<b>Manager Finance</b><font color= blue ><small>Verifikasi Manager </small></font>";
        } else if (status == '4') {
            var content_manager_ga = "<b>Manager Ga</b><font color= blue ><small>Verifikasi GM Finance </small></font>";
        } else if (status == '5') {
            var content_direktur = "<b>Direktur</b><font color= blue ><small>Verifikasi Direktur </small></font>";
        }

        /*
            1 = Submit Quatation Purchasing
            2 = Verifikasi Cost Control
            3 = Approval Manager
            4 = Approval GM Finance
            5 = Approval Direktur
            6 = Proses Pembayaran Kasir
    */
        console.log(status);

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
                date: app_cc,
                content: content_cc
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
            },
            {
                date: app_kasir,
                content: content_kasir
            }
        ];

        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 8,
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