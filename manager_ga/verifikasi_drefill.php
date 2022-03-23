<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

if (isset($_POST['approve'])) {

    // deklarasi
    $id_refill  = $_POST['id_refill'];
    $date  = dateNow();

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM refill_funds WHERE id_refill = '$id_refill' "));
    $jenis = $data['jenis'];
    $created_at = $data['created_at'];
    $keterangan = $data['keterangan'];
    $nominal = $data['nominal'];
    $app_mgr = $date;

    /* jika pettycash approval cukup sampai ke pa yudha
        Status 5 : selesai
    */


    mysqli_begin_transaction($koneksi);
    // Jika pettycash auto jadi bkk
    if ($jenis == 'petty_cash' || $jenis == 'transfer_pendapatan') {
        $status = '5';
        $bkk_lokal = '1';

        // Nomor BKK
        $no_bkk = nomorBkkNew($created_at);
        $nomor = nomorAwal($no_bkk);

        $insert = mysqli_query($koneksi, "INSERT INTO bkk_final (pengajuan, id_kdtransaksi, nomor, tgl_bkk, no_bkk, nilai_barang, nominal, keterangan,  created_on_bkk, v_mgr_finance, v_direktur, release_on_bkk,  status_bkk) VALUES
                                                            ('REFILL FUND', '$id_refill', '$nomor', '$created_at', '$no_bkk', '$nominal', '$nominal', '$keterangan' , '$created_at', '$app_mgr' , '$date', '$created_at', '4')");

        // jika non pettycash lanjut ke gm finance
    } else {
        $status = '3';
        $bkk_lokal = '0';

        $insert = 'Berhasil';
    }

    $update = mysqli_query($koneksi, "UPDATE refill_funds
                                                    SET status = '$status', app_mgr = '$date', bkk_lokal = '$bkk_lokal'
                                                    WHERE id_refill = '$id_refill' ");

    if ($insert && $update) {

        mysqli_commit($koneksi);

        setcookie('pesan', 'Permohonan Berhasil di Approve!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        mysqli_rollback($koneksi);
        setcookie('pesan', 'Permohonan gagal di Approve!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }

    header("location:index.php?p=verifikasi_refill");
}

if (isset($_POST['reject'])) {

    // deklarasi
    $id_refill  = $_POST['id_refill'];
    $komentar  = $Nama . " : " . $_POST['komentar'];
    $date  = dateNow();

    mysqli_begin_transaction($koneksi);
    // echo "Kosong";

    $update = mysqli_query($koneksi, "UPDATE refill_funds
                                                    SET status = '202', app_mgr = NULL
                                                    WHERE id_refill = '$id_refill' ");

    $insert = mysqli_query($koneksi, "INSERT INTO tolak_refill (refill_id, komentar, created_at)VALUES 
                                                                ('$id_refill', '$komentar', '$date')");


    if ($update && $insert) {

        mysqli_commit($koneksi);

        setcookie('pesan', 'Permohonan Berhasil di Reject!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        mysqli_rollback($koneksi);
        setcookie('pesan', 'Permohonan gagal di Reject!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }

    header("location:index.php?p=verifikasi_refill");
}


$id = dekripRambo($_GET['id']);

$query =  mysqli_query($koneksi, "SELECT * FROM refill_funds
                                                         WHERE id_refill = '$id' ");
$data = mysqli_fetch_assoc($query);

?>


<section class="content">
    <a href="index.php?p=verifikasi_refill" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
    <br><br>
    <div class="row">

        <!-- Button kembali -->
        <!-- Detail -->
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Refill Fund</h3>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <label id="tes" for="nm_barang" class="col-sm-offset-1 col-sm-3 control-label">Tanggal </label>
                        <div class="col-sm-5">
                            <input type="text" readonly class="form-control is-valid" name="nm_barang" value="<?= formatTanggal($data['created_at']); ?>">
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label id="tes" for="jenis" class="col-sm-offset-1 col-sm-3 control-label">Jenis </label>
                        <div class="col-sm-5">
                            <input type="text" readonly class="form-control is-valid" name="jenis" value="<?= kataJenis($data['jenis']); ?>">
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label id="tes" for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                        <div class="col-sm-5">
                            <input type="text" readonly class="form-control is-valid" name="nominal" value="<?= formatRupiah($data['nominal']); ?>">
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label id="tes" for="keterangan" class="col-sm-offset-1 col-sm-3 control-label">Keterangan </label>
                        <div class="col-sm-5">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 210px" disabled><?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <span class="text-danger">(*Di buat oleh <?= $data['created_by'] ?>)</span>
                </div>

            </div>
        </div>

        <!-- Document Pendukung -->
        <div class="col-sm-12 col-xs-12">
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

    <button type="button" class="btn btn-primary col-sm-offset-10" data-toggle="modal" data-target="#approve"><i class="fa fa-check"></i> Approve </button></span></a>
    &nbsp;
    <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak"> <i class="fa fa-reply"></i> Reject </button></span></a>
</section>

<!-- Approved tanpa persetujuan direktur -->
<div id="approve" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Konfirmasi Persetujuan Permohonan Refill</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="id_refill" value="<?= $data['id_refill']; ?>">
                        <h4 class="text-center">Apakah anda yakin ingin menyetujui permohonan ini ?</h4>
                        <br>
                        <div class=" modal-footer">
                            <button class="btn btn-primary" type="submit" name="approve"><i class="fa fa-check"></i> Ya</button></span>
                            &nbsp;
                            <button class="btn btn-dark" data-dismiss="modal"><i class="fa fa-close"></i> No</button></span>
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
            <div class="modal-header bg-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Alasan Penolakan </h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                    <input type="hidden" name="id_refill" value="<?= $data['id_refill']; ?>">
                    <input type="hidden" value="<?= $Nama; ?>" class="form-control" name="Nama" readonly>
                    <div class="box-body">
                        <div class="mb-3">
                            <label for="validationTextarea">Komentar</label>
                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required></textarea>
                            <div class="invalid-feedback">
                                Please enter a message in the textarea.
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-danger" type="submit" name="reject"><i class="fa fa-reply"></i> Reject</button>
                            &nbsp;
                            <button class="btn btn-dark" data-dismiss="modal"><i class="fa fa-close"></i> No</button>
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
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });
</script>