<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);
$host = host();

$queryData =  mysqli_query($koneksi, "SELECT *  FROM sr s
                                                -- JOIN anggaran a
                                                -- ON a.id_anggaran = s.id_anggaran
                                                JOIN kasbon k
                                                    ON k.sr_id = s.id_sr
                                                LEFT JOIN pph p
                                                    ON p.id_pph = k.id_pph
                                                WHERE s.id_sr = $id ");
$data = mysqli_fetch_assoc($queryData);

// print_r($data);
// die;

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


$np = $data['nilai_ppn'];

// simpan
if (isset($_POST['simpan'])) {
    $id_kasbon = $_POST['id_kasbon'];
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
    $query = "UPDATE kasbon SET nilai_barang = '$nilai_barang' , nilai_jasa = '$nilai_jasa' , 
                                nilai_ppn = '$ppn_nilai', nilai_pph = '$pph_nilai', 
                                id_pph = '$id_pph', harga_akhir = '$harga_akhir',  app_pajak = '$tanggal'                                              
    WHERE id_kasbon ='$id_kasbon' ";

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
<section class="content-header">
    <h1>
        Kasbon
        <small>Service Request</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="?pg=dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="index.php?p=payment_kasbon&sp=pk_sr">Daftar Kasbon SR</a></li>
        <li class="active">Detail Kasbon SR</li>
    </ol>
</section>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-md-2 col-xs-3">
            <a href="index.php?p=payment_kasbon&sp=pk_sr" class="btn btn-success"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <div class="col-sm-2 col-sm-offset-8 col-xs-offset-2 col-xs-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSr"><i class="fa fa-send"></i> Payment </button>
        </div>
        <br><br>
    </div>


    <!-- Modal release -->
    <div id="approveSr" class="modal fade" role="dialog">
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
                    <form method="post" enctype="multipart/form-data" action="payment_kasbon_sr.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="nominal" disabled value="<?= formatRupiah($data['harga_akhir']); ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="penerima_dana" class="col-sm-offset-1 col-sm-3 control-label">Nama </label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="penerima_dana" placeholder="Nama Penerima Dana">
                                </div>
                            </div>

                            <input type="hidden" name="id" value="<?= $data['id_kasbon']; ?>">
                            <div class=" modal-footer">
                                <button class="btn btn-primary" type="submit" name="approve"> <i class="fa fa-check"></i> Ya, Saya sudah menyerahkan </button></span></a>
                                &nbsp;
                                <button type="reset" class="btn btn-danger" data-dismiss="modal"> <i class="fa fa-close"></i> Batal </button>
                            </div>
                        </div>
                    </form>
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
                <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
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
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-3 control-label">Nominal </label>
                            <div class="col-sm-6">
                                <div class="input-group">
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

    <!-- Form Verifikasi Pajak -->
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Pajak</h3>
                </div>
                <div class="perhitungan">
                    <form method="post" name="form" action="" class="form-horizontal">
                        <input type="hidden" name="id_kasbon" value="<?= $data['id_kasbon']; ?>">
                        <input type="hidden" name="link" value="<?= $host; ?>manager_keuangan/index.php?p=detail_srk&id=<?= $_GET['id'] ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="nilai_barang" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control" value="<?= formatRupiah2($data['nilai_barang']) ?>" name="nilai_barang" id="nilai_barang" autocomplete="off " />
                                    </div>
                                    <i><span id="nb_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_jasa" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control" value="<?= formatRupiah2($data['nilai_jasa']) ?>" name="nilai_jasa" id="nilai_jasa" autocomplete="off" />
                                    </div>
                                    <i><span id="nj_ui"></span></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">PPN 10%</label>
                                <div class="col-sm-1">
                                    <input type="checkbox" name="all" id="myCheck" onclick="checkBox()" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text" readonly class="form-control " name="ppn_nilai" id="ppn_nilai" value="<?= formatRupiah2($data['nilai_ppn']) ?>" />
                                    </div>
                                </div>
                            </div>

                            <?php if ($data['jenis'] != '') { ?>
                                <div class="form-group">
                                    <label id="tes" for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                    <div class="col-sm-5">
                                        <select name="id_pph" class="form-control" id="id_pph" value="<?= $row2['id_pph'] ?>" disabled>
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
                                <?php
                                if ($data['jenis'] == 'fixed') {
                                    $persen = ($data['nilai_pph'] / $data['nilai_jasa']) * 100;
                                ?>
                                    <div id="fixed">
                                        <div class="form-group">
                                            <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">PPh</span>
                                                    <input type="text" required class="form-control " name="pph_persen" value="<?= $persen; ?>" id="pph_persen" disabled />
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
                                <?php } else if ($data['jenis'] == 'progresive') { ?>
                                    <div id="progresive">
                                        <div class="form-group">
                                            <label id="tes" for="pph_nilai2" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Rp.</span>
                                                    <input type="text" class="form-control " name="pph_nilai2" value="<?= formatRupiah2($data['nilai_pph']) ?>" id="pph_nilai2" disabled />
                                                </div>
                                                <i><span id="pph_ui"></span></i>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    $("#id_pph").val(id_pph);
    // perhitungan pajak
</script>