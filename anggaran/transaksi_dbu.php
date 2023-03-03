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

$queryNama =  mysqli_query($koneksi, "SELECT nama , id_divisi from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];


$queryBkk = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            LEFT JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.id_bkk = '$id' ");

$tahun = date("Y");

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">


                <!-- Detail Job Order -->
                <br><br>
                <div class="box-header with-border">
                    <h3 class="text-center">Rubah Kode Anggaran</h3>
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


                        $Divisi = $row2['id_divisi'];

                ?>
                        <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">
                            <div class="box-body">
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
                                        <input type="text" value="<?= $row2['kd_anggaran'] . " [" . $row2['nm_item']; ?>]" class="form-control " name="kd_transaksi" readonly>
                                    </div>
                                    <button type="button" class="btn btn-danger col-sm-offset-" data-toggle="modal" data-target="#rubah">Rubah</button></span></a>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $row2['keterangan']; ?>" class="form-control " name="keterangan" readonly>
                                    </div>
                                    <!-- </div>$Divisi =   
                            <label for="terbilang_bkk" class=" col-sm-2 control-label">Terbilang</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['terbilang_bkk'] . ' Rupiah'; ?>" disabled class="form-control tanggal" name="terbilang_bkk">
                            </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label id="tes"for="nm_vendor"  class=" col-sm-2 control-label">No Rekening</label>
                            <div class="col-sm-3">
                                <input type="text"  value="<?= $row2['bank_tujuan'] . " - " . $row2['norek_tujuan']; ?>" disabled class="form-control" name="nm_vendor">
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
                    <form method="post" enctype="multipart/form-data" action="rubah_anggaranbu.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $row2['kd_transaksi']; ?>" class="form-control" name="kd_transaksi" readonly>
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
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox" <?= $row2['id_anggaran'] == $rowAnggaran['id_anggaran'] ? "selected" : ""; ?>><?= $rowAnggaran['kd_anggaran'] . ' [' . $rowAnggaran['nm_item']; ?>]<?= $rowAnggaran['row_version'] == 2 ? " - <b>NEW!</b>" : ""; ?></option>
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
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
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