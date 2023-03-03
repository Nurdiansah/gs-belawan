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

$query =  mysqli_query($koneksi, "SELECT *, b.id as id_bkk_final FROM bkk_final b     
                                    JOIN anggaran a
                                        ON b.id_anggaran = a.id_anggaran 
                                    LEFT JOIN tolak_bkk_final
                                        ON b.id = id_bkk_final                                                                       
                                    WHERE b.id ='$id' ");
$data2 = mysqli_fetch_assoc($query);
$id_kdtransaksi = $data2['id_kdtransaksi'];

// simpan perubahan data
if (isset($_POST['simpan'])) {

    // cek jika inputan file tidak ada maka memakai file lama
    $cek_lpj = ($_FILES['doc_pendukung']['name']);
    if ($cek_lpj == '') {
        $nama_doc = $_POST['doc_pendukung_lama'];
    } else {
        // Haspus dulu dokument lama nya
        $doc_lama = $_POST['doc_pendukung_lama'];
        unlink("../file/doc_pendukung/" . $doc_lama);

        // Upload document pendukung yang baru
        $lokasi_doc_pendukung = ($_FILES['doc_pendukung']['tmp_name']);
        $doc_pendukung = ($_FILES['doc_pendukung']['name']);
        $ekstensi = pathinfo($doc_pendukung, PATHINFO_EXTENSION);
        $nama_doc = "doc-pendukung-biaya-khusus-" . time() . "." . $ekstensi;
        move_uploaded_file($lokasi_doc_pendukung, "../file/doc_pendukung/" . $nama_doc);
    }
    // Akhir upload document pendukung

    $id = $_POST['id'];
    $id_anggaran = $_POST['id_anggaran'];
    $nominal = str_replace(".", "", $_POST['nominal']);
    $keterangan = $_POST['keterangan'];
    $remarks = $_POST['remarks'];

    $updateBK = mysqli_query($koneksi, "UPDATE bkk_final SET id_anggaran = '$id_anggaran',
                                            nilai_barang = '$nominal',
                                            nominal = '$nominal',
                                            keterangan = '$keterangan',
                                            remarks = '$remarks',
                                            doc_pendukung = '$nama_doc'
                                        WHERE id = '$id'
    ");

    if ($updateBK) {
        setcookie('pesan', 'Berhasil di simpan!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header('Location: index.php?p=dtl_bkditolak&id=' . $id . '');
    }
}
// end simpan perubahan data
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
                    <div class="col-md-2">
                        <a href="index.php?p=ditolak_biayakhusus" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Detail Biaya Khusus Ditolak</h3>
                </div>
                <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <input type="hidden" name="doc_pendukung_lama" value="<?= $data2['doc_pendukung']; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset-1 col-sm-1 control-label">Kode Anggaran </label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran">
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='20' AND tahun='$tahun' ORDER BY nm_item ASC");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" <?php if ($rowAnggaran['kd_anggaran'] == $data2['kd_anggaran']) {
                                                                                                    echo "selected=selected";
                                                                                                } ?>><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                            <label for="nominal" class="col-sm-offset- col-sm-2 control-label">Nominal</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control is-valid" name="nominal" value="<?= formatRupiah2($data2['nominal']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset-1 col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                            <label for="remarks" class="col-sm-offset-1 col-sm-1 control-label">Remarks</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="remarks" class="form-control "><?php
                                                                                                    if (!isset($data['remarks'])) {
                                                                                                        echo $data2['remarks'];
                                                                                                    }
                                                                                                    ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="alasan_penolakan" class="col-sm-offset-1 col-sm-1 control-lable">Alasan Penolakan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="remarks" class="form-control " disabled><?= $data2['alasan_tolak_mgrfin'] ?></textarea>
                            </div>

                            <label for="" class="col-sm-offset-1 col-sm-1 control-lable">Waktu Penolakan</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" disabled value="<?= $data2['waktu_tolak_mgrfin']; ?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="doc_pendukung" class="col-sm-offset-1 col-sm-1 control-label">Document Pendukung </label>
                            <div class="col-sm-4">
                                <div class="input-group input-file" name="doc_pendukung">
                                    <input type="text" class="form-control" placeholder="*Browse" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <span class="text-danger"><i>*Kosongkan jika tidak ada perubahan</i></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-8 col-sm-6">
                                <br>
                                <button type="submit" name="simpan" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                <a href="hapus_bkditolak.php?id=<?= $data2['id_bkk_final']; ?>&pg=ditolak_biayakhusus" class="btn btn-danger" onclick="return confirm('Yakin pengajuan Biaya Khusus dihapus?')"><i class="fa fa-trash"></i> Hapus Pengajuan</a>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approve"><i class="fa fa-send"></i> Ajukan Kembali</button></span></a>
                            </div>
                        </div>

                        <!-- Document pendukung  -->
                        <?php
                        if ($data2['pengajuan'] == 'BIAYA KHUSUS') { ?>
                            <?php if (!empty($data2['doc_pendukung'])) {
                                $doc =  "../file/doc_pendukung/" . $data2['doc_pendukung'];
                                if (file_exists($doc)) { ?>
                                    <h3 class="text-center">Document LPJ</h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?php echo $data2['doc_pendukung']; ?> "></iframe>
                                        <!-- <object width="800" height="500"></object> -->
                                    </div>
                                <?php } else {
                                    echo "";
                                } ?>

                            <?php } ?>
                        <?php } ?>
                        <!-- akhir document pendukung -->
                    </div>
                </form>
                <div class="form-group ">

                </div>
                <br>
            </div>
        </div>
    </div>
</section>

<!--  -->
<div id="approve" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alasan Ajukan Kembali</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="reapprovekasir_bkk.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $id; ?>" class="form-control" name="id">
                                <input type="hidden" name="pg" value="ditolak_biayakhusus">
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
                            <button class="btn btn-success" type="submit" name="kirim">Kirim</button></span></a>
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

    // Browse
    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost'  accept='application/pdf' style='visibility:hidden; height:0'>");
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