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

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran !='$data[id_anggaran]' ORDER BY nm_item ASC");

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo ");

$tanggalCargo = date("Y-m-d");

?>


<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=verifikasi_kasbon&sp=vk_purchasing" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Pajak</h3>
                </div>
                <div class="perhitungan">
                    <form method="post" name="form" action="vrf_itemmr.php" enctype="multipart/form-data" class="form-horizontal">

                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label">Nama Barang</label>
                                <input type="hidden" required class="form-control is-valid" name="id_kasbon" value="<?= $data['id_kasbon']; ?>">
                                <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                                <input type="hidden" required name="from_user" value="<?= $data['from_user']; ?>">
                                <input type="hidden" required name="vrf_pajak" value="<?= $data['vrf_pajak']; ?>">
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
                            <?php if (isset($dataReapp['alasan_reapprove_purchasing']) != NULL) { ?>
                                <div class="form-group">
                                    <label for="keterangan" class="col-sm-offset-5 col-sm-2 control-label">Submit Kembali</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $dataReapp['alasan_reapprove_purchasing']; ?></textarea>
                                    </div>
                                </div>
                            <?php
                            }
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
                                <div class="col-sm-12">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/foto/<?php echo $data['foto_item']; ?> "></iframe>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Embed Document               -->
                            <?php
                            $doc_penawaran = $data['doc_penawaran'];
                            $harga_akhir = number_format($data['harga_akhir'], 0, ",", ".");

                            if (!is_null($doc_penawaran)) { ?>
                                <br><br>
                                <div class="box-header with-border">
                                    <h3 class="text-center">Document Penawaran</h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?php echo $data['doc_penawaran']; ?> "></iframe>
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
                                <div class="form-group">
                                    <label id="tes" for="harga" class="col-sm-offset-1 col-sm-1 control-label">Harga</label>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <span class="input-group-addon ">Rp.</span>
                                            <input type="text" disabled class="form-control is-valid" name="harga" value="<?= $harga_akhir ?>">
                                        </div>
                                    </div>
                                    <!-- </div>   
                        <div class="form-group"> -->
                                    <label id="tes" for="supplier" class="col-sm-offset-1 col-sm-1 control-label">Supplier</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled class="form-control is-valid" name="supplier" value="<?= $data['nm_supplier'] ?>">
                                    </div>
                                </div>

                                <!-- Verifikasi Tax  -->
                                <hr>
                                <div class="box-header with-border">
                                    <h3 class="text-center">Verifikasi Tax</h3>
                                </div>
                                <br>
                                <input type="hidden" name="free_approve" value="<?= $data['free_approve']; ?>">
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="number" required class="form-control" value="<?= round($data['nilai_barang']) ?>" name="nilai_barang" id="nilai_barang" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="number" required class="form-control" value="<?= round($data['nilai_jasa']) ?>" name="nilai_jasa" id="nilai_jasa" />
                                        </div>
                                    </div>
                                </div>
                                <div id="bgn-dpp-lain">
                                    <div class="form-group">
                                        <label id="tes" for="dpp_nilai_lain" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">DPP Nilai Lain</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" class="form-control " name="dpp_nilai_lain" id="dpp_nilai_lain" min="0" value="<?= formatRibuan($data['dpp_nilai_lain']); ?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">PPN</span>
                                            <input type="number" required min="0" max="12" class="form-control " id="ppn_persen" name="ppn_persen" value="<?= round(($data['nilai_ppn'] / ($data['nilai_barang'] + $data['nilai_jasa'])) * 100) ?>" id="ppn_persen" />
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" readonly class="form-control " name="ppn_nilai" value="<?= formatRibuan(round($data['nilai_ppn'])); ?>" id="ppn_nilai" />
                                        </div>
                                    </div>
                                </div>
                                <div id="bgn-pembulatan" class="bg-warning col-sm-offset-2 col-sm-3 mb-2" style="width: 60%;">
                                    <br>
                                    <div class="form-group">
                                        <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-2 control-label" id="rupiah">PPN Atas</label>
                                        <div class="col-sm-3">
                                            <input type="radio" name="ppn_atas" value="all" id="all" onclick="checkPpnAtas()" checked="checked"> Barang & Jasa
                                        </div>
                                        <div class=" col-sm-3">
                                            <input type="radio" name="ppn_atas" value="barang" id="barang" onclick="checkPpnAtas()"> Hanya Barang
                                        </div>
                                        <div class=" col-sm-3">
                                            <input type="radio" name="ppn_atas" value="jasa" id="jasa" onclick="checkPpnAtas()"> Hanya Jasa
                                        </div>
                                        <div class=" col-sm-3">
                                            <input type="radio" name="ppn_atas" value="dpp_lain" id="dpp_lain" onclick="checkPpnAtas()"> (11/12)
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-2 control-label" id="rupiah">Pembulatan</label>
                                        <div class="col-sm-3">
                                            <input type="radio" name="pembulatan" value="keatas" id="keatas" onclick="checkPembulatan()"> Ke atas
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="radio" name="pembulatan" value="kebawah" id="kebawah" onclick="checkPembulatan()" checked="checked"> Ke bawah
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                    <div class="col-sm-2">
                                        <select name="id_pph" class="form-control">
                                            <option value="">--Jenis PPh--</option>
                                            <?php
                                            $queryPph = mysqli_query($koneksi, "SELECT * FROM pph ORDER BY nm_pph ASC");
                                            if (mysqli_num_rows($queryPph)) {
                                                while ($rowPph = mysqli_fetch_assoc($queryPph)) :
                                            ?>
                                                    <option value="<?= $rowPph['id_pph']; ?>" type="checkbox"><?= $rowPph['nm_pph'] ?></option>
                                            <?php endwhile;
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">PPh</span>
                                            <span class="input-group-addon bg-dark">Rp.</span>
                                            <input type="number" required class="form-control" value="<?= round($data['nilai_pph']) ?>" name="pph_nilai" id="pph_nilai" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Potongan</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="number" class=" form-control" value="<?= round($data['potongan']) ?>" name="potongan" id="potongan" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group">
                                        <label id="tes" for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Jumlah</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp.</span>
                                                <input type="text" required class="form-control" value="<?= round($data['harga_akhir']) ?>" name="jml_bkk" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="simpan" class="btn btn-primary col-sm-offset-5"><i class="fa fa-save"></i> Simpan</button>
                                        &nbsp;
                                        <!-- <button type="submit" name="submit" class="btn btn-warning"><i class="fa fa-rocket"></i> Submit</button> -->
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#submit"><i class="fa fa-rocket"></i> Submit</button>
                                        &nbsp;
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Reject </button></span></a>
                                    </div>
                                    <hr>
                                </div>
                                </div>
                    </form>
                </div>

            </div>
        </div>
</section>

<!-- modal submit -->
<div id="submit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Submit Pengajuan</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="vrf_itemmr.php" class="form-horizontal">
                    <input type="hidden" name="id_kasbon" value="<?= $data['id_kasbon'] ?>" class="form-control">
                    <input type="hidden" name="from_user" value="<?= $data['from_user'] ?>" class="form-control">
                    <input type="hidden" name="id" value="<?= $data['id'] ?>" class="form-control">
                    <input type="hidden" name="vrf_pajak" value="<?= $data['vrf_pajak'] ?>" class="form-control">
                    <input type="hidden" value="<?= round($data['nilai_barang']); ?>" class="form-control" name="nilai_barang" readonly>
                    <input type="hidden" value="<?= round($data['nilai_jasa']); ?>" class="form-control" name="nilai_jasa" readonly>
                    <input type="hidden" value="<?= round($data['nilai_ppn']); ?>" class="form-control" name="ppn_nilai" readonly>
                    <input type="hidden" value="<?= $data['id_pph']; ?>" class="form-control" name="id_pph" readonly>
                    <input type="hidden" value="<?= round($data['nilai_pph']); ?>" class="form-control" name="pph_nilai" readonly>
                    <input type="hidden" value="<?= round($data['potongan']); ?>" class="form-control" name="diskon" readonly>
                    <input type="hidden" value="<?= round($data['harga_akhir']); ?>" class="form-control" name="jml_bkk" readonly>
                    <input type="hidden" name="free_approve" value="<?= $data['free_approve']; ?>">

                    <div class="box-body">
                        <div class="form-group ">
                            <h4 class="text-center">Yakin ingin mensubmit pengajuan <b><?= $data['keterangan']; ?></b>?</h4>
                            <h5 class="text-center">Pastikan pengajuan sudah diverifikasi dan nominal sudah sesuai</h5>
                        </div>
                        <div class=" modal-footer">
                            <button class="btn btn-warning" type="submit" name="submit">Kirim</button></span></a>
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
                                <input type="hidden" value="verifikasi_kasbon&sp=vk_purchasing" class="form-control" name="url">
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

    let dpp_nilai_lain = '<?= $data['dpp_nilai_lain'] ?>'
    if (dpp_nilai_lain > 0) {
        $("#bgn-dpp-lain").show();
    } else {
        $("#bgn-dpp-lain").hide();
    }

    ppn_atas = $("input[name='ppn_atas']:checked").val();

    function checkPpnAtas() {
        // ambil cek ppn atas
        ppn_atas = $("input[name='ppn_atas']:checked").val();
        var nilai_pph = parseInt($('#pph_nilai').val())
        var potongan = parseInt($("#potongan").val())

        var ppn_persen = parseInt($("#ppn_persen").val())
        var nilai_ppn = Math.floor(ppn_persen * (getDpp()) / 100);

        // set nilai ppn
        var nilai_ppna = tandaPemisahTitik(nilai_ppn);
        $("#ppn").attr("value", nilai_ppna);
        document.form.ppn_nilai.value = nilai_ppna;


        if (nilai_pph == 0) {
            var jmla = getDpp() + nilai_ppn + potongan;
        } else {
            var jmla = getDpp() + nilai_ppn + nilai_pph - potongan;
        }

        var jml = tandaPemisahTitik(jmla);
        document.form.jml_bkk.value = jml;

        var nilai_ppna = tandaPemisahTitik(nilai_ppn);
        $("#ppn").attr("value", nilai_ppna);
        document.form.ppn_nilai.value = nilai_ppna;
    }

    // check pembulatan
    function checkPembulatan() {
        var pembulatan = $("input[name='pembulatan']:checked").val();

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nilaiBarang = parseInt($("#nilai_barang").val())
        var nilai_pph = parseInt($('#pph_nilai').val())
        var potongan = parseInt($("#potongan").val())

        var desimal_persen = parseInt($("#ppn_persen").val()) / 100;

        if (pembulatan == 'keatas') {
            // pembulatan ke atas
            var nilai_ppn = Math.ceil(desimal_persen * (getDpp()));
        } else if (pembulatan == 'kebawah') {
            // pembulatan ke bawah
            var nilai_ppn = Math.floor(desimal_persen * (getDpp()));
        }
        // console.log(nilai_ppn)

        if (nilai_pph == 0) {
            var jmla = getDpp() + nilai_ppn + potongan;
        } else {
            var jmla = getDpp() + nilai_ppn + nilai_pph - potongan;
        }

        var jml = tandaPemisahTitik(jmla);
        document.form.jml_bkk.value = jml;

        var nilai_ppna = tandaPemisahTitik(nilai_ppn);
        $("#ppn").attr("value", nilai_ppna);
        document.form.ppn_nilai.value = nilai_ppna;

    }

    function getDpp() {
        // var nilaiDpp = 0;

        if (ppn_atas == 'all') {
            $("#bgn-dpp-lain").hide();
            var nilaiDpp = getNilaiBarang() + getNilaiJasa();

        } else if (ppn_atas == 'barang') {
            $("#bgn-dpp-lain").hide();
            var nilaiDpp = getNilaiBarang();

        } else if (ppn_atas == 'jasa') {
            $("#bgn-dpp-lain").hide();
            var nilaiDpp = getNilaiJasa();

        } else if (ppn_atas == 'dpp_lain') {
            $("#bgn-dpp-lain").show();

            dpp_lain = (11 / 12) * (getNilaiBarang() + getNilaiJasa());
            $('#dpp_nilai_lain').val(tandaPemisahTitik(Math.round(dpp_lain)))

            var nilaiDpp = getDPPNilaiLain();
        }

        return nilaiDpp;
    }

    // hitung total
    function hitungTotal() {
        var grandTotal = getNilaiBarang() + getNilaiJasa() + getPpnNilai() + getBiayaLain() - getPphNilai() - getPotongan();

        var jml = tandaPemisahTitik(grandTotal);
        document.form.jml_bkk.value = jml;

        return grandTotal;
    }


    $(".perhitungan").keyup(function() {
        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var pph_nilai = parseInt($("#pph_nilai").val())

        var nilaiBarang = parseInt($("#nilai_barang").val())
        var ppn_persen = parseInt($("#ppn_persen").val())
        var potongan = parseInt($("#potongan").val())

        var ppn_nilai = (getDpp() * ppn_persen / 100);
        var ppn_nilaia = tandaPemisahTitik(Math.round(ppn_nilai));
        $("#ppn").attr("value", ppn_nilaia);
        document.form.ppn_nilai.value = ppn_nilaia;

        var jmla = Math.round(nilaiBarang) + Math.round(nilaiJasa) + Math.round(ppn_nilai) - Math.round(pph_nilai) - Math.round(potongan);
        var jml = tandaPemisahTitik(Math.round(jmla));
        $("#jml").attr("value", Math.round(jml));
        document.form.jml_bkk.value = jml;
    });

    if (parseInt($('#ppn_nilai').val()) == '0') {
        $('#bgn-pembulatan').hide();
    } else {
        $('#bgn-pembulatan').show();
    }

    $('#ppn_persen').keyup(function() {
        if (parseInt($('#ppn_persen').val()) == '0') {
            $('#bgn-pembulatan').hide();
        } else {
            $('#bgn-pembulatan').show();
        }
    });

    function getNilaiBarang() {
        return parseInt($("#nilai_barang").val())
    }

    function getNilaiJasa() {
        return parseInt($("#nilai_jasa").val())
    }

    function getDPPNilaiLain() {
        return parseInt(hilangkanTitik($("#dpp_nilai_lain").val()));
    }

    function getPpnNilai() {
        return hilangkanTitik(parseInt($("#nilai_ppn").val()));
    }

    function getPpnAtas() {
        return ppn_atas = $("input[name='ppn_atas']:checked").val();
    }

    function getBiayaLain() {
        return parseInt($("#biaya_lain").val());
    }

    function getPotongan() {
        return parseInt($("#potongan").val());
    }

    function getPphNilai() {

        // if (jenis == 'fixed') {

        // pph nilai 1 untuk tarif fix
        var pph_nilai = parseInt($("#pph_nilai").val())

        // } else if (jenis == 'progresive') {

        //     // pph nilai 2 untuk tarif progresive
        //     var pph_nilai = hilangkanTitik('pph_nilai2')

        // } else {
        //     var pph_nilai = 0;
        // }

        return pph_nilai;
    }

    function hilangkanTitik(idTag) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(idTag))))); //input ke dalam angka tanpa titik

        return angka;
    }
</script>