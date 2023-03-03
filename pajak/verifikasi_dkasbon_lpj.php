<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryReapp = mysqli_query($koneksi, "SELECT * FROM reapprove_kasbon WHERE kasbon_id = '$id'");
$dataReapp = mysqli_fetch_assoc($queryReapp);
$totalReapp = mysqli_num_rows($queryReapp);

$queryDetail =  mysqli_query($koneksi, "SELECT *
                                                         FROM kasbon k
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id' ");
$data = mysqli_fetch_assoc($queryDetail);
$id_supplier = $data['id_supplier'];
$Divisi = $data['id_divisi'];
$id_dbo = $data['id'];
$harga_akhir = number_format($data['harga_akhir'], 0, ",", ".");

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran !='$data[id_anggaran]' ORDER BY nm_item ASC");

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo ");

$tanggalCargo = date("Y-m-d");

if ($data['nilai_ppn'] > 0) {
    $ppn_persen = $data['nilai_ppn'] / ($data['nilai_jasa'] + $data['nilai_barang']) * 100;
} else {
    $ppn_persen = "0";
}
?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=verifikasi_lpj" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Pajak</h3>
                </div>
                <div class="perhitungan">

                    <div class="box-body">
                        <form class="form-horizontal">
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
                                        <input type="text" disabled class="form-control is-valid" name="harga" value="<?= $harga_akhir ?>">
                                    </div>
                                </div>
                                <label id="tes" for="supplier" class="col-sm-offset-1 col-sm-1 control-label">Supplier</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="supplier" value="<?= $data['nm_supplier'] ?>">
                                </div>
                            </div>
                            <?php if (isset($dataReapp['alasan_reapprove_purchasing']) != NULL) { ?>
                                <div class="form-group">
                                    <label for="keterangan" class="col-sm-offset-5 col-sm-2 control-label">Submit Kembali</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $dataReapp['alasan_reapprove_purchasing']; ?></textarea>
                                    </div>
                                </div>
                            <?php
                            } ?>

                        </form>

                        <?php if ($data['from_user'] == 0) {
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
                                    <h3 class="text-center">Foto Barang</h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/foto/<?php echo $data['foto_item']; ?> "></iframe>
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
                                </div>
                            <?php    } ?>

                            <br><br>
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
                                                if (mysqli_num_rows($querySbo)) {
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
                                                } ?>
                                    <tr style="background-color :#B0C4DE;">
                                        <td colspan="5"><b>Total</b></td>
                                        <td><b><?= formatRupiah($total); ?></b></td>
                                    </tr>
                                        </tbody>
                                </table>
                            </div>
                            <br>
                            <br>
                        <?php } else { ?>
                            <?php if (!is_null($data['doc_lpj'])) { ?>
                                <div class="box-header with-border">
                                    <h3 class="text-center">Document LPJ</h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/doc_lpj/<?php echo $data['doc_lpj']; ?> "></iframe>
                                    </div>
                                </div>
                            <?php }  ?>
                        <?php } ?>
                        <!-- Verifikasi Tax  -->
                        <br>

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="box box-primary">
                                    <?php if (!is_null($data['doc_lpj'])) { ?>
                                        <div class="box-header with-border">
                                            <h3 class="text-center">Document LPJ</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="embed-responsive embed-responsive-4by3">
                                                <iframe class="embed-responsive-item" src="../file/doc_lpj/<?php echo $data['doc_lpj']; ?> "></iframe>
                                            </div>
                                        </div>
                                    <?php }  ?>
                                </div>
                            </div>

                            <!-- Verifikasi Tax -->
                            <div class="col-sm-6 col-xs-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="text-center">Verifikasi Tax</h3>
                                    </div>
                                    <!-- action="vrf_item_lpj.php" -->
                                    <div class="box-body">
                                        <form method="post" name="form" action="vrf_item_lpj.php" enctype="multipart/form-data" class="form-horizontal">
                                            <!-- value hidden -->
                                            <input type="hidden" required name="id_kasbon" value="<?= $data['id_kasbon']; ?>">

                                            <div class="form-group">
                                                <label id="tes" for="nilai_bkk" class=" col-sm-4 control-label" id="rupiah">Nilai Barang</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" required class="form-control" name="nilai_barang" id="nilai_barang" value="<?= round($data['nilai_barang']); ?>" />
                                                    </div>
                                                    <i><span id="nb_ui"></span></i>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label id="tes" for="nilai_bkk" class=" col-sm-4 control-label" id="rupiah">Nilai Jasa</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" required class="form-control" value="<?= round($data['nilai_jasa']); ?>" name="nilai_jasa" id="nilai_jasa" />
                                                    </div>
                                                    <i><span id="nj_ui"></span></i>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN
                                                    <select name="pilih_ppn" id="setppn">
                                                        <option value="0.11">11%</option>
                                                        <option value="0.10">10%</option>
                                                        <option value="0.011">1.1%</option>
                                                    </select>
                                                </label>
                                                <div class="col-sm-1">
                                                    <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" class="form-control " name="ppn_nilai" id="ppn_nilai" value="<?= formatRibuan($data['nilai_ppn']) ?>" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="bgn-pembulatan" class="bg-warning">
                                                <hr>
                                                <div class="form-group">
                                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN Atas</label>
                                                    <div class="col-sm-3">
                                                        <input type="radio" name="ppn_atas" value="all" id="all" onclick="checkPpnAtas()" checked=" checked"> Barang & Jasa
                                                    </div>
                                                    <div class=" col-sm-3">
                                                        <input type="radio" name="ppn_atas" value="barang" id="barang" onclick="checkPpnAtas()"> Hanya Barang
                                                    </div>
                                                    <div class=" col-sm-3">
                                                        <input type="radio" name="ppn_atas" value="jasa" id="jasa" onclick="checkPpnAtas()"> Hanya Jasa
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Pembulatan</label>
                                                    <div class="col-sm-3">
                                                        <input type="radio" name="pembulatan" value="keatas" id="pembulatan" onclick="checkPembulatan()"> Ke atas
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="radio" name="pembulatan" value="kebawah" id="pembulatan" onclick="checkPembulatan()" checked="checked"> Ke bawah
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            <div class="form-group">
                                                <label id="tes" for="biaya_lain" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Biaya Lain</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" required class="form-control" value="<?= round($data['biaya_lain']) ?>" name="biaya_lain" id="biaya_lain" autocomplete="off" />
                                                    </div>
                                                    <i><span id="bl_ui"></span></i></br>
                                                    <i><span class="text-danger">*Biaya Materai/lain</span></i>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                                <div class="col-sm-5">
                                                    <select name="id_pph" class="form-control" id="id_pph" value="<?= $data['id_pph'] ?>">
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
                                            <div id="fixed" class="bg-success">
                                                <hr>
                                                <div class="form-group">
                                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                                    <div class="col-sm-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">PPh</span>
                                                            <input type="text" required class="form-control " name="pph_persen" value="0" id="pph_persen" />
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                                    <div class="col-sm-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Rp.</span>
                                                            <input type="text" readonly class="form-control " name="pph_nilai" value="<?= formatRibuan($data['nilai_pph']) ?>" id="pph_nilai" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            <div id="progresive" class="bg-success">
                                                <hr>
                                                <div class="form-group">
                                                    <label id="tes" for="pph_nilai2" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                                    <div class="col-sm-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">Rp.</span>
                                                            <input type="text" class="form-control " name="pph_nilai2" value="<?= round($data['nilai_pph']) ?>" id="pph_nilai2" />
                                                        </div>
                                                        <i><span id="pph_ui"></span></i>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            <div class="form-group">
                                                <label id="tes" for="jml_bkk" class=" col-sm-4 control-label">Jumlah</label>
                                                <div class="col-sm-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input type="text" required class="form-control" name="jml" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <div class="form-group">
                                                    <button type="submit" name="simpan" class="btn btn-primary col-sm-offset-4"><i class="fa fa-save"></i> Simpan</button>
                                                    &nbsp;
                                                    <button type="submit" name="submit" class="btn btn-warning "><i class="fa fa-rocket"></i> Submit</button>
                                                    &nbsp;
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Reject </button></span></a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <!-- row -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- modal tolak -->
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
                <form method="POST" enctype="multipart/form-data" action="tolaktax_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group ">

                            <div class="col-sm-4">
                                <input type="hidden" value="<?= $data['id_kasbon']; ?>" class="form-control" name="id_kasbon">
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
                            <!-- <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-1 " value="kirim" >  -->
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end modal tolak -->

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


    // cek apakah ada nilai ppn
    var np = parseFloat("<?= round($data['nilai_ppn']) ?>");

    if (np > 0) {
        $('#myCheck').attr('checked', 'checked');
        $("#bgn-pembulatan").show();
    } else {
        $("#bgn-pembulatan").hide();
    }

    // cek apa kah ada nilai barang
    var nilaiBarang = <?= $data['nilai_barang'] ?>;

    if (nilaiBarang > 0) {
        var nb_ui = tandaPemisahTitik(nilaiBarang);
        $('#nb_ui').text('Rp.' + nb_ui);
    }

    // cek apa kah ada nilai jasa
    var nilaiJasa = <?= $data['nilai_jasa'] ?>;

    if (nilaiJasa > 0) {
        var nb_ui = tandaPemisahTitik(nilaiJasa);
        $('#nj_ui').text('Rp.' + nb_ui);
    }
    // Cek PPH
    let id_pph = '<?= $data['id_pph'] ?>';

    // DEKLASARI DPP

    let dpp = nilaiBarang + nilaiJasa;

    let persentasePpn = np / dpp;
    // pembulatan di desimal 2 belakang koma 
    persentasePpn = persentasePpn.toFixed(2);

    // set ppn default 11%
    let setPpn = 0.11;
    if (np != 0 && dpp != 0) {

        $('#setppn').val(persentasePpn);

        setPpn = persentasePpn;
    }

    // jika ada perubahan ppn
    $('#setppn').on('change', function() {
        let ppnTemp = parseFloat(this.value);

        if (setPpn != ppnTemp) {
            setPpn = ppnTemp;
            // cek terlebih dahulu apakah checkbox nya ini aktif
            checkBox();

        }

    });


    /* untk melihat ppn
     var jenis = '<$data['jenis'] ';

     */

    let jenis = ''

    if (id_pph == '1') {
        jenis = 'progresive'
    } else if (id_pph == '2' || id_pph == '3') {
        jenis = 'fixed'
    }
    // console.log(jenis);

    showPph(jenis);


    $("#id_pph").val(id_pph);

    // $("#ktk").hide();

    $('#id_pph').on('change', function() {
        let id_pph = this.value;
        let jenis = $(this).find(':selected').data('id')

        showPph(jenis);

    });


    $(".perhitungan").keyup(function() {

        var nilaiJasa = parseInt($("#nilai_jasa").val())


        var nj_ui = tandaPemisahTitik(nilaiJasa);
        $('#nj_ui').text('Rp.' + nj_ui);

        var pph_persen = parseInt($("#pph_persen").val())
        var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);

        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;


        var nilaiBarang = parseInt($("#nilai_barang").val())
        var nb_ui = tandaPemisahTitik(nilaiBarang);
        $('#nb_ui').text('Rp.' + nb_ui);

        // Biaya lain
        var biayaLain = parseInt($("#biaya_lain").val())
        var bl_ui = tandaPemisahTitik(biayaLain);
        $('#bl_ui').text('Rp.' + bl_ui);

        // nilai pph untuk pajak progresive
        var pph_nilai2 = parseInt($("#pph_nilai2").val())
        var pph_ui = tandaPemisahTitik(pph_nilai2);
        $('#pph_ui').text('Rp.' + pph_ui);

        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {
            var ppn_nilai = Math.floor(setPpn * (nilaiBarang + nilaiJasa));
        } else if (checkBox.checked == false) {
            var ppn_nilai = 0;
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        document.form.ppn_nilai.value = ppn_nilaia;

        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2 + biayaLain;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);

        document.form.jml.value = jml;



    });

    function hilangkanTitik(data) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value))))); //input ke dalam angka tanpa titik

        return angka;
    }

    function showPph(data) {

        var nilai_barang = parseInt($("#nilai_barang").val())
        var nilai_jasa = hilangkanTitik('nilai_jasa')
        var ppn_nilai = hilangkanTitik('ppn_nilai')
        var biaya_lain = hilangkanTitik('biaya_lain')


        // var jml = hilangkanTitik('jml')
        var pph_nilai = hilangkanTitik('pph_nilai')

        // pph nilai 2 untuk tarif progresive
        var pph_nilai2 = hilangkanTitik('pph_nilai2')


        if (data == 'fixed') {
            $("#fixed").show();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + ppn_nilai + biaya_lain) - pph_nilai;


            jml = tandaPemisahTitik(jml);



            document.form.pph_nilai2.value = 0;
            document.form.jml.value = jml;

            if (pph_nilai > 0) {
                var persen = (pph_nilai / nilai_jasa) * 100;

                document.form.pph_persen.value = persen;
            }

        } else if (data == 'progresive') {
            $("#fixed").hide();
            $("#progresive").show();

            var jml = (nilai_barang + nilai_jasa + ppn_nilai + biaya_lain) - pph_nilai2;
            jml = tandaPemisahTitik(jml);

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
            document.form.jml.value = jml;
        } else {
            $("#fixed").hide();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + ppn_nilai + biaya_lain);
            jml = tandaPemisahTitik(jml);

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
            document.form.pph_nilai2.value = 0;
            document.form.jml.value = jml;

        }

        // hitungTotal();
    }

    function hitungCheckBox(angkaPpn) {

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var pph_persen = parseInt($("#pph_persen").val())
        var pph_nilai = Math.floor(nilaiJasa * pph_persen / 100);
        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;


        var nilaiBarang = parseInt($("#nilai_barang").val())
        var biayaLain = parseInt($("#biaya_lain").val())

        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {
            var ppn_nilai = Math.floor(angkaPpn * (nilaiBarang + nilaiJasa));
        } else if (checkBox.checked == false) {
            var ppn_nilai = 0;
        }
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        var pph_nilai2 = parseInt($("#pph_nilai2").val())

        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2 + biayaLain;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);
        document.form.jml.value = jml;

    }

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            $("#bgn-pembulatan").show();

            hitungCheckBox(setPpn);

        } else if (checkBox.checked == false) {

            $("#bgn-pembulatan").hide();

            hitungCheckBox(setPpn);

        }

        hitungTotal();
    }

    // check ppn atas
    function checkPpnAtas() {
        // ambil cek ppn atas
        ppn_atas = $("input[name='ppn_atas']:checked").val();

        var ppn_nilai = Math.floor(setPpn * (getDpp()));

        // set nilai ppn
        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;


        // var grandTotal = getNilaiBarang() + getNilaiJasa() + ppn_nilai + getBiayaLain() - getPphNilai() - getPotongan();

        // var jml = tandaPemisahTitik(grandTotal);
        // console.log('jumlah ', jml)

        // document.form.jml.value = jml;
        hitungTotal()
    }

    function getDpp() {
        // var nilaiDpp = 0;

        if (ppn_atas == 'all') {
            var nilaiDpp = getNilaiBarang() + getNilaiJasa();
        } else if (ppn_atas == 'barang') {
            var nilaiDpp = getNilaiBarang();
        } else if (ppn_atas == 'jasa') {
            var nilaiDpp = getNilaiJasa();
        }

        return nilaiDpp;
    }

    function getPersentasePpn() {

        // let dpp = parseInt($("#nilai_barang").val()) + parseInt($("#nilai_jasa").val());
        let percent = np / getDpp();
        let percentOke = percent.toFixed(2);

        return parseFloat(percent.toFixed(2));
    }

    // check pembulatan
    function checkPembulatan() {

        var pembulatan = $("input[name='pembulatan']:checked").val();

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nilaiBarang = parseInt($("#nilai_barang").val())

        if (pembulatan == 'keatas') {

            // pembulatan ke atas
            var ppn_nilai = Math.ceil(setPpn * (nilaiBarang + nilaiJasa));

        } else if (pembulatan == 'kebawah') {

            // pembulatan ke bawah
            var ppn_nilai = Math.floor(setPpn * (nilaiBarang + nilaiJasa));
        }

        // if (pembulatan == 'keatas') {
        //     // pembulatan ke atas
        //     var ppn_nilai = Math.ceil(setPpn * (getDpp()));
        // } else if (pembulatan == 'kebawah') {
        //     // pembulatan ke bawah
        //     var ppn_nilai = Math.floor(setPpn * (getDpp()));
        // }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        hitungTotal();
    }

    // hitung total
    function hitungTotal() {
        var grandTotal = getNilaiBarang() + getNilaiJasa() + getPpnNilai() + getBiayaLain() - getPphNilai(); // - getPotongan();

        var jml = tandaPemisahTitik(grandTotal);
        document.form.jml.value = jml;

        return grandTotal;
    }

    function showValueInput(idSpan, angka) {

        return $('#' + idSpan).text('Rp.' + tandaPemisahTitik(angka));
    }

    function getNilaiBarang() {
        return hilangkanTitik('nilai_barang');
    }

    function getNilaiJasa() {
        return hilangkanTitik('nilai_jasa');
    }

    function getPpnNilai() {
        return hilangkanTitik('ppn_nilai');
    }

    function getPpnAtas() {
        return ppn_atas = $("input[name='ppn_atas']:checked").val();
    }

    function getBiayaLain() {
        return hilangkanTitik('biaya_lain');
    }

    function getPotongan() {
        return hilangkanTitik('potongan');
    }

    function getPphNilai() {

        if (jenis == 'fixed') {

            // pph nilai 1 untuk tarif fix
            var pph_nilai = hilangkanTitik('pph_nilai')

        } else if (jenis == 'progresive') {

            // pph nilai 2 untuk tarif progresive
            var pph_nilai = hilangkanTitik('pph_nilai2')

        } else {
            var pph_nilai = 0;
        }

        return pph_nilai;


    }

    function hilangkanTitik(idTag) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(idTag).value))))); //input ke dalam angka tanpa titik

        return angka;
    }
</script>