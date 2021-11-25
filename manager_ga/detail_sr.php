<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);

$queryData =  mysqli_query($koneksi, "SELECT *  FROM so s
                                                JOIN anggaran a
                                                ON a.id_anggaran = s.id_anggaran
                                                WHERE s.id_so = $id ");
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


?>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-md-2">
            <a href="index.php?p=<?= dekripRambo($_GET['pg']); ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <br><br>
    </div>
    <!-- SR -->
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Form Penawaran</h3>
                </div>
                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
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
                            <!-- <div class="form-group">
                                <label for="tgl_tempo" class="col-sm-offset col-sm-3 control-label">Tempo Pembayaran</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control is-valid" name="tgl_tempo" value="<?= $data['tgl_tempo']; ?>" readonly>
                                </div>
                            </div> -->
                        </div>
                        <div class="form-group ">
                            <label for="validationTextarea" class="col-sm-3 control-label">Note</label>
                            <div class="col-sm-6">
                                <textarea rows="5" class="form-control is-invalid" name="note_sr" id="validationTextarea" placeholder="DP 30% dibayar 1 minggu, Pelunasan 70% di bayar 2 minggu, Pekerjaan harus dilaksanakan terhitung sejak uang DP diterima, Apabila pekerjaan belum dilaksanakn maka akan dilakukan pemotongan sebesar 1/m/hari dari nilai PO" readonly><?= $data['note']; ?></textarea>
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

    // Format Select 2
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        var $state = $(
            '<span> <span></span></span>'
        );

        // Use .text() instead of HTML string concatenation to avoid script injection issues
        $state.find("span").text(state.text);

        return $state;
    };

    $("#idSupplier").select2({
        templateSelection: formatState
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' accept='application/pdf' style='visibility:hidden; height:0'>");
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

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_dsr').val(data.id_dsr);
                    $('#me_deskripsi').val(data.deskripsi);
                    $('#me_merk').val(data.merk);
                    $('#me_type').val(data.type);
                    $('#me_spesifikasi').val(data.spesifikasi);
                    $('#me_qty').val(data.qty);
                    $('#me_satuan').val(data.satuan);
                    $('#me_keterangan').val(data.keterangan);
                }
            });
        });
    });
    // Akhir modal edit

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#md_id_dsr').val(data.id_dsr);
                    $('#md_sr_id').val(data.sr_id);
                    $('#md_deskripsi').text(data.deskripsi);
                }
            });
        });
    });
    // Akhir modal delete


    var nilai_ppn = parseInt($("#nilai_ppn").val())
    console.log(nilai_ppn);
    if (nilai_ppn > 0) {
        $('#myCheck').prop('checked', true);
    } else {
        $('#myCheck').prop('checked', false);
    }

    // Perhitungan
    $(".perhitungan").keyup(function() {


        //ambil inputan harga            

        var diskon_sr = parseInt($("#diskon_sr").val())

        var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik
        // var nominal = parseInt($("#nominal").val())

        var total_sr = nominal - diskon_sr;

        var total_sra = tandaPemisahTitik(total_sr);
        document.form.total_sr.value = total_sra;

        var grand_totalsr = total_sr;
        var grand_totalsra = tandaPemisahTitik(grand_totalsr);

        document.form.grand_totalsr.value = grand_totalsra;

    });

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var diskon_sr = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('diskon_sr').value))))); //input ke dalam angka tanpa titik

            var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik

            var total_sr = nominal - diskon_sr;

            var total_sra = tandaPemisahTitik(total_sr);

            var nilai_ppn = Math.floor(0.1 * total_sr);

            var nilai_ppna = tandaPemisahTitik(nilai_ppn);

            document.form.nilai_ppn.value = nilai_ppna;

            var grand_totalsr = total_sr + nilai_ppn;
            var grand_totalsra = tandaPemisahTitik(grand_totalsr);

            document.form.grand_totalsr.value = grand_totalsra;


        } else if (checkBox.checked == false) {
            var diskon_sr = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('diskon_sr').value))))); //input ke dalam angka tanpa titik

            var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik

            var total_sr = nominal - diskon_sr;

            var total_sra = tandaPemisahTitik(total_sr);

            var nilai_ppn = 0;

            document.form.nilai_ppn.value = 0;

            var grand_totalsr = total_sr;
            var grand_totalsra = tandaPemisahTitik(grand_totalsr);

            document.form.grand_totalsr.value = grand_totalsra;
        }
    }
</script>