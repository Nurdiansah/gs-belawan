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


$queryDetail =  mysqli_query($koneksi, "SELECT * FROM detail_biayaops db 
                                                              JOIN anggaran a
                                                              ON db.id_anggaran = a.id_anggaran 
                                                              JOIN supplier s
                                                              ON s.id_supplier = db.id_supplier
                                                              WHERE db.id=$id ");
$data = mysqli_fetch_assoc($queryDetail);
$id_supplier = $data['id_supplier'];
$Divisi = $data['id_divisi'];

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran !='$data[id_anggaran]' ORDER BY nm_item ASC");

$tanggalCargo = date("Y-m-d");

$harga_estimasi = number_format($data['harga_estimasi'], 0, ",", ".");

?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=verifikasi_dmr&id=<?= $data['kd_transaksi']; ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Barang</h3>
                </div>
                <div class="perhitungan">
                    <form method="post" name="form" action="vrf_itemmr.php" enctype="multipart/form-data" class="form-horizontal">

                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label">Nama Barang</label>
                                <input type="hidden" required class="form-control is-valid" name="kd_transaksi" value="<?= $data['kd_transaksi']; ?>">
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
                            <div class="form-group">
                                <label id="tes" for="harga" class="col-sm-offset-1 col-sm-1 control-label">Harga</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" disabled class="form-control is-valid" name="harga" value="<?= $harga_estimasi ?>">
                                    </div>
                                </div>
                                <!-- </div>   
                        <div class="form-group"> -->
                                <label id="tes" for="supplier" class="col-sm-offset-1 col-sm-1 control-label">Supplier</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="supplier" value="<?= $data['nm_supplier'] ?>">
                                </div>
                            </div>
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
                                    <div class="col-sm-offset-2">
                                        <img src="../file/foto/<?= $data['foto_item']; ?>" width="80%" alt="...">
                                        <!-- <h5 class="text-center">Tidak Ada Foto</h5> -->
                                    </div>
                                </div>
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

                                </div>
                        </div>
                    </form>
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


    $(".perhitungan").keyup(function() {


        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var pph_persen = parseInt($("#pph_persen").val())
        var pph_nilai = nilaiJasa * pph_persen / 100;
        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;



        var nilaiBarang = parseInt($("#nilai_barang").val())
        var ppn_persen = parseInt($("#ppn_persen").val())
        var ppn_nilai = (nilaiJasa + nilaiBarang) * ppn_persen / 100;
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;


        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);
        document.form.jml_bkk.value = jml;

    });
</script>