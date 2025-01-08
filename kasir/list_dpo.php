<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBo =  mysqli_query($koneksi, "SELECT * FROM tagihan_po tp
                                            JOIN po p
                                            ON p.id_po = tp.po_id
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
                                            WHERE tp.id_tagihan ='$id' ");

$query =  mysqli_query($koneksi, "SELECT * FROM tagihan_po tp
                                            JOIN po p
                                            ON p.id_po = tp.po_id
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
                                            WHERE tp.id_tagihan ='$id' ");
$data2 = mysqli_fetch_assoc($query);

// print_r($data2);
// die;

$id_supplier = $data2['id_supplier'];
$id_anggaran = $data2['id_anggaran'];
$totalPengajuan = $data2['total_po'];

$id_dbo = $data2['id_dbo'];

$id_po = $data2['id_po'];

$queryTagihan =  mysqli_query($koneksi, "SELECT *, tp.persentase AS tppersentase, tp.nominal AS tpnominal
                                            FROM tagihan_po tp
                                            JOIN po p
                                                ON p.id_po = tp.po_id
                                                AND metode_pembayaran = 'Transfer'
                                            JOIN bkk_ke_pusat bf
                                                ON id = bkk_id
                                            WHERE tp.po_id = '$id_po'

                                            UNION ALL

                                            SELECT *, tp.persentase AS tppersentase, tp.nominal AS tpnominal
                                            FROM tagihan_po tp
                                            JOIN po p
                                                ON p.id_po = tp.po_id
                                                AND metode_pembayaran = 'Tunai'
                                            JOIN bkk_final bf
                                                ON id = bkk_id
                                            WHERE tp.po_id = '$id_po'
                                ");

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");


?>
<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=list_po" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-offset-10">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#konfirmasi"> <i class="fa fa-edit"></i> Create LPD</button></span></a>
                        <!-- <a target="_blank" href="cetak_po.php?id=<?= $id; ?>" class="btn btn-success"><i class="fa fa-print"></i> PO </a> -->
                    </div>
                </div>
                <!--  -->
                <!-- Detail Job Order -->
                <div class="box-header with-border">
                    <h3 class="text-center">Detail PO</h3>
                </div>

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
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">PO Number</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['po_number']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Note</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['note_po']; ?></textarea>
                            </div>
                            <label for="keterangan" class="col-sm-offset-2 col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" readonly class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                        </div>
                        <br>
                    </div>
                </form>

                <!--  -->
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kode Anggaran</th>
                                <th>Merk</th>
                                <th>Supplier/Vendor</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($queryBo)) {
                                while ($row = mysqli_fetch_assoc($queryBo)) :

                            ?>
                                    <tr>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['nm_barang']; ?> </td>
                                        <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                        <td> <?= $row['merk']; ?> </td>
                                        <td> <?= $row['nm_supplier']; ?> </td>
                                        <td> <?= $row['satuan']; ?> </td>
                                        <td> <?= $row['jumlah']; ?> </td>
                                        <td>Rp. <?= number_format($data2['grand_totalpo'], 0, ",", "."); ?> </td>
                                    </tr>
                            <?php
                                    $no++;
                                endwhile;
                            } ?>
                        </tbody>
                    </table>
                </div>
                <br>
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
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    if (mysqli_num_rows($querySbo)) {
                                        while ($row = mysqli_fetch_assoc($querySbo)) :

                                    ?>
                                            <tr>
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
                                    <tr>
                                        <td colspan="5" style="text-align: right;"><b> PPN </b></td>
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
                                    <th>%</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>#</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    if (mysqli_num_rows($queryTagihan)) {
                                        while ($row = mysqli_fetch_assoc($queryTagihan)) :

                                            $pathTagihan = $row['metode_pembayaran'] == "Tunai" ? pathBelawan() : pathPusat();
                                    ?>
                                            <tr>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['tgl_buat']); ?> </td>
                                                <td> <?= formatTanggal($row['tgl_tempo']); ?> </td>
                                                <td><?= $row['tppersentase']; ?></td>
                                                <td> <?= formatRupiah(round($row['tpnominal'])); ?> </td>
                                                <td>
                                                    <?php if ($row['status_tagihan'] == "5") { ?>
                                                        <span class="label label-success">Terbayar</span>
                                                    <?php } else { ?>
                                                        <span class="label label-warning">Belum di bayar</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#invoice_<?= $row['id_tagihan']; ?>"><i class="fa fa-folder-open" title="Invoice" data-toggle="tooltip"></i></button>
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#pembayaran_<?= $row['id_tagihan']; ?>"><i class="fa fa-folder-open" title="Bukti Pembayaran" data-toggle="tooltip"></i></button>
                                                </td>
                                            </tr>

                                            <!-- Modal invoice -->
                                            <div id="invoice_<?= $row['id_tagihan']; ?>" class="modal fade" role="dialog">
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
                                            <!-- Akhir modal invoice -->

                                            <!-- Modal pembayaran -->
                                            <div id="pembayaran_<?= $row['id_tagihan']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Bukti Pembayaran</h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <form class="form-horizontal">
                                                            <div class="modal-body">
                                                                <div class="perhitungan">
                                                                    <div class="box-body">
                                                                        <div class="form-group">
                                                                            <?php if (file_exists($pathTagihan . "bukti_pembayaran/" . $row['bukti_pembayaran']) && !empty($row['bukti_pembayaran'])) { ?>
                                                                                <div class="embed-responsive embed-responsive-16by9">
                                                                                    <iframe class="embed-responsive-item" src="<?= $pathTagihan; ?>bukti_pembayaran/<?= $row['bukti_pembayaran']; ?>"></iframe>
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
                                            <!-- Akhir modal pemabayaran -->
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
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
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
                            <!-- Embed Document -->
                            <div class="box-header with-border">
                                <h3 class="text-center">Foto Barang/BA</h3>
                                <div class="embed-responsive embed-responsive-4by3">
                                    <iframe class="embed-responsive-item" src="../file/foto/<?php echo $data2['foto_item']; ?> "></iframe>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <!-- Embed Document -->
                        <div class="box-header with-border">
                            <h3 class="text-center">Document Penawaran</h3>
                            <div class="embed-responsive embed-responsive-4by3">
                                <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?php echo $data2['doc_penawaran']; ?> "></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <!--  -->
                <!-- Embed Document -->
                <!-- <div class="box-header with-border">
                    <?php echo $data2['doc_quotation']; ?>
                    <h3 class="text-center">Document Quatation</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_quotation/<?php echo $data2['doc_quotation']; ?> "></iframe>
                    </div>
                </div> -->
                <br>
                <br>
            </div>
            <br>
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
                    <h4 class="modal-title">Create LPD</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <div class="perhitungan">
                        <form method="post" name="form" enctype="multipart/form-data" action="create_bkkpo.php" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" value="<?= $data2['id_po']; ?>" name="id_po">
                                <input type="hidden" value="<?= $data2['id_tagihan']; ?>" name="id_tagihan">
                                <input type="hidden" value="<?= $data2['id_anggaran']; ?>" name="id_anggaran">
                                <input type="hidden" value="<?= $data2['id_supplier']; ?>" name="id_supplier">
                                <input type="hidden" value="<?= round($data2['nilai_barang']); ?>" name="nilai_barang">
                                <input type="hidden" value="<?= round($data2['nilai_jasa']); ?>" name="nilai_jasa">
                                <input type="hidden" value="<?= round($data2['nilai_ppn']); ?>" name="nilai_ppn">
                                <input type="hidden" value="<?= $data2['id_pph']; ?>" name="id_pph">
                                <input type="hidden" value="<?= round($data2['nilai_pph']); ?>" name="nilai_pph">
                                <input type="hidden" value="<?= $data2['persentase_pembayaran1']; ?>" name="persen">

                                <div class="form-group ">
                                    <label id="tes" for="id_supplier" class="col-sm-4 control-label">Tanggal BKK</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control " name="tgl_bkk" id="tgl_bkk" value="<?= formatTanggal($tanggal) ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="id_supplier" class="col-sm-4 control-label">Supplier/Vendor</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control " name="nm_supplier" id="nm_supplier" value="<?= $data2['nm_supplier'] ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="nocek_bkk" class="col-sm-4 control-label">Total PO</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control " name="grand_totalpo" value="<?= formatRupiah2(round($data2['grand_totalpo'])); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="persentase_pembayaran1" class="col-sm-4 control-label">Persentase Payment</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control " name="persentase_pembayaran1" value="<?= formatRupiah2($data2['persentase_pembayaran1']); ?>%" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="nocek_bkk" class="col-sm-4 control-label">Total Payment</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control " name="nominal" value="<?= formatRupiah2($data2['nominal_pembayaran1']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="id_supplier" class="col-sm-4 control-label">Metode Pembayaran</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control " name="metode_pembayaran" id="metode_pembayaran" value="<?= $data2['metode_pembayaran'] ?>" readonly>
                                    </div>
                                </div>
                                <?php
                                // if ($data2['metode_pembayaran'] == 'Transfer') {
                                ?>
                                <div class="form-group">
                                    <label for="doc_faktur" class="col-sm-offset-1 col-sm-3 control-label">Invoice/Faktur </label>
                                    <div class="col-sm-5">
                                        <div class="input-group input-file" name="doc_faktur">
                                            <input type="text" class="form-control" <?php echo $data2['metode_pembayaran'] = 'Transfer' ? 'required' : ''; ?> />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default btn-choose" type="button">Browse</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php // } 
                                ?>

                                <div class="mb-3">
                                    <label for="validationTextarea">Redaksi : </label>
                                    <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Redaksi"></textarea>
                                </div>
                                <div class=" modal-footer">
                                    <button class="btn btn-primary" type="submit" name="submit"> <i class="fa fa-edit"></i> Create</button>
                                    &nbsp;
                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                </div>
                            </div>
                        </form>
                        <!-- div perhitungan -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });

    $(".perhitungan").keyup(function() {
        var persen = parseInt($("#persentase_pembayaran").val())

        var grand_totalpo = "<?php print($data2['grand_totalpo']); ?>";

        var nominal_pembayaran = Math.floor((persen / 100) * grand_totalpo);
        var nominal_pembayarana = tandaPemisahTitik(nominal_pembayaran);

        document.form.nominal_pembayaran1.value = nominal_pembayarana;

    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost'  accept='application/pdf' style='visibility:hidden; height:0'>");
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