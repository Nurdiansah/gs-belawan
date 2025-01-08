<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsianggaran.php";

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
                                            LEFT JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            LEFT JOIN po p
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            LEFT JOIN detail_biayaops dbo
                                            ON p.id_dbo = dbo.id
                                            LEFT JOIN anggaran a
                                            ON dbo.id_anggaran = a.id_anggaran
                                            LEFT JOIN pph pp
                                            ON p.id_pph = pp.id_pph
                                            WHERE p.id_po ='$id' ");
$data2 = mysqli_fetch_assoc($query);

$id_supplier = $data2['id_supplier'];
$id_anggaran = $data2['id_anggaran'];
$totalPengajuan = $data2['grand_totalpo'];

$id_dbo = $data2['id_dbo'];
$id_divisi = $data2['id_divisi'];
$tahun = date("Y");

// print_r($data2['foto_item']);

// print_r($id_dbo);

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");

// Data Anggaran
$rowAnggaran = dataAnggaran($id_anggaran);
$no_coa = $rowAnggaran['no_coa'];

// total anggaran yang ada di anggaran
$rowTotal = totalAnggaranCoaDivisi($id_divisi, $no_coa, $tahun);
$totalAnggaran = $rowTotal['total_anggaran'];
$nama_coa =  $rowTotal['nama_coa'];

// realisasi anggaran
$totalRealisasi = realisasiCoaDivisi($id_divisi, $no_coa, $tahun);

$queryReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_po WHERE po_id = '$id'");
$dataReapp = mysqli_fetch_assoc($queryReapp);
$totalReapp = mysqli_num_rows($queryReapp);
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
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">PO Number</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['po_number']; ?>">
                            </div>
                        </div>
                        <?php if (isset($dataReapp['alasan_reapprove_mgrfin']) != NULL) { ?>
                            <div class="form-group">
                                <label for="alasan_reapprove" class="col-sm-offset- col-sm-1 control-label">Alasan Setuju Kembali</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "> <?= $dataReapp['alasan_reapprove_mgrfin']; ?></textarea>
                                </div>
                                <label for="waktu_reapprove" class="col-sm-offset-2 col-sm-3 control-label">Waktu Setuju Kembali</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "> <?= $dataReapp['waktu_reapprove_mgrfin']; ?></textarea>
                                </div>
                            </div>
                        <?php } ?>
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
                                            <td><b>Rp. <?= number_format($data2['grand_totalpo'], 0, ",", "."); ?></b></td>
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
                            <td colspan="5"><b> PPN </b></td>
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
                            <!-- <img src="../file/foto/<?= $data2['foto_item']; ?>" width="80%" alt="..."> -->
                            <!-- <h5 class="text-center">Tidak Ada Foto</h5> -->
                        </div>
                    </div>
                    <div class="embed-responsive embed-responsive-16by9">
                        <!-- <iframe class="embed-responsive-item" src="../file/pdfjs/web/viewer.html?file=../../doc_penawaran/<php echo $data2['doc_penawaran']; ?> "></iframe> -->
                        <iframe class="embed-responsive-item" src="../file/pdfjs/web/viewer.html?file=../../foto/<?= $data2['foto_item']; ?>"></iframe>
                    </div>
                <?php } ?>
                <br>
                <!-- Embed Document -->
                <!-- DOCUMENT QUOTATION DI HIDE SEMENTARA -->
                <!-- <div class="box-header with-border">
                    <h3 class="text-center">Document Quotation</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/pdfjs/web/viewer.html?file=../../doc_quotation/<?php echo $data2['doc_quotation']; ?> "></iframe>
                    </div>
                </div> -->
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Document Penawaran</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/pdfjs/web/viewer.html?file=../../doc_penawaran/<?php echo $data2['doc_penawaran']; ?> "></iframe>
                    </div>
                </div>
                <br>
                <!--  -->

                <!--  -->
            </div>
            <br>
            <?php
            // pengajuan di bandingkan dengan total Anggaran divisi
            $selisihAnggaran = round(@($totalPengajuan / $totalAnggaran * 100), 0);
            $selisihRealisasi = round(@($totalRealisasi / $totalAnggaran * 100), 0);
            $persentaseProgress = $selisihRealisasi + $selisihAnggaran;

            $sisaBudget = $totalAnggaran - ($totalRealisasi + $totalPengajuan);
            $persentaseSisaBudget = round(@($sisaBudget / $totalAnggaran * 100), 0);


            ?>
            <div class="box-header with-border">
                <!-- <div class="form-group">   -->
                <h4 class="text-left"><b>Total Budget <?= '<font color="red">' . $nama_coa . '</font>' . ' Setahun : ' . formatRupiah($totalAnggaran); ?> &nbsp;</b></b></h4>
                <div class="col-sm-offset-1 col-sm-9">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success" style="width: <?= $selisihRealisasi; ?>%">
                            <!-- <span><?= $selisihRealisasi; ?> %</span> -->
                        </div>
                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: <?= $selisihAnggaran; ?>%">
                            <!-- <span ><b><?= "  (" . $selisihAnggaran . "%)"; ?></b></span> -->
                        </div>
                        <label for=""> &nbsp;<b>(<?= $persentaseProgress ?> %)</label>
                    </div>
                </div>
                <!-- </div>                                                 -->
                <div class="col-sm-offset-1 col-sm-3 ">
                    <button type="button" class="btn btn-success"></button> <b> (<?= $selisihRealisasi ?> %)</b>
                    <h5><b>Realisasi : <?= 'Rp. ' . number_format($totalRealisasi, 0, ",", ".") ?> </b></h5>
                </div>
                <div class="col-sm-offset-1 col-sm-3">
                    <button type="button" class="btn btn-primary"></button> <b> (<?= $selisihAnggaran ?> %)</b>
                    <h5><b> Pengajuan : <?= 'Rp. ' . number_format($totalPengajuan, 0, ",", ".") ?> </b></h5>
                </div>
                <div class="col-sm-offset-1 col-sm-3">
                    <button type="button" class="btn btn-dark" style="background-color :#708090;"></button> <b> (<?= $persentaseSisaBudget ?> %)</b>
                    <h5><b> Sisa Budget : <?= 'Rp. ' . number_format($sisaBudget, 0, ",", ".") ?> </b></h5>
                </div>
            </div>
        </div>
        <br>
        <div class="form-group ">
            <button type="button" class="btn btn-danger col-sm-offset-10" data-toggle="modal" data-target="#tolak">Reject </button></span></a>
            &nbsp;
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approve"> Approve </button></span></a>
        </div>
        <br>
    </div>
    </div>
    </div>

    <!--  -->
    <div id="approve" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Konfirmasi </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <!-- <form method="post" enctype="multipart/form-data" action="setuju_kasbon.php" class="form-horizontal"> -->
                    <div class="box-body">
                        <h4 class="text-center">Apakah anda yakin ingin menyetujui ?</h4>
                        <br>
                        <div class=" modal-footer">
                            <a href="setuju_po.php?id=<?= $data2['id_po']; ?>"><span data-placement='top' data-toggle='tooltip' title='Approve'><button class="btn btn-primary">Yes</button></span></a>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                        </div>
                    </div>
                    <!-- </form>  -->
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
                    <form method="post" enctype="multipart/form-data" action="tolakdirektur_po.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['id_po']; ?>" class="form-control" name="id_po" readonly>
                                    <input type="hidden" value="<?= $Nama; ?>" class="form-control" name="Nama" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="validationTextarea">Komentar</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required></textarea>
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
</script>