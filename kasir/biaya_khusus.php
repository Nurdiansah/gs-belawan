<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'payment') {
        header("location:?p=send_paymentkhusus&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * FROM bkk_final b
                                        LEFT JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran                                       
                                        WHERE b.status_bkk = '0' AND b.pengajuan='BIAYA KHUSUS'
                                        UNION
                                        SELECT *  FROM bkk_final b
                                        LEFT JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran                                   
                                        WHERE b.status_bkk between 1 ANd 3 AND b.pengajuan='BIAYA KHUSUS'

                                                ");

$jumlahData = mysqli_num_rows($query);
?>
<!-- Main content -->
<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="box-header with-border">
                    <div class="col-sm-offset-11">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create"><i class="fa fa-envelope-o"></i> Create </button></span></a>
                    </div>
                    <h3 class="text-center">Biaya Operasional</h3>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $no = 1;
                                        if (mysqli_num_rows($query)) {
                                            while ($row = mysqli_fetch_assoc($query)) :
                                        ?>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['created_on_bkk']); ?> </td>
                                                <td> <?= batasiKata($row['keterangan']); ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> </td>
                                                <td> <?= formatRupiah($row['nominal']); ?> </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary modalLihat" data-toggle="modal" data-target="#lihatBkk" data-id="<?= $row['id']; ?>"><i class="fa fa-binoculars"></i> Show</button>
                                                    <?php if ($row['status_bkk'] == 0) { ?>
                                                        <button type="button" class="btn btn-success modalEdit" data-toggle="modal" data-target="#editBkk" data-id="<?= $row['id']; ?>"><i class="fa fa-edit"></i> Edit</button>
                                                        <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#hapusBkk" data-id="<?= $row['id']; ?>"><i class="fa fa-trash"></i> Delete</button>
                                                        <a href="release_biayakhusus.php?id=<?= enkripRambo($row['id']); ?>"><span data-placement='top' data-toggle='tooltip' title='Release'><button type="button" class="btn btn-warning"><i class="fa fa-rocket"> </i> Release</button></span></a>
                                                    <?php  } else if ($row['status_bkk'] == 1) { ?>
                                                        <span class="label label-primary">Menunggu Approve Manager Finance </span>
                                                    <?php  } else if ($row['status_bkk'] == 2) { ?>
                                                        <span class="label label-primary">Menunggu Approve Direktur</span>
                                                    <?php  } else if ($row['status_bkk'] == 3) { ?>
                                                        <a href="?p=biaya_khusus&aksi=payment&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Payment' onclick="javascript: return confirm('Konfirmasi Pembayaran ?')"><button type="button" class="btn btn-warning">Payment</button></span></a>
                                                    <?php  } ?>
                                                </td>
                                    </tr>
                            <?php
                                                $no++;
                                            endwhile;
                                        }
                                        if ($jumlahData == 0) {
                                            echo
                                            "<tr>
                                                  <td colspan='7'> Tidak Ada Data</td>
                                             </tr>
                                             ";
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Create  -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buat Biaya Operasional</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="create_bkk_khusus.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2" name="id_anggaran" required>
                                        <option value="">--Kode Anggaran--</option>
                                        <?php
                                        $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='20' AND tahun='$tahun' ORDER BY nm_item ASC");
                                        if (mysqli_num_rows($queryAnggaran)) {
                                            while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                        ?>
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nilai_barang" class="col-sm-offset-1 col-sm-3 control-label">Nilai Barang </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nilai_barang" id="nilai_barang" autocomplete="off" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nilai_jasa" class="col-sm-offset-1 col-sm-3 control-label">Nilai Jasa </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nilai_jasa" id="nilai_jasa" autocomplete="off" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label">PPN 10% </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nilai_ppn" id="nilai_ppn" autocomplete="off" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                <div class="col-sm-5">
                                    <select name="id_pph" class="form-control" id="id_pph" value="<?= $row2['id_pph'] ?>">
                                        <option value="">--Jenis PPh--</option>
                                        <?php
                                        $queryPph = mysqli_query($koneksi, "SELECT * FROM pph ORDER BY nm_pph ASC");
                                        if (mysqli_num_rows($queryPph)) {
                                            while ($rowPph = mysqli_fetch_assoc($queryPph)) :
                                        ?>
                                                <option value="<?= $rowPph['id_pph']; ?>" data-id="<?= $rowPph['jenis']; ?>" type="checkbox"><?= $rowPph['nm_pph'] ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nilai_pph" class="col-sm-offset-1 col-sm-3 control-label">PPh </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nilai_pph" id="nilai_pph" autocomplete="off" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Total </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_pendukung" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                                <div class="col-sm-5">
                                    <div class="input-group input-file" name="doc_pendukung">
                                        <input type="text" class="form-control" placeholder="*Opsional" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="validationTextarea">Deskripsi : </label>
                                <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Deskripsi Biaya Operasional"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="validationTextarea">Remarks : </label>
                                <textarea rows="8" class="form-control is-invalid" name="remarks" id="validationTextarea" required placeholder="Remarks Biaya Operasional"></textarea>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Create</button></span></a>
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
<!-- Akhir modal create -->


<!-- Modal Lihat -->
<div id="lihatBkk" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail BKK</h4>
            </div>
            <!-- body modal -->
            <form class="form-horizontal">
                <div class="modal-body">
                    <div class="perhitungan">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-2 control-label">Tanggal</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="ml_tanggal" readonly>
                                </div>
                                <label for="id_anggaran" class="col-sm-2 control-label">Pengajuan</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="ml_pengajuan" readonly>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-2 control-label">Nominal</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control text-right text-bold" id="ml_nominal" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="validationTextarea" class="col-sm-2 control-label">Deskripsi : </label>
                                    <div class="col-sm-9">
                                        <textarea rows="8" class="form-control is-invalid" id="ml_keterangan" placeholder="Deskripsi" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="validationTextarea" class="col-sm-2 control-label">Remarks : </label>
                                    <div class="col-sm-9">
                                        <textarea rows="8" class="form-control is-invalid" id="ml_remarks" placeholder="Deskripsi" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="doc">
                                <div class="form-group">
                                    <h3 class="text-center">Document Pendukung </h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="" id="ml_doc"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                        </div>
                    </div>
                    <!-- div perhitungan -->
                </div>
        </div>
        </form>
    </div>
</div>
</div>
<!-- Akhir modal lihat -->

<!-- Modal Edit -->
<div id="editBkk" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Biaya Khusus</h4>
            </div>
            <!-- body modal -->
            <form method="post" name="form" enctype="multipart/form-data" action="edit_biayakhusus.php" class="form-horizontal">
                <input type="hidden" name="id" id="me_id">
                <input type="hidden" name="doc_pendukung_lama" id="me_doc_pendukung_lama">
                <div class="modal-body">
                    <div class="perhitungan">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-2 control-label">Tanggal</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="me_tanggal" readonly>
                                </div>
                                <label for="id_anggaran" class="col-sm-2 control-label">Pengajuan</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="me_pengajuan" readonly>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-2 control-label">Nominal</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control text-right text-bold" name="nominal" id="me_nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="validationTextarea" class="col-sm-2 control-label">Deskripsi : </label>
                                    <div class="col-sm-9">
                                        <textarea rows="8" class="form-control is-invalid" name="keterangan" id="me_keterangan" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="validationTextarea" class="col-sm-2 control-label">Remarks : </label>
                                    <div class="col-sm-9">
                                        <textarea rows="8" class="form-control is-invalid" name="remarks" id="me_remarks" placeholder="Deskripsi"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_pendukung" class="col-sm-offset- col-sm-2 control-label">Document Pendukung </label>
                                <div class="col-sm-9">
                                    <div class="input-group input-file" name="doc_pendukung">
                                        <input type="text" class="form-control" placeholder="*Opsional" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                    <span class="text-danger"><i>*Kosongkan jika tidak di rubah</i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-9 col-sm-6">
                                    <button type="submit" name="edit" class="btn btn-success">Edit</button>
                                    <input type="reset" class="btn btn-warning" data-dismiss="modal" value="Batal">
                                </div>
                            </div>
                            <div id="doc">
                                <div class="form-group">
                                    <h3 class="text-center">Document Pendukung </h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="" id="me_doc"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                        </div>
                    </div>
                    <!-- div perhitungan -->
                </div>
        </div>
        </form>
    </div>
</div>
</div>
<!-- Akhir modal lihat -->

<!-- Modal hapus -->
<!-- Modal hapus -->
<div id="hapusBkk" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="delete_bkk.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="md_id">
                            <h4>Apakah anda yakin ingin menghapus biaya khusus ini <b><span id="md_keterangan"></b> ?</span></h4>
                            <div class=" modal-footer">
                                <button class="btn btn-danger" type="submit" name="delete">Delete</button></span></a>
                                &nbsp;
                                <input type="reset" class="btn btn-warning" data-dismiss="modal" value="Batal">
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

<!-- Akhir modal hapus -->


<?php
$host = host();

?>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../assets/plugins/alertify/lib/alertify.min.js"></script>

<script src="../assets/bootstrap/js/notif_wa.js"></script>
<script>
    var host = '<?= $host ?>';
    var status = '<?= $_COOKIE['status'] ?>';

    if (status == 200) {

        noWa = '<?= $_COOKIE['noWa'] ?>';
        body = '<?= $_COOKIE['body'] ?>';

        console.log(kirimWa(noWa, body))
    }



    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    // Modal Lihat
    $(function() {
        $('.modalLihat').on('click', function() {

            const id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: host + 'api/bkk/getdatabkk.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#ml_tanggal').val(convertDateTimeDBtoIndo(data.created_on_bkk));
                    $('#ml_pengajuan').val(data.pengajuan);
                    $('#ml_nilai_barang').val(formatRibuan(data.nilai_barang));
                    $('#ml_nilai_jasa').val(formatRibuan(data.nilai_jasa));
                    $('#ml_nilai_pph').val(formatRibuan(data.nilai_pph));
                    $('#ml_nilai_ppn').val(formatRibuan(data.nilai_ppn));
                    $('#ml_nominal').val(formatRibuan(data.nominal));
                    $('#ml_keterangan').val(data.keterangan);
                    $('#ml_remarks').val(data.remarks);
                    if (data.doc_pendukung == null) {
                        // console.log("Kosong");
                        $('#doc').hide();
                    } else {
                        // console.log("Ada isinya");
                        $('#doc').show();
                        $('#ml_doc').attr('src', '../file/doc_pendukung/' + data.doc_pendukung);
                    }
                }
            });
        });
    });

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/bkk/getdatabkk.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id').val(data.id);
                    $('#me_doc_pendukung_lama').val(data.doc_pendukung);
                    $('#me_tanggal').val(convertDateTimeDBtoIndo(data.created_on_bkk));
                    $('#me_pengajuan').val(data.pengajuan);
                    $('#me_nominal').val(formatRibuan(data.nominal));
                    $('#me_keterangan').val(data.keterangan);
                    $('#me_remarks').val(data.remarks);
                    if (data.doc_pendukung == null) {
                        // console.log("Kosong");
                        $('#doc').hide();
                    } else {
                        // console.log("Ada isinya");
                        $('#doc').show();
                        $('#me_doc').attr('src', '../file/doc_pendukung/' + data.doc_pendukung);
                    }
                }
            });
        });
    });

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/bkk/getdatabkk.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#md_id').val(data.id);
                    $('#md_keterangan').text(data.keterangan);
                }
            });
        });
    });

    function reset() {
        $("#toggleCSS").attr("href", "../assets/plugins/alertify/themes/alertify.default.css");
        alertify.set({
            labels: {
                ok: "OK",
                cancel: "Cancel"
            },
            delay: 5000,
            buttonReverse: false,
            buttonFocus: "ok"
        });
    }

    // ==============================
    // Standard Dialogs
    $("#alert").on('click', function() {
        reset();
        alertify.alert("This is an alert dialog");
        return false;
    });

    $("#confirm").on('click', function() {
        reset();
        alertify.confirm("Konfirmasi Pembayaran Dana", function(e) {
            if (e) {
                alertify.success("Berhasil di update");
            } else {
                alertify.error("Cancel");
            }
        });
        return false;
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

    function formatRibuan(angka) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');

        return ribuan;
    }


    function convertDateDBtoIndo(string) {
        bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        tanggal = string.split("-")[2];
        bulan = string.split("-")[1];
        tahun = string.split("-")[0];

        return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun;
    }


    function convertDateTimeDBtoIndo(string) {
        bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        date = string.split(" ")[0];
        time = string.split(" ")[1];

        tanggal = date.split("-")[2];
        bulan = date.split("-")[1];
        tahun = date.split("-")[0];

        return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun + " " + time;
    }
</script>