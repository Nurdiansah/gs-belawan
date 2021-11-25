<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id_kasbon = $_GET['id_kasbon'];

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryDetail =  mysqli_query($koneksi, "SELECT * FROM detail_biayaops db 
                                                              JOIN anggaran a
                                                              ON db.id_anggaran = a.id_anggaran 
                                                              JOIN supplier s
                                                              ON s.id_supplier = db.id_supplier
                                                              JOIN kasbon k
                                                              ON db.kd_transaksi = k.kd_transaksi
                                                              LEFT JOIN tolak_kasbon 
                                                              ON k.id_kasbon = kasbon_id
                                                              WHERE k.id_kasbon = '$id_kasbon'");
$data = mysqli_fetch_assoc($queryDetail);
$id = $data['id'];
$id_supplier = $data['id_supplier'];
$Divisi = $data['id_divisi'];

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran !='$data[id_anggaran]' ORDER BY nm_item ASC");

$querySbo =  mysqli_query($koneksi, "SELECT * FROM sub_dbo WHERE id_dbo = '$id'");

$tanggalCargo = date("Y-m-d");

if (isset($_POST['update'])) {


    $id_subdbo = $_POST['id_subdbo'];
    $sub_unitprice = str_replace(".", "", $_POST['sub_unitprice']);
    $sub_qty = $_POST['sub_qty'];
    $id_dbo = $_POST['id_dbo'];

    $total_price = $sub_qty * $sub_unitprice;

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $query = mysqli_query($koneksi, "UPDATE sub_dbo
                                    SET sub_unitprice = '$sub_unitprice', total_price = '$total_price'                                         
                                    WHERE id_subdbo='$id_subdbo' ");

    if ($query) {
        header("location:index.php?p=dtl_submitkasbon&id_kasbon=$id_kasbon");
    } else {
        echo "ada yang salah" . mysqli_error($koneksi);
    }
}

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <!-- <a href="index.php?p=verifikasi_dmr&id=<?= $data['kd_transaksi']; ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> -->
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Kasbon</h3>
                </div>

                <form class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label">Nama Barang</label>
                            <input type="hidden" required class="form-control is-valid" name="kd_transaksi" value="<?= $data['kd_transaksi']; ?>">
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran" disabled>
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                    <?php
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
                            <label id="tes" for="merk" class="col-sm-offset col-sm-2 control-label">Merk </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="merk" value="<?= $data['merk']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="type" class="col-sm-offset- col-sm-2 control-label">Type</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control " name="type" value="<?= $data['type']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="jumlah" class="col-sm-offset col-sm-2 control-label">Jumlah</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="jumlah" value="<?= $data['jumlah']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="satuan" class="col-sm-offset- col-sm-2 control-label">Satuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control " name="satuan" value="<?= $data['satuan']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="satuan" class="col-sm-offset col-sm-2 control-label">Spesifikasi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="spesifikasi" value="<?= $data['spesifikasi']; ?>">
                            </div>


                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>
                </form>
                <!-- <textarea name="" readonly id="" cols="170" rows="10"> Alasan Penolakan : <?= $data['alasan_penolakan']; ?></textarea>                 -->
                <?php
                $foto = $data['foto_item'];
                if ($foto === '0') { ?>
                    <h3 class="text-center">Foto Barang</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-offset-">
                            <h5 class="text-center">Tidak Ada Foto</h5>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="box-header with-border">
                        <h3 class="text-center">Document Pendukung/Foto</h3>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/foto/<?= $data['foto_item']; ?>"></iframe>
                        </div>
                    </div>
                <?php } ?>

                <!-- Embed Document               -->
                <?php
                $doc_penawaran = $data['doc_penawaran'];
                $harga_estimasi = number_format($data['harga_estimasi'], 0, ",", ".");

                if (!is_null($doc_penawaran)) { ?>
                    <div class="box-header with-border">
                        <h3 class="text-center">Document Penawaran</h3>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?php echo $data['doc_penawaran']; ?> "></iframe>
                        </div>
                    </div>
                <?php    }

                ?>

                <br><br><br>
                <div id="rincian_barang"> </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Rincian Barang</h3>
                </div>
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Deskripsi</th>
                            <th>QTY</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Edit</th>
                        </thead>

                        <tr>
                            <tbody>
                                <?php
                                // <?php 
                                $no = 1;
                                $total = 0;
                                if (mysqli_num_rows($querySbo)) {
                                    while ($row = mysqli_fetch_assoc($querySbo)) :
                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['sub_deskripsi']; ?> </td>
                                        <td> <?= $row['sub_qty']; ?> </td>
                                        <td> <?= $row['sub_unit']; ?> </td>
                                        <form method="post" name="form" action="" class="form-horizontal">
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-addon ">Rp.</span>
                                                    <input type="text" class="form-control" value="<?= $row['sub_unitprice']; ?>" name="sub_unitprice" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" autocomplete="off">
                                            </td>
                </div>
                <td><?= formatRupiah($row['total_price']); ?></td>
                <td>
                    <input type="hidden" name="id_subdbo" value="<?= $row['id_subdbo']; ?>">
                    <input type="hidden" name="sub_qty" value="<?= $row['sub_qty']; ?>">
                    <input type="hidden" name="id_dbo" value="<?= $row['id_dbo']; ?>">
                    <?php if ($row['sub_unitprice'] == 0) { ?>
                        <input type="submit" name="update" class="btn btn-success " value="Update">
                    <?php } else { ?>
                        <input type="submit" name="update" class="btn btn-danger " value="Update">
                    <?php } ?>
                    </form>
                </td>
                </tr>
        <?php
                                        $total += $row['total_price'];
                                        $no++;
                                    endwhile;
                                } ?>
        <tr style="background-color :#B0C4DE;">
            <td colspan="5"><b>Total</b></td>
            <td><b><?= formatRupiah($total); ?></b></td>
            <td></td>
        </tr>
        </tbody>
        </table>
            </div>

            <br>
            <div class="form-group">
                <button type="button" class="btn btn-primary col-sm-offset-5" data-toggle="modal" data-target="#submit"> Submit </button></span></a>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak"> Reject </button></span></a>

                <!-- <input type="submit" name="reject" class="btn btn-danger" value="Reject"> -->
            </div>

        </div>

    </div>
    </div>
    </div>
</section>


<!--  -->
<div id="submit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Submit Kembali Pengajuan</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="reapprove_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" value="<?= $data['id_dbo']; ?>" class="form-control" name="id_dbo">
                        <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon">
                        <input type="hidden" value="<?= $data['doc_penawaran']; ?>" class="form-control" name="doc_penawaran_lama">
                        <input type="hidden" required class="form-control is-valid" name="kd_transaksi" value="<?= $data['kd_transaksi']; ?>">
                        <input type="hidden" required class="form-control is-valid" name="harga" value="<?= $total; ?>">
                        <input type="hidden" name="url" value="submit_kasbon">
                        <div class="form-group">
                            <label for="id_anggaran" class="col-sm-2 control-label">Supplier</label>
                            <div class="col-sm-8">
                                <select class="form-control select2 " name="id_supplier" required>
                                    <option value="<?= $data['id_supplier']; ?>"><?= $data['nm_supplier']; ?></option>
                                    <?php
                                    $querySupplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier != '$id_supplier' ORDER BY nm_supplier ASC");
                                    if (mysqli_num_rows($querySupplier)) {
                                        while ($rowSupplier = mysqli_fetch_assoc($querySupplier)) :
                                    ?>
                                            <option value="<?= $rowSupplier['id_supplier']; ?>" type="checkbox"><?= $rowSupplier['nm_supplier']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="doc_penawaran" class="col-sm-offset- col-sm-2 control-label">Document Penawaran</label>
                            <div class="col-sm-8">
                                <!-- <input type="file" required class="form-control is-valid"  name="doc_penawaran" required > -->
                                <div class="input-group input-file" name="doc_penawaran">
                                    <input type="text" class="form-control" required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="validationTextarea">Komentar</label>
                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required autocomplete>@<?php echo $Nama ?> : </textarea>
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
                <form method="POST" enctype="multipart/form-data" action="tolakpurchasing_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon">
                                <input type="hidden" value="<?= $data['kd_transaksi']; ?>" class="form-control" name="kd_transaksi">
                                <input type="hidden" value="<?= $data['id']; ?>" class="form-control" name="id">
                                <input type="hidden" value="submit_kasbon" name="url">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="validationTextarea">Komentar</label>
                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required autocomplete>@<?php echo $Nama ?> : </textarea>
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
        $('.datatable').DataTable();
    });

    // batas script baru         

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