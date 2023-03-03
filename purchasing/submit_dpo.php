<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN detail_biayaops dbo
                                            ON dbo.kd_transaksi = bo.kd_transaksi
                                            JOIN po p
                                            ON dbo.id = p.id_dbo
                                            JOIN anggaran a
                                            ON a.id_anggaran = dbo.id_anggaran
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            JOIN supplier s
                                            ON s.id_supplier = dbo.id_supplier
                                            WHERE id_po='$id' ");
$row = mysqli_fetch_assoc($queryBo);
$id_dbo = $row['id_dbo'];


$query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            JOIN po p
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            WHERE id_po='$id' ");
$data2 = mysqli_fetch_assoc($query);

$queryDbo =  mysqli_query($koneksi, "SELECT *   FROM detail_biayaops                                                         
                                                        WHERE id=$id_dbo ");
$data = mysqli_fetch_assoc($queryDbo);


$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo ");

$queryTotal = mysqli_query($koneksi, "SELECT sum(harga_estimasi) as total FROM detail_biayaops WHERE kd_transaksi='$id' ");
$rowTotal = mysqli_fetch_assoc($queryTotal);

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=list_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Submit Document Quotation PO</h3>
                </div>
                <form method="post" action="#" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['tgl_pengajuan']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-9 control-label">PO Number</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['po_number']; ?>">
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
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <td> 1 </td>
                                    <td> <?= $row['nm_barang']; ?> </td>
                                    <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                    <td> <?= $row['merk']; ?> </td>
                                    <td> <?= $row['nm_supplier']; ?> </td>
                                    <td> <?= $row['satuan']; ?> </td>
                                    <td> <?= $row['jumlah']; ?> </td>
                                    <td>Rp. <?= number_format($row['harga_estimasi'], 0, ",", "."); ?> </td>
                                </tr>
                            </tbody>
                        </tr>
                    </table>
                </div>
                <br>
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
                            <td colspan="5"><b>Sub Total</b></td>
                            <td><b><?= formatRupiah($total); ?></b></td>
                        </tr>
                            </tbody>
                    </table>
                </div>
                <br>

                <!-- Embed Document               -->
                <div class="box-header with-border">
                    <h3 class="text-center">Foto Barang</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/foto/<?= $data['foto_item']; ?>"></iframe>
                    </div>
                </div>

                <!-- Embed Document               -->
                <div class="box-header with-border">
                    <h3 class="text-center">Document Penawaran</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?= $data['doc_penawaran']; ?>"></iframe>
                    </div>
                    <br>
                    <div class="col-sm-offset-11 col-sm-3 control-label">
                        <br>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submit"><i class="fa fa-file-pdf-o"></i> Submit</button></span></a>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>

    <div id="submit" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Submit Document Quotation</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <div class="perhitungan">
                        <form method="post" name="form" enctype="multipart/form-data" action="add_docpo.php" class="form-horizontal">
                            <div class="box-body">
                                <input type="hidden" value="<?= $data2['kd_transaksi']; ?>" class="form-control" name="kd_transaksi" readonly>
                                <input type="hidden" value="<?= $data2['id_po']; ?>" class="form-control" name="id_po" readonly>
                                <div class="form-group ">
                                    <div class="mb-5">
                                        <label for="validationTextarea">Note</label>
                                        <textarea rows="5" class="form-control is-invalid" name="note_po" id="validationTextarea" placeholder="DP 30% dibayar 1 minggu, Pelunasan 70% di bayar 2 minggu, Pekerjaan harus dilaksanakan terhitung sejak uang DP diterima, Apabila pekerjaan belum dilaksanakn maka akan dilakukan pemotongan sebesar 1/m/hari dari nilai PO"></textarea>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="doc_quotation" class="col-sm-offset-1 col-sm-3 control-label">Sub Total </label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon ">Rp.</span>
                                            <input type="text" class="form-control" name="sub_totalpo" value="<?= formatRupiah2(round($data2['sub_totalpo'])); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="doc_quotation" class="col-sm-offset-1 col-sm-3 control-label">Diskon </label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon ">Rp.</span>
                                            <input type="text" class="form-control" name="diskon_po" id="diskon_po" value="0" required autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="doc_quotation" class="col-sm-offset-1 col-sm-3 control-label">Total </label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon ">Rp.</span>
                                            <input type="text" class="form-control" name="total_po" id="total_po" value="<?= formatRupiah2(round($data2['sub_totalpo'])); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="doc_quotation" class="col-sm-offset-1 col-sm-3 control-label">PPN 11%</label>
                                    <div class="col-sm-5">
                                        <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="doc_quotation" class="col-sm-offset-1 col-sm-3 control-label">Nilai PPN</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon ">Rp.</span>
                                            <input type="text" class="form-control" name="nilai_ppn" id="nilai_ppn" value="0" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="pembulatan" class="col-sm-offset-1 col-sm-3 control-label">Pembulatan</label>
                                    <div class="col-sm-5">
                                        <input type="checkbox" name="cek_pembulatan" id="cek_pembulatan" onclick="checkPembulatan()">
                                    </div>
                                </div>
                                <div id="bgn-pembulatan">
                                    <div class="form-group ">
                                        <label for="nilai_pembulatan" class="col-sm-offset-1 col-sm-3 control-label">Nilai Pembulatan</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <span class="input-group-addon ">Rp.</span>
                                                <input type="text" class="form-control" name="nilai_pembulatan" id="nilai_pembulatan" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="doc_quotation" class="col-sm-offset-1 col-sm-3 control-label">Grand Total</label>
                                    <div class="col-sm-5">
                                        <div class="input-group">
                                            <span class="input-group-addon ">Rp.</span>
                                            <input type="text" class="form-control" id="grand_totalpo" name="grand_totalpo" value="<?= formatRupiah2($data2['sub_totalpo']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="doc_quotation" class="col-sm-offset-1 col-sm-3 control-label">Document </label>
                                    <div class="col-sm-5">
                                        <div class="input-group input-file" name="doc_quotation" required>
                                            <input type="text" class="form-control" required />
                                            <span class="input-group-btn">
                                                <button class="btn btn-default btn-choose" type="button">Browse</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class=" modal-footer">
                                    <button class="btn btn-success" type="submit" name="submit">Kirim</button></span></a>
                                    &nbsp;
                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                </div>
                            </div>
                        </form>
                        <!-- div untuk perhitungan -->
                    </div>
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

    $("#bgn-pembulatan").hide();

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

    $(".perhitungan").keyup(function() {


        //ambil inputan harga            


        var diskon_po = parseInt($("#diskon_po").val())

        var sub_totalpo = "<?php print($data2['sub_totalpo']); ?>";

        var nilai_ppn = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nilai_ppn').value))))); //input ke dalam angka tanpa titik


        var total_po = sub_totalpo - diskon_po;

        var total_poa = tandaPemisahTitik(total_po);
        document.form.total_po.value = total_poa;

        // console.log(nilai_ppn);
        // console.log(total_po);

        var grand_totalpo = total_po + nilai_ppn;

        var grand_totalpoa = tandaPemisahTitik(grand_totalpo);

        document.form.grand_totalpo.value = grand_totalpoa;

        checkPembulatan()

    });

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var diskon_po = parseInt($("#diskon_po").val())

            var sub_totalpo = "<?php print($data2['sub_totalpo']); ?>";

            var total_po = sub_totalpo - diskon_po;

            var total_poa = tandaPemisahTitik(total_po);

            var nilai_ppn = Math.floor(0.11 * total_po);
            var nilai_ppna = tandaPemisahTitik(nilai_ppn);

            document.form.nilai_ppn.value = nilai_ppna;

            var grand_totalpo = total_po + nilai_ppn;
            var grand_totalpoa = tandaPemisahTitik(grand_totalpo);

            document.form.grand_totalpo.value = grand_totalpoa;


        } else if (checkBox.checked == false) {
            var diskon_po = parseInt($("#diskon_po").val())

            var sub_totalpo = "<?php print($data2['sub_totalpo']); ?>";

            var total_po = sub_totalpo - diskon_po;

            var total_poa = tandaPemisahTitik(total_po);

            var nilai_ppn = 0;

            document.form.nilai_ppn.value = 0;

            var grand_totalpo = total_po;
            var grand_totalpoa = tandaPemisahTitik(grand_totalpo);

            document.form.grand_totalpo.value = grand_totalpoa;
        }
    }

    // Pembulatan
    function checkPembulatan() {
        var checkPembulatan = document.getElementById("cek_pembulatan");

        var total_po = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('total_po').value))))); //input ke dalam angka tanpa titik
        var nilai_ppn = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nilai_ppn').value))))); //input ke dalam angka tanpa titik

        let grand_totalpo = total_po + nilai_ppn;

        if (checkPembulatan.checked == true) {

            $("#bgn-pembulatan").show();

            let sisa = grand_totalpo % 100;

            let pembulatan = 100 - sisa;

            if (pembulatan < 100) {

                if (sisa > 50) {

                    grand_totalpo = grand_totalpo + pembulatan;

                    var grand_totalpoa = tandaPemisahTitik(grand_totalpo);

                    document.form.grand_totalpo.value = grand_totalpoa;

                    document.form.nilai_pembulatan.value = pembulatan;

                } else {

                    grand_totalpo = grand_totalpo - sisa;

                    var grand_totalpoa = tandaPemisahTitik(grand_totalpo);

                    document.form.grand_totalpo.value = grand_totalpoa;

                    document.form.nilai_pembulatan.value = sisa;

                }

            }

        } else if (checkPembulatan.checked == false) {
            // var diskon_po = parseInt($("#diskon_po").val())

            $("#bgn-pembulatan").hide();

            var grand_totalpoa = tandaPemisahTitik(grand_totalpo);

            document.form.grand_totalpo.value = grand_totalpoa;

        }
    }
</script>