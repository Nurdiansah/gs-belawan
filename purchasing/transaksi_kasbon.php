<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=transaksi_dkasbon&id=$id");
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
                                        FROM kasbon k
                                        JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                        JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                        JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi                                            
                                        WHERE k.status_kasbon = '10'
                                        AND MONTH(tgl_kasbon) = '$bulan'
                                        AND YEAR(tgl_kasbon) = '$tahun'
                                        ORDER BY k.id_kasbon, tgl_kasbon DESC   ");
} elseif (isset($_POST['cetak_excel'])) {
    header('Location: cetak_excel_kasbon.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
} elseif (isset($_POST['cetak_timeline'])) {
    header('Location: cetak_excel_timeline.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
} else {
    $query = mysqli_query($koneksi, "SELECT * 
                                        FROM kasbon k
                                        JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                        JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                        JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi                                            
                                        WHERE k.status_kasbon = '10'
                                        AND MONTH(tgl_kasbon) = '$bulanSekarang'
                                        AND YEAR(tgl_kasbon) = '$tahunSekarang'
                                        ORDER BY k.id_kasbon DESC   ");
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
                    <h3 class="text-center">Transaksi Kasbon</h3>
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
                        <button type="submit" name="cetak_excel" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Cetak</button>
                        <button type="submit" name="cetak_timeline" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Timeline</button>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?= $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Kode </th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
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
                                            <td> <?= $row['id_kasbon']; ?> </td>
                                            <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
                                            <td>
                                                <a href="?p=transaksi_kasbon&aksi=lihat&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                                            </td>
                                </tr>
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