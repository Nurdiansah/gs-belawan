<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


// controller create
if (isset($_POST['create'])) {

    // deklarasi
    $nominal  = penghilangTitik($_POST['nominal']);
    $jenis  = $_POST['jenis'];
    $keterangan  = $_POST['keterangan'];
    $created_by  = $_POST['created_by'];
    $date = dateNow();

    //baca lokasi file sementara dan nama file dari form (doc_ptw)		
    $lokasi_doc = ($_FILES['doc_pendukung']['tmp_name']);
    $doc = ($_FILES['doc_pendukung']['name']);
    $ekstensi = pathinfo($doc, PATHINFO_EXTENSION);

    // Jika file yang di upload bukan pdf
    if ($ekstensi != 'pdf') {
        setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=create_refill");
    } else if ($nominal <= 0) {
        setcookie('pesan', 'Nominal harus lebih dari 0!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    } else {


        mysqli_begin_transaction($koneksi);

        $generateString = md5(time());

        $namaDoc = $generateString . "-doc-refill." . $ekstensi;

        $return = mysqli_query($koneksi, "INSERT INTO refill_funds (jenis, keterangan, nominal, doc_pendukung, created_by, created_at, updated_at)VALUES 
                                                                    ('$jenis', '$keterangan', '$nominal', '$namaDoc','$created_by', '$date', '$date')");
        echo mysqli_error($koneksi);

        if ($return) {

            move_uploaded_file($lokasi_doc, "../file/doc_pendukung/" . $namaDoc);

            mysqli_commit($koneksi);

            setcookie('pesan', 'Refill Fund Berhasil di buat!', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');
        } else {

            mysqli_rollback($koneksI);
            setcookie('pesan', 'Refill Fund gagal di buat!', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');
        }
    }
    header("location:index.php?p=create_refill");
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

    header("location:index.php?p=create_refill");
}

// controller release
if (isset($_POST['release'])) {

    $id_refill = $_POST['id'];

    mysqli_begin_transaction($koneksi);

    $return = mysqli_query($koneksi, "UPDATE refill_funds SET status = '1' WHERE id_refill = '$id_refill'");

    if ($return) {

        mysqli_commit($koneksi);

        setcookie('pesan', 'Refill Fund Berhasil di release!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        mysqli_rollback($koneksi);
        setcookie('pesan', 'Refill Fund gagal di release!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }

    header("location:index.php?p=create_refill");
}



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

$query = mysqli_query($koneksi, "SELECT * FROM refill_funds WHERE status = '0'");

$jumlahData = mysqli_num_rows($query);

// print_r($jumlahData);
// die;
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
                    <div class="form-group">
                        <button type="button" class="btn btn-primary  col-sm-offset-11" data-toggle="modal" data-target="#create"><i class="fa fa-edit"></i> Create </button></span></a>
                    </div>

                    <h3 class="text-center">Refill Fund</h3>
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
                                    <th>Action</th>
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
                                                <a href="index.php?p=refill_show&id=<?= enkripRambo($row['id_refill']) ?>&back=create_refill"><?= orderNumber($row['id_refill']);  ?></a>
                                            </td>
                                            <td> <?= formatTanggal($row['created_at']); ?> </td>
                                            <td> <?= batasiKata($row['keterangan']); ?> </td>
                                            <td> <?= formatRupiah($row['nominal']); ?> </td>
                                            <td>

                                                <?php if ($row['status'] == 0) { ?>
                                                    <a href="index.php?p=refill_edit&id=<?= enkripRambo($row['id_refill']) ?>"><button type="button" class="btn btn-success"><i class="fa fa-edit"></i> </button></a>
                                                    <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#deleteRefill" data-id="<?= $row['id_refill']; ?>"><i class="fa fa-trash"></i> </button>
                                                    <button type="button" class="btn btn-warning modalRelease" data-toggle="modal" data-target="#releaseRefill" data-id="<?= $row['id_refill']; ?>"><i class="fa fa-rocket"></i> Release</button>
                                                <?php  } ?>
                                            </td>
                                </tr>
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

    <div class="alert alert-warning" role="alert">
        Fitur Refill Fund masih on progress !
    </div>
</section>

<!-- Modal Create  -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Refill Fund</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" value="<?= $Nama ?>" name="created_by">
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Total </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="doc_pendukung" class="col-sm-offset-1 col-sm-3 control-label">Jenis </label>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" value="petty_cash" id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Petty Cash
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" value="kas_besar" id="flexRadioDefault1">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Kas Besar
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" value="transfer_pendapatan" id="transfer_pendapatan">
                                        <label class="form-check-label" for="transfer_pendapatan">
                                            Transfer Pendapatan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="jenis" value="droping_fund" id="droping_fund">
                                        <label class="form-check-label" for="droping_fund">
                                            Droping Fund
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_pendukung" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                                <div class="col-sm-5">
                                    <div class="input-group input-file" name="doc_pendukung">
                                        <input type="text" class="form-control" placeholder="*Opsional" required />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="validationTextarea">Deskripsi : </label>
                                <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Deskripsi Refill Fund"></textarea>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="create">Create</button></span></a>
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
<!-- Akhir modal create -->


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