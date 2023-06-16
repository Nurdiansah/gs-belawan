<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsianggaran.php";

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['id_divisi'];

$tahun = date("Y");

$queryDetail =  mysqli_query($koneksi, "SELECT * 
                                                FROM detail_biayaops db
                                                JOIN anggaran a
                                                ON db.id_anggaran = a.id_anggaran
                                                WHERE db.status  = '0'
                                                AND db.id_divisi='$Divisi'
                                                AND db.is_for = 'mr'
                                                AND alasan_penolakan IS NULL");

$queryGrand =  mysqli_query($koneksi, "SELECT * 
                                        FROM detail_biayaops db
                                        JOIN anggaran a
                                        ON db.id_anggaran = a.id_anggaran
                                        WHERE db.status  = '0' AND db.id_divisi='$Divisi' AND db.is_for = 'mr'");

$queryTotal = mysqli_query($koneksi, " SELECT sum(jumlah_nominal) as total_anggaran
                                                FROM anggaran 
                                                WHERE id_divisi='$Divisi' AND tahun = '$tahun'");
$rowTotal = mysqli_fetch_assoc($queryTotal);
$totalAnggaran = $rowTotal['total_anggaran'];

$queryRealisasi = mysqli_query($koneksi, " SELECT *
                                                FROM anggaran                                           
                                                WHERE id_divisi='$Divisi' AND tahun = '$tahun' ");
$totalRealisasi = 0;
if (mysqli_num_rows($queryRealisasi)) {
    while ($rowR = mysqli_fetch_assoc($queryRealisasi)) :
        $realisasiT = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];

        $totalRealisasi += $realisasiT;
    endwhile;
}

$tanggalCargo = date("Y-m-d");




if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=edit_item&id=$id");
    } else if ($_GET['aksi'] == 'lihat') {
        header("location:?p=detail_item&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        hapusItemMr($id, $foto);
    }
}
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
                            <a href="index.php?p=dashboard" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Create Material Request</h3>
                </div>

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                    <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                </div>

                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-hover" border="2px;">
                        <tr style="background-color :#B0C4DE;" border="2px;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">Merk</th>
                            <th rowspan="2">Type</th>
                            <!-- <th>Spesifikasi</th>                                 -->
                            <th rowspan="2">Satuan</th>
                            <th rowspan="2">Kode Anggaran</th>
                            <th colspan="2">Sisa Budget</th>
                            <th colspan="2">Pengajuan</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr style="background-color :#B0C4DE;" border="2px;">
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Harga</th>
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

                                    $kd_anggaran = $row['kd_anggaran'];


                                    if ($no >= 2) {

                                        $sisaAnggaran = 0;
                                        $sisaQty = 0;

                                        foreach ($Array as $key => $value) {
                                            if ($value['id_anggaran'] == $row['id_anggaran']) {

                                                $saldoAnggaran = $value['sisa_anggaran'];
                                                $saldoQty = $value['sisa_qty'];

                                                $sisaAnggaran = $value['sisa_anggaran'] - $estimasiHarga;
                                                $sisaQty = $value['sisa_qty'] - $row['jumlah'];
                                                // break;
                                            }
                                        }

                                        if ($sisaAnggaran == 0 && $sisaQty == 0) {
                                            $saldoAnggaran = $saldoAnggaran;
                                            $saldoQty = $row['jumlah_kuantitas'];

                                            $sisaAnggaran = $saldoAnggaran - $estimasiHarga;
                                            $sisaQty = $saldoQty - $row['jumlah'];
                                        }
                                    } else {
                                        $saldoAnggaran = $saldoAnggaran;
                                        $saldoQty = $row['jumlah_kuantitas'];

                                        $sisaAnggaran = $saldoAnggaran - $estimasiHarga;
                                        $sisaQty = $saldoQty - $row['jumlah'];
                                    }


                                    if ($kd_anggaran == '1000-0001') { ?>

                                        <tr style="background-color : 	#FF6347;">

                                        <?php } else if ($selisihHarga < 0) { ?>

                                        <tr style="background-color : #98FB98;">

                                        <?php } else {
                                        echo "<tr>";
                                    }

                                    // Untuk Lock anggaran 
                                    $sisaBudgetLock = getSaldoAnggaran($row['id_anggaran']);

                                        ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['nm_barang']; ?> </td>
                                        <td> <?= $row['merk']; ?> </td>
                                        <td> <?= $row['type']; ?> </td>
                                        <!-- <td> <?= $row['spesifikasi']; ?> </td> -->
                                        <td> <?= $row['satuan']; ?> </td>
                                        <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                        <td> <?= $saldoQty; ?> </td>
                                        <td> <?= 'Rp. ' . number_format($saldoAnggaran, 0, ",", "."); ?></td>
                                        <td> <?= $row['jumlah']; ?> </td>
                                        <td> <?= 'Rp. ' . number_format($estimasiHarga, 0, ",", "."); ?> </td>
                                        <td>
                                            <?php if ($sisaBudgetLock <= 0) { ?>
                                                <button type="button" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#notifBudget"><i class="fa fa-rocket"></i> </button>
                                            <?php } else { ?>
                                                <a href="add_mr.php?id=<?= $row['id']; ?>" onclick="javascript: return confirm('Anda yakin ingin mensubmit <?= $row['nm_barang']; ?> ?')"><span data-placement='top' data-toggle='tooltip' title='Submit'><button class="btn btn-warning btn-sm"> <i class="fa fa-rocket"></i> </button></span></a>
                                            <?php } ?>
                                            <!-- <a href="?p=buat_mr&aksi=lihat&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info btn-sm"> <i class="fa fa-search-plus"></i> </button></span></a> -->
                                            <a href="?p=buat_mr&aksi=edit&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </button></span></a>
                                            <a href="?p=buat_mr&aksi=hapus&id=<?= $row['id']; ?>&doc=<?= $row['foto_item']; ?>" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> </button></span></a>

                                        </td>
                                        </tr>
                                <?php
                                    $totalEstimasi += $estimasiHarga;
                                    // $totalSaldo += $saldoAnggaran;
                                    $totalRealisasi += $realisasi;

                                    $totalQtyPengajuan += $row['jumlah'];




                                    if ($no >= 2) {

                                        foreach ($ArrayIdAnggaran as $key => $value) {
                                            if ($value == $row['id_anggaran']) {
                                                $saldoTemp = 0;
                                                $qtyTemp = 0;
                                                break;
                                            } else {
                                                $saldoTemp =  $saldoAnggaran;
                                                $qtyTemp =  $row['jumlah_kuantitas'];
                                                continue;
                                            }
                                        }

                                        $totalSaldo += $saldoTemp;
                                        $totalQtyBudget += $qtyTemp;
                                    } else {
                                        $totalSaldo += $saldoAnggaran;
                                        $totalQtyBudget += $row['jumlah_kuantitas'];
                                    }

                                    $ArrayIdAnggaran[] = $row['id_anggaran'];

                                    $Array[] = [
                                        'id_anggaran' => $row['id_anggaran'],
                                        'sisa_anggaran' => $sisaAnggaran,
                                        'sisa_qty' => $sisaQty
                                    ];

                                    $no++;
                                endwhile;
                            } ?>
                        </tbody>

                        <!-- </tr> -->
                        <tr style="background-color :#B0C4DE;">
                            <td colspan="8" class="text-center"><b>Total</b></td>
                            <!-- <td><b><?= $totalQtyBudget; ?></b></td>
                            <td><b><?= 'Rp. ' . number_format($totalSaldo, 0, ",", "."); ?></b></td> -->
                            <td><b><?= $totalQtyPengajuan; ?></b></td>
                            <td><b><?= 'Rp. ' . number_format($totalEstimasi, 0, ",", "."); ?></b></td>
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

                if ($totalAnggaran == 0) {
                    $persentaseProgress = 0;
                    $selisihRealisasi = 0;
                    $selisihAnggaran = 0;
                    $persentaseSisaBudget = 0;
                    $persentaseSisaBudget = 0;
                }

                ?>

                <br>
                <div class="box-header with-border">
                    <div class="form-group">
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
                    </div>
                    <div class="form-group">
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



                <form method="post" name="form" action="add_mr.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="id_divisi" class="col-sm-offset- col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="nm_divisi" value="<?= $rowUser['nm_divisi']; ?>">
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label for="tgl_bkk" class="col-sm-offset-3 col-sm-2 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control tanggal" name="tgl_pengajuan" value="<?= $tanggalCargo ?>">
                            </div>
                        </div>

                        <br>
                        <!-- <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-5 " value="Submit">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div> -->

                        <!-- Modal notif -->
                        <div id="notifBudget" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- konten modal-->
                                <div class="modal-content">
                                    <!-- heading modal -->
                                    <div class="modal-header bg-danger ">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Informasi !</h4>
                                    </div>
                                    <!-- body modal -->
                                    <div class="modal-body">
                                        <div class="perhitungan">
                                            <form class="form-horizontal">
                                                <div class="box-body">
                                                    <input type="hidden" name="id" value="" id="mr_id_kasbon">
                                                    <input type="hidden" name="id_dbo" value="" id="mr_id_dbo">

                                                    <h4> <span class="text-red"><i> Pengajuan mr ini tidak bisa di release karena saldo anggaran tersebut sudah terlimit! </i></span> silahkan kordinasi dengan team anggaran. </h4>

                                                    <div class=" modal-footer">
                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Tutup">
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- div perhitungan -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End notif -->

                    </div>
                </form>

                <!-- Modal Tambah -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog lg">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tambah Barang</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="add_itempengajuan.php" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id_divisi" value="<?= $Divisi ?>">
                                        <div class="form-group">
                                            <label for="nm_barang" class="col-sm-offset-1 col-sm-3 control-label">Nama Barang</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="nm_barang" placeholder="Nama Barang">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                            <div class="col-sm-5">
                                                <select class="form-control select2 programkerja_id" name="id_programkerja" required>
                                                    <option value="">--Program Kerja--</option>
                                                    <?php

                                                    $queryProgramKerja = mysqli_query($koneksi, "SELECT *
                                                                                FROM program_kerja pk
                                                                                JOIN cost_center cc
                                                                                    ON pk.costcenter_id = cc.id_costcenter
                                                                                WHERE cc.divisi_id = '$idDivisi'
                                                                                AND tahun = '$tahun'
                                                                                ORDER BY pk.nm_programkerja ASC
                                                                                ");
                                                    if (mysqli_num_rows($queryProgramKerja)) {
                                                        while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                                    ?>
                                                            <option value="<?= $rowPK['id_programkerja']; ?>" type="checkbox"><?= $rowPK['nm_programkerja']; ?></option>
                                                    <?php endwhile;
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="kotakAnggaran">
                                            <div class="form-group">
                                                <label id="tes" for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                                <div class="col-sm-5">
                                                    <select class="form-control select2 id_anggaran" name="id_anggaran" id="id_anggaran" required>
                                                        <option>--Kode Anggaran--</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="merk" class="col-sm-offset-1 col-sm-3 control-label">Merk</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="merk" placeholder="Merk Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="type" class="col-sm-offset-1 col-sm-3 control-label">Type</label>
                                            <div class="col-sm-5 ">
                                                <input type="text" required class="form-control" name="type" placeholder="Type Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="spesifikasi" class="col-sm-offset-1 col-sm-3 control-label">Spesifikasi </label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control " name="spesifikasi" placeholder="Spesifikasi Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="jumlah" class="col-sm-offset-1 col-sm-3 control-label">Jumlah</label>
                                            <div class="col-sm-5">
                                                <input type="number" min="1" value="1" required class="form-control" name="jumlah">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="satuan" class="col-sm-offset-1 col-sm-3 control-label">Satuan</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="satuan" placeholder="Satuan Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="foto" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung/BA/Foto </label>
                                            <div class="col-sm-5    ">
                                                <div class="input-group input-file" name="foto">
                                                    <input type="text" class="form-control" />
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="keterangan" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                            <div class="col-sm-8">
                                                <textarea rows="7" type="textarea" required class="form-control" name="keterangan" placeholder="Detail Kebutuhan"></textarea>
                                            </div>
                                        </div>
                                        <div class=" modal-footer">
                                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-1 " value="Tambah">
                                            &nbsp;
                                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Akhir Modal Tambah  -->

            </div>
        </div>
    </div>
</section>

<?php
$host = host();
?>

<script>
    var host = '<?= $host ?>';

    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' accept='application/pdf' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function() {
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function() {
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function() {
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor", "pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }
    $(function() {
        bs_input_file();
    });
</script>