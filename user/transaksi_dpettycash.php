<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$query =  mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp   
                                            JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran     
                                            JOIN divisi d
                                            ON tp.id_divisi = d.id_divisi                                                                     
                                            WHERE tp.id_pettycash ='$id' ");
$data2 = mysqli_fetch_assoc($query);
$totalPengajuan = $data2['total_pettycash'];
$id_anggaran = $data2['id_anggaran'];
$totalAnggaran = $data2['jumlah_nominal'];

// realisasi anggaran
$queryRealisasi = mysqli_query($koneksi, " SELECT * FROM anggaran WHERE id_anggaran = '$id_anggaran' ");
$rowR = mysqli_fetch_assoc($queryRealisasi);
$totalRealisasi = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=transaksi_pettycash" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Petty Cash</h3>
                </div>
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['created_pettycash_on']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">Kode Anggaran </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_anggaran'] . ' [' . $data2['nm_item'] . ']'; ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">ID Pettycash</label>
                            <div class="col-sm-3">
                                <b><input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_pettycash']; ?>"> </b>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan_pettycash']; ?></textarea>
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Total Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['total_pettycash']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Pengembalian</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['pengembalian']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Grand Total</label>
                            <div class="col-sm-3">
                                <b><input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['total_pettycash']); ?>"> </b>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="penerima_dana" class="col-sm-offset-6 col-sm-3 control-label">Penerima Dana</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="penerima_dana" value="<?= ucwords($data2['penerima_dana']); ?>">
                            </div>
                            <label for="tgl_penerima" class="col-sm-offset-6 col-sm-3 control-label">Tanggal Penerima Dana</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_penerima" value="<?= $data2['pym_ksr']; ?>">
                            </div>
                        </div>
                        <br>
                    </div>
                </form>
                <!-- Embed Document    LPJ           -->
                <div class="box-header with-border">
                    <h3 class="text-center">Document LPJ</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_lpj/<?= $data2['doc_lpj_pettycash']; ?> "></iframe>
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
</script>