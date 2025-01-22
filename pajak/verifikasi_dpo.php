<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = dekripRambo($_GET['id']);
$id_tagihan = dekripRambo($_GET['id_tagihan']);
$bkk = dekripRambo($_GET['bkk']);


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

$queryCek = mysqli_query($koneksi, "SELECT * FROM tagihan_po WHERE id_tagihan = '$id_tagihan'");
$dataCek = mysqli_fetch_assoc($queryCek);
$metode_pembayaran = $dataCek['metode_pembayaran'];

if ($metode_pembayaran == 'Transfer') {
    $tableBkk = 'bkk_ke_pusat';
} else {
    $tableBkk = 'bkk_final';
}

$query =  mysqli_query($koneksi, "SELECT *, bf.nilai_barang as n_barang, bf.nilai_jasa as n_jasa, bf.nilai_ppn as n_ppn, bf.id_pph as bf_id_pph, bf.nilai_pph as n_pph, bf.nominal
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
                        <tbody>
                            <?php
                            $no = 1;
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
                                <td colspan="5"><b> PPN </b></td>
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
                                                            WHERE tp.po_id = '$id'
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
                                            <th>%</th>
                                            <th>Status</th>
                                            <th>Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($dataTagihan = mysqli_fetch_assoc($queryTagihan)) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= formatTanggal($dataTagihan['tgl_buat']); ?></td>
                                                <td><?= formatTanggal($dataTagihan['tgl_tempo']); ?></td>
                                                <td><?= formatRupiah($dataTagihan['nominal']); ?></td>
                                                <td><?= $dataTagihan['tppersentase']; ?></td>
                                                <td>
                                                    <?php

                                                    if ($dataTagihan['status_tagihan'] < 4) {

                                                        echo "<button class='btn btn-warning'>Belum di bayar</button>";
                                                    } else if ($dataTagihan['status_tagihan'] == 5) {

                                                        echo "<button class='btn btn-success'>Terbayar</button>";
                                                    }

                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#lihat_<?= $dataTagihan['id_tagihan']; ?>"><i class="fa fa-folder-open" title="Lihat" data-toggle="tooltip"></i></button>
                                                </td>
                                            </tr>

                                            <!-- Modal Lihat -->
                                            <div id="lihat_<?= $dataTagihan['id_tagihan']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-lg">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Invoice PO [<?= $dataTagihan['po_number']; ?>], pembayaran ke-<?= $no . " (" .  $dataTagihan['tppersentase']; ?>%)</h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <form class="form-horizontal">
                                                            <div class="modal-body">
                                                                <div class="box-body">
                                                                    <div class="form-group">
                                                                        <?php if (file_exists("../file/invoice/" . $dataTagihan['doc_faktur']) && !empty($dataTagihan['doc_faktur'])) { ?>
                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                <iframe class="embed-responsive-item" src="../file/invoice/<?= $dataTagihan['doc_faktur']; ?>"></iframe>
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
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir modal lihat -->
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
                                    <div id="bgn-dpp-lain">
                                        <div class="form-group">
                                            <label id="tes" for="dpp_nilai_lain" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">DPP Nilai Lain</label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" class="form-control " name="dpp_nilai_lain" id="dpp_nilai_lain" min="0" value="<?= formatRibuan($data2['dpp_nilai_lain']); ?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN
                                            <select name="pilih_ppn" id="setppn">
                                                <option value="0.12">12%</option>
                                                <option value="0.11">11%</option>
                                                <option value="0.012">1.2%</option>
                                                <option value="0.011">1.1%</option>
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
                                    <div id="bgn-pembulatan" class="bg-warning">
                                        <hr>
                                        <div class="form-group">
                                            <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN Atas</label>
                                            <div class="col-sm-3">
                                                <input type="radio" name="ppn_atas" value="all" id="all" onclick="checkPpnAtas()" checked=" checked"> Barang & Jasa
                                            </div>
                                            <div class=" col-sm-3">
                                                <input type="radio" name="ppn_atas" value="barang" id="barang" onclick="checkPpnAtas()"> Hanya Barang
                                            </div>
                                            <div class=" col-sm-3">
                                                <input type="radio" name="ppn_atas" value="jasa" id="jasa" onclick="checkPpnAtas()"> Hanya Jasa
                                            </div>
                                            <div class=" col-sm-3">
                                                <input type="radio" name="ppn_atas" value="dpp_lain" id="dpp_lain" onclick="checkPpnAtas()"> (11/12)
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Pembulatan</label>
                                            <div class="col-sm-3">
                                                <input type="radio" name="pembulatan" value="keatas" id="pembulatan" onclick="checkPembulatan()"> Ke atas
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="radio" name="pembulatan" value="kebawah" id="pembulatan" onclick="checkPembulatan()" checked="checked"> Ke bawah
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="biaya_lain" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Biaya Lain</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" value="<?= round($data2['biaya_lain']); ?>" name="biaya_lain" id="biaya_lain" autocomplete="off" />
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
                                    <div id="fixed" class="bg-success">
                                        <hr>
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
                                                    <input type="text" readonly class="form-control " name="pph_nilai" value="<?= formatRibuan($data2['n_pph']); ?>" id="pph_nilai" />
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div id="progresive" class="bg-success">
                                        <hr>
                                        <div class="form-group">
                                            <label id="tes" for="pph_nilai2" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" class="form-control " name="pph_nilai2" value="<?= round($data2['n_pph']); ?>" id="pph_nilai2" />
                                                </div>
                                                <i><span id="pph_ui"></span></i>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="potongan" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Potongan</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" value="<?= $data2['potongan']; ?>" name="potongan" id="potongan" autocomplete="off" />
                                            </div>
                                            <i><span id="np_ui"></span></i>
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
                                            <!-- <button type="submit" name="submit" class="btn btn-warning "><i class="fa fa-rocket"></i> Submit</button> -->
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#submit"><i class="fa fa-rocket"></i> Submit</button>
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

    <!-- modal submit -->
    <div id="submit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Submit Pengajuan</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="vrf_po.php" class="form-horizontal">
                        <input type="hidden" required class="form-control is-valid" name="id_po" value="<?= $data2['id_po']; ?>">
                        <input type="hidden" value="<?= $data2['id_tagihan']; ?>" name="id_tagihan" readonly>
                        <input type="hidden" value="<?= $data2['metode_pembayaran']; ?>" name="metode_pembayaran" readonly>
                        <input type="hidden" required class="form-control is-valid" name="id_bkk" value="<?= $data2['id']; ?>">
                        <input type="hidden" value="<?= round($data2['nilai_barang']); ?>" class="form-control" name="nilai_barang" readonly>
                        <input type="hidden" value="<?= round($data2['nilai_jasa']); ?>" class="form-control" name="nilai_jasa" readonly>
                        <input type="hidden" value="<?= round($data2['nilai_ppn']); ?>" class="form-control" name="ppn_nilai" readonly>
                        <input type="hidden" value="<?= $data2['id_pph']; ?>" class="form-control" name="id_pph" readonly>
                        <input type="hidden" value="<?= round($data2['nilai_pph']); ?>" class="form-control" name="pph_nilai" readonly>
                        <input type="hidden" value="<?= round($data2['nilai_pph']); ?>" class="form-control" name="pph_nilai2" readonly>
                        <input type="hidden" value="<?= round($data2['potongan']); ?>" class="form-control" name="potongan" readonly>
                        <input type="hidden" value="<?= round($data2['nominal']); ?>" class="form-control" name="jml" readonly>
                        <input type="hidden" value="<?= round($data2['biaya_lain']); ?>" class="form-control" name="biaya_lain" readonly>


                        <div class="box-body">
                            <div class="form-group ">
                                <h4 class="text-center">Yakin ingin mensubmit pengajuan <b><?= $data2['keterangan']; ?></b>?</h4>
                                <h5 class="text-center">Pastikan pengajuan sudah diverifikasi dan nominal sudah sesuai</h5>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-warning" type="submit" name="submit">Kirim</button></span></a>
                                <!-- <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-1 " value="kirim" >  -->
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
                                    <input type="hidden" value="<?= $tableBkk; ?>" name="tabel_bkk">
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

    let dpp_nilai_lain = '<?= $data2['dpp_nilai_lain'] ?>'
    if (dpp_nilai_lain > 0) {
        $("#bgn-dpp-lain").show();
    } else {
        $("#bgn-dpp-lain").hide();
    }

    ppn_atas = $("input[name='ppn_atas']:checked").val();


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
    let setPpn = 0.12;
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

        var pph_persen = parseFloat($("#pph_persen").val())
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

        // nilai potongan
        var potongan = parseInt($("#potongan").val())
        var np_ui = tandaPemisahTitik(potongan);
        $('#np_ui').text('Rp.' + np_ui);

        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {
            var ppn_nilai = Math.floor(setPpn * (nilaiBarang + nilaiJasa));
        } else if (checkBox.checked == false) {
            var ppn_nilai = 0;
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        document.form.ppn_nilai.value = ppn_nilaia;

        var jmla = (nilaiBarang + nilaiJasa + ppn_nilai + biayaLain) - (pph_nilai + pph_nilai2 + potongan);
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);

        document.form.jml.value = jml;

        hitungTotal();
    });

    function showPph(data) {

        var nilai_barang = parseInt($("#nilai_barang").val())
        var nilai_jasa = parseInt($("#nilai_jasa").val()) // hilangkanTitik('nilai_jasa')
        var ppn_nilai = parseInt(hilangkanTitik($("#ppn_nilai").val()));

        var biaya_lain = parseInt($("#biaya_lain").val()) // hilangkanTitik('biaya_lain')
        // var biaya_lain = hilangkanTitik('biaya_lain')

        // var jml = hilangkanTitik('jml')
        var pph_nilai = parseInt(hilangkanTitik($("#pph_nilai").val())); // hilangkanTitik('pph_nilai')

        // pph nilai 2 untuk tarif progresive
        var pph_nilai2 = parseInt(hilangkanTitik($("#pph_nilai2").val())); // hilangkanTitik('pph_nilai2')


        if (data == 'fixed') {
            $("#fixed").show();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + ppn_nilai + biaya_lain) - pph_nilai;


            jml = tandaPemisahTitik(jml);



            document.form.pph_nilai2.value = 0;
            document.form.jml.value = jml;

            if (pph_nilai > 0) {
                var persen = (pph_nilai / nilai_jasa) * 100;

                document.form.pph_persen.value = parseFloat(persen).toFixed(2);
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

        hitungTotal();
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
            var ppn_nilai = Math.floor(angkaPpn * (getDpp()));
        } else if (checkBox.checked == false) {
            var ppn_nilai = 0;
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        var pph_nilai2 = parseInt($("#pph_nilai2").val())

        var jmla = (nilaiBarang + nilaiJasa + ppn_nilai + biayaLain) - (pph_nilai + pph_nilai2 + potongan);
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
            $("#bgn-dpp-lain").hide();

            hitungCheckBox(setPpn);
        }
        hitungTotal();
    }

    // check ppn atas
    function checkPpnAtas() {
        // ambil cek ppn atas
        ppn_atas = $("input[name='ppn_atas']:checked").val();

        var ppn_nilai = Math.floor(setPpn * (getDpp()));

        // set nilai ppn
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;


        // var grandTotal = getNilaiBarang() + getNilaiJasa() + ppn_nilai + getBiayaLain() - getPphNilai() - getPotongan();

        // var jml = tandaPemisahTitik(grandTotal);

        // document.form.jml.value = jml;
        hitungTotal()
    }

    function getDpp() {
        // var nilaiDpp = 0;

        if (ppn_atas == 'all') {
            $("#bgn-dpp-lain").hide();
            var nilaiDpp = getNilaiBarang() + getNilaiJasa();

        } else if (ppn_atas == 'barang') {
            $("#bgn-dpp-lain").hide();
            var nilaiDpp = getNilaiBarang();

        } else if (ppn_atas == 'jasa') {
            $("#bgn-dpp-lain").hide();
            var nilaiDpp = getNilaiJasa();

        } else if (ppn_atas == 'dpp_lain') {
            $("#bgn-dpp-lain").show();

            dpp_lain = (11 / 12) * (getNilaiBarang() + getNilaiJasa());
            $('#dpp_nilai_lain').val(tandaPemisahTitik(Math.round(dpp_lain)))

            var nilaiDpp = getDPPNilaiLain();
        }

        return nilaiDpp;
    }

    function getPersentasePpn() {

        // let dpp = parseInt($("#nilai_barang").val()) + parseInt($("#nilai_jasa").val());
        let percent = np / getDpp();
        let percentOke = percent.toFixed(2);

        return parseFloat(percent.toFixed(2));
    }

    // check pembulatan
    function checkPembulatan() {

        var pembulatan = $("input[name='pembulatan']:checked").val();

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nilaiBarang = parseInt($("#nilai_barang").val())

        if (pembulatan == 'keatas') {

            // pembulatan ke atas
            var ppn_nilai = Math.ceil(setPpn * (nilaiBarang + nilaiJasa));

        } else if (pembulatan == 'kebawah') {

            // pembulatan ke bawah
            var ppn_nilai = Math.floor(setPpn * (nilaiBarang + nilaiJasa));
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        hitungTotal();
    }

    // hitung total
    function hitungTotal() {
        var grandTotal = getNilaiBarang() + getNilaiJasa() + getPpnNilai() + getBiayaLain() - getPphNilai() - getPotongan();

        var jml = tandaPemisahTitik(grandTotal);
        document.form.jml.value = jml;

        return grandTotal;
    }

    function showValueInput(idSpan, angka) {

        return $('#' + idSpan).text('Rp.' + tandaPemisahTitik(angka));
    }

    function getNilaiBarang() {
        return parseInt($("#nilai_barang").val())
    }

    function getNilaiJasa() {
        return parseInt($("#nilai_jasa").val())
    }

    function getDPPNilaiLain() {
        return parseInt(hilangkanTitik($("#dpp_nilai_lain").val()));
    }

    function getPpnNilai() {
        return parseInt(hilangkanTitik($("#ppn_nilai").val()));
    }

    function getPpnAtas() {
        return ppn_atas = $("input[name='ppn_atas']:checked").val();
    }

    function getBiayaLain() {
        return parseInt($("#biaya_lain").val());
    }

    function getPotongan() {
        return parseInt($("#potongan").val());
    }

    function getPphNilai() {

        if (jenis == 'fixed') {

            // pph nilai 1 untuk tarif fix
            var pph_nilai = parseInt(hilangkanTitik($("#pph_nilai").val()));

        } else if (jenis == 'progresive') {

            // pph nilai 2 untuk tarif progresive
            var pph_nilai = parseInt(hilangkanTitik($("#pph_nilai2").val()));

        } else {
            var pph_nilai = 0;
        }

        return pph_nilai;


    }

    function hilangkanTitik(idTag) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(idTag))))); //input ke dalam angka tanpa titik

        return angka;
    }
</script>