<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}


$id = dekripRambo($_GET['id']);

$query =  mysqli_query($koneksi, "SELECT * FROM refill_funds
                                                         WHERE id_refill = '$id' ");
$data = mysqli_fetch_assoc($query);

?>


<section class="content">
    <a href="index.php?p=<?= $_GET['back'] ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
    <br><br>
    <div class="row">

        <!-- Button kembali -->
        <!-- Detail -->
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Refill Fund</h3>
                </div>
                <div class="box-body">

                    <div class="form-group">
                        <label id="tes" for="nm_barang" class="col-sm-offset-1 col-sm-3 control-label">Tanggal </label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control is-valid" name="nm_barang" value="<?= formatTanggal($data['created_at']); ?>">
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label id="tes" for="jenis" class="col-sm-offset-1 col-sm-3 control-label">Jenis </label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control is-valid" name="jenis" value="<?= kataJenis($data['jenis']); ?>">
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label id="tes" for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                        <div class="col-sm-7">
                            <input type="text" readonly class="form-control is-valid" name="nominal" value="<?= formatRupiah($data['nominal']); ?>">
                        </div>
                    </div>
                    <br><br>
                    <div class="form-group">
                        <label id="tes" for="keterangan" class="col-sm-offset-1 col-sm-3 control-label">Keterangan </label>
                        <div class="col-sm-7">
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

        <!-- Bukti pembayaran -->
        <?php
        if (file_exists("../file/bukti_pembayaran/" . $data['bukti_pembayaran'] . "") && $data['bukti_pembayaran'] != NULL) { ?>
            <div class="col-sm-12 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Bukti Pembayaran</h3>
                    </div>
                    <div class="box-body">
                        <div class="embed-responsive embed-responsive-4by3">
                            <iframe class="embed-responsive-item" src="../file/bukti_pembayaran/<?= $data['bukti_pembayaran']; ?> "></iframe>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
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
</script>