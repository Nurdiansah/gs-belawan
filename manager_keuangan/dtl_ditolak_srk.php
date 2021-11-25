<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);

$queryData =  mysqli_query($koneksi, "SELECT *  FROM sr s
                                                JOIN anggaran a
                                                ON a.id_anggaran = s.id_anggaran
                                                JOIN kasbon k
                                                ON k.sr_id = s.id_sr
                                                WHERE s.id_sr = $id ");
$data = mysqli_fetch_assoc($queryData);

$isiDoc =  "../file/doc_penawaran/" . $data['doc_penawaran'];
if (file_exists($isiDoc)) {
    $isiDoc = 1;
} else {
    $isiDoc = 0;
}


$isiDocQt =  "../file/doc_quotation/" . $data['doc_quotation'];

if (file_exists($isiDocQt)) {
    $isiDocQt = 1;
} else {
    $isiDocQt = 0;
}

$queryDSR =  mysqli_query($koneksi, "SELECT *  FROM detail_sr dsr
                                        INNER JOIN sr s
                                            ON dsr.sr_id = s.id_sr
                                        WHERE id_sr = '$id' ");

$jumlahData  = mysqli_num_rows($queryDSR);


?>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-md-2 col-xs-3">
            <a href="index.php?p=ditolak_kasbon&sp=tolak_sr" class="btn btn-success"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <div class="col-sm-3 col-sm-offset-7 col-xs-offset-3 col-xs-3">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectSr"><i class="fa fa-reply"></i> Reject</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSr"><i class="fa fa-send"></i> Approve</button>
        </div>
        <br><br>
    </div>

    <!-- Modal Reject -->
    <div id="rejectSr" class="modal fade" role="dialog">
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
                    <form method="post" enctype="multipart/form-data" action="tolak_kasbon_sr.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" id="id" value="<?= $data['id_kasbon']; ?>">
                            <input type="hidden" name="url" id="url" value="ditolak_kasbon&sp=tolak_sr">
                            <div class="mb-3">
                                <label for="validationTextarea">Komentar</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
                                <div class="invalid-feedback">
                                    Please enter a message in the textarea.
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="update">Kirim</button></span></a>
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
    <!-- Modal release -->
    <div id="approveSr" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Konfirmasi</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <div class="perhitungan">
                        <form method="post" name="form" enctype="multipart/form-data" action="setuju_kembali_ksr.php" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" name="id" value="<?= $data['id_kasbon']; ?>">
                                <h4>Apakah anda yakin ingin menyetujui service request <b><?= $data['nm_barang']; ?> ? </b></h4>
                                <div class=" modal-footer">
                                    <button class="btn btn-primary" type="submit" name="approve">Ya, Saya yakin</button></span></a>
                                    &nbsp;
                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                </div>
                            </div>
                        </form>
                        <!-- div perhitungan -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End release -->
    <!-- SR -->
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Form Penawaran</h3>
                </div>
                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-3 control-label">Supplier</label>
                            <div class="col-sm-6">
                                <select id="idSupplier" class="form-control" name="id_supplier" readonly>
                                    <option value="">--- Pilih Supplier ---</option>
                                    <?php
                                    $querySupplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier != '$id_supplier' ORDER BY nm_supplier ASC");
                                    if (mysqli_num_rows($querySupplier)) {
                                        while ($rowSupplier = mysqli_fetch_assoc($querySupplier)) :
                                    ?>
                                            <option <?php if ($rowSupplier['id_supplier'] == $data['id_supplier']) echo 'selected="selected"'; ?> value="<?= $rowSupplier['id_supplier']; ?>" type="checkbox"><?= $rowSupplier['nm_supplier']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="perhitungan">
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="<?= formatRupiah2($data['nominal']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" readonly>
                                        <!-- <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="<?= formatRupiah2($data['total']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" readonly> -->
                                    </div>
                                </div>
                            </div>
                            <!-- Tambahan  -->
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Diskon </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="diskon_sr" id="diskon_sr" value="<?= formatRupiah2($data['diskon']); ?>" placeholder="0" readonly onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Total </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="total_sr" id="total_sr" value="<?= formatRupiah2($data['total']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Nilai PPN</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nilai_ppn" id="nilai_ppn" value="<?= formatRupiah2($data['nilai_ppn']); ?>" placeholder="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Grand Total</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" id="grand_totalsr" name="grand_totalsr" value="<?= formatRupiah2($data['grand_total']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="validationTextarea" class="col-sm-3 control-label">Note</label>
                            <div class="col-sm-6">
                                <textarea rows="5" class="form-control is-invalid" name="note_sr" id="validationTextarea" readonly><?= $data['note']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Document Penawaran</h3>
                </div>
                <div class="box-body">
                    <?php if ($isiDoc == 1) { ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?= $data['doc_penawaran'] ?> "></iframe>
                        </div>
                    <?php } else {
                        echo "<h4 class='text-center'>-- Document Kosong --</h4>";
                    } ?>

                </div>
                <br>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Document Quotation</h3>
                </div>
                <div class="box-body">
                    <?php if ($isiDocQt == 1) { ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/doc_quotation/<?= $data['doc_quotation'] ?> "></iframe>
                        </div>
                    <?php } else {
                        echo "<h4 class='text-center'>-- Document Kosong --</h4>";
                    } ?>

                </div>
                <br>
            </div>
        </div>
    </div>
    <!-- End SR -->

    <!-- SR -->
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Service Request</h3>
                </div>
                <form class="form-horizontal">
                    <input type="hidden" readonly class="form-control is-valid" name="id" value="<?= $id; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="hidden" for="nm_barang" class="col-sm-offset col-sm-3 control-label">Nama Barang</label>
                            <input type="hidden" readonly class="form-control is-valid" name="url" value="buat_sr">
                            <div class="col-sm-6">
                                <input type="text" readonly class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="id_anggaran" readonly>
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$idDivisi' AND tahun = '$tahun' ORDER BY nm_item ASC");
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
                            <label for="keterangan" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-6">
                                <textarea rows="5" type="text" readonly name="keterangan" readonly class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Document BA</h3>
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_ba'] ?> "></iframe>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>


    <!-- Detail sr -->
    <?php
    if (isset($_COOKIE['pesan2'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan2'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Rincian Service Request</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-hover" id="material">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Merk</th>
                                        <th>Type</th>
                                        <th>Spesifikasi</th>
                                        <th>Qty</th>
                                        <th>Satuan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($queryDSR)) {
                                        while ($row = mysqli_fetch_assoc($queryDSR)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['deskripsi']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['type']; ?> </td>
                                            <td> <?= $row['spesifikasi']; ?> </td>
                                            <td> <?= $row['qty']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            </tr>
                                    <?php
                                            $no++;
                                        endwhile;
                                    }

                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                            <td colspan='9'> Tidak Ada Data</td>
                                        </tr>
                                        ";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Rincian</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="add_dsr.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="sr_id" value="<?= $id ?>">
                        <input type="hidden" name="id_dsr">
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" readonly class="form-control" name="deskripsi" placeholder="Deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Merk</label>
                            <div class="col-sm-8 ">
                                <input type="text" readonly class="form-control" name="merk" placeholder="Merk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Type</label>
                            <div class="col-sm-8 ">
                                <input type="text" readonly class="form-control" name="type" placeholder="Type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Spesifikasi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" readonly class="form-control" name="spesifikasi" placeholder="Spesifikasi"></textarea>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                            <div class="col-sm-8">
                                <input type="number" readonly class="form-control" name="qty">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Satuan</label>
                            <div class="col-sm-8 ">
                                <input type="text" readonly class="form-control" name="satuan" placeholder="Satuan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" readonly class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button type="submit" name="create" class="btn btn-primary col-sm-offset-1 "><i class="fa fa-add"></i>Tambah</button>
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

<!-- Modal Edit -->
<div id="editDsr" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Rincian</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="upd_dsr.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="sr_id" value="<?= $id ?>">
                        <input type="hidden" name="id_dsr" id="me_id_dsr">
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" readonly class="form-control" name="deskripsi" id="me_deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Merk</label>
                            <div class="col-sm-8 ">
                                <input type="text" readonly class="form-control" name="merk" placeholder="Merk" id="me_merk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Type</label>
                            <div class="col-sm-8 ">
                                <input type="text" readonly class="form-control" name="type" placeholder="Type" id="me_type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Spesifikasi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" readonly class="form-control" name="spesifikasi" id="me_spesifikasi"></textarea>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                            <div class="col-sm-8">
                                <input type="number" readonly class="form-control" name="qty" id="me_qty">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Satuan</label>
                            <div class="col-sm-8 ">
                                <input type="text" readonly class="form-control" name="satuan" placeholder="Satuan" id="me_satuan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" readonly class="form-control" name="keterangan" placeholder="Keterangan" id="me_keterangan"></textarea>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button type="submit" name="update" class="btn btn-primary col-sm-offset-1 "><i class="fa fa-add"></i>Tambah</button>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Edit  -->

<!-- Modal hapus -->
<div id="hapusDsr" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" enctype="multipart/form-data" action="del_dsr.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="sr_id" value="" id="md_sr_id">
                            <input type="hidden" name="id" value="" id="md_id_dsr">
                            <h4>Apakah anda yakin ingin menghapus item <b><span id="md_deskripsi"></b></span></h4>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="delete">Delete</button></span></a>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                    <!-- div perhitungan -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End hapus -->

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

        // $('.js-example-basic-single').select2();
    });

    // Format Select 2
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        var $state = $(
            '<span> <span></span></span>'
        );

        // Use .text() instead of HTML string concatenation to avoid script injection issues
        $state.find("span").text(state.text);

        return $state;
    };

    $("#idSupplier").select2({
        templateSelection: formatState
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

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_dsr').val(data.id_dsr);
                    $('#me_deskripsi').val(data.deskripsi);
                    $('#me_merk').val(data.merk);
                    $('#me_type').val(data.type);
                    $('#me_spesifikasi').val(data.spesifikasi);
                    $('#me_qty').val(data.qty);
                    $('#me_satuan').val(data.satuan);
                    $('#me_keterangan').val(data.keterangan);
                }
            });
        });
    });
    // Akhir modal edit

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#md_id_dsr').val(data.id_dsr);
                    $('#md_sr_id').val(data.sr_id);
                    $('#md_deskripsi').text(data.deskripsi);
                }
            });
        });
    });
    // Akhir modal delete


    var nilai_ppn = parseInt($("#nilai_ppn").val())
    console.log(nilai_ppn);
    if (nilai_ppn > 0) {
        $('#myCheck').prop('checked', true);
    } else {
        $('#myCheck').prop('checked', false);
    }

    // Perhitungan
    $(".perhitungan").keyup(function() {


        //ambil inputan harga            

        var diskon_sr = parseInt($("#diskon_sr").val())

        var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik
        // var nominal = parseInt($("#nominal").val())

        var total_sr = nominal - diskon_sr;

        var total_sra = tandaPemisahTitik(total_sr);
        document.form.total_sr.value = total_sra;

        var grand_totalsr = total_sr;
        var grand_totalsra = tandaPemisahTitik(grand_totalsr);

        document.form.grand_totalsr.value = grand_totalsra;

    });

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var diskon_sr = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('diskon_sr').value))))); //input ke dalam angka tanpa titik

            var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik

            var total_sr = nominal - diskon_sr;

            var total_sra = tandaPemisahTitik(total_sr);

            var nilai_ppn = Math.floor(0.1 * total_sr);

            var nilai_ppna = tandaPemisahTitik(nilai_ppn);

            document.form.nilai_ppn.value = nilai_ppna;

            var grand_totalsr = total_sr + nilai_ppn;
            var grand_totalsra = tandaPemisahTitik(grand_totalsr);

            document.form.grand_totalsr.value = grand_totalsra;


        } else if (checkBox.checked == false) {
            var diskon_sr = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('diskon_sr').value))))); //input ke dalam angka tanpa titik

            var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik

            var total_sr = nominal - diskon_sr;

            var total_sra = tandaPemisahTitik(total_sr);

            var nilai_ppn = 0;

            document.form.nilai_ppn.value = 0;

            var grand_totalsr = total_sr;
            var grand_totalsra = tandaPemisahTitik(grand_totalsr);

            document.form.grand_totalsr.value = grand_totalsra;
        }
    }
</script>