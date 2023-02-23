<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=transaksi_dpo&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

if (isset($_POST['tahun']) && isset($_POST['bulan'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
} elseif (isset($_GET['tahun']) && isset($_GET['bulan'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
} else {
    $bulan = date("m");
    $tahun = date("Y");
}

$query = mysqli_query($koneksi, "SELECT * 
                                    FROM po p
                                    JOIN biaya_ops bo
                                        ON p.kd_transaksi = bo.kd_transaksi
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi  
                                    JOIN anggaran a
                                        ON dbo.id_anggaran = a.id_anggaran
                                    WHERE MONTH(tgl_po) = '$bulan'
                                    AND YEAR(tgl_po) = '$tahun'
                                                ORDER BY p.kd_transaksi DESC   ");

$tahunAyeuna = date("Y");

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi PO</h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="bulan" class="form-control" required>
                                    <?php foreach (bulanLoop() as $no_bln => $bln) { ?>
                                        <option value="<?= $no_bln; ?>" <?= $no_bln == $bulan ? "selected" : ""; ?>><?= $bln; ?></option>;
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="tahun" class="form-control" required>
                                    <?php foreach (range(2019, $tahunAyeuna) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahun ? "selected" : ''; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="cari" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                    </form>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_po2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="material">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Po Number</th>
                                        <th>Tanggal</th>
                                        <th>Divisi</th>
                                        <th>Kode Anggaran</th>
                                        <th>Deskripsi</th>
                                        <th>Total</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :

                                            if ($row['tahun'] == '2025') {
                                                # code...
                                                echo "<tr style='background-color :#ff751a;'>";
                                            } else {
                                                # code...
                                                echo "<tr>";
                                            }
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['po_number']; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_po']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['kd_anggaran'] . ' [' . $row['nm_item'] . ']'; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <span class="label label-success"><?= formatRupiah($row['total_po']) ?> </span></td>
                                            <td>
                                                <a href="?p=transaksi_po&aksi=lihat&id=<?= $row['id_po']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                                            </td>
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
<script>
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    $(function() {

        // add multiple select / deselect functionality
        $("#selectall").click(function() {
            $('.case').attr('checked', this.checked);
        });

        // if all checkbox are selected, check the selectall checkbox
        // and viceversa
        $(".case").click(function() {

            if ($(".case").length == $(".case:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }

        });
    });
</script>