<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");
$tgl_sekarang = date("Y-m-d");

$queryData =  mysqli_query($koneksi, "SELECT *, s.created_by as yg_buat
                                        FROM so s
                                        JOIN anggaran a
                                            ON a.id_anggaran = s.id_anggaran
                                        JOIN divisi d
                                            ON d.id_divisi = s.id_divisi
                                        WHERE s.status = '5'
                                        -- AND tgl_tempo <= '$tgl_sekarang'
                                        ORDER BY tgl_tempo ASC
                ");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_sr&id=$id&pg=" . enkripRambo("payment_sr") . "");
    } else if ($_GET['aksi'] == 'release') {
        header("location:rls_sr.php?id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:del_sr.php?id=$id");
    }
}

?>

<section class="content-header">
    <h1>
        Payment SO
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Payment SO</li>
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
                    <h3 class="text-center">List Service Order</h3>
                </div>

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                </div>

                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-hover" id="material">
                        <tr style="background-color :#B0C4DE;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Divisi</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Kode Anggaran</th>
                            <th rowspan="2">Tanggal Tempo</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <tbody>

                                <?php
                                $no = 1;
                                if (mysqli_num_rows($queryData)) {
                                    while ($row = mysqli_fetch_assoc($queryData)) :

                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['yg_buat']; ?> </td>
                                        <td> <?= $row['nm_divisi']; ?> </td>
                                        <td> <?= formatTanggal($row['created_at']); ?> </td>
                                        <td> <?= $row['nm_barang']; ?> </td>
                                        <td> <?= $row['keterangan']; ?> </td>
                                        <td> <?= $row['kd_anggaran'] . " - " . $row['nm_item']; ?> </td>
                                        <td> <?= $row['tgl_tempo']; ?> </td>
                                        <td>
                                            <?php if ($row['tgl_tempo'] <= $tgl_sekarang) { ?>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymentSO-<?= $row['id_so']; ?>"><i class="fa fa-money"></i> Payment</button>
                                                <a href="?p=payment_sr&aksi=edit&id=<?= enkripRambo($row['id_so']); ?>"><span data-placement='top' data-toggle='tooltip'><button class="btn btn-success"> <i class="fa fa-file-text-o"></i> Detail</button></span></a>
                                                <!-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectSO-<?= $row['id_so']; ?>"><i class="fa fa-reply"></i> Reject</button> -->
                                            <?php } else {
                                                echo "<span class='label label-warning'>Belum masuk tempo</span>";
                                            } ?>

                                        </td>
                        </tr>

                        <!-- Modal release -->
                        <div id="paymentSO-<?= $row['id_so']; ?>" class="modal fade" role="dialog">
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
                                            <form method="POST" name="form" enctype="multipart/form-data" action="payment_so.php" class="form-horizontal">
                                                <div class="box-body">
                                                    <input type="hidden" name="id" value="<?= $row['id_so']; ?>">
                                                    <input type="hidden" name="url" id="url" value="payment_sr">
                                                    <h4>Apakah anda yakin ingin sudah payment service order <b><?= $row['nm_barang']; ?> ? </b></h4>
                                                    <br>
                                                    <label for="doc_pembayaran" class="col-sm-offset- col-sm-3 control-label">Bukti Pembayaran</label>
                                                    <div class="col-sm-6">
                                                        <!-- <div class="input-group input-file" name="doc_pembayaran">
                                                            <input type="text" class="form-control" required>
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                            </span>
                                                        </div> -->
                                                        <input type="file" name="doc_pembayaran" class="form-control" accept="application/pdf" required>
                                                    </div>
                                                    <br><br><br>
                                                    <div class=" modal-footer">
                                                        <input type="submit" name="kirim" class="btn btn-primary" value="Kirim">
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
                        <div id="rejectSO-<?= $row['id_so']; ?>" class="modal fade" role="dialog">
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
                                        <form method="POST" enctype="multipart/form-data" action="tolak_so.php" class="form-horizontal">
                                            <div class="box-body">
                                                <input type="hidden" name="id_so" id="id_so" value="<?= $row['id_so']; ?>">
                                                <input type="hidden" name="url" id="url" value="payment_sr">
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
    });

    console.log(host);

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
</script>