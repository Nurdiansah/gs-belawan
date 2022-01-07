<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['id_divisi'];

$queryDetail =  mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp
                                                JOIN anggaran a
                                                ON a.id_anggaran = tp.id_anggaran
                                                WHERE tp.id_pettycash = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$idAnggaran = $data['id_anggaran'];

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id ");

$tanggalCargo = date("Y-m-d");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=lihat_detailanggaran&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_sdboedit&id=$id");
    }
}

if (isset($_POST['tolak'])) {
    $id = $_POST['id_pettycash'];
    $komentar = "@" . $_POST['Nama'] . " : " . $_POST['komentar'];

    $tolak = mysqli_query($koneksi, "UPDATE transaksi_pettycash SET status_pettycash = '202', komentar_pettycash = '$komentar'
                                        WHERE id_pettycash = '$id'
                                    ");

    if ($tolak) {
        header('Location: index.php?p=payment_pettycash');
    }
}

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Payment Pettycash</h3>
                </div>
                <form method="post" name="form" action="upd_revisi_petty.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label">ID Pettycash </label>
                            <div class="col-sm-3">
                                <input name="id" type="text" class="form-control" value="<?= $data['kd_pettycash']; ?>" readonly>
                            </div>
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label"> </label>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payment"> Payment </button></span></a>
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject"> Reject </button></span></a>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-1 control-label">Nominal </label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon ">Rp.</span>
                                    <input type="text" class="form-control" value="<?= formatRupiah2($data['total_pettycash']); ?>" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" readonly>
                                </div>
                            </div>
                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran" disabled>
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran != '$idAnggaran' ORDER BY nm_item ASC");
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
                            <div class="mb-3">
                                <label for="validationTextarea" class="col-sm-offset-1 col-sm-1 control-label">Deskripsi : </label>
                                <div class="col-sm-8">
                                    <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Deskripsi" readonly><?= $data['keterangan_pettycash']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">

                        </div>
                        <div class="box-header with-border">
                            <?php if (!empty($data['doc_lpj_pettycash'])) { ?>
                                <h3 class="text-center">Document LPJ</h3>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/doc_lpj/<?= $data['doc_lpj_pettycash']; ?> "></iframe>
                                </div>
                            <?php } ?>

                        </div>
                        <br>
                </form>
            </div>
        </div>
    </div>
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
                <form method="post" enctype="multipart/form-data" action="upd_pettycash.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" name="nominal" value="<?= $data['total_pettycash'] ?>">
                                <input type="hidden" name="id_anggaran" value="<?= $data['id_anggaran'] ?>">
                                <input type="hidden" name="from" value="<?= $data['from']; ?>">
                                <input type="hidden" name="total_pettycash" value="<?= $data['total_pettycash']; ?>">
                                <input type="hidden" value="<?= $data['id_pettycash']; ?>" class="form-control" name="id_pettycash" readonly>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nominal" disabled value="<?= formatRupiah($data['total_pettycash']); ?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="penerima_dana" class="col-sm-offset-1 col-sm-3 control-label">Nama </label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="penerima_dana" placeholder="Nama Penerima Dana">
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

<!--  -->
<div id="reject" class="modal fade" role="dialog">
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
                <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['id_pettycash']; ?>" class="form-control" name="id_pettycash" readonly>
                                <input type="hidden" value="payment_pettycash" class="form-control" name="url" readonly>
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

    $(document).ready(function() {
        $('.datatab').DataTable();
    });

    // batas script baru
</script>