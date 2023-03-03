<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
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
$queryRealisasi = mysqli_query($koneksi, " SELECT * FROM anggaran WHERE id_anggaran='$id_anggaran' ");
$rowR = mysqli_fetch_assoc($queryRealisasi);
$totalRealisasi = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];


?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=approval_pettycash" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Petty Cash</h3>
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
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggalWaktu($data2['created_pettycash_on']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">Kode Anggaran </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_anggaran'] . ' [' . $data2['nm_item'] . ']'; ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Total </label>
                            <div class="col-sm-3">
                                <b><input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['total_pettycash']); ?>"> </b>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan_pettycash']; ?></textarea>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="text-center">Document LPJ</h3>
                        <!-- format pdf baru -->
                        <iframe src="../file/pdfjs/web/viewer.html?file=../../doc_lpj/<?= $data2['doc_lpj_pettycash']; ?> " frameborder="0" width="100%" height="550"></iframe>
                        <!-- format pdf lama -->
                        <!-- <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/doc_lpj/< $data2['doc_lpj_pettycash']; ?> "></iframe>
                        </div> -->
                    </div>
                    <br>
                </form>
                <div class="form-group col-sm-offset-10">
                    <!-- <a target="_blank" href="cetak_bkk.php?id=<?= $id; ?>" class="btn btn-success"><i class="fa fa-print"></i> BKK </a>                                                                                                                                 -->
                    <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak">Reject </button></span></a>
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
                    <form method="post" enctype="multipart/form-data" action="setuju_pettycash.php" class="form-horizontal">
                        <div class="box-body">
                            <h4 class="text-center">Apakah anda yakin ingin menyetujui ?</h4>
                            <br>
                            <input type="hidden" value="<?= $data2['id_pettycash']; ?>" class="form-control" name="id" readonly>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Yes</button></span></a>
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
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
        <h4 class="text-left"><b>Total Budget <?= '<font color="red">' . $data2['nm_item'] . '</font>' . ' Setahun : ' . formatRupiah($totalAnggaran); ?> &nbsp;</b></b></h4>
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
                    <form method="post" enctype="multipart/form-data" action="tolak_pettycash.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['id_pettycash']; ?>" class="form-control" name="id" readonly>
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