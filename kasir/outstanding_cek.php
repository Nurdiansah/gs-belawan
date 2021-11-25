<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (!$koneksi) {
    echo "Koneksi gagal " . mysqli_connect_error();
}

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'payment') {
        header("location:?p=send_paymentkhusus&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT b.id , b.created_on_bkk, b.pengajuan, b.keterangan, a.kd_anggaran, b.nominal, b.status_bkk
                                                FROM bkk_final b    
                                                JOIN anggaran a
                                                ON b.id_anggaran = a.id_anggaran                                                
                                                LEFT JOIN tagihan_po tp
                                                ON b.id_tagihan = tp.id_tagihan
                                                WHERE b.status_bkk IN ( '17', '18')
                                                ORDER BY b.tgl_bkk DESC
                        ");

$jumlahData = mysqli_num_rows($query);

?>
<!-- Main content -->
<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Outstanding Cek</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th>Kode Anggaran</th>
                                    <th>Total</th>
                                    <th>Preview</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :
                                ?>
                                        <tr>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= formatTanggal($row['created_on_bkk']); ?> </td>
                                            <td> <?= $row['pengajuan']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <?= $row['kd_anggaran']; ?> </td>
                                            <td> <?= formatRupiah($row['nominal']); ?> </td>
                                            <td>
                                                <?php if ($row['pengajuan'] == "PO") { ?>
                                                    <a target="_blank" onclick="window.open('cetak_pengambilandana_vendorpo.php?id=<?= enkripRambo($row['id']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> LPD </a>
                                                <?php } elseif ($row['pengajuan'] == "BIAYA UMUM") { ?>
                                                    <a target="_blank" onclick="window.open('cetak_pengambilandana_vendorbu.php?id=<?= enkripRambo($row['id']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> LPD </a>
                                                <?php } ?>
                                                <?php if ($row['status_bkk'] == 17) { ?>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#konfirmasi_<?= $row['id']; ?>"><i class="fa fa-check"></i> Payment</button>
                                                <?php } else if ($row['status_bkk'] == 18) { ?>
                                                    <button type="button" class="btn btn-primary modalSubmit" data-toggle="modal" data-target="#submit" data-id="<?= $row['id']; ?>"><i class="fa fa-send"></i> Submit Invoice</button>
                                                <?php } ?>
                                            </td>
                                            <td> <?php if ($row['status_bkk'] == 17) { ?>
                                                    <span class="label label-primary">Menunggu Penarikan dana </span>
                                                <?php } else if ($row['status_bkk'] == 18) { ?>
                                                    <span class="label label-warning">Menunggu Invoice </span>
                                                <?php } ?>
                                            </td>
                                        </tr>

                                        <!-- Modal Konfirmasi  -->
                                        <div id="konfirmasi_<?= $row['id']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title"> Konfirmasi Outstanding Cek </h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="payment_outstanding_vendor.php" class="form-horizontal">
                                                            <div class="box-body">
                                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                                <input type="hidden" name="regulasi_tempo" value="<?= $row['regulasi_tempo']; ?>">
                                                                <h4>Apakah pengajuan <b><?= $row['keterangan']; ?></b> sudah dibayarkan ?</h4>
                                                                <label for="tanggal" class="col-sm-offset- col-sm-3 control-label">Bukti Pembayaran</label>
                                                                <div class="form-group">
                                                                    <div class="col-sm-6">
                                                                        <input type="file" required class="form-control is-valid" name="bukti_pembayaran" accept="application/pdf" required>
                                                                        <!-- <div class="input-group input-file" name="bukti_pembayaran">
                                                                            <input type="text" class="form-control" required>
                                                                            <span class="input-group-btn">
                                                                                <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                                            </span>
                                                                        </div> -->
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group">
                                                                    <label for="tanggal" class="col-sm-offset- col-sm-3 control-label">Tanggal</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" required class="form-control tanggal" name="tanggal" autocomplete="off" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class=" modal-footer">
                                                                <button class="btn btn-success" type="submit" name="payment">Yes</button></span>
                                                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Modal Konfirmasi  -->

                                <?php
                                        $no++;
                                    endwhile;
                                }

                                if ($jumlahData == 0) {
                                    echo
                                    "<tr>
                                                 <td colspan='8'> Tidak Ada Data</td>
                                            </tr>
                                            ";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Modal Submit  -->
<div id="submit" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Submit Invoice </h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="payment_outstanding_vendor.php" class="form-horizontal">
                    <input type="hidden" name="id" id="me_id">
                    <input type="hidden" name="id_tagihan" id="me_id_tagihan">
                    <div class="box-body">
                        <div class="form-group ">
                            <label for="doc_faktur" class="col-sm-offset- col-sm-4 control-label">Invoice / Faktur</label>
                            <div class="col-sm-6">
                                <div class="input-group input-file" name="doc_faktur" required>
                                    <input type="text" class="form-control" required />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" modal-footer">
                        <button class="btn btn-primary" type="submit" name="submit">Submit</button></span>
                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Konfirmasi  -->

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../assets/plugins/alertify/lib/alertify.min.js"></script>
<script>
    var host = '<?= host() ?>';

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

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    // Browse
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

    // Modal Payment
    $(function() {
        $('.modalSubmit').on('click', function() {

            const id = $(this).data('id');

            console.log(host);

            $.ajax({
                url: host + 'api/bkk/getbkktagihan.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#me_id').val(data.id);
                    $('#me_id_tagihan').val(data.id_tagihan);
                    // $('#me_doc').attr('src', '../file/doc_lpj/' + data.doc_lpj_pettycash);
                }
            });
        });
    });
</script>