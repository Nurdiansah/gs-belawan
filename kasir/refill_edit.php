<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}


if (isset($_POST['update'])) {

    // deklarasi
    $id_refill  = $_POST['id_refill'];
    $nominal  = penghilangTitik($_POST['nominal']);
    $jenis  = $_POST['jenis'];
    $keterangan  = $_POST['keterangan'];
    $created_at  = datetimeHtml($_POST['created_at']);
    $updated_by  = $_POST['updated_by'];
    $date = dateNow();


    // Jika file yang di upload bukan pdf
    if ($nominal <= 0) {
        setcookie('pesan', 'Nominal harus lebih dari 0!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    } else {



        mysqli_begin_transaction($koneksi);

        if (empty($_FILES['doc_pendukung']['name'])) {
            // echo "Kosong";

            $return = mysqli_query($koneksi, "UPDATE refill_funds
                                                    SET jenis = '$jenis', 
                                                        keterangan = '$keterangan', 
                                                        nominal = '$nominal', 
                                                        created_at = '$created_at', 
                                                        updated_by = '$updated_by', 
                                                        updated_at =  '$date'
                                                    WHERE id_refill = '$id_refill' ");


            if ($return) {

                mysqli_commit($koneksi);

                setcookie('pesan', 'Pengajuan Berhasil di buat!', time() + (3), '/');
                setcookie('warna', 'alert-success', time() + (3), '/');
            } else {

                mysqli_rollback($koneksi);
                setcookie('pesan', 'Pengajuan gagal di buat!', time() + (3), '/');
                setcookie('warna', 'alert-danger', time() + (3), '/');
            }

            // kalo document pendukung di update juga
        } else {
            // echo "Isi";
            $del_lpj = $_POST['doc_pendukung_lama'];
            if (isset($del_lpj)) {
                unlink("../file/doc_pendukung/$del_lpj");
            }

            //baca lokasi file sementara dan nama file dari form (doc_ptw)		
            $lokasi_doc = ($_FILES['doc_pendukung']['tmp_name']);
            $doc = ($_FILES['doc_pendukung']['name']);
            $ekstensi = pathinfo($doc, PATHINFO_EXTENSION);

            // Cek Document yang di lampirkan
            if ($ekstensi != 'pdf') {
                setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
                setcookie('warna', 'alert-danger', time() + (3), '/');
            } else {

                $generateString = md5(time());
                $namaDoc = $generateString . "-doc-refill." . $ekstensi;

                $return = mysqli_query($koneksi, "UPDATE refill_funds
                                                    SET jenis = '$jenis', 
                                                        keterangan = '$keterangan', 
                                                        nominal = '$nominal', 
                                                        doc_pendukung = '$namaDoc', 
                                                        updated_by = '$updated_by', 
                                                        updated_at = '$date'
                                                    WHERE id_refill = '$id_refill' ");

                if ($return) {

                    move_uploaded_file($lokasi_doc, "../file/doc_pendukung/" . $namaDoc);

                    mysqli_commit($koneksi);

                    setcookie('pesan', 'Pengajuan Berhasil di buat!', time() + (3), '/');
                    setcookie('warna', 'alert-success', time() + (3), '/');
                } else {

                    mysqli_rollback($koneksi);
                    setcookie('pesan', 'Pengajuan gagal di buat!', time() + (3), '/');
                    setcookie('warna', 'alert-danger', time() + (3), '/');
                }
            }
        }
    }


    header("location:index.php?p=refill_edit&id=" . enkripRambo($id_refill));
}


$id = dekripRambo($_GET['id']);

$query =  mysqli_query($koneksi, "SELECT * FROM refill_funds
                                                         WHERE id_refill = '$id' ");
$data = mysqli_fetch_assoc($query);



?>


<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>

    <a href="index.php?p=create_refill" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
    <br><br>
    <div class="row">

        <!-- Button kembali -->
        <!-- Detail -->
        <div class="col-sm-6 col-xs-12">
            <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                <input type="hidden" name="updated_by" value="<?= $Nama ?>">
                <input type="hidden" name="id_refill" value="<?= $data['id_refill'] ?>">
                <input type="hidden" name="doc_pendukung_lama" value="<?= $data['doc_pendukung'] ?>">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Detail Refill Fund</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset-1 col-sm-3 control-label">Tanggal </label>
                            <div class="col-sm-7">
                                <input type="datetime-local" class="form-control " name="created_at" value="<?= convertDatetimeLocal($data['created_at']); ?>" autocomplete=" off" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="doc_pendukung" class="col-sm-offset-1 col-sm-3 control-label">Jenis </label>
                            <div class="col-sm-5">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" value="petty_cash" id="flexRadioDefault2" <?= $data['jenis'] == 'petty_cash' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Petty Cash
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" value="kas_besar" id="flexRadioDefault1" <?= $data['jenis'] == 'kas_besar' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Kas Besar
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" value="transfer_pendapatan" id="transfer_pendapatan" <?= $data['jenis'] == 'transfer_pendapatan' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="transfer_pendapatan">
                                        Transfer Pendapatan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" value="droping_fund" id="droping_fund" <?= $data['jenis'] == 'droping_fund' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="droping_fund">
                                        Droping Fund
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label id="tes" for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <span class="input-group-addon ">Rp.</span>
                                    <input type="text" class="form-control" name="nominal" id="nominal" value="<?= formatRibuan($data['nominal']); ?>" autocomplete="off" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="doc_pendukung" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                            <div class="col-sm-7">
                                <div class="input-group input-file" name="doc_pendukung">
                                    <input type="text" class="form-control" placeholder="*Opsional" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <span class="text-danger"><i>*Kosongkan jika dokumen tidak ingin di rubah</i></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label id="tes" for="keterangan" class="col-sm-offset-1 col-sm-3 control-label">Keterangan </label>
                            <div class="col-sm-7">
                                <div class="form-floating">
                                    <textarea class="form-control" name="keterangan" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 210px"><?= $data['keterangan']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="form-group">
                            <button class="col-sm-offset-8 btn btn-success" name="update" type="submit"><i class="fa fa-save"></i> Update</button>
                            <button class="btn btn-danger" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                        <span class="text-danger">(*Di buat oleh <?= $data['created_by'] ?>)</span>
                    </div>
            </form>

        </div>
    </div>

    <!-- Document Pendukung -->
    <div class="col-sm-6 col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="text-center">Dokumen Pendukung</h3>
            </div>
            <div class="box-body">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_pendukung']; ?> "></iframe>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    </div>
</section>


<script>
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