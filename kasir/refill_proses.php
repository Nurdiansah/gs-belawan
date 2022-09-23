<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


// Link
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

$query = mysqli_query($koneksi, "SELECT * FROM refill_funds WHERE status BETWEEN '0' AND '5'");

$jumlahData = mysqli_num_rows($query);


if (isset($_POST['verifikasi'])) {
    $id_refill = $_POST['id_refill'];
    $tgl_bkk = datetimeHtml($_POST['tanggal']);
    
    mysqli_begin_transaction($koneksi);

    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM refill_funds WHERE id_refill = '$id_refill' "));
    // $created_at = $data['created_at'];
    $keterangan = $data['keterangan'];
    $nominal = $data['nominal'];
    $app_mgr = $data['app_mgr'] == "" ? "0000-00-00" : $data['app_mgr'];
    $app_direksi = $data['app_direksi'] == "" ? "0000-00-00" : $data['app_direksi'];

    // Nomor BKK
    $no_bkk = nomorBkkNew($tgl_bkk);
    $nomor = nomorAwal($no_bkk);

    $cek_bukti = $_FILES['bukti_pembayaran']['name'];
    if ($cek_bukti == "") {
        $nm_baru = NULL;
    } else {
        $path = $_FILES['bukti_pembayaran']['tmp_name'];
        $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];
        $ekstensi = pathinfo($bukti_pembayaran, PATHINFO_EXTENSION);
        $nm_baru = time() . "-bukti-pembayaran-refill-funds." . $ekstensi;
        move_uploaded_file($path, "../file/bukti_pembayaran/" . $nm_baru);
    }

    $update = mysqli_query($koneksi, "UPDATE refill_funds SET status = '6', bukti_pembayaran = '$nm_baru'
                                        WHERE id_refill = '$id_refill'
                            ");

    $insert = mysqli_query($koneksi, "INSERT INTO bkk_final (pengajuan, id_kdtransaksi, nomor, tgl_bkk, no_bkk, nilai_barang, nominal, keterangan,  created_on_bkk, v_mgr_finance, v_direktur, release_on_bkk, status_bkk) VALUES
                                                            ('REFILL FUND', '$id_refill', '$nomor', '$tgl_bkk', '$no_bkk', '$nominal', '$nominal', '$keterangan', '$tgl_bkk', '$app_mgr', '$app_direksi', '$tgl_bkk', '4')");
    // $insert = "Berhasil";

    if ($update && $insert) {
        mysqli_commit($koneksi);

        header('Location: index.php?p=refill_proses');
    } else {
        mysqli_rollback($koneksi);
        echo "Ada error brayy " . mysqli_error($koneksi);
    }
}


// controller delete
if (isset($_POST['delete'])) {
    $id_refill = $_POST['id'];
    mysqli_begin_transaction($koneksi);

    $return = mysqli_query($koneksi, "DELETE FROM refill_funds WHERE id_refill = '$id_refill'");

    if ($return) {

        $del_lpj = $_POST['doc_pendukung'];
        if (isset($del_lpj)) {
            unlink("../file/doc_pendukung/$del_lpj");
        }


        mysqli_commit($koneksi);

        setcookie('pesan', 'Refill Fund Berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        mysqli_rollback($koneksI);
        setcookie('pesan', 'Refill Fund gagal di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }

    header("location:index.php?p=refill_proses");
}

// controller release
if (isset($_POST['release'])) {
    $id_refill = $_POST['id'];
    mysqli_begin_transaction($koneksi);

    $return = mysqli_query($koneksi, "UPDATE refill_funds SET status = '1' WHERE id_refill = '$id_refill'");

    $hapusKomentar = mysqli_query($koneksi, "DELETE FROM tolak_refill WHERE refill_id = '$id_refill'");

    if ($return) {

        mysqli_commit($koneksi);

        setcookie('pesan', 'Refill Fund Berhasil di release!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        mysqli_rollback($koneksi);
        setcookie('pesan', 'Refill Fund gagal di release!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }

    header("location:index.php?p=refill_proses");
}

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
                    <h3 class="text-center">Refill Fund Proses</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>Order Number</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :
                                    ?>
                                            <td>
                                                <a href="index.php?p=refill_show&id=<?= enkripRambo($row['id_refill']) ?>&back=refill_proses"><?= orderNumber($row['id_refill']);  ?></a>
                                            </td>
                                            <td> <?= formatTanggal($row['created_at']); ?> </td>
                                            <td> <?= batasiKata($row['keterangan']); ?> </td>
                                            <td>
                                                <button class="btn btn-primary"><?= formatRupiah($row['nominal']); ?></button>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['status'] == '1') {
                                                    # code...
                                                    echo "<span class='label label-warning'>Approval Cost Control</span>";
                                                } else if ($row['status'] == '2') {
                                                    echo "<span class='label label-primary'>Approval Manager</span>";
                                                } else if ($row['status'] == '3') {
                                                    echo "<span class='label label-success'>Approval GM Finance</span>";
                                                } else if ($row['status'] == '4') {
                                                    echo "<span class='label label-warning'>Approval Direksi</span>";
                                                } elseif ($row['status'] == '5' && $row['jenis'] != 'kas_besar') { ?>
                                                    <button type="button" class="btn btn-success modalRelease" data-toggle="modal" data-target="#verifikasiRefill_<?= $row['id_refill']; ?>" data-id="<?= $row['id_refill']; ?>"><i class="fa fa-check-square-o"></i> Verifikasi</button>
                                                <?php } elseif ($row['status'] == '5' && $row['jenis'] == "kas_besar") {
                                                    echo "<span class='label label-info'>Verifikasi Kasir Jakarta</span>";
                                                } elseif ($row['status'] == 101) { ?>
                                                    <span class='label label-danger'>Ditolak Cost Control</span><br><br>
                                                    <a href="index.php?p=refill_tolak&id=<?= enkripRambo($row['id_refill']) ?>"><button type="button" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i> </button></a>
                                                    <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#deleteRefill" data-id="<?= $row['id_refill']; ?>" title="Delete"><i class="fa fa-trash"></i> </button>
                                                    <button type="button" class="btn btn-warning modalRelease" data-toggle="modal" data-target="#releaseRefill" data-id="<?= $row['id_refill']; ?>" title="Release"><i class="fa fa-rocket"></i> </button>
                                                <?php } elseif ($row['status'] == 202) { ?>
                                                    <span class='label label-danger'>Ditolak Manager</span><br><br>
                                                    <a href="index.php?p=refill_tolak&id=<?= enkripRambo($row['id_refill']) ?>"><button type="button" class="btn btn-success" title="Edit"><i class="fa fa-edit"></i> </button></a>
                                                    <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#deleteRefill" data-id="<?= $row['id_refill']; ?>" title="Delete"><i class="fa fa-trash"></i> </button>
                                                    <button type="button" class="btn btn-warning modalRelease" data-toggle="modal" data-target="#releaseRefill" data-id="<?= $row['id_refill']; ?>" title="Release"><i class="fa fa-rocket"></i> </button>
                                                <?php } ?>
                                            </td>
                                </tr>

                                <!-- Modal Release -->
                                <div id="verifikasiRefill_<?= $row['id_refill']; ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <!-- konten modal-->
                                        <div class="modal-content">
                                            <!-- heading modal -->
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Verifikasi</h4>
                                            </div>
                                            <!-- body modal -->
                                            <div class="modal-body">
                                                <div class="perhitungan">
                                                    <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                                                        <div class="box-body">
                                                            <input type="hidden" name="id_refill" value="<?= $row['id_refill']; ?>">
                                                            <h4>Apakah anda yakin ingin memverfikasi Refill fund ini <b><?= $row['keterangan']; ?></b>?</h4>
                                                            <label for="tanggal" class="col-sm-offset- col-sm-3 control-label">Bukti Pembayaran</label>
                                                            <div class="form-group">
                                                                <div class="col-sm-6">
                                                                    <input type="file" class="form-control is-valid" name="bukti_pembayaran" accept="application/pdf">
                                                                </div>
                                                                <p style="color: red;"><i>*Opsional</i></p>
                                                            </div>
                                                            <br>
                                                            <label for="tanggal" class="col-sm-offset- col-sm-3 control-label">Tanggal</label>
                                                            <div class="form-group">
                                                                <div class="col-sm-6">
                                                                    <input type="datetime-local" class="form-control " name="tanggal" autocomplete="off" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class=" modal-footer">
                                                            <button class="btn btn-success" type="submit" name="verifikasi"> Yes</button></span></a>
                                                            <button class="btn btn-danger" type="reset" data-dismiss="modal"> No</button>
                                                        </div>
                                                    </form>
                                                    <!-- div perhitungan -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Release -->

                        <?php
                                            $no++;
                                        endwhile;
                                    }
                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                                  <td colspan='5'> Tidak Ada Data</td>
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

    <!-- <div class="alert alert-warning" role="alert">
        Fitur Refill Fund masih on progress !
    </div> -->
</section>

<!-- Modal hapus -->
<div id="deleteRefill" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="md_id">
                            <input type="hidden" name="doc_pendukung" value="" id="md_doc_pendukung">
                            <h4>Apakah anda yakin ingin menghapus Refill fund ini <b><span id="md_keterangan"></b> ?</span></h4>
                            <div class=" modal-footer">
                                <button class="btn btn-danger" type="submit" name="delete"><i class="fa fa-trash"></i> Delete</button></span></a>

                                <button class="btn btn-success" type="reset" data-dismiss="modal"><i class="fa fa-refresh"></i> Cancel</button>
                            </div>
                        </div>
                    </form>
                    <!-- div perhitungan -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End hapus -->

<!-- Akhir modal hapus -->

<!-- Modal Release -->
<div id="releaseRefill" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="mr_id">
                            <h4>Apakah anda yakin ingin merelease Refill fund ini <b><span id="mr_keterangan"></b> ?</span></h4>
                            <div class=" modal-footer">
                                <button class="btn btn-warning" type="submit" name="release"><i class="fa fa-rocket"></i> Release</button></span></a>

                                <button class="btn btn-default" type="reset" data-dismiss="modal"><i class="fa fa-refresh"></i> Cancel</button>
                            </div>
                        </div>
                    </form>
                    <!-- div perhitungan -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Release -->


<?php
$host = host();

?>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../assets/plugins/alertify/lib/alertify.min.js"></script>
<script>
    var host = '<?= $host ?>';

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    function reset() {
        $("#toggleCSS").attr("href", "../assets/plugins/alertify/themes/alertify.default.css");
        alertify.set({
            labels: {
                ok: "OK",
                cancel: "Cancel"
            },
            delay: 5000,
            buttonReverse: false,
            buttonFocus: "ok"
        });
    }

    // ==============================
    // Standard Dialogs
    $("#alert").on('click', function() {
        reset();
        alertify.alert("This is an alert dialog");
        return false;
    });

    $("#confirm").on('click', function() {
        reset();
        alertify.confirm("Konfirmasi Pembayaran Dana", function(e) {
            if (e) {
                alertify.success("Berhasil di update");
            } else {
                alertify.error("Cancel");
            }
        });
        return false;
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

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/refill/get_refill.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data.doc_pendukung);
                    $('#md_id').val(data.id_refill);
                    $('#md_keterangan').text(data.keterangan);
                    $('#md_doc_pendukung').val(data.doc_pendukung);
                }
            });
        });
    });

    // modal release
    $(function() {
        $('.modalRelease').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/refill/get_refill.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#mr_id').val(data.id_refill);
                    $('#mr_keterangan').text(data.keterangan);
                }
            });
        });
    });
    // end


    function formatRibuan(angka) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');

        return ribuan;
    }


    function convertDateDBtoIndo(string) {
        bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        tanggal = string.split("-")[2];
        bulan = string.split("-")[1];
        tahun = string.split("-")[0];

        return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun;
    }


    function convertDateTimeDBtoIndo(string) {
        bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        date = string.split(" ")[0];
        time = string.split(" ")[1];

        tanggal = date.split("-")[2];
        bulan = date.split("-")[1];
        tahun = date.split("-")[0];

        return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun + " " + time;
    }
</script>