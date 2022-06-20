<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = dekripRambo($_GET['id']);
$id_tagihan = dekripRambo($_GET['id_tagihan']);
$bkk = dekripRambo($_GET['bkk']);


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

$queryCek = mysqli_query($koneksi, "SELECT * FROM tagihan_po WHERE id_tagihan = '$id_tagihan'");
$dataCek = mysqli_fetch_assoc($queryCek);
$metode_pembayaran = $dataCek['metode_pembayaran'];

if ($metode_pembayaran == 'Transfer') {
    $tableBkk = 'bkk_ke_pusat';
} else {
    $tableBkk = 'bkk_final';
}



$query =  mysqli_query($koneksi, "SELECT *, bf.nilai_barang as n_barang, bf.nilai_jasa as n_jasa, bf.nilai_ppn as n_ppn, bf.id_pph as bf_id_pph, bf.nilai_pph as n_pph
                                    FROM tagihan_po  tp
                                    JOIN po p
                                        ON p.id_po = tp.po_id
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN biaya_ops bo
                                        ON bo.kd_transaksi = dbo.kd_transaksi
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi 
                                    JOIN $tableBkk bf
                                        ON bf.id = tp.bkk_id
                                    LEFT JOIN pph ph
                                        ON ph.id_pph = bf.id_pph                                    
                                    WHERE p.id_po = '$id' AND tp.id_tagihan = '$id_tagihan'
                                    ");

$data2 = mysqli_fetch_assoc($query);


$id_dbo = $data2['id_dbo'];


$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");

$queryReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_po WHERE po_id = '$id'");
$dataReap = mysqli_fetch_assoc($queryReapp);
$totalReapp = mysqli_num_rows($queryReapp);

// echo die($data2['id_kdtransaksi']);
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
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=list_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Detail Invoice PO</h3>
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
                        <?php if (isset($dataReap['alasan_reapprove_mgrga']) != NULL) { ?>
                            <div class="form-group">
                                <label for="alasan_reapprove" class="col-sm-offset- col-sm-1 control-label">Alasan Setuju Kembali</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "> <?= $dataReap['alasan_reapprove_mgrga']; ?></textarea>
                                </div>
                                <label for="waktu_reapprove" class="col-sm-offset-2 col-sm-3 control-label">Waktu Setuju Kembali</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "> <?= $dataReap['waktu_reapprove_mgrga']; ?></textarea>
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
                                            <td>Rp. <?= number_format($data2['grand_totalpo'], 0, ",", "."); ?> </td>
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
                                            $no++;
                                        endwhile;
                                    } ?>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Sub Total</b></td>
                            <td style="text-align: right;"><b> <?= formatRupiah($data2['sub_totalpo']); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Diskon </b></td>
                            <td style="text-align: right;"><b> <?= formatRupiah($data2['diskon_po']); ?></b></td>
                        </tr>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b>Total </b></td>
                            <td style="text-align: right;"><b> <?= formatRupiah($data2['total_po']); ?></b></td>
                        </tr>
                        <?php
                        $total = $data2['sub_totalpo'] - $data2['diskon_po'];
                        $grandTotal = $total + $data2['nilai_ppn'];
                        ?>
                        <tr>
                            <td colspan="5"><b> PPN 10% </b></td>
                            <td style="text-align: right;"><b> <?= formatRupiah($data2['nilai_ppn']); ?></b></td>
                        </tr>
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="5"><b> Grand Total </b></td>
                            <td style="text-align: right;"><b> <?= formatRupiah($data2['grand_totalpo']); ?></b></td>
                        </tr>

                            </tbody>
                    </table>
                </div>

                <?php
                $queryTagihan = mysqli_query($koneksi, "SELECT * FROM tagihan_po tp
                                                                            JOIN po po
                                                                                ON id_po = po_id
                                                                            WHERE id_po = '$id'
                                                        ");

                $no = 1;

                if (mysqli_num_rows($queryTagihan) > 0) {
                ?>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="box box-danger">
                                <h3 class="text-center">Riwayat Tagihan</h3>
                                <table class="table text-center table table-striped table-dark table-hover ">
                                    <thead style="background-color :#B0C4DE;">
                                        <tr>
                                            <th>No</th>
                                            <th>Tgl Invoice</th>
                                            <th>Tgl Tempo</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($dataTagihan = mysqli_fetch_assoc($queryTagihan)) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= formatTanggal($dataTagihan['tgl_buat']); ?></td>
                                                <td><?= formatTanggal($dataTagihan['tgl_tempo']); ?></td>
                                                <td><?= formatRupiah($dataTagihan['nominal']); ?></td>
                                                <td>
                                                    <?php
                                                    if ($dataTagihan['status_tagihan'] < 4) {
                                                        echo "<button class='btn btn-warning'>Belum di bayar</button>";
                                                    } else if ($dataTagihan['status_tagihan'] == 5) {
                                                        echo "<button class='btn btn-success'>Terbayar</button>";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php $no++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <br>
                <!-- doc penawaran dan form verifikasi pajak -->
                <div class="row">
                    <div class="col-sm-6 col-xs-12">
                        <div class="box box-primary">
                            <h3 class="text-center">Invoice/Faktur</h3>
                            <div class="embed-responsive embed-responsive-4by3">
                                <?php if (!is_null($data2['doc_faktur']) || !file_exists("../file/invoice/" . $data2['doc_faktur'] . "")) { ?>
                                    <iframe class="embed-responsive-item" src="../file/invoice/<?= $data2['doc_faktur']; ?>"></iframe>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="box box-primary">
                            <!-- VERIFIKASI TAX -->
                            <div class="perhitungan">
                                <div class="box-header with-border">
                                    <h3 class="text-center">Verifikasi Tax</h3>
                                </div>
                                <form method="post" name="form" action="vrf_po.php" enctype="multipart/form-data" class="form-horizontal">
                                    <input type="hidden" required class="form-control is-valid" name="id_po" value="<?= $data2['id_po']; ?>">
                                    <input type="hidden" value="<?= $data2['id_tagihan']; ?>" name="id_tagihan" readonly>
                                    <input type="hidden" value="<?= $data2['metode_pembayaran']; ?>" name="metode_pembayaran" readonly>
                                    <input type="hidden" required class="form-control is-valid" name="id_bkk" value="<?= $data2['id']; ?>">
                                    <div class="form-group">
                                        <label id="tes" for="nilai_bkk" class=" col-sm-4 control-label" id="rupiah">Nilai Barang</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" name="nilai_barang" id="nilai_barang" value="<?= round($data2['n_barang']); ?>" />
                                            </div>
                                            <i><span id="nb_ui"></span></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="nilai_bkk" class=" col-sm-4 control-label" id="rupiah">Nilai Jasa</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" value="<?= round($data2['nilai_jasa']); ?>" name="nilai_jasa" id="nilai_jasa" />
                                            </div>
                                            <i><span id="nj_ui"></span></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN
                                            <select name="pilih_ppn" id="setppn">
                                                <option value="0.11">11%</option>
                                                <option value="0.1">10%</option>
                                            </select>
                                        </label>
                                        <div class="col-sm-1">
                                            <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control " name="ppn_nilai" id="ppn_nilai" value="<?= formatRibuan($data2['nilai_ppn']) ?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bgn-pembulatan">
                                        <div class="form-group">
                                            <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Pembulatan</label>
                                            <div class="col-sm-3">
                                                <input type="radio" name="pembulatan" value="keatas" id="pembulatan" onclick="checkPembulatan()"> Ke atas
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="radio" name="pembulatan" value="kebawah" id="pembulatan" onclick="checkPembulatan()" checked="checked"> Ke bawah
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="biaya_lain" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Biaya Lain</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" value="<?= round($data2['biaya_lain']) ?>" name="biaya_lain" id="biaya_lain" autocomplete="off" />
                                            </div>
                                            <i><span id="bl_ui"></span></i></br>
                                            <i><span class="text-danger">*Biaya Materai/lain</span></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                        <div class="col-sm-5">
                                            <select name="id_pph" class="form-control" id="id_pph" value="<?= $data2['bf_id_pph'] ?>">
                                                <option value="">--Jenis PPh--</option>
                                                <?php
                                                $queryPph = mysqli_query($koneksi, "SELECT * FROM pph ORDER BY nm_pph ASC");
                                                if (mysqli_num_rows($queryPph)) {
                                                    while ($rowPph = mysqli_fetch_assoc($queryPph)) :
                                                ?>
                                                        <option value="<?= $rowPph['id_pph']; ?>" data-id="<?= $rowPph['jenis']; ?>" type="checkbox"><?= $rowPph['nm_pph'] ?></option>
                                                <?php endwhile;
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="fixed">
                                        <div class="form-group">
                                            <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">PPh</span>
                                                    <input type="text" required class="form-control " name="pph_persen" value="0" id="pph_persen" />
                                                    <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" readonly class="form-control " name="pph_nilai" value="<?= formatRibuan($data2['n_pph']) ?>" id="pph_nilai" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="progresive">
                                        <div class="form-group">
                                            <label id="tes" for="pph_nilai2" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" class="form-control " name="pph_nilai2" value="<?= round($data2['n_pph']) ?>" id="pph_nilai2" />
                                                </div>
                                                <i><span id="pph_ui"></span></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="jml_bkk" class=" col-sm-4 control-label">Jumlah</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" name="jml" value="" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <div class="form-group">
                                            <button type="submit" name="simpan" class="btn btn-primary col-sm-offset-4"><i class="fa fa-save"></i> Simpan</button>
                                            &nbsp;
                                            <button type="submit" name="submit" class="btn btn-warning "><i class="fa fa-rocket"></i> Submit</button>
                                            &nbsp;
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Reject </button></span></a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end doc penawaran dan form verifikasi pajak -->
                <br>
                <!-- doc pendukung dan doc quotation -->
                <div class="row ">
                    <div class="col-sm-6 col-xs-12">
                        <!-- Embed Document -->
                        <h3 class="text-center">Document Penawaran</h3>
                        <div class="embed-responsive embed-responsive-4by3">
                            <?php if (!is_null($data2['doc_penawaran']) || !file_exists("../file/doc_penawaran/" . $data2['doc_penawaran'] . "")) { ?>
                                <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?php echo $data2['doc_penawaran']; ?> "></iframe>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <h3 class="text-center">Document Quotation</h3>
                        <div class="embed-responsive embed-responsive-4by3">
                            <?php if (!is_null($data2['doc_quotation']) || !file_exists("../file/doc_quotation/" . $data2['doc_quotation'] . "")) { ?>
                                <iframe class="embed-responsive-item" src="../file/doc_quotation/<?php echo $data2['doc_quotation']; ?> "></iframe>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- end doc pendukung dan doc quotation -->

                <br>


                <br>
            </div>
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
                    <h4 class="modal-title">Alasan Penolakan</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="tolak_po.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['id_po']; ?>" class="form-control" name="id_po" readonly>
                                    <input type="hidden" value="<?= $data2['id_tagihan']; ?>" name="id_tagihan" readonly>
                                    <input type="hidden" value="<?= $bkk; ?>" class="form-control" name="id_bkk" readonly>
                                    <input type="hidden" value="verifikasi_po" class="form-control" name="url" readonly>
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

    // cek apakah ada nilai ppn
    var np = <?= $data2['nilai_ppn'] ?>;

    if (np > 0) {
        $('#myCheck').attr('checked', 'checked');
        $("#bgn-pembulatan").show();
    } else {
        $("#bgn-pembulatan").hide();
    }

    // cek apa kah ada nilai barang
    var nilaiBarang = <?= $data2['nilai_barang'] ?>;

    if (nilaiBarang > 0) {
        var nb_ui = tandaPemisahTitik(nilaiBarang);
        $('#nb_ui').text('Rp.' + nb_ui);
    }

    // cek apa kah ada nilai jasa
    var nilaiJasa = <?= $data2['nilai_jasa'] ?>;

    if (nilaiJasa > 0) {
        var nb_ui = tandaPemisahTitik(nilaiJasa);
        $('#nj_ui').text('Rp.' + nb_ui);
    }
    // Cek PPH
    var id_pph = '<?= $data2['id_pph']; ?>';
    var jenis = '<?= $data2['jenis']; ?>';
    let dpp = nilaiBarang + nilaiJasa;

    let persentasePpn = np / dpp;

    // set ppn default 11%
    let setPpn = 0.11;
    if (np != 0 && dpp != 0) {

        $('#setppn').val(persentasePpn);

        setPpn = persentasePpn;
    }

    // jika ada perubahan ppn
    $('#setppn').on('change', function() {
        let ppnTemp = parseFloat(this.value);

        if (setPpn != ppnTemp) {
            setPpn = ppnTemp;
            // cek terlebih dahulu apakah checkbox nya ini aktif
            checkBox();

        }

    });



    $("#id_pph").val(id_pph);

    showPph(jenis);
    // $("#ktk").hide();

    $('#id_pph').on('change', function() {
        let id_pph = this.value;
        let jenis = $(this).find(':selected').data('id')

        showPph(jenis);

    });


    $(".perhitungan").keyup(function() {

        var nilaiJasa = parseInt($("#nilai_jasa").val())


        var nj_ui = tandaPemisahTitik(nilaiJasa);
        $('#nj_ui').text('Rp.' + nj_ui);

        var pph_persen = parseInt($("#pph_persen").val())
        var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);

        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;


        var nilaiBarang = parseInt($("#nilai_barang").val())
        var nb_ui = tandaPemisahTitik(nilaiBarang);
        $('#nb_ui').text('Rp.' + nb_ui);

        // Biaya lain
        var biayaLain = parseInt($("#biaya_lain").val())
        var bl_ui = tandaPemisahTitik(biayaLain);
        $('#bl_ui').text('Rp.' + bl_ui);

        // nilai pph untuk pajak progresive
        var pph_nilai2 = parseInt($("#pph_nilai2").val())
        var pph_ui = tandaPemisahTitik(pph_nilai2);
        $('#pph_ui').text('Rp.' + pph_ui);

        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {
            var ppn_nilai = Math.floor(0.11 * (nilaiBarang + nilaiJasa));
        } else if (checkBox.checked == false) {
            var ppn_nilai = 0;
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        document.form.ppn_nilai.value = ppn_nilaia;

        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2 + biayaLain;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);

        document.form.jml.value = jml;


    });

    function hilangkanTitik(data) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value))))); //input ke dalam angka tanpa titik

        return angka;
    }

    function showPph(data) {

        var nilai_barang = parseInt($("#nilai_barang").val())
        var nilai_jasa = hilangkanTitik('nilai_jasa')
        var ppn_nilai = hilangkanTitik('ppn_nilai')
        var biaya_lain = hilangkanTitik('biaya_lain')


        // var jml = hilangkanTitik('jml')
        var pph_nilai = hilangkanTitik('pph_nilai')

        // pph nilai 2 untuk tarif progresive
        var pph_nilai2 = hilangkanTitik('pph_nilai2')


        if (data == 'fixed') {
            $("#fixed").show();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + ppn_nilai + biaya_lain) - pph_nilai;


            jml = tandaPemisahTitik(jml);



            document.form.pph_nilai2.value = 0;
            document.form.jml.value = jml;

            if (pph_nilai > 0) {
                var persen = (pph_nilai / nilai_jasa) * 100;

                document.form.pph_persen.value = persen;
            }

        } else if (data == 'progresive') {
            $("#fixed").hide();
            $("#progresive").show();

            var jml = (nilai_barang + nilai_jasa + ppn_nilai + biaya_lain) - pph_nilai2;
            jml = tandaPemisahTitik(jml);

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
            document.form.jml.value = jml;
        } else {
            $("#fixed").hide();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + ppn_nilai + biaya_lain);
            jml = tandaPemisahTitik(jml);

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
            document.form.pph_nilai2.value = 0;
            document.form.jml.value = jml;
        }
    }

    function hitungCheckBox(angkaPpn) {
        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var pph_persen = parseInt($("#pph_persen").val())
        var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);
        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;


        var nilaiBarang = parseInt($("#nilai_barang").val())
        var biayaLain = parseInt($("#biaya_lain").val())

        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {
            var ppn_nilai = Math.floor(angkaPpn * (nilaiBarang + nilaiJasa));
        } else if (checkBox.checked == false) {
            var ppn_nilai = 0;
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        var pph_nilai2 = parseInt($("#pph_nilai2").val())

        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2 + biayaLain;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);
        document.form.jml.value = jml;
    }

    // function checkBox() {
    //     var checkBox = document.getElementById("myCheck");
    //     if (checkBox.checked == true) {

    //         $("#bgn-pembulatan").show();

    //         // $("#pembulatan").val('kebawah');

    //         var nilaiJasa = parseInt($("#nilai_jasa").val())
    //         var pph_persen = parseInt($("#pph_persen").val())
    //         var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);
    //         var pph_nilaia = tandaPemisahTitik(pph_nilai);
    //         $("#pph").attr("value", pph_nilaia);
    //         document.form.pph_nilai.value = pph_nilaia;


    //         var nilaiBarang = parseInt($("#nilai_barang").val())
    //         var biayaLain = parseInt($("#biaya_lain").val())

    //         var ppn_nilai = Math.floor(0.11 * (nilaiBarang + nilaiJasa));
    //         var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
    //         $("#ppn").attr("value", ppn_nilaia);
    //         document.form.ppn_nilai.value = ppn_nilaia;

    //         var pph_nilai2 = parseInt($("#pph_nilai2").val())

    //         var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2 + biayaLain;
    //         var jml = tandaPemisahTitik(jmla);
    //         $("#jml").attr("value", jml);
    //         document.form.jml.value = jml;

    //     } else if (checkBox.checked == false) {

    //         $("#bgn-pembulatan").hide();

    //         var nilaiJasa = parseInt($("#nilai_jasa").val())
    //         var pph_persen = parseInt($("#pph_persen").val())
    //         var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);
    //         var pph_nilaia = tandaPemisahTitik(pph_nilai);
    //         $("#pph").attr("value", pph_nilaia);
    //         document.form.pph_nilai.value = pph_nilaia;


    //         var nilaiBarang = parseInt($("#nilai_barang").val())
    //         var biayaLain = parseInt($("#biaya_lain").val())

    //         var ppn_nilai = 0;
    //         var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
    //         $("#ppn").attr("value", ppn_nilaia);
    //         document.form.ppn_nilai.value = ppn_nilaia;

    //         var pph_nilai2 = parseInt($("#pph_nilai2").val())

    //         var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2 + biayaLain;
    //         var jml = tandaPemisahTitik(jmla);
    //         $("#jml").attr("value", jml);
    //         document.form.jml.value = jml;
    //     }
    // }

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            $("#bgn-pembulatan").show();

            hitungCheckBox(setPpn);

        } else if (checkBox.checked == false) {

            $("#bgn-pembulatan").hide();

            hitungCheckBox(setPpn);
        }
    }

    // check pembulatan
    function checkPembulatan() {

        var pembulatan = $("input[name='pembulatan']:checked").val();

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nilaiBarang = parseInt($("#nilai_barang").val())

        if (pembulatan == 'keatas') {

            // pembulatan ke atas
            var ppn_nilai = Math.ceil(0.11 * (nilaiBarang + nilaiJasa));

        } else if (pembulatan == 'kebawah') {

            // pembulatan ke bawah
            var ppn_nilai = Math.floor(0.11 * (nilaiBarang + nilaiJasa));
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;
    }
</script>