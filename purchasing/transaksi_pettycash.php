<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=transaksi_dpettycash&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];
$idDivisi = $rowUser['id_divisi'];

$bulanSekarang =  date("m");
$tahunSekarang = date("Y");

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    $query = mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp   
                                        JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran 
                                        JOIN divisi d
                                            ON tp.id_divisi = d.id_divisi
                                        WHERE tp.status_pettycash = '5'
                                        AND `from` IN ('mr', 'sr')
                                        AND MONTH(created_pettycash_on) = '$bulan'
                                        AND YEAR(created_pettycash_on) = '$tahun'
                                        ORDER BY tp.created_pettycash_on DESC
                            ");
} elseif (isset($_POST['cetak_excel'])) {
    header('Location: cetak_excel_petty.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp   
                                        JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran 
                                        JOIN divisi d
                                            ON tp.id_divisi = d.id_divisi
                                        WHERE tp.status_pettycash = '5'
                                        AND `from` IN ('mr', 'sr')
                                        AND MONTH(created_pettycash_on) = '$bulanSekarang'
                                        AND YEAR(created_pettycash_on) = '$tahunSekarang'
                                        ORDER BY tp.created_pettycash_on DESC
                            ");
}

$jumlahData  = mysqli_num_rows($query);

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Petty Cash</h3>
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
                        <button type="submit" name="cetak_excel" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Cetak Pettycash</button>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?= $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Kode Pettycash</th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th>Keterangan</th>
                                    <th>Kode Anggaran</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
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
                                            <td> <?= $row['kd_pettycash']; ?> </td>
                                            <td> <?= formatTanggal($row['created_pettycash_on']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['keterangan_pettycash']; ?> </td>
                                            <td> <?= $row['kd_anggaran'] . " [" . $row['nm_item']; ?>]</td>
                                            <td> <?= formatRupiah($row['total_pettycash']); ?> </td>
                                            <td>
                                                <a href="?p=transaksi_pettycash&aksi=lihat&id=<?= $row['id_pettycash']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info"><i class="fa fa-search-plus"></i></button></span></a>
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

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>