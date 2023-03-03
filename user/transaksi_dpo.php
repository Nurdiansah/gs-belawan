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
                                            LEFT  JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                           LEFT  JOIN supplier s
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
                                            LEFT JOIN pph pp
                                            ON p.id_pph = pp.id_pph
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
                    <!-- <div class="row "> -->
                    <!-- <div class="col-sm-offset-2"> -->
                    <!-- <img src="../file/foto/<?= $data2['foto_item']; ?>" width="80%" alt="..."> -->
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/foto/<?php echo $data2['foto_item']; ?> "></iframe>
                    </div>
                    <!-- <h5 class="text-center">Tidak Ada Foto</h5> -->
                    <!-- </div> -->
                    <!-- </div> -->
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
            <!--  -->
            <div class="col-sm-offset-11">
                <a target="_blank" href="cetak_po.php?id=<?= $id; ?>" class="btn btn-success"><i class="fa fa-print"></i> PO </a>
            </div>
            <!--  -->
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
                    <h4 class="modal-title">Verifikasi PO</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <div class="perhitungan">
                        <form method="post" name="form" enctype="multipart/form-data" action="add_po.php" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" value="<?= $row2['kd_transaksi']; ?>" disabled class="form-control" name="kd_transaksi">
                                <div class="form-group ">
                                    <div class="col-sm-4">
                                        <input type="hidden" value="<?= $data2['id_po']; ?>" class="form-control" name="id_po" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="dari_bank" class="col-sm-5 control-label">Regulasi Jatuh Tempo</label>
                                    <div class="col-sm-4">
                                        <select name="tgl_tempo1" class="form-control">
                                            <option value="7">1 - 7 Hari </option>
                                            <option value="14">1 - 14 Hari </option>
                                            <option value="30">1 - 30 Hari </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="nocek_bkk" class="col-sm-5 control-label">Total PO</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control " name="nocek_bkk" value="<?= formatRupiah($data2['grand_totalpo']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="persentase_pembayaran1" class="col-sm-5 control-label">Pembayaran</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <input type="number" class="form-control " min="0" max="100" placeholder="100" name="persentase_pembayaran1" id="persentase_pembayaran" value="100" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label id="tes" for="persentase_pembayaran" class="col-sm-5 control-label">Nominal Pembayaran</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control " name="nominal_pembayaran1" id="nominal_pembayaran1" value="<?= formatRupiah2($data2['grand_totalpo']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class=" modal-footer">
                                    <button class="btn btn-success" type="submit" name="submit">Submit</button></span></a>
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

    <!--  -->
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