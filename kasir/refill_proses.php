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

$query = mysqli_query($koneksi, "SELECT * FROM refill_funds WHERE status > '0'");

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
                                                    echo "<span class='label label-warning'>Approval GM Finance</span>";
                                                } else if ($row['status'] == '2') {
                                                    echo "<span class='label label-warning'>Approval Direksi</span>";
                                                }

                                                ?>

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