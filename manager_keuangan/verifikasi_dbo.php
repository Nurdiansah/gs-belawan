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

$query =  mysqli_query($koneksi, "SELECT * FROM bkk_final b     
                                            LEFT JOIN anggaran a
                                            ON b.id_anggaran = a.id_anggaran 
                                            -- JOIN supplier s
                                            -- ON b.id_supplier = s.id_supplier                                                                         
                                            WHERE b.id ='$id' ");
$data2 = mysqli_fetch_assoc($query);
$id_kdtransaksi = $data2['id_kdtransaksi'];

$id_tagihan = $data2['id_tagihan'];


// query jika pengajuan bkk kasbon
if ($data2['pengajuan'] == 'KASBON') {
    $queryKasbon =  mysqli_query($koneksi, "SELECT *
                                                    FROM kasbon k
                                                         JOIN biaya_ops bo
                                                         ON k.kd_transaksi = bo.kd_transaksi
                                                         JOIN divisi d
                                                         ON bo.id_divisi = d.id_divisi
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         LEFT JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id_kdtransaksi' ");
    $data = mysqli_fetch_assoc($queryKasbon);
    $id_dbo = $data['id'];

    $querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo ");

    // query kasbon user
    $queryKU =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                        JOIN detail_biayaops db 
                                            ON k.id_dbo = db.id
                                        JOIN divisi d
                                            ON d.id_divisi = db.id_divisi
                                        LEFT JOIN anggaran a
                                            ON db.id_anggaran = a.id_anggaran 
                                        JOIN supplier s
                                            ON s.id_supplier = db.id_supplier
                                        WHERE k.id_kasbon = '$id_kdtransaksi' ");
    $dataKU = mysqli_fetch_assoc($queryKU);

    $vrf_pajak = $dataKU['vrf_pajak'];
}

// Ketika pengajuan jenis biaya umum
if ($data2['pengajuan'] == 'BIAYA UMUM') {
    $queryBU = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            LEFT JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.kd_transaksi = '$id_kdtransaksi' ");

    $dataBU = mysqli_fetch_assoc($queryBU);
}
// Akhir

// ketika pengajuan PO
if ($data2['pengajuan'] == 'PO') {
    $query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            JOIN po p
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON p.id_dbo = dbo.id
                                            LEFT JOIN anggaran a
                                            ON dbo.id_anggaran = a.id_anggaran
                                            LEFT JOIN pph pp
                                            ON p.id_pph = pp.id_pph
                                            WHERE p.id_po ='$id_kdtransaksi' ");
    $dataPO = mysqli_fetch_assoc($query);

    $id_po = $dataPO['id_po'];

    // var_dump($dataPO['id_pph']); die;

    $id_supplier = $dataPO['id_supplier'];
    $id_anggaran = $dataPO['id_anggaran'];
    $totalPengajuan = $dataPO['grand_totalpo'];

    $id_dbo = $dataPO['id_dbo'];
    $id_divisi = $dataPO['id_divisi'];

    $querySPO =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");
}
// akhir query PO

// ngecek alasan reapprove sama kasir
$reApprove = mysqli_query($koneksi, "SELECT * FROM reapprove_bkk_final WHERE id_bkk_final = '$id'");
$dataReapp = mysqli_fetch_assoc($reApprove);
$jmlReapp = mysqli_num_rows($reApprove);

?>
<section class="content">
    <div class="row">
        <div class="col-md-2">
            <a href="index.php?p=verifikasi_biayaops" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <br><br>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">


                <!-- Detail Job Order -->

                <div class="box-header with-border bg-info">
                    <h3 class="text-center">Verifikasi BKK</h3>
                </div>
                <br>
                <form method="post" action="simpan_bkk.php" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="id_bkk" value="<?= $data2['id'] ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['pengajuan'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['created_on_bkk']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">DPP Barang</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= formatRupiah($data2['nilai_barang']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">DPP Jasa</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= formatRupiah($data2['nilai_jasa']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Nilai PPN</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= formatRupiah($data2['nilai_ppn']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Nilai PPh</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= "( " . formatRupiah($data2['nilai_pph']) . " )"; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">Kode Anggaran </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_anggaran'] . ' [' . $data2['nm_item'] . ']'; ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Total </label>
                            <div class="col-sm-3">
                                <b><input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['nominal']); ?>"> </b>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarks" class="col-sm-offset- col-sm-1 control-label">Remarks</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="remarks" disabled class="form-control "> <?= $data2['remarks']; ?></textarea>
                            </div>
                        </div>

                        <?php if ($data2['pengajuan'] == "KASBON") { ?>
                            <div class="form-group">
                                <?php if ($jmlReapp > 0) { ?>
                                    <label for="remarks" class="col-sm-offset- col-sm-1 control-label">Alasan Pengajuan Kembali</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="remarks" class="form-control" disabled><?= $dataReapp['alasan_reapprove_kasir']; ?></textarea>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } elseif ($data2['pengajuan'] == 'BIAYA KHUSUS') { ?>
                            <div class="form-group">
                                <label for="remarks" class="col-sm-offset- col-sm-1 control-label">Remarks</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="remarks" class="form-control "><?php
                                                                                                        if (isset($data2['remarks'])) {
                                                                                                            echo $data2['remarks'];
                                                                                                        }
                                                                                                        ?></textarea>
                                </div>

                                <?php if ($jmlReapp > 0) { ?>
                                    <label for="remarks" class="col-sm-offset-4 col-sm-1 control-label">Alasan Pengajuan Kembali</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="remarks" class="form-control" disabled><?= $dataReapp['alasan_reapprove_kasir']; ?></textarea>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                            if (isset($_COOKIE['pesan'])) {
                                echo "<div class='form-group'>
                                        <label class='col-sm-offset- col-sm-2'></label>
                                        <span class='text-success'>" . $_COOKIE['pesan'] . "</span>
                                      </div>";
                            }
                            ?>
                            <div class="form-group">
                                <button type="submit" name="simpan" class="col-xs-offset-3 btn bg-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                            <?php }

                        // echo $data2['doc_pendukung'];

                        $doc = "../file/doc_pendukung/" . $data2['doc_pendukung'];
                        if ($data2['doc_pendukung'] != '') {
                            if (file_exists($doc)) { ?>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe src="../file/pdfjs/web/viewer.html?file=../../doc_pendukung/<?= $data2['doc_pendukung']; ?> " frameborder="0" width="100%" height="550"></iframe>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </form>

                <br>
                <!-- 
                    #escalte : Persetujuan dengan pemilihan direktur
                    #Approve : Persetujuan normal 
                    #Approve2 : Persetujuan tanpa melalui direktur

                 -->
                <?php if ($data2['pengajuan'] == 'BIAYA KHUSUS') { ?>
                    <div class="form-group ">
                        <!-- <button type="button" class="btn btn-primary col-sm-offset-1" data-toggle="modal" data-target="#escalate"> Escalate </button></span></a>
                        &nbsp; -->
                        <!-- <button type="button" class="btn btn-primary col-sm-offset-9" data-toggle="modal" data-target="#approve"> Approve </button></span></a> -->
                        &nbsp;
                        <button type="button" class="btn btn-success col-sm-offset-9" data-toggle="modal" data-target="#approve2"> Approve </button></span></a>
                        &nbsp;
                        <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak">Reject </button></span></a>
                        &nbsp;
                        <a target="_blank" href="bkk_new.php?id=<?= enkripRambo($data2['id']); ?>" class="btn btn-primary"><i class="fa fa-print"></i> BKK</a>
                    </div>
                <?php } else { ?>
                    <div class="form-group ">
                        <!-- <button type="button" class="btn btn-primary col-sm-offset-1" data-toggle="modal" data-target="#escalate"> Escalate </button></span></a>
                        &nbsp; -->
                        <button type="button" class="btn btn-primary col-sm-offset-9" data-toggle="modal" data-target="#approve"> Approve </button></span></a>
                        &nbsp;
                        <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak"> Reject </button></span></a>
                        &nbsp;
                        <a target="_blank" href="bkk_new.php?id=<?= enkripRambo($data2['id']); ?>" class="btn btn-success"><i class="fa fa-print"></i> BKK</a>
                    </div>
                <?php } ?>

                <!-- Tombol detail kasbon purchasing  -->
                <?php
                if (isset($dataKU['from_user'])) {
                    if ($data['from_user'] == '0') { ?>
                        <div class="row">
                            <div class="col-sm-offset-10 col-sm-1">
                                <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-kasbon-purchasing" aria-expanded="false" aria-controls="collapseExample">
                                    <i id="logo" class=""></i>
                                    <span id="tmlKp"></span>
                                </button>
                            </div>
                        </div>
                <?php }
                } ?>
                <!-- Akhir tombol detail kasbon purchasing  -->

                <!-- Tombol detail kasbon user -->
                <?php
                if (isset($dataKU['from_user'])) {
                    if ($dataKU['from_user'] == '1') { ?>
                        <div class="row">
                            <div class="col-sm-offset-10 col-sm-1">
                                <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-kasbon-user" aria-expanded="false" aria-controls="collapseExample">
                                    <i id="logo" class=""></i>
                                    <span id="tmlKp"></span>
                                </button>
                            </div>
                        </div>
                <?php }
                } ?>
                <!-- Akhir tombol detail kasbon user -->

                <!-- Tombol detail biaya umum -->
                <?php
                if ($data2['pengajuan'] == 'BIAYA UMUM') {
                ?>
                    <div class="row">
                        <div class="col-sm-offset-10 col-sm-1">
                            <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-biaya-umum" aria-expanded="false" aria-controls="collapseExample">
                                <i id="logo" class=""></i>
                                <span id="tmlKp"></span>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <!-- Akhir tombol detail biaya umum -->

                <!-- Tombol detail po -->
                <?php if ($data2['pengajuan'] == 'PO') { ?>
                    <div class="row">
                        <div class="col-sm-offset-9 col-sm-3">
                            <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#clp-tagihan" aria-expanded="false" aria-controls="collapseExample">
                                <i id="logoTagihan" class=""></i>
                                <span id="tblTagihan">Riwayat Tagihan</span>
                            </button>
                            <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-po" aria-expanded="false" aria-controls="collapseExample">
                                <i id="logo" class=""></i>
                                <span id="tmlKp"></span>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <!-- Akhir tombol detail po -->

                <br>
            </div>
        </div>
    </div>

    <!-- Approved dengan pemilihan direktur  -->
    <div id="escalate" class="modal fade" role="dialog">
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
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk3.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <h4 class="text-center">Apakah anda yakin ingin menyetujui bkk ini? <br> jika ya silahkan pilih direktur pada kolom di bawah .</h4>
                            </div>
                            <input type="hidden" name="id" value="<?= $dataPO['id']; ?>">
                            <div class="form-group">
                                <label id="tes" for="id_direktur" class="col-sm-offset-1 col-sm-3 control-label">Direktur</label>
                                <div class="col-sm-5">
                                    <select name="id_direktur" class="form-control" required>
                                        <option value="">-- Pilih direktur --</option>
                                        <?php
                                        $queryDirektur = mysqli_query($koneksi, "SELECT * FROM user WHERE level = 'direktur' ORDER BY nama ASC");
                                        if (mysqli_num_rows($queryDirektur)) {
                                            while ($rowDirektur = mysqli_fetch_assoc($queryDirektur)) :
                                        ?>
                                                <option value="<?= $rowDirektur['id_user']; ?>" type="checkbox"><?= $rowDirektur['nama']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Kirim</button></span>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  -->

    <!-- Approved normal -->
    <div id="approve" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Konfirmasi </h4>
                </div>
                <form method="post" enctype="multipart/form-data" action="setuju_bkk4.php" class="form-horizontal">
                    <!-- body modal -->
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $data2['id']; ?>">
                        <div class="box-body">
                            <h4 class="text-center">Apakah anda yakin ingin menyetujui BKK ini ?</h4>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit"> Yes </button>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->

    <!-- Approved tanpa persetujuan direktur -->
    <div id="approve2" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Konfirmasi Persetujuan BKK </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk_direct.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="<?= $data2['id']; ?>">
                            <h4 class="text-center">Apakah anda yakin ingin menyetujui pengajuan ini ?</h4>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Ya</button></span>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                            </div>
                        </div>
                    </form>
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
                    <form method="post" enctype="multipart/form-data" action="tolak_bkk.php" class="form-horizontal">
                        <input type="hidden" value="verifikasi_biayaops" class="form-control" name="url" readonly>
                        <input type="hidden" value="<?= $Nama; ?>" class="form-control" name="Nama" readonly>
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['id']; ?>" class="form-control" name="id" readonly>
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

        var logo = 'fa fa-arrow-down';
        var tmlKp = 'Detail';

        $("#tmlKp").html(tmlKp);
        $("#logo").addClass(logo);


        $("#tmlKp").click(function() {
            if (tmlKp == 'Detail') {
                $("#tmlKp").html('Hide');
                tmlKp = 'Hide';

                $("#logo").removeClass(logo);
                $("#logo").addClass("fa fa-arrow-up");


            } else {
                $("#tmlKp").html('Detail');
                tmlKp = 'Detail';

                $("#logo").removeClass(logo);
                $("#logo").addClass("fa fa-arrow-down");
            }
        });
    });
</script>