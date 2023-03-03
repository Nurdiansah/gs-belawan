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
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];


$queryDetail =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                                         JOIN biaya_ops bo
                                                         ON k.kd_transaksi = bo.kd_transaksi
                                                         JOIN divisi d
                                                         ON bo.id_divisi = d.id_divisi
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$id_supplier = $data['id_supplier'];
$id_anggaran = $data['id_anggaran'];
$totalPengajuan = $data['harga_akhir'];
$id_dbo = $data['id'];

// total anggaran yang ada di anggaran
$queryTotal = mysqli_query($koneksi, " SELECT sum(jumlah_nominal) as total_anggaran 
                                                FROM anggaran
                                                WHERE id_anggaran='$id_anggaran' ");
$rowTotal = mysqli_fetch_assoc($queryTotal);
$totalAnggaran = $rowTotal['total_anggaran'];

// realisasi anggaran
$queryRealisasi = mysqli_query($koneksi, " SELECT *
                                                FROM anggaran
                                                WHERE id_anggaran = '$id_anggaran' ");
$rowR = mysqli_fetch_assoc($queryRealisasi);
$totalRealisasi = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];

$querySbo =  mysqli_query($koneksi, "SELECT * FROM sub_dbo
                                        WHERE id_dbo=$id_dbo ");
?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <br><br>
                </div>

                <div class="box-header with-border">
                    <h3 class="text-center">Payment Kasbon</h3>
                </div>
                <div class="perhitungan">
                    <form method="post" name="form" action="vrf_itemmr.php" enctype="multipart/form-data" class="form-horizontal">

                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label">Nama Barang</label>
                                <input type="hidden" required class="form-control is-valid" name="id_kasbon" value="<?= $data['id_kasbon']; ?>">
                                <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                                <div class="col-sm-3">
                                    <input type="text" readonly class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2" name="id_anggaran" disabled>
                                        <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
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
                                <label for="divisi" class="col-sm-offset- col-sm-2 control-label">Divisi</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control " name="divisi" value="<?= $data['nm_divisi']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data['keterangan']; ?></textarea>
                                </div>
                            </div>
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
                                    </thead>
                                    <tr>
                                        <tbody>
                                            <tr>
                                                <?php
                                                $no = 1;
                                                $total = 0;
                                                while ($row = mysqli_fetch_assoc($querySbo)) :

                                                ?>
                                                    <td> <?= $no; ?> </td>
                                                    <td> <?= $row['sub_deskripsi']; ?> </td>
                                                    <td> <?= $row['sub_qty']; ?> </td>
                                                    <td> <?= $row['sub_unit']; ?> </td>
                                                    <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
                                                    <td><?= formatRupiah($row['total_price']); ?></td>
                                            </tr>
                                        <?php
                                                    $total += $row['total_price'];
                                                    $no++;
                                                endwhile;
                                        ?>
                                        <tr style="background-color :#B0C4DE;">
                                            <td colspan="5"><b>Total</b></td>
                                            <td><b><?= formatRupiah($total); ?></b></td>
                                        </tr>
                                        </tbody>
                                </table>
                            </div>
                            <br>
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
                                <h3 class="text-center">Foto Barang</h3>
                                <br>
                                <!-- <div class="row ">
                                    <div class="col-sm-offset-2"> -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/foto/<?php echo $data['foto_item']; ?> "></iframe>
                                </div>
                                <!-- <img src="../file/foto/<?= $data['foto_item']; ?>" width="80%" alt="..."> -->
                                <!-- <h5 class="text-center">Tidak Ada Foto</h5> -->
                                <!-- </div>
                                </div> -->
                            <?php } ?>

                            <!-- Embed Document               -->
                            <?php
                            $doc_penawaran = $data['doc_penawaran'];

                            if (!is_null($doc_penawaran)) { ?>
                                <div class="box-header with-border">
                                    <h3 class="text-center">Document Penawaran</h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?php echo $data['doc_penawaran']; ?> "></iframe>
                                    </div>
                                <?php    } ?>

                                <br><br><br>
                                <div class="form-group">
                                    <label id="tes" for="supplier" class="col-sm-offset-1 col-sm-1 control-label">Supplier</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled class="form-control is-valid" name="supplier" value="<?= $data['nm_supplier'] ?>">
                                    </div>
                                    <label id="tes" for="harga" class="col-sm-offset-1 col-sm-1 control-label">Harga</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled class="form-control is-valid" name="harga" value="<?= formatRupiah($totalPengajuan) ?>">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <button type="button" class="btn btn-danger col-sm-offset-10" data-toggle="modal" data-target="#tolak">Reject </button></span></a>
                                    &nbsp;
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payment"> Payment </button></span></a>
                                </div>
                                <hr>
                                </div>
                        </div>
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
                <form method="post" enctype="multipart/form-data" action="send_paymentkasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon" readonly>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nominal" disabled value="<?= formatRupiah($totalPengajuan); ?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="penerima_dana" class="col-sm-offset-1 col-sm-3 control-label">Nama </label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="penerima_dana" placeholder="Nama Penerima Dana">
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-success" type="submit" name="submit">Kirim</button></span></a>
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
                <form method="post" enctype="multipart/form-data" action="tolak_payment_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">
                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $id; ?>" class="form-control" name="id_kasbon" readonly>
                                <input type="hidden" value="payment_kasbon&sp=pk_purchasing" class="form-control" name="url" readonly>
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
</script>