<?php


include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsianggaran.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}
$tahun = date("Y");

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryDetail =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         JOIN divisi d
                                                         ON d.id_divisi = d.id_divisi
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
                        <a href="index.php?p=payment_kasbon&sp=pk_user" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
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
                                    <h3 class="text-center">Document Pendukung </h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_pendukung']; ?>" id="ml_doc"></iframe>
                                    </div>
                                </div>
                            </div>

                            <!-- Rincian Harga -->
                            <?php if ($vrf_pajak == 'bp') { ?>
                                <div class="col-sm-12">
                                    <h3 class="text-center">Rincian Harga</h3>
                                    <div class="table-responsive">
                                        <table class="table" border="2px">
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
                            <?php } ?>
                            <br>
                            <div class=" col-sm-offset-10">
                                <!-- <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak">Reject </button></span></a> -->
                                &nbsp;
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payment"> Payment </button></span></a>
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

<!--  -->
<div id="payment" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Konfirmasi Penyerahan Dana </h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="send_paymentkasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon" readonly>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nominal" disabled value="<?= formatRupiah($totalPengajuan); ?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="penerima_dana" class="col-sm-offset-1 col-sm-3 control-label">Nama </label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="penerima_dana" placeholder="Nama Penerima Dana">
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-success" type="submit" name="submit">Kirim</button></span></a>
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