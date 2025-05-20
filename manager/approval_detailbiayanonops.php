<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama FROM user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBkk = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.id_bkk = '$id' ");

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=data_jovessel" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Aprrove Biaya Umum</h3>
                </div>
                <?php
                $row2 = mysqli_fetch_assoc($queryBkk);
                // if (mysqli_num_rows($queryBkk)) {
                //     while ($row2 = mysqli_fetch_assoc($queryBkk)) :
                // query Total_cargo
                $nilai_barang = number_format($row2['nilai_barang'], 0, ",", ".");
                $nilai_jasa = number_format($row2['nilai_jasa'], 0, ",", ".");
                $ppn_nilai = number_format($row2['ppn_nilai'], 0, ",", ".");
                $pph_nilai = number_format($row2['pph_nilai'], 0, ",", ".");
                $jml_bkk = number_format($row2['jml_bkk'], 0, ",", ".");
                $bll_bkk = number_format($row2['bll_bkk'], 0, ",", ".");

                $budget = $row2['januari_nominal'] + $row2['februari_nominal'] + $row2['maret_nominal'] + $row2['april_nominal'] + $row2['mei_nominal'] + $row2['juni_nominal'] + $row2['juli_nominal'] + $row2['agustus_nominal'] + $row2['september_nominal'] + $row2['oktober_nominal'] + $row2['november_nominal'] + $row2['desember_nominal'];
                $realisasi = $row2['januari_realisasi'] + $row2['februari_realisasi'] + $row2['maret_realisasi'] + $row2['april_realisasi'] + $row2['mei_realisasi'] + $row2['juni_realisasi'] + $row2['juli_realisasi'] + $row2['agustus_realisasi'] + $row2['september_realisasi'] + $row2['oktober_realisasi'] + $row2['november_realisasi'] + $row2['desember_realisasi'];
                $saldoAnggaranb = $budget - $realisasi;
                $saldoAnggaran = 'Rp. ' . number_format($saldoAnggaranb, 2, ",", ".");

                ?>



                <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">
                    <div class="box-body">

                        <div class="form-group ">
                            <label for="id_joborder" class=" col-sm-2 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['kd_transaksi']; ?>" disabled class="form-control" name="id_bkk">
                            </div>
                            <!-- </div>
                    <div class="form-group "> -->
                            <label id="tes" for="tgl_bkk" class=" col-sm-2 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['tgl_pengajuan']; ?>" disabled class="form-control" name="tgl_bkk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="nm_vendor" class=" col-sm-2 control-label">Nama Vendor</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['nm_vendor']; ?>" disabled class="form-control" name="nm_vendor">
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label for="kd_transaksi" class="col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['kd_anggaran']; ?>" class="form-control " name="kd_transaksi" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['keterangan']; ?>" class="form-control " name="keterangan" readonly>
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label for="terbilang_bkk" class=" col-sm-2 control-label">Terbilang</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['terbilang_bkk'] . ' Rupiah'; ?>" disabled class="form-control tanggal" name="terbilang_bkk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="jml_bkk" class="col-sm-2 control-label">Saldo Anggaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $saldoAnggaran; ?>" readonly class="form-control" name="jml_bkk">
                            </div>

                            <label id="tes" for="nama_mengajukan" class=" col-sm-2 control-label">Nama yang Mengajukan</label>
                            <div class="col-sm-3">
                                <input type="text" readonly required class="form-control is-valid" name="nama_mengajukan" placeholder="Fulan/Fulanah" value="<?= $row2['nama_mengajukan']; ?>">
                            </div>
                        </div>
                        <!-- <?php if ($row2['diajukan_lagi'] == "1") { ?>
                            <div class="form-group">
                                <label id="tes" for="jml_bkk" class="col-sm-2 control-label">History Ditolak</label>
                                <div class="col-sm-3">
                                    <textarea name="" readonly class="form-control" id="" cols="30" rows="5"><?= $row2['komentar'] . "&#13;&#10;" . $row2['komentar_mgrfin'] . "&#13;&#10;" . $row2['komentar_direktur']; ?></textarea>
                                </div>
                            </div>
                        <?php } ?> -->
                        <hr>
                        <div class="form-group">
                            <div class="col-sm-offset-1 col-sm-10">
                                <div class="table-responsive">
                                    <table class="table text-right table-striped table-hover" id=" ">
                                        <thead style="background-color: royalblue;">
                                            <tr>
                                                <th class="text-center">Deskripsi</th>
                                                <th class="text-center">Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">Nilai Barang</td>
                                                <td><?= "Rp." . $nilai_barang; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Nilai Jasa</td>
                                                <td><?= "Rp." . $nilai_jasa; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Nilai PPN</td>
                                                <td><?= "Rp." . $ppn_nilai; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">Nilai PPh</td>
                                                <td>(<?= "Rp." . $pph_nilai; ?>)</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center">Grand Total</th>
                                                <th class="text-right"><?= "Rp." . $jml_bkk; ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Jenis</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= strtoupper($row2['jenis']) ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                        </div>
                        <?php
                        if ($row2['jenis'] == 'kontrak') { ?>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Tanggal Tempo</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= formatTanggalHari($row2['tgl_tempo']);  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Tanggal Pembayaran </label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= formatTanggalHari($row2['tgl_payment']);  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                            </div>
                        <?php } ?>
                        <hr>
                        <div class="form-group">
                            <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Metode Pembayaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= strtoupper($row2['metode_pembayaran']) ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                        </div>
                        <?php
                        if ($row2['metode_pembayaran'] == 'transfer') { ?>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Bank Tujuan</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $row2['bank_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">No Rekening</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $row2['norek_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nama Penerima</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $row2['penerima_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label id="tes" for="jml_bkk" class="col-sm-2 control-label">Jumlah</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp." . $jml_bkk; ?>" readonly class="form-control" name="jml_bkk">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <h3 class="text-center">Invoice </h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe src="../file/pdfjs/web/viewer.html?file=../../<?php echo $row2['invoice']; ?> " frameborder="0" width="100%" height="550"></iframe>
                    </div>
            </div>
            </form>

            <!-- Embed Document               -->
            <!-- Document PTW -->
            <div class="box-header with-border">
                <!-- pdf baru -->
                <!-- <iframe src="../file/pdfjs/web/viewer.html?file=../../<?php echo $row2['invoice']; ?> " frameborder="0" width="100%" height="550"></iframe> -->

                <!-- pdf lama -->
                <!-- <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="../file/<php echo $row2['invoice']; ?> "></iframe>
                </div> -->

                <div class="col-sm-offset-9 col-sm-3 control-label">
                    <h4> Verifikasi </h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approve">Approve</button>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Reject</button></span></a>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    </div>

    <!-- Modal approve -->
    <div id="approve" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Konfirmasi </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <h4 class="text-center">Apakah anda yakin ingin menyetujui biaya umum ini?</h4>
                        </div>
                        <br>
                        <div class=" modal-footer">
                            <a href="setuju_bkk.php?id=<?= $row2['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Kirim'><button class="btn btn-primary">Ya</button></span></a>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir modal approve -->

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
                    <form method="post" enctype="multipart/form-data" action="tolak_biayanonops.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">

                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $row2['id_bkk']; ?>" class="form-control" name="id_bkk" readonly>
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
                        </div>
                </div>
                <div class=" modal-footer">
                    <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>
                    <!-- <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-1 " value="kirim" >  -->
                    &nbsp;
                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                </div>
            </div>
            </form>
        </div>

        <?php // endwhile;
        // } 
        ?>
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
</script>