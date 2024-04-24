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

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$bulanSekarang =  date("m");
$tahunSekarang = date("Y");

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    $query = mysqli_query($koneksi, "SELECT * 
                                    FROM po p
                                    JOIN biaya_ops bo
                                        ON p.kd_transaksi = bo.kd_transaksi
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi
                                    WHERE status_po = '10'
                                    AND MONTH(tgl_po) = '$bulan'
                                    AND YEAR(tgl_po) = '$tahun'
                                    ORDER BY p.kd_transaksi DESC
                        ");
} elseif (isset($_POST['cetak_excel'])) {
    header('Location: cetak_excel_po.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
} elseif (isset($_POST['cetak_timeline'])) {
    header('Location: cetak_timeline_po.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
} else {
    $query = mysqli_query($koneksi, "SELECT * 
                                    FROM po p
                                    JOIN biaya_ops bo
                                        ON p.kd_transaksi = bo.kd_transaksi
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi
                                    WHERE status_po = '10'
                                    AND MONTH(tgl_po) = '$bulanSekarang'
                                    AND YEAR(tgl_po) = '$tahunSekarang'
                                    ORDER BY p.kd_transaksi DESC
                        ");
}

$jumlahData = mysqli_num_rows($query);

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
                                    <?php if (isset($_POST['cari'])) {
                                        foreach (bulanLoop() as $no_bln => $bln) { ?>
                                            <option value="<?= $no_bln; ?>" <?= $no_bln == $_POST['bulan'] ? "selected" : ""; ?>><?= $bln; ?></option>;
                                        <?php }
                                    } else {
                                        foreach (bulanLoop() as $no_bln => $bln) { ?>
                                            <option value="<?= $no_bln; ?>" <?= $no_bln == $bulanSekarang ? "selected" : ""; ?>><?= $bln; ?></option>;
                                    <?php }
                                    } ?>
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
                        <button type="submit" name="cari" class="btn bg-primary"><i class="fa fa-search"></i> Cari</button>
                        <button type="submit" name="cetak_excel" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Cetak PO</button>
                        <button type="submit" name="cetak_timeline" class="btn btn-warning"><i class="fa fa-file-excel-o"></i> Timeline</button>
                    </form>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_po2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?= $jumlahData > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Po Number</th>
                                        <th>Tanggal</th>
                                        <th>Divisi</th>
                                        <th>Deskripsi</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Detail</th>
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
                                                <td> <?= $row['po_number']; ?> </td>
                                                <td> <?= formatTanggal($row['tgl_po']); ?> </td>
                                                <td> <?= $row['nm_divisi']; ?> </td>
                                                <td> <?= $row['nm_barang']; ?> </td>
                                                <td> <span class="label label-success"><?= formatRupiah($row['grand_totalpo']) ?> </span></td>
                                                <?php if ($row['status_po'] == '6') { ?>
                                                    <td><span class="label label-warning">Pengajuan sedang dibelikan / Diapprove Direktur</span></td>
                                                <?php } elseif ($row['status_po'] == '10') { ?>
                                                    <td><span class="label label-success">Pengajuan Selesai</span></td>
                                                <?php } ?>
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

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>