<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama, id_divisi from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];
$id_divisi = $rowNama['id_divisi'];

$queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                                        JOIN divisi d
                                                        ON d.id_divisi = bo.id_divisi 
                                                        JOIN po p
                                                        ON p.kd_transaksi = bo.kd_transaksi
                                                        JOIN detail_biayaops dbo
                                                        ON p.id_dbo = dbo.id
                                                        JOIN anggaran a
                                                        ON dbo.id_anggaran = a.id_anggaran
                                                        LEFT JOIN pph pp
                                                        ON p.id_pph = pp.id_pph
                                                        JOIN supplier spl
                                                        ON spl.id_supplier = dbo.id_supplier
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
                                            LEFT JOIN pph pp
                                            ON pp.id_pph = p.id_pph
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
                                                WHERE id_divisi='$id_divisi' ");
$rowR = mysqli_fetch_assoc($queryRealisasi);
$totalRealisasi = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=verifikasi_po" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>

                <!-- Detail Job Order -->
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi PO</h3>
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
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan']; ?></textarea>
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
                                <tr>
                                    <td colspan="5" style="text-align: right;"><b> PPN 11% </b></td>
                                    <td style="text-align: right;"><b> <?= formatRupiah($data2['nilai_ppn']); ?></b></td>
                                </tr>
                                <?php if ($data2['nilai_pph'] > 0) { ?>
                                    <tr>
                                        <td colspan="5" style="text-align: right;"><b> PPh </b></td>
                                        <td style="text-align: right;"><b> <?= formatRupiah($data2['nilai_pph']); ?></b></td>
                                    </tr>
                                <?php } ?>
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
                            <h4 class="text-center">Pembayaran</h4>
                        </div>
                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-dark table-hover ">
                                <thead style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Tgl Invoice</th>
                                    <th>Tgl Tempo</th>
                                    <th>%</th>
                                    <th>Nominal</th>
                                    <th>Pembayaran</th>
                                    <th>Status</th>
                                    <th>Invoice</th>
                                </thead>

                                <tbody>
                                    <?php
                                    $queryTagihan =  mysqli_query($koneksi, "SELECT *, tp.persentase AS tppersentase
                                                                                FROM tagihan_po tp
                                                                                JOIN po p
                                                                                    ON p.id_po = tp.po_id
                                                                                    AND metode_pembayaran = 'Transfer'
                                                                                JOIN bkk_ke_pusat bf
                                                                                    ON id = bkk_id
                                                                                WHERE tp.po_id = '$id'
                                                                                
                                                                                UNION ALL

                                                                                SELECT *, tp.persentase AS tppersentase
                                                                                FROM tagihan_po tp
                                                                                JOIN po p
                                                                                    ON p.id_po = tp.po_id
                                                                                    AND metode_pembayaran = 'Tunai'
                                                                                JOIN bkk_final bf
                                                                                    ON id = bkk_id
                                                                                WHERE tp.po_id = '$id' ");

                                    $jumlahData = mysqli_num_rows($queryTagihan);

                                    $no = 1;
                                    $total = 0;

                                    $persent = 0;
                                    if (mysqli_num_rows($queryTagihan)) {
                                        while ($row = mysqli_fetch_assoc($queryTagihan)) :

                                    ?>
                                            <tr>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['tgl_buat']); ?> </td>
                                                <td> <?= formatTanggal($row['tgl_tempo']); ?> </td>
                                                <td><?= $row['tppersentase']; ?></td>
                                                <td> <?= formatRupiah(round($row['nominal'])); ?> </td>
                                                <td><?= $row['metode_pembayaran']; ?></td>
                                                <td>
                                                    <?php
                                                    $id_tagihan = $row['id_tagihan'];

                                                    if ($row['status_tagihan'] == '5') {
                                                        echo "<span class='label label-success'>Terbayar</span>";
                                                    } else {
                                                        if ($row['metode_pembayaran'] == 'Transfer') {


                                                            $dataBkk =  mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tagihan_po tp
                                                                            JOIN bkk_ke_pusat bp
                                                                            ON tp.bkk_id = bp.id
                                                                            WHERE tp.id_tagihan = '$id_tagihan'  "));

                                                            if ($dataBkk['status_bkk'] == '0') {
                                                                echo "<span class='label label-primary'>Verifikasi Pajak</span>";
                                                            } else if ($dataBkk['status_bkk'] == '1') {
                                                                echo "<span class='label label-primary'>Verifikasi GM Finance</span>";
                                                            } else if ($dataBkk['status_bkk'] == '2') {
                                                                echo "<span class='label label-primary'>Verifikasi Direktur</span>";
                                                            } else  if ($dataBkk['status_bkk'] == '17') {
                                                                echo "<span class='label label-warning'>Outstanding Cek Kasir JKT</span>";
                                                            }
                                                        } else {
                                                            $dataBkk =  mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tagihan_po tp
                                                            JOIN bkk_final bp
                                                            ON tp.bkk_id = bp.id
                                                            WHERE tp.id_tagihan = '$id_tagihan'  "));

                                                            if ($dataBkk['status_bkk'] == '0') {
                                                                echo "<span class='label label-primary'>Verifikasi Pajak</span>";
                                                            } else if ($dataBkk['status_bkk'] == '1') {
                                                                echo "<span class='label label-primary'>Verifikasi Cost Control</span>";
                                                            } else if ($dataBkk['status_bkk'] == '2') {
                                                                echo "<span class='label label-primary'>Verifikasi Manager</span>";
                                                            } else  if ($dataBkk['status_bkk'] == '17') {
                                                                echo "<button type='button' class='btn btn-warning modalSubmit' data-toggle='modal' data-target='#submit_" . $dataBkk['id_tagihan'] . "' data-id='" . $row['id'] . "'><i class='fa fa-credit-card'></i> Payment</button>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#invoice_<?= $row['id_tagihan']; ?>"><i class="fa fa-folder-open" title="Invoice" data-toggle="tooltip"></i></button></td>
                                            </tr>

                                            <!-- Modal Payment  -->
                                            <div id="submit_<?= $dataBkk['id_tagihan'] ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title"> Submit Invoice </h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <div class="modal-body">
                                                            <form method="post" enctype="multipart/form-data" action="payment_invoice_po.php" class="form-horizontal">
                                                                <!-- <input type="text" name="id_tagihan" value="<?= $dataBkk['id_tagihan'] ?>"> -->
                                                                <input type="hidden" name="id" value="<?= $dataBkk['id'] ?>">
                                                                <!-- <input type="text" name="id_bkk" value="<?= $dataBkk['regulasi_tempo'] ?>"> -->
                                                                <!-- <input type="text" name="id_tagihan" id="me_id_tagihan"> -->
                                                                <div class="box-body">
                                                                    <div class="form-group">
                                                                        <label for="tanggal" class="col-sm-offset- col-sm-4 control-label">Tanggal</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" required class="form-control tanggal" name="tanggal" autocomplete="off" required>
                                                                        </div>
                                                                    </div>
                                                                    <br><br>
                                                                    <div class="form-group ">
                                                                        <label for="doc_faktur" class="col-sm-offset- col-sm-4 control-label">Invoice / Faktur</label>
                                                                        <div class="col-sm-6">
                                                                            <!-- <div class="input-group input-file" name="doc_faktur" required> -->
                                                                            <input type="file" class="form-control" required name="doc_faktur" required />
                                                                            <!-- <span class="input-group-btn">
                                                                                    <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                                                </span>
                                                                            </div> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-primary" type="submit" name="payment">Submit</button></span>
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal Payment  -->

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

                                    <?php

                                            $persent += $row['persentase'];
                                            $no++;
                                        endwhile;
                                    }
                                    if ($jumlahData == 0) {
                                        echo "<tr>
                                                <td style='text-align: center;' colspan='6'> Belum ada pembayaran</td>
                                            </tr>";
                                    }

                                    $sisaPembayaran = $data2['grand_totalpo'] * ($persent / 100);
                                    $sisaPersen = 100 - $persent;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- verifikasi term payment -->
                        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#konfirmasi">Verifikasi Term Payment</button></span></a> -->
                    </div>
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
                    <div class="box-header with-border">
                        <h3 class="text-center">Document Pendukung</h3>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/foto/<?= $data2['foto_item']; ?>"></iframe>
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
                <!--  -->
                <!-- Embed Document -->
                <!-- <div class="box-header with-border">
                    <h3 class="text-center">Document Quatation</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_quotation/<?php echo $data2['doc_quotation']; ?> "></iframe>
                    </div>
                </div> -->
                <br>
                <!--  -->
                <div class="col-sm-offset-11">

                    <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Reject To Purchasing</button></span></a> -->
                    <!-- <a target="_blank" href="cetak_po.php?id=<?= $id; ?>" class="btn btn-success"><i class="fa fa-print"></i> PO </a>                                                                                                 -->
                </div>
                <!--  -->
                <br>
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
    });

    // Browse
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

    $(".perhitungan").keyup(function() {
        var persen = parseInt($("#persentase_pembayaran").val())

        var grand_totalpo = "<?php print($data2['grand_totalpo']); ?>";

        var nominal_pembayaran = Math.floor((persen / 100) * grand_totalpo);
        var nominal_pembayarana = tandaPemisahTitik(nominal_pembayaran);

        document.form.nominal_pembayaran1.value = nominal_pembayarana;

    });
</script>