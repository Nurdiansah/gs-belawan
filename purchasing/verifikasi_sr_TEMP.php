<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

$queryData =  mysqli_query($koneksi, "SELECT * FROM sr s
                                               JOIN anggaran a
                                               ON a.id_anggaran = s.id_anggaran 
                                               WHERE s.status = '2' 
                                               ORDER BY created_at ASC
                                               ");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_sr&id=$id&pg=" . enkripRambo("verifikasi_sr") . "");
    } else if ($_GET['aksi'] == 'release') {
        header("location:rls_sr.php?id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:del_sr.php?id=$id");
    }
}

?>

<section class="content-header">
    <h1>
        Verifikasi SR
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Verifikasi SR</li>
    </ol>
</section>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">

                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Service Request</h3>
                </div>

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                </div>

                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-hover" id="material">
                        <tr style="background-color :#B0C4DE;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Kode Anggaran</th>
                            <th rowspan="2">Total</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <!-- <tr> -->
                        <tbody>

                            <?php
                            $no = 1;
                            if (mysqli_num_rows($queryData)) {
                                while ($row = mysqli_fetch_assoc($queryData)) :

                            ?>
                                    <td> <?= $no; ?> </td>
                                    <td> <?= formatTanggal($row['created_at']); ?> </td>
                                    <td> <?= $row['nm_barang']; ?> </td>
                                    <td> <?= $row['keterangan']; ?> </td>
                                    <td> <?= $row['kd_anggaran'] . " - " . $row['nm_item']; ?> </td>
                                    <td> <?= formatRupiah($row['grand_total']); ?> </td>
                                    <td>
                                        <?php if ($row['grand_total'] > 0) { ?>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#approveSr-<?= $row['id_sr']; ?>"><i class="fa fa-send"></i> Submit</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-dark"><i class="fa fa-send"></i> Submit</button>
                                        <?php } ?>
                                        <a href="?p=verifikasi_sr&aksi=edit&id=<?= enkripRambo($row['id_sr']); ?>"><span data-placement='top' data-toggle='tooltip' title='Detail'><button class="btn btn-success"> <i class="fa fa-file-text-o"></i> Detail</button></span></a>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectSr-<?= $row['id_sr']; ?>"><i class="fa fa-reply"></i> Reject</button>
                                    </td>
                                    </tr>

                                    <!-- Modal release -->
                                    <div id="approveSr-<?= $row['id_sr']; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- konten modal-->
                                            <div class="modal-content">
                                                <!-- heading modal -->
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Konfirmasi</h4>
                                                </div>
                                                <!-- body modal -->
                                                <div class="modal-body">
                                                    <div class="perhitungan">
                                                        <form method="post" name="form" enctype="multipart/form-data" action="submit_sr.php" class="form-horizontal">
                                                            <div class="box-body">
                                                                <input type="hidden" name="id" value="<?= $row['id_sr']; ?>">
                                                                <input type="hidden" name="id_user" value="<?= $row['id_user']; ?> ">
                                                                <input type="hidden" name="id_manager" value="<?= $row['id_manager']; ?> ">
                                                                <input type="hidden" name="id_anggaran" value="<?= $row['id_anggaran']; ?> ">
                                                                <input type="hidden" name="keterangan" value="<?= $row['keterangan']; ?> ">
                                                                <input type="hidden" name="pembuat" value="<?= $Nama; ?> ">
                                                                <input type="hidden" name="doc_penawaran" value="<?= $row['doc_penawaran']; ?> ">
                                                                <input type="hidden" name="doc_quotation" value="<?= $row['doc_quotation']; ?> ">
                                                                <input type="hidden" name="url" id="url" value="verifikasi_sr">
                                                                <input type="hidden" name="total" value="<?= $row['total']; ?> ">
                                                                <input type="hidden" name="nilai_ppn" value="<?= $row['nilai_ppn']; ?> ">
                                                                <input type="hidden" name="id_user" value="<?= $row['id_user']; ?> ">
                                                                <input type="hidden" name="id_sr" value="<?= $row['id_sr']; ?> ">
                                                                <input type="hidden" name="id_divisi" value="<?= $row['id_divisi']; ?> ">
                                                                <input type="hidden" name="id_manager" value="<?= $row['id_manager']; ?> ">
                                                                <h4>Apakah anda yakin ingin mensubmit service request <b><?= $row['nm_barang']; ?> ? </b></h4>
                                                                <br>
                                                                <!-- jika diatas 10jt, maka nampilin input tanggal tempo pembayaran -->
                                                                <!-- </?php if ($row['grand_total'] > 10000000) { ?> -->
                                                                <!-- <div class="form-group"> -->
                                                                <!-- <label for="tgl_tempo" class="col-sm-offset- col-sm-3 control-label">Tanggal Tempo</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" required class="form-control tanggal" name="tgl_tempo">
                                                                    </div> -->
                                                                <!-- </div> -->
                                                                <!-- <br><br> -->
                                                                <!-- </?php } ?> -->
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-primary" type="submit" name="approve">Ya, Saya yakin</button></span></a>
                                                                    &nbsp;
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                </div>
                                                            </div>
                                                        </form>
                                                        <!-- div perhitungan -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End release -->

                                    <!-- Modal Reject -->
                                    <div id="rejectSr-<?= $row['id_sr']; ?>" class="modal fade" role="dialog">
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
                                                    <form method="post" enctype="multipart/form-data" action="tolak_sr.php" class="form-horizontal">
                                                        <div class="box-body">
                                                            <input type="hidden" name="id_sr" id="id_sr" value="<?= $row['id_sr']; ?>">
                                                            <input type="hidden" name="url" id="url" value="verifikasi_sr">
                                                            <div class="mb-3">
                                                                <label for="validationTextarea">Komentar</label>
                                                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
                                                                <div class="invalid-feedback">
                                                                    Please enter a message in the textarea.
                                                                </div>
                                                            </div>
                                                            <div class=" modal-footer">
                                                                <button class="btn btn-success" type="submit" name="update">Kirim</button></span></a>
                                                                &nbsp;
                                                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  -->
                            <?php
                                    $no++;
                                endwhile;
                            } ?>
                        </tbody>
                    </table>
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
</script>