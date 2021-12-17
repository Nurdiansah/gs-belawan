<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=cetak_bkk&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$queryBkk = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.id_bkk = '$id' ");

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">


                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Detail Biaya Umum</h3>
                </div>
                <?php
                if (mysqli_num_rows($queryBkk)) {
                    while ($row2 = mysqli_fetch_assoc($queryBkk)) :
                        // query Total_cargo
                        $nilai_barang = number_format($row2['nilai_barang'], 0, ",", ".");
                        $nilai_jasa = number_format($row2['nilai_jasa'], 0, ",", ".");
                        $ppn_nilai = number_format($row2['ppn_nilai'], 0, ",", ".");
                        $pph_nilai = number_format($row2['pph_nilai'], 0, ",", ".");
                        $jml_bkk = number_format($row2['jml_bkk'], 0, ",", ".");
                        $bll_bkk = number_format($row2['bll_bkk'], 0, ",", ".");

                        $budget = $row2['januari_nominal'] + $row2['februari_nominal'] + $row2['maret_nominal'] + $row2['april_nominal'] + $row2['mei_nominal'] + $row2['juni_nominal'] + $row2['juli_nominal'] + $row2['agustus_nominal'] + $row2['september_nominal'] + $row2['oktober_nominal'] + $row2['november_nominal'] + $row2['desember_nominal'];
                        $realisasi = $row2['januari_realisasi'] + $row2['februari_realisasi'] + $row2['maret_realisasi'] + $row2['april_realisasi'] + $row2['mei_realisasi'] + $row2['juni_realisasi'] + $row2['juli_realisasi'] + $row2['agustus_realisasi'] + $row2['september_realisasi'] + $row2['oktober_realisasi'] + $row2['november_realisasi'] + $row2['desember_realisasi'];
                        $saldoAnggaranb = $budget - $realisasi;
                        $saldoAnggaran = 'Rp. ' . number_format($saldoAnggaranb, 0, ",", ".");

                ?>

                        <!-- form -->
                        <form method="post" enctype="multipart/form-data" action="cetak_bkk.php" class="form-horizontal">
                            <div class="col-sm-offset- col-sm-1 control-label">
                                <input type="hidden" class="form-control" name="id_bkk" value="<?= $row2['id_bkk']; ?>">
                                <!-- <input type="submit" name="simpan" class="btn btn-success " value="Cetak BKK "   >                                                                             -->
                            </div>
                        </form>
                        <!-- akhir form -->





                        <form class="form-horizontal">
                            <div class="box-body">
                                <div class="form-group ">
                                    <label class="col-sm-offset-10   control-label"></label>
                                    <!-- <a target="_blank"  href="cetak_bkk.php&id=<?= $row2['id_bkk']; ?>" class="btn btn-success"> Cetak BKK <i class="fa fa-print"></i> </a> -->

                                    <?php
                                    if ($row2['status_bkk'] == '9') {
                                        # code...
                                        echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#payment'>Payment</button></span>";
                                    }

                                    ?>


                                </div>
                                <div class="form-group ">
                                    <label for="id_joborder" class=" col-sm-2 control-label">Kode Transaksi</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['kd_transaksi']; ?>" disabled class="form-control" name="id_bkk">
                                    </div>
                                    <!-- </div>
                    <div class="form-group "> -->
                                    <label id="tes" for="tgl_bkk" class=" col-sm-2 control-label">Tanggal Pengajuan</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['tgl_pengajuan']; ?>" disabled class="form-control" name="tgl_bkk">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nm_vendor" class=" col-sm-2 control-label">Nama Vendor</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['nm_vendor']; ?>" disabled class="form-control" name="nm_vendor">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label for="kd_transaksi" class="col-sm-2 control-label">Kode Anggaran</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['kd_anggaran']; ?>" class="form-control " name="kd_transaksi" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['keterangan']; ?>" class="form-control " name="keterangan" readonly>
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label for="terbilang_bkk" class=" col-sm-2 control-label">Terbilang</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['terbilang_bkk'] . ' Rupiah'; ?>" disabled class="form-control tanggal" name="terbilang_bkk">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label id="tes" for="nm_vendor" class=" col-sm-2 control-label">No Rekening</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['bank_tujuan'] . " - " . $row2['norek_tujuan']; ?>" disabled class="form-control" name="nm_vendor">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label for="kd_transaksi" class="col-sm-2 control-label">Nama Penerima</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['penerima_tujuan']; ?>" class="form-control " name="kd_transaksi" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-4 control-label">Saldo Anggaran</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $saldoAnggaran; ?>" readonly class="form-control" name="jml_bkk">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai Barang</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $nilai_barang; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai Jasa</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $nilai_jasa; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">PPN</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['ppn_persen'];  ?> %" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">PPh</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['pph_persen'];  ?> %" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai PPN</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $ppn_nilai; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                    <!-- </div>
                    <div class="form-group"> -->
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nilai PPh</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $pph_nilai; ?>" readonly class="form-control" name="nilai_bkk">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label id="tes" for="jml_bkk" class="col-sm-4 control-label">Jumlah</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= "Rp." . $jml_bkk; ?>" readonly class="form-control" name="jml_bkk">
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </form>


                        <!-- Embed Document               -->
                        <!-- Document PTW -->
                        <div class="box-header with-border">
                            <h3 class="text-center">Invoice </h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" type="application/pdf" src="../file/<?php echo $row2['invoice']; ?> "></iframe>
                            </div>
                            <!-- <object data="mypdf.pdf" type="application/pdf" frameborder="0" width="100%" height="600px" style="padding: 20px;">
                                <embed src="../file/<?php echo $row2['invoice']; ?>" width="100%" height="600px" />
                            </object> -->


                            <br>
                            <br>

                        </div>
            </div>
        </div>
    </div>

    <!-- Modal Payment  -->

    <div id="payment" class="modal fade" role="dialog">

        <div class="modal-dialog">

            <!-- konten modal-->

            <div class="modal-content">

                <!-- heading modal -->

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Lengkapi Pembayaran</h4>

                </div>

                <!-- body modal -->

                <div class="modal-body">

                    <form method="post" enctype="multipart/form-data" action="add_paymentbno.php" class="form-horizontal">

                        <div class="box-body">

                            <input type="hidden" value="<?= $row2['kd_transaksi']; ?>" disabled class="form-control" name="kd_transaksi">

                            <div class="form-group ">

                                <div class="col-sm-4">

                                    <input type="hidden" value="<?= $row2['id_bkk']; ?>" class="form-control" name="id_bkk" readonly>

                                    <input type="hidden" value="<?= $row2['id_anggaran']; ?>" class="form-control" name="id_anggaran" readonly>

                                    <input type="hidden" value="<?= $row2['jml_bkk']; ?>" class="form-control" name="jml_bkk" readonly>

                                    <input type="hidden" value="<?= $row2['kd_transaksi']; ?>" class="form-control" name="kd_transaksi" readonly>

                                    <input type="hidden" value="<?= $row2['metode_pembayaran']; ?>" name="metode_pembayaran">

                                    <!-- nilai -->

                                    <input type="hidden" value="<?= $row2['nilai_barang']; ?>" name="nilai_barang">

                                    <input type="hidden" value="<?= $row2['nilai_jasa']; ?>" name="nilai_jasa">

                                    <input type="hidden" value="<?= $row2['ppn_nilai']; ?>" name="nilai_ppn">

                                    <input type="hidden" value="<?= $row2['id_pph']; ?>" name="id_pph">

                                    <input type="hidden" value="<?= $row2['pph_nilai']; ?>" name="nilai_pph">

                                    <input type="hidden" value="<?= $row2['jml_bkk']; ?>" name="nominal">

                                    <input type="hidden" value="<?= $row2['jenis']; ?>" name="jenis">

                                    <!-- end nilai -->

                                </div>

                            </div>

                            <?php if ($row2['metode_pembayaran'] === 'transfer') {  ?>

                                <!-- Jika metode pembayaran nya transfer -->

                                <div class="form-group ">

                                    <label id="tes" for="dari_bank" class="col-sm-4 control-label">Bank</label>

                                    <div class="col-sm-4">

                                        <select name="dari_bank" class="form-control">

                                            <option value="">--Pilih Bank--</option>

                                            <?php

                                            $queryBank = mysqli_query($koneksi, "SELECT * FROM bank ORDER BY nm_bank ASC");

                                            if (mysqli_num_rows($queryBank)) {

                                                while ($rowBank = mysqli_fetch_assoc($queryBank)) :

                                            ?>

                                                    <option value="<?= $rowBank['id_bank']; ?>" type="checkbox"><?= $rowBank['nm_bank']; ?></option>

                                            <?php endwhile;
                                            } ?>

                                        </select>

                                    </div>

                                </div>

                                <div class="form-group ">

                                    <label id="tes" for="dari_rekening" class="col-sm-4 control-label">Rekening</label>

                                    <div class="col-sm-4">

                                        <input type="text" class="form-control " name="dari_rekening" value="">

                                    </div>

                                </div>

                                <div class="form-group ">

                                    <label id="tes" for="nocek_bkk" class="col-sm-4 control-label">No. Cek/Giro</label>

                                    <div class="col-sm-4">

                                        <input type="text" class="form-control " name="nocek_bkk" value="-">

                                    </div>

                                </div>

                                <!-- End Metode pembayarn transfer -->

                            <?php } ?>



                            <?php

                            if ($row2['jenis'] == 'umum') {

                            ?>

                                <div class="form-group">

                                    <label for="doc_lpj" class="col-sm-offset- col-sm-4 control-label">Bukti Pembayaran</label>

                                    <div class="col-sm-5">

                                        <div class="input-group input-file" name="doc_lpj">

                                            <input type="text" class="form-control" placeholder="Lampirkan Bukti Pembayaran disini" />

                                            <span class="input-group-btn">

                                                <button class="btn btn-default btn-choose" type="button">Browse</button>

                                            </span>

                                        </div>

                                        <span class="text-danger"><i>*Opsional</i></span>

                                    </div>

                                </div>

                            <?php

                            }

                            ?>

                            <div class="mb-3">

                                <label for="validationTextarea">Rubah Redaksi</label>

                                <textarea rows="5" class="form-control is-invalid" name="keterangan" id="validationTextarea" required> <?= $row2['keterangan']; ?> </textarea>

                                <div class="invalid-feedback">

                                    Please enter a message in the textarea.

                                </div>

                            </div>

                            <div class=" modal-footer">

                                <button class="btn btn-success" type="submit" name="simpan">Submit</button></span></a>

                                &nbsp;

                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

<?php endwhile;
                } ?>

</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });


    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' accept='application/pdf' class='input-ghost' style='visibility:hidden; height:0'>");
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
</script>