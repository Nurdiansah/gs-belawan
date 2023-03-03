<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


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

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$bulanSekarang =  date("m");
$tahunSekarang = date('Y');

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                                JOIN anggaran a
                                                ON b.id_anggaran = a.id_anggaran                                                
                                                WHERE b.status_bkk = '4' AND b.pengajuan='BIAYA KHUSUS'
                                                AND MONTH(created_on_bkk) = '$bulan'
                                                AND YEAR(created_on_bkk) = '$tahun'
                                                ORDER BY b.tgl_bkk DESC
                                    ");
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                                JOIN anggaran a
                                                ON b.id_anggaran = a.id_anggaran                                                
                                                WHERE b.status_bkk = '4' AND b.pengajuan='BIAYA KHUSUS'
                                                AND MONTH(created_on_bkk) = '$bulanSekarang'
                                                AND YEAR(created_on_bkk) = '$tahunSekarang'
                                                ORDER BY b.tgl_bkk DESC
                                    ");
}

$jumlah = mysqli_num_rows($query);

?>

<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Biaya Khusus</h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="bulan" class="form-control" required>
                                    <?php if (isset($_POST['cari'])) { ?>
                                        <option value="01" <?php if ("01" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Januari</option>
                                        <option value="02" <?php if ("02" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Februari</option>
                                        <option value="03" <?php if ("03" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Maret</option>
                                        <option value="04" <?php if ("04" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>April</option>
                                        <option value="05" <?php if ("05" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Mei</option>
                                        <option value="06" <?php if ("06" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Juni</option>
                                        <option value="07" <?php if ("07" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Juli</option>
                                        <option value="08" <?php if ("08" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Agustus</option>
                                        <option value="09" <?php if ("09" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>September</option>
                                        <option value="10" <?php if ("10" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Oktober</option>
                                        <option value="11" <?php if ("11" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>November</option>
                                        <option value="12" <?php if ("12" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Desember</option>
                                    <?php } else { ?>
                                        <option value="01" <?php if ("01" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Januari</option>
                                        <option value="02" <?php if ("02" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Februari</option>
                                        <option value="03" <?php if ("03" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Maret</option>
                                        <option value="04" <?php if ("04" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>April</option>
                                        <option value="05" <?php if ("05" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Mei</option>
                                        <option value="06" <?php if ("06" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Juni</option>
                                        <option value="07" <?php if ("07" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Juli</option>
                                        <option value="08" <?php if ("08" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Agustus</option>
                                        <option value="09" <?php if ("09" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>September</option>
                                        <option value="10" <?php if ("10" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Oktober</option>
                                        <option value="11" <?php if ("11" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>November</option>
                                        <option value="12" <?php if ("12" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Desember</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="tahun" class="form-control" required>
                                    <?php
                                    if (isset($_POST['cari'])) {
                                        foreach (range(2019, $tahunSekarang) as $tahun) { ?>
                                            <option value="<?= $tahun; ?>" <?php if ($tahun == $_POST['tahun']) {
                                                                                echo "selected=selected";
                                                                            } ?>><?= $tahun; ?></option>
                                        <?php }
                                    } else {
                                        foreach (range(2019, $tahunSekarang) as $tahun) { ?>
                                            <option value="<?= $tahun; ?>" <?php if ($tahun == $tahunSekarang) {
                                                                                echo "selected=selected";
                                                                            } ?>><?= $tahun; ?></option>
                                    <?php }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="cari" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                    </form>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?= $jumlah > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $no = 1;
                                        if (mysqli_num_rows($query)) {
                                            while ($row = mysqli_fetch_assoc($query)) :
                                        ?>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['created_on_bkk']); ?> </td>
                                                <td> <?= $row['keterangan']; ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> </td>
                                                <td> <?= formatRupiah($row['nominal']); ?> </td>
                                    </tr>
                            <?php
                                                $no++;
                                            endwhile;
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--  -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Membuat Biaya Khusus</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="create_bkk_khusus.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2" name="id_anggaran">
                                        <option value="">--Kode Anggaran--</option>
                                        <?php
                                        $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='20' ORDER BY nm_item ASC");
                                        if (mysqli_num_rows($queryAnggaran)) {
                                            while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                        ?>
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="validationTextarea">Deskripsi : </label>
                                <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Deskripsi Biaya Khusus"></textarea>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Create</button></span></a>
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
<!--  -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../assets/plugins/alertify/lib/alertify.min.js"></script>
<script>
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
</script>