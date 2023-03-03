<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];
$tahun = date("Y");

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            RIGHT JOIN detail_biayaops dbo
                                            ON dbo.kd_transaksi = bo.kd_transaksi
                                            JOIN anggaran a
                                            ON a.id_anggaran = dbo.id_anggaran
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            WHERE bo.kd_transaksi='$id' ");
// $data=mysqli_fetch_assoc($queryBo);

$query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            WHERE kd_transaksi='$id' ");
$data2 = mysqli_fetch_assoc($query);
$Divisi = $data2['id_divisi'];

$queryTotal = mysqli_query($koneksi, " SELECT sum(jumlah_nominal) as total_anggaran 
                                                FROM anggaran
                                                WHERE id_divisi='$Divisi' 
                                                AND tahun = '$tahun'
                                                ");
$rowTotal = mysqli_fetch_assoc($queryTotal);
$totalAnggaran = $rowTotal['total_anggaran'];


$queryDetail =  mysqli_query($koneksi, "SELECT * 
                                                FROM detail_biayaops db
                                                JOIN anggaran a
                                                ON db.id_anggaran = a.id_anggaran
                                                WHERE db.kd_transaksi='$id' ");

$queryRealisasi = mysqli_query($koneksi, " SELECT *
                                                FROM anggaran
                                                WHERE id_divisi='$Divisi' 
                                                AND tahun = '$tahun'
                                                ");
$totalRealisasi = 0;
if (mysqli_num_rows($queryRealisasi)) {
    while ($rowR = mysqli_fetch_assoc($queryRealisasi)) :
        $realisasiT = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];

        $totalRealisasi += $realisasiT;
    endwhile;
}

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=dmr&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}
?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <?php
            if (isset($_COOKIE['pesan'])) {
                echo "<div class='alert alert-danger' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
            }
            ?>

            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=data_jovessel" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Approve Material Request</h3>
                </div>
                <form method="" name="form" action="#" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['tgl_pengajuan']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-9 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_transaksi']; ?>">
                            </div>
                        </div>
                        <br>
                    </div>
                </form>
                <!--  -->

                <form method="post" enctype="multipart/form-data" action="setuju_mr.php" class="form-horizontal">
                    <input type="hidden" class="form-control is-valid" name="kd_transaksi" value="<?= $data2['kd_transaksi']; ?>">
                    <div class="table-responsive datatab">
                        <table class="table text-center table table-striped table-hover" border="2px;">
                            <tr style="background-color :#B0C4DE;" border="2px;">
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Barang</th>
                                <th rowspan="2">Merk</th>
                                <th rowspan="2">Type</th>
                                <!-- <th>Spesifikasi</th>                                Aksi -->
                                <th rowspan="2">Satuan</th>
                                <th rowspan="2">Kode Anggaran</th>
                                <th colspan="2">Budget</th>
                                <th colspan="2">Akumulasi</th>
                                <th colspan="2">Pengajuan</th>
                                <th>Pilih</th>
                                <th rowspan="2">Detail</th>
                            </tr>
                            <tr style="background-color :#B0C4DE;" border="2px;">
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Harga</th>
                                <th>Persentase</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>&nbsp; <input type="checkbox" id="selectall" /></th>
                            </tr>
                            <!-- <tr> -->
                            <tbody>

                                <?php
                                $no = 1;
                                $totalEstimasi = 0;
                                $totalSaldo = 0;
                                $totalQtyBudget = 0;
                                $totalQtyPengajuan = 0;



                                if (mysqli_num_rows($queryDetail)) {
                                    while ($row = mysqli_fetch_assoc($queryDetail)) :

                                        $budget = $row['januari_nominal'] + $row['februari_nominal'] + $row['maret_nominal'] + $row['april_nominal'] + $row['mei_nominal'] + $row['juni_nominal'] + $row['juli_nominal'] + $row['agustus_nominal'] + $row['september_nominal'] + $row['oktober_nominal'] + $row['november_nominal'] + $row['desember_nominal'];
                                        $realisasi = $row['januari_realisasi'] + $row['februari_realisasi'] + $row['maret_realisasi'] + $row['april_realisasi'] + $row['mei_realisasi'] + $row['juni_realisasi'] + $row['juli_realisasi'] + $row['agustus_realisasi'] + $row['september_realisasi'] + $row['oktober_realisasi'] + $row['november_realisasi'] + $row['desember_realisasi'];
                                        $saldoAnggaran = $budget - $realisasi;

                                        $estimasiHarga = $row['jumlah'] * $row['harga'];

                                        $selisihHarga = $saldoAnggaran - $estimasiHarga;

                                        $persentaseAkumulasi = round(@($realisasi / $budget * 100), 0);

                                        $kd_anggaran = $row['kd_anggaran'];

                                        if ($kd_anggaran == '1000-0001') { ?>

                                            <tr style="background-color : 	#FF6347;">

                                            <?php } else if ($selisihHarga < 0) { ?>

                                            <tr style="background-color : #98FB98;">

                                            <?php } else {
                                            echo "<tr>";
                                        }


                                            ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['type']; ?> </td>
                                            <!-- <td> <?= $row['spesifikasi']; ?> </td> -->
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                            <!-- realisaisi -->
                                            <td> <?= $row['jumlah_kuantitas']; ?> </td>
                                            <td> <?= 'Rp. ' . number_format($saldoAnggaran, 0, ",", "."); ?></td>
                                            <td> <?= formatRupiah($realisasi); ?> </td>
                                            <!--  -->
                                            <td> <?= $persentaseAkumulasi . ' %'; ?> </td>
                                            <td> <?= $row['jumlah']; ?> </td>
                                            <td> <?= 'Rp. ' . number_format($estimasiHarga, 0, ",", "."); ?> </td>
                                            <td>
                                                <input type="checkbox" class="case" name="id_item[]" value="<?= $row['id']; ?>" />
                                            </td>
                                            <td>
                                                <a href="?p=app_dmr&aksi=lihat&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><i class="fa fa-search-plus"></i></span></a>
                                            </td>
                                            </tr>
                                    <?php
                                        $totalEstimasi += $estimasiHarga;
                                        $totalSaldo += $saldoAnggaran;
                                        $totalRealisasi += $realisasi;
                                        $totalQtyBudget += $row['jumlah_kuantitas'];
                                        $totalQtyPengajuan += $row['jumlah'];

                                        $no++;
                                    endwhile;
                                } ?>
                            </tbody>
                            <!-- </tr> -->
                            <tr style="background-color :#B0C4DE;">
                                <td colspan="6" class="text-center"><b>Total</b></td>
                                <td><b><?= $totalQtyBudget; ?></b></td>
                                <td><b><?= 'Rp. ' . number_format($totalSaldo, 0, ",", "."); ?></b></td>
                                <td></td>
                                <td></td>
                                <td><b><?= $totalQtyPengajuan; ?></b></td>
                                <td><b><?= 'Rp. ' . number_format($totalEstimasi, 0, ",", "."); ?></b></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>

                    <?php
                    // pengajuan di bandingkan dengan total Anggaran divisi
                    $selisihAnggaran = round(@($totalEstimasi / $totalAnggaran * 100), 0);
                    $selisihRealisasi = round(@($totalRealisasi / $totalAnggaran * 100), 0);
                    $persentaseProgress = $selisihRealisasi + $selisihAnggaran;

                    $sisaBudget = $totalAnggaran - ($totalRealisasi + $totalEstimasi);
                    $persentaseSisaBudget = round(@($sisaBudget / $totalAnggaran * 100), 0);


                    ?>
                    <br>
                    <div class="box-header with-border">
                        <!-- <div class="form-group">   -->
                        <h4 class="text-left"><b>Total Budget Setahun : <?= 'Rp. ' . number_format($totalAnggaran, 0, ",", "."); ?> &nbsp;</b></b></h4>
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
                            <h5><b> Pengajuan : <?= 'Rp. ' . number_format($totalEstimasi, 0, ",", ".") ?> </b></h5>
                        </div>
                        <div class="col-sm-offset-1 col-sm-3">
                            <button type="button" class="btn btn-dark" style="background-color :#708090;"></button> <b> (<?= $persentaseSisaBudget ?> %)</b>
                            <h5><b> Sisa Budget : <?= 'Rp. ' . number_format($sisaBudget, 0, ",", ".") ?> </b></h5>
                        </div>
                    </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-sm-offset-9 col-sm-3 control-label">
                    <h4> Verifikasi </h4>
                    <button class="btn btn-success" type="submit" name="submit">Approve</button></span></a>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak"> Reject </button></span></a>
                </div>
                <!-- </div> -->
            </div>
            </form>
        </div>
    </div>
    </div>

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
                    <form method="post" enctype="multipart/form-data" action="tolak_mr.php" class="form-horizontal">
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
</section>
<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script> -->
<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

    });


    $(function() {

        $("#selectall").change(function() {
            if (this.checked) {
                $(".case").each(function() {
                    this.checked = true;
                });
                var jumlahCheck = $(".case").length;
            } else {
                $(".case").each(function() {
                    this.checked = false;
                });
                var jumlahCheck = 0;
            }

            // menampilkan output ke elemen hasil
            total.innerHTML = jumlahCheck;
            // console.log(jumlahCheck);
        });

        $(".case").click(function() {
            if ($(this).is(":checked")) {
                var isAllChecked = 0;
                var jumlahCheck = $('input:checkbox:checked').length;

                $(".case").each(function() {
                    if (!this.checked)
                        isAllChecked = 1;
                });

                if (isAllChecked == 0) {
                    $("#selectall").prop("checked", true);

                    jumlahCheck = $(".case").length;
                }


            } else {
                $("#selectall").prop("checked", false);

                jumlahCheck = $('input:checkbox:checked').length;
            }
            total.innerHTML = jumlahCheck;
            console.log(jumlahCheck);

        });


    });
</script>