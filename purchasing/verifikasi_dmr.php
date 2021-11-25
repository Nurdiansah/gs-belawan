<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            RIGHT JOIN detail_biayaops dbo
                                            ON dbo.kd_transaksi = bo.kd_transaksi
                                            JOIN anggaran a
                                            ON a.id_anggaran = dbo.id_anggaran
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            JOIN supplier s
                                            ON s.id_supplier = dbo.id_supplier
                                            WHERE bo.kd_transaksi='$id' AND dbo.status = '2' ");


$query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            WHERE kd_transaksi='$id' ");
$data2 = mysqli_fetch_assoc($query);

$queryTotal = mysqli_query($koneksi, "SELECT sum(harga_estimasi) as total FROM detail_biayaops WHERE kd_transaksi='$id' ");
$rowTotal = mysqli_fetch_assoc($queryTotal);

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=bidding_itemmr&id=$id");
    } else if ($_GET['aksi'] == 'lihat') {
        header("location:?p=detail_item&id=$id");
    }
}

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=list_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Bidding MR</h3>
                </div>
                <form method="post" name="form" action="#" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['tgl_pengajuan']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-9 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_transaksi']; ?>">
                            </div>
                        </div>
                        <br>
                    </div>
                </form>

                <!--  -->
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Anggaran</th>
                            <th>Merk</th>
                            <th>Supplier/Vendor</th>
                            <th>Satuan</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Edit</th>
                            <th>Submit</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($queryBo)) {
                                        while ($row = mysqli_fetch_assoc($queryBo)) :

                                            $hargaEstimasi = $row['harga_estimasi'];
                                            $namaSupplier = $row['id_supplier'];
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['nm_supplier']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['jumlah']; ?> </td>
                                            <td><?= formatRupiah($row['harga_estimasi']); ?> </td>
                                            <td><a href="?p=verifikasi_dmr&aksi=edit&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success"> <i class="fa fa-edit"></i> </button></span></a></td>
                                            <!-- cek jika harga sudah di inputkan button aktif -->
                                            <?php
                                            if ($hargaEstimasi == 0 && $namaSupplier == '0') { ?>
                                                <td><a href='#'><span data-placement='top' data-toggle='tooltip' title='Submit'><button class='btn btn-dark'> <i class='fa fa-send'></i> </button></span></a></td>
                                            <?php } else { ?>
                                                <td><a href="submit_mr.php?id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Submit'><button class='btn btn-primary' onclick="javascript: return confirm('Yakin Ingin Submit ?')"> <i class='fa fa-send'></i> </button></span></a></td>
                                            <?php } ?>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                        </tr>
                        <!-- <tr>
                                <td colspan="7"><b>Total Harga</b></td>
                                <td><b>Rp. <?= number_format($rowTotal['total'], 0, ",", "."); ?></b></td>
                                <td></td>
                                </tr> -->
                    </table>
                </div>
                <div class="col-sm-offset-11 col-sm-3 control-label">
                    <h4> </h4>
                    <!-- <a  href="setuju_mr.php?id=<?= $data2['kd_transaksi']; ?>"><span data-placement='top' data-toggle='tooltip' title='Kirim'><button   class="btn btn-primary">Done</button></span></a>                 -->
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak"> Reject </button></span></a>
                </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    </div>

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
                    <form method="post" enctype="multipart/form-data" action="tolak_mr.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['kd_transaksi']; ?>" class="form-control" name="kd_transaksi" readonly>
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
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });
</script>