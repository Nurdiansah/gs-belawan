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
                                            JOIN supplier s
                                            ON dbo.id_supplier = s.id_supplier
                                            JOIN pph pp
                                            ON p.id_pph = pp.id_pph
                                            WHERE p.id_po ='$id' ");
$data2 = mysqli_fetch_assoc($query);

$id_supplier = $data2['id_supplier'];
$id_anggaran = $data2['id_anggaran'];
$totalPengajuan = $data2['total_po'];
$sisaPembayaran = $data2['grand_totalpo'] - $data2['nominal_pembayaran1'];

$id_dbo = $data2['id_dbo'];

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d H:i:s");


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=list_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>
                <!--  -->
                <div class="col-sm-offset-10">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#konfirmasi">Create BKK</button></span></a>
                    <a target="_blank" href="cetak_po.php?id=<?= $id; ?>" class="btn btn-success"><i class="fa fa-print"></i> PO </a>
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
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Grand Total</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['grand_totalpo']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= formatTanggal($data2['tgl_pengajuan']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Pembayaran 1</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['nominal_pembayaran1']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">PO Number</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['po_number']; ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Sisa Pembayaran</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($sisaPembayaran); ?>">
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
                <div class="box-header with-border">
                    <h3 class="text-center">Rincian Barang</h3>
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
                                            <td><?= formatRupiah($row['total_price']); ?></td>
                                </tr>
                        <?php
                                            $total += $row['total_price'];
                                            $no++;
                                        endwhile;
                                    } ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Sub Total</b></td>
                            <td><b> <?= formatRupiah($data2['sub_totalpo']); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Diskon </b></td>
                            <td><b> <?= formatRupiah($data2['diskon_po']); ?></b></td>
                        </tr>
                        <?php
                        $total = $data2['sub_totalpo'] - $data2['diskon_po'];
                        $grandTotal = $total + $data2['nilai_ppn'];
                        ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Total </b></td>
                            <td><b> <?= formatRupiah($data2['total_po']); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b> PPN 10% </b></td>
                            <td><b> <?= formatRupiah($data2['nilai_ppn']); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b> Nilai <?= $data2['nm_pph']; ?> </b></td>
                            <td><b> (<?= formatRupiah($data2['nilai_pph']); ?>)</b></td>
                        </tr>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b> Grand Total </b></td>
                            <td><b> <?= formatRupiah($data2['grand_totalpo']); ?></b></td>
                        </tr>
                            </tbody>
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
                    <h3 class="text-center">Foto Barang</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-offset-2">
                            <img src="../file/foto/<?= $data2['foto_item']; ?>" width="80%" alt="...">
                            <!-- <h5 class="text-center">Tidak Ada Foto</h5> -->
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
                <div class="box-header with-border">
                    <h3 class="text-center">Document Quatation</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_quotation/<?php echo $data2['doc_quotation']; ?> "></iframe>
                    </div>
                </div>
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
                    <h4 class="modal-title">Create BKK</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <div class="perhitungan">
                        <form method="post" name="form" enctype="multipart/form-data" action="create_bkkpo.php" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" value="<?= $data2['id_po']; ?>" class="form-control" name="id_po" readonly>
                                <input type="hidden" value="<?= $data2['id_anggaran']; ?>" class="form-control" name="id_anggaran" readonly>
                                <input type="hidden" value="<?= $data2['id_supplier']; ?>" class="form-control" name="id_supplier" readonly>
                                <input type="hidden" value="<?= $data2['nilai_barang']; ?>" class="form-control" name="nilai_barang" readonly>
                                <input type="hidden" value="<?= $data2['nilai_jasa']; ?>" class="form-control" name="nilai_jasa" readonly>
                                <input type="hidden" value="<?= $data2['nilai_ppn']; ?>" class="form-control" name="nilai_ppn" readonly>
                                <input type="hidden" value="<?= $data2['id_pph']; ?>" class="form-control" name="id_pph" readonly>
                                <input type="hidden" value="<?= $data2['nilai_pph']; ?>" class="form-control" name="nilai_pph" readonly>
                                <input type="hidden" value="<?= $data2['persentase_pembayaran2']; ?>" class="form-control" name="persen" readonly>

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
                                            <input type="text" class="form-control " name="nominal" value="<?= formatRupiah2($data2['nominal_pembayaran2']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="validationTextarea">Redaksi : </label>
                                    <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Redaksi BKK"></textarea>
                                </div>
                                <div class=" modal-footer">
                                    <button class="btn btn-success" type="submit" name="submit">Create</button></span></a>
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
</script>