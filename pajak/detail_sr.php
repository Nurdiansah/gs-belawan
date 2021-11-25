<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);

$queryData =  mysqli_query($koneksi, "SELECT *  FROM so s
                                                LEFT JOIN pph p
                                                    ON p.id_pph = s.id_pph
                                                JOIN anggaran a
                                                ON a.id_anggaran = s.id_anggaran
                                                WHERE s.id_so = '$id'");
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
                                        INNER JOIN so so
                                            ON dsr.sr_id = so.sr_id
                                        WHERE id_so = '$id' ");

$jumlahData  = mysqli_num_rows($queryDSR);

$np = $data['nilai_ppn'];

if (isset($_POST['update'])) {
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $id = $_POST['id'];
    $nominal = penghilangTitik($_POST['nominal']);
    $diskon = penghilangTitik($_POST['diskon_sr']);
    $total = penghilangTitik($_POST['total_sr']);
    $nilai_ppn = penghilangTitik($_POST['nilai_ppn']);
    $grand_total = penghilangTitik($_POST['grand_totalsr']);

    $update = mysqli_query($koneksi, "UPDATE so SET nominal = '$nominal',
                                            diskon = '$diskon',
                                            total = '$total',
                                            nilai_ppn = '$nilai_ppn',
                                            grand_total = '$grand_total'
                                        WHERE id_so = '$id'");

    if ($update) {
        setcookie('pesan', 'SO berhasil di update !', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:index.php?p=verifikasi_sr");
    } else {
        die("Ada kesalahan " . mysqli_error($koneksi));
    }
}

// simpan
if (isset($_POST['simpan'])) {

    // print_r($_POST);
    // die;
    $id_so = $_POST['id_so'];
    $link = $_POST['link'];
    $nilai_barang = $_POST['nilai_barang'];
    $nilai_jasa = $_POST['nilai_jasa'];
    $ppn_nilai = penghilangTitik($_POST['ppn_nilai']);
    $id_pph = $_POST['id_pph'];
    $pph_persen = $_POST['pph_persen'];
    $harga_akhir = penghilangTitik($_POST['harga_akhir']);

    if ($_POST['pph_nilai2'] == 0) {
        $pph_nilai = penghilangTitik($_POST['pph_nilai']);
    } else {
        $pph_nilai = $_POST['pph_nilai2'];
    }

    $tanggal = dateNow();


    #kondisi jika verfikasi pajak sebelum pembayaran
    $query = "UPDATE so SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                nilai_ppn = '$ppn_nilai', nilai_pph = '$pph_nilai', 
                                id_pph = '$id_pph', harga_akhir = '$harga_akhir',  app_pajak = '$tanggal'                                              
    WHERE id_so ='$id_so' ";

    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {
        setcookie('pesan', 'Data berhasil di simpan!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:" . $link);
    } else {
        die("ada kesalahan : " . mysqli_error($koneksi));
    }
}

?>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-md-2">
            <a href="index.php?p=verifikasi_sr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <br><br>
    </div>

    <!-- Form Verifikasi Pajak -->
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Pajak</h3>
                </div>
                <div class="perhitungan">
                    <form method="post" name="form" action="" class="form-horizontal">
                        <input type="hidden" name="id_so" value="<?= $data['id_so']; ?>">
                        <input type="hidden" name="link" value="index.php?p=detail_sr&id=<?= $_GET['id'] ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="nilai_barang" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" value="<?= $data['nilai_barang'] ?>" name="nilai_barang" id="nilai_barang" autocomplete="off" />
                                    </div>
                                    <i><span id="nb_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_jasa" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" required class="form-control" value="<?= $data['nilai_jasa'] ?>" name="nilai_jasa" id="nilai_jasa" autocomplete="off" />
                                    </div>
                                    <i><span id="nj_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN 10%</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control " name="ppn_nilai" id="ppn_nilai" value="<?= formatRupiah2($data['nilai_ppn']) ?>" />
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
                            <div id="fixed">
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
                                            <input type="text" readonly class="form-control " name="pph_nilai" value="<?= formatRupiah2($data['nilai_pph']) ?>" id="pph_nilai" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="progresive">
                                <div class="form-group">
                                    <label id="tes" for="pph_nilai2" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" class="form-control " name="pph_nilai2" value="<?= $data['nilai_pph'] ?>" id="pph_nilai2" />
                                        </div>
                                        <i><span id="pph_ui"></span></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Grand Total</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp.</span>
                                            <input type="text" required class="form-control" name="harga_akhir" id="jml" readonly value="<?= formatRupiah2($data['harga_akhir']) ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="form-group">
                                    <button type="submit" name="simpan" class="btn btn-primary col-sm-offset-6"> <i class="fa fa-save"></i> Simpan</button>
                                    &nbsp;
                                    <button type="reset" name="reset" class="btn btn-danger col-sm-offset-"> <i class="fa fa-refresh"></i> Reset</button>
                                    <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolak">Reject </button></span></a> -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Form Pajak -->

    <!-- SR -->
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Form Penawaran</h3>
                </div>
                <form class="form-horizontal">
                    <input type="hidden" readonly class="form-control is-valid" name="id" value="<?= $id; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-3 control-label">Supplier</label>
                            <div class="col-sm-6">
                                <select id="idSupplier" class="form-control" name="id_supplier" disabled>
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
                                        <input type="text" class="form-control" readonly name="nominal" id="nominal" autocomplete="off" value="<?= formatRupiah2($data['nominal']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                        <!-- <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="<?= formatRupiah2($data['total']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required> -->
                                    </div>
                                </div>
                            </div>
                            <!-- Tambahan  -->
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Diskon </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" readonly name="diskon_sr" id="diskon_sr" value="<?= formatRupiah2($data['diskon']); ?>" placeholder="0" required onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
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
                                <label for="doc_quotation" class="col-sm-3 control-label">PPN 10 %</label>
                                <div class="col-sm-6">
                                    <input type="checkbox" disabled name="all" id="myCheck" onclick="checkBox()" <?php if ($data['nilai_ppn'] > 0) {
                                                                                                                        echo "checked = checked";
                                                                                                                    } ?>>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label" id="myCheck">Nilai PPN</label>
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
                        <!-- <div class="form-group">
                            <label for="tgl_tempo" class="col-sm-offset col-sm-3 control-label">Tempo Pembayaran</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control is-valid" name="tgl_tempo" value="<?= formatTanggal($data['tgl_tempo']); ?>" readonly>
                            </div>
                        </div> -->
                        <div class="form-group ">
                            <label for="validationTextarea" class="col-sm-3 control-label">Note</label>
                            <div class="col-sm-6">
                                <textarea rows="5" class="form-control is-invalid" name="note_sr" id="validationTextarea" placeholder="DP 30% dibayar 1 minggu, Pelunasan 70% di bayar 2 minggu, Pekerjaan harus dilaksanakan terhitung sejak uang DP diterima, Apabila pekerjaan belum dilaksanakn maka akan dilakukan pemotongan sebesar 1/m/hari dari nilai PO" readonly><?= $data['note']; ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-5">
                                <input type="submit" name="update" value="Update" class="btn btn-primary">
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
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
            <!-- <div class="box box-primary">
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
            </div> -->
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
    // perhitungan pajak
    var np = <?= $np ?>;

    if (np > 0) {
        $('#myCheck').attr('checked', 'checked');
    }

    // Cek PPH
    var id_pph = '<?= $data['id_pph']; ?>';

    var jenis = '<?= $data['jenis']; ?>';

    var harga_akhir = '<?= $data['harga_akhir']; ?>';
    // console.log(harga_akhir);

    // document.form.jml.value = harga_akhir;


    $("#id_pph").val(id_pph);

    showPph(jenis);

    // $("#tf").hide();
    // $("#ktk").hide();
    console.log(id_pph);

    $('#id_pph').on('change', function() {
        let id_pph = this.value;
        let jenis = $(this).find(':selected').data('id')

        console.log(id_pph);
        console.log(jenis);

        showPph(jenis);

    });


    $(".perhitungan").keyup(function() {

        var nilaiJasa = parseInt($("#nilai_jasa").val())
        var nj_ui = tandaPemisahTitik(nilaiJasa);
        $('#nj_ui').text('Rp.' + nj_ui);

        var pph_persen = parseInt($("#pph_persen").val())
        var pph_nilai = nilaiJasa * pph_persen / 100;
        var pph_nilaia = tandaPemisahTitik(pph_nilai);
        $("#pph").attr("value", pph_nilaia);
        document.form.pph_nilai.value = pph_nilaia;


        var nilaiBarang = parseInt($("#nilai_barang").val())
        var nb_ui = tandaPemisahTitik(nilaiBarang);
        $('#nb_ui').text('Rp.' + nb_ui);

        // nilai pph untuk pajak progresive
        var pph_nilai2 = parseInt($("#pph_nilai2").val())
        var pph_ui = tandaPemisahTitik(pph_nilai2);
        $('#pph_ui').text('Rp.' + pph_ui);

        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {
            var ppn_nilai = Math.floor(0.1 * (nilaiBarang + nilaiJasa));
        } else if (checkBox.checked == false) {
            var ppn_nilai = 0;
        }

        var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
        document.form.ppn_nilai.value = ppn_nilaia;

        var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2;
        var jml = tandaPemisahTitik(jmla);
        $("#jml").attr("value", jml);
        document.form.jml.value = jml;


    });

    function hilangkanTitik(data) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value))))); //input ke dalam angka tanpa titik

        return angka;
    }

    function showPph(data) {

        var nilai_barang = hilangkanTitik('nilai_barang')
        var nilai_jasa = hilangkanTitik('nilai_jasa')
        var ppn_nilai = hilangkanTitik('ppn_nilai')

        // var jml = hilangkanTitik('jml')
        var pph_nilai = hilangkanTitik('pph_nilai')

        // pph nilai 2 untuk tarif progresive
        var pph_nilai2 = hilangkanTitik('pph_nilai2')
        console.log(nilai_barang);

        if (data == 'fixed') {
            $("#fixed").show();
            $("#progresive").hide();

            var jml = (nilai_barang + nilai_jasa + ppn_nilai) - pph_nilai;
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

            var jml = (nilai_barang + nilai_jasa + ppn_nilai) - pph_nilai2;
            jml = tandaPemisahTitik(jml);

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
            document.form.jml.value = jml;
        } else {
            $("#fixed").hide();
            $("#progresive").hide();


            var jml = (nilai_barang + nilai_jasa + ppn_nilai);
            jml = tandaPemisahTitik(jml);

            document.form.pph_persen.value = 0;
            document.form.pph_nilai.value = 0;
            document.form.pph_nilai2.value = 0;
            document.form.jml.value = jml;
        }
    }

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var nilaiJasa = parseInt($("#nilai_jasa").val())
            var pph_persen = parseInt($("#pph_persen").val())
            var pph_nilai = nilaiJasa * pph_persen / 100;
            var pph_nilaia = tandaPemisahTitik(pph_nilai);
            $("#pph").attr("value", pph_nilaia);
            document.form.pph_nilai.value = pph_nilaia;


            var nilaiBarang = parseInt($("#nilai_barang").val())
            var ppn_nilai = Math.floor(0.1 * (nilaiBarang + nilaiJasa));
            var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
            $("#ppn").attr("value", ppn_nilaia);
            document.form.ppn_nilai.value = ppn_nilaia;

            var pph_nilai2 = parseInt($("#pph_nilai2").val())

            var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2;
            var jml = tandaPemisahTitik(jmla);
            $("#jml").attr("value", jml);
            document.form.jml.value = jml;

        } else if (checkBox.checked == false) {
            var nilaiJasa = parseInt($("#nilai_jasa").val())
            var pph_persen = parseInt($("#pph_persen").val())
            var pph_nilai = nilaiJasa * pph_persen / 100;
            var pph_nilaia = tandaPemisahTitik(pph_nilai);
            $("#pph").attr("value", pph_nilaia);
            document.form.pph_nilai.value = pph_nilaia;


            var nilaiBarang = parseInt($("#nilai_barang").val())
            var ppn_nilai = 0;
            var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
            $("#ppn").attr("value", ppn_nilaia);
            document.form.ppn_nilai.value = ppn_nilaia;

            var pph_nilai2 = parseInt($("#pph_nilai2").val())

            var jmla = nilaiBarang + nilaiJasa + ppn_nilai - pph_nilai - pph_nilai2;
            var jml = tandaPemisahTitik(jmla);
            $("#jml").attr("value", jml);
            document.form.jml.value = jml;
        }
    }
    // perhitungan pajak
</script>