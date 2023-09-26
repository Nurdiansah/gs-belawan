<?php


include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsianggaran.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}
$tahun = date("Y");

$id = $_GET['id'];

$queryUser = mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryDetail = mysqli_query($koneksi, "SELECT * FROM kasbon k
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         JOIN divisi d
                                                         ON d.id_divisi = db.id_divisi
                                                         JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$id_supplier = $data['id_supplier'];
$id_anggaran = $data['id_anggaran'];
$totalPengajuan = $data['harga_akhir'];
$id_dbo = $data['id'];
$id_divisi = $data['id_divisi'];
$vrf_pajak = $data['vrf_pajak'];

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$id_anggaran'");

?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=verifikasi_kasbonlpj&sp=vlk_user" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>

                <div class="box-header with-border">
                    <h3 class="text-center">Payment Kasbon</h3>
                </div>
                <div class="perhitungan">
                    <form method="post" name="form" action="vrf_itemmr.php" enctype="multipart/form-data" class="form-horizontal">

                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="tanggal" class="col-sm-offset col-sm-2 control-label">Tanggal Pengajuan</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="tanggal" value="<?= formatTanggal($data['tgl_kasbon']); ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label for="satuan" class="col-sm-offset- col-sm-2 control-label">Divisi</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control " name="satuan" value="<?= $data['nm_divisi']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="nominal" for="nominal" class="col-sm-offset col-sm-2 control-label">Nominal</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="nominal" value="<?= formatRupiah($data['harga_akhir']); ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2" name="id_anggaran" disabled>
                                        <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                        <?php
                                        if (mysqli_num_rows($queryAnggaran)) {
                                            while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                        ?>
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <input type="hidden" required class="form-control is-valid" name="id_kasbon" value="<?= $data['id_kasbon']; ?>">
                                <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                                <input type="hidden" required class="form-control is-valid" name="from_user" value="<?= $data['from_user']; ?>">
                                <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <!-- <input type="text" readonly class="form-control is-valid" name="nm_barang"> -->
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->

                                <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data['keterangan']; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group ">
                                <div class="box-header with-border">
                                    <h3 class="text-center">Document LPJ </h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/doc_lpj/<?= $data['doc_lpj']; ?>" id="ml_doc"></iframe>
                                    </div>
                                </div>
                            </div>

                            <!-- Rincian Harga -->
                            <?php // if ($vrf_pajak == 'bp') { 
                            ?>
                            <div class="col-sm-12">
                                <h3 class="text-center">Rincian Harga</h3>
                                <div class="table-responsive">
                                    <table class="table" border="2px">
                                        <tr>
                                            <td colspan="5"><b>Nominal Pengajuan</b></td>
                                            <td><b><?= formatRupiah($data['nilai_pengajuan']); ?></b></td>
                                        </tr>
                                        <tr style="background-color :#B0C4DE;">
                                            <td colspan="5"><b>Pengembalian</b></td>
                                            <td><b><?= formatRupiah($data['pengembalian']); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><b>Penambahan</b></td>
                                            <td><b><?= formatRupiah($data['penambahan']); ?></b></td>
                                        </tr>
                                        <tr style="background-color : grey;">
                                            <td colspan="6"><b></b></td>
                                        </tr>
                                        <tr style="background-color :#B0C4DE;">
                                            <td colspan="5"><b>Nilai Barang</b></td>
                                            <td><b><?= formatRupiah($data['nilai_barang']); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><b>Nilai Jasa</b></td>
                                            <td><b><?= formatRupiah($data['nilai_jasa']); ?></b></td>
                                        </tr>
                                        <tr style="background-color :#B0C4DE;">
                                            <td colspan="5"><b>PPN</b></td>
                                            <td><b><?= formatRupiah($data['nilai_ppn']); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><b>PPh</b></td>
                                            <td><b>(<?= formatRupiah($data['nilai_pph']); ?>)</b></td>
                                        </tr>
                                        <tr style="background-color :#B0C4DE;">
                                            <td colspan="5"><b>Grand Total</b></td>
                                            <td><b><?= formatRupiah($data['harga_akhir']); ?></b></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php // } 
                            ?>
                            <br>
                            <div class=" col-sm-offset-8 col-sm-4 control-label">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#konfirmasi">Done</button></span></a>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tolakPajak">Verifikasi To Pajak</button></span></a>
                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak">Reject To User</button></span></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>

    </div>
    <!-- </div> -->
</section>

<!-- Modal konfirmasi  -->
<div id="konfirmasi" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Konfirmasi LPJ</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="setuju_kasbonlpj.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['id_divisi']; ?>" class="form-control" name="id_divisi" readonly>
                                <input type="hidden" value="<?= $data['id_manager']; ?>" class="form-control" name="id_manager" readonly>
                                <input type="hidden" value="<?= $data['doc_lpj']; ?>" class="form-control" name="doc_lpj" readonly>
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon" readonly>
                                <input type="hidden" value="<?= round($totalPengajuan); ?>" class="form-control" name="total" readonly>
                                <input type="hidden" value="<?= $data['id_anggaran']; ?>" class="form-control" name="id_anggaran" readonly>
                                <input type="hidden" value="<?= $data['id_supplier']; ?>" class="form-control" name="id_supplier" readonly>
                                <input type="hidden" value="<?= $data['nilai_barang']; ?>" class="form-control" name="nilai_barang" readonly>
                                <input type="hidden" value="<?= $data['nilai_jasa']; ?>" class="form-control" name="nilai_jasa" readonly>
                                <input type="hidden" value="<?= $data['nilai_ppn']; ?>" class="form-control" name="nilai_ppn" readonly>
                                <input type="hidden" value="<?= $data['nilai_pph']; ?>" class="form-control" name="nilai_pph" readonly>
                                <input type="hidden" value="<?= $data['id_pph']; ?>" class="form-control" name="id_pph" readonly>
                                <input type="hidden" value="<?= $data['pengembalian']; ?>" class="form-control" name="pengembalian" readonly>
                                <input type="hidden" value="1" class="form-control" name="qty" readonly>
                                <input type="hidden" value="<?= $data['waktu_penerima_dana']; ?>" class="form-control" name="waktu_penerima_dana" readonly>
                                <input type="hidden" value="verifikasi_kasbonlpj&sp=vlk_user" class="form-control" name="url" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="" class="label-control">Tanggal BKK</label>
                                <input type="datetime-local" class="form-control" name="tgl_bkk" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="validationTextarea">Redaksi</label>
                            <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Redaksi BKK"></textarea>
                            <div class="invalid-feedback">
                                *Redaksi akan di tampilkan di BKK
                            </div>
                        </div>
                        <!-- <h4 class="text-center">Document LPJ Sudah di Verifikasi</h4> -->
                        <br>
                        <div class=" modal-footer">
                            <button class="btn btn-primary" type="submit" name="submit">Submit</button></span></a>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal konfirmasi  -->

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
                <form method="post" enctype="multipart/form-data" action="tolak_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['nilai_barang']; ?>" class="form-control" name="nilai_barang" readonly>
                                <input type="hidden" value="<?= $data['nilai_jasa']; ?>" class="form-control" name="nilai_jasa" readonly>
                                <input type="hidden" value="<?= round($data['pengembalian']); ?>" name="pengembalian" readonly>
                                <input type="hidden" value="<?= round($data['penambahan']); ?>" name="penambahan" readonly>
                                <input type="hidden" value="<?= $id; ?>" class="form-control" name="id_kasbon" readonly>
                                <input type="hidden" value="verifikasi_kasbonlpj&sp=vlk_user" class="form-control" name="url" readonly>
                                <input type="hidden" name="Nama" value="<?= $Nama; ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="validationTextarea">Komentar</label>
                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required autocomplete></textarea>
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

<!--  -->
<div id="tolakPajak" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alasan Verifikasi Pajak</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="tolak_kasbon_pajak.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $id; ?>" class="form-control" name="id_kasbon" readonly>
                                <input type="hidden" value="verifikasi_kasbonlpj&sp=vlk_user" class="form-control" name="url" readonly>
                                <input type="hidden" name="Nama" value="<?= $Nama; ?>">
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

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

    });
</script>