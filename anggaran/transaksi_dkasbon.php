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
                                                         LEFT JOIN biaya_ops bo
                                                         ON k.kd_transaksi = bo.kd_transaksi                                                         
                                                         LEFT JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         LEFT JOIN divisi d
                                                         ON db.id_divisi = d.id_divisi
                                                         LEFT JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         LEFT JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$id_supplier = $data['id_supplier'];
$id_anggaran = $data['id_anggaran'];
$totalPengajuan = $data['harga_akhir'];
$Divisi = $data['id_divisi'];

// total anggaran yang ada di anggaran
$queryTotal = mysqli_query($koneksi, " SELECT sum(jumlah_nominal) as total_anggaran 
                                                FROM anggaran
                                                WHERE id_anggaran='$id_anggaran' ");
$rowTotal = mysqli_fetch_assoc($queryTotal);
$totalAnggaran = $rowTotal['total_anggaran'];

// realisasi anggaran
$queryRealisasi = mysqli_query($koneksi, " SELECT *
                                                FROM anggaran
                                                WHERE id_divisi='$id_anggaran' ");
$rowR = mysqli_fetch_assoc($queryRealisasi);
$totalRealisasi = $rowR['januari_realisasi'] + $rowR['februari_realisasi'] + $rowR['maret_realisasi'] + $rowR['april_realisasi'] + $rowR['mei_realisasi'] + $rowR['juni_realisasi'] + $rowR['juli_realisasi'] + $rowR['agustus_realisasi'] + $rowR['september_realisasi'] + $rowR['oktober_realisasi'] + $rowR['november_realisasi'] + $rowR['desember_realisasi'];

$tahun = date("Y");

?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Rubah Kode Anggaran Kasbon</h3>
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
                                        <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' [' . $data['nm_item']; ?>]</option>
                                    </select>
                                </div>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rubah">Rubah</button></span></a>
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
                                <label id="tes" for="supplier" class="col-sm-offset-1 col-sm-1 control-label">Supplier</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="supplier" value="<?= $data['nm_supplier'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="harga" class="col-sm-offset-1 col-sm-1 control-label">Pengembalian</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="harga" value="<?= formatRupiah($data['pengembalian']) ?>">
                                </div>
                                <label id="tes" for="harga" class="col-sm-offset-1 col-sm-1 control-label">Harga</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="harga" value="<?= formatRupiah($totalPengajuan) ?>">
                                </div>
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
                                <div class="row ">
                                    <!-- <div class="col-sm-offset-2">                                
                                        <img src="../file/foto/<?= $data['foto_item']; ?>" width="80%" alt="...">  
                                    </div>                             -->

                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/foto/<?php echo $data['foto_item']; ?> "></iframe>
                                    </div>
                                </div>
                            <?php } ?>
                            <br><br><br>
                            <hr>
                        </div>
                </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Rubah -->
    <div id="rubah" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Rubah Kode Anggaran</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="rubah_anggarankasbon.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data['id_dbo']; ?>" class="form-control" name="id_dbo" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label id="tes" for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                <div class="col-sm-6">
                                    <select name="id_anggaran" class="form-control">
                                        <?php
                                        $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi = '$Divisi' AND tahun IN ('$tahun', '2025') ORDER BY nm_item ASC");
                                        if (mysqli_num_rows($queryAnggaran)) {
                                            while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                        ?>
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox" <?= $data['id_anggaran'] == $rowAnggaran['id_anggaran'] ? "selected" : ""; ?>><?= $rowAnggaran['kd_anggaran'] . ' [' . $rowAnggaran['nm_item']; ?>]<?= $rowAnggaran['row_version'] == 2 ? " - <b>NEW!</b>" : ""; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-success" type="submit" name="rubah">Rubah</button></span></a>
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
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });
</script>