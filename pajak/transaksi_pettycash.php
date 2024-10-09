<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_POST['cetak'])) {
    header('Location: cetak_pettycash_excel.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']));
}

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
} else {
    $bulan = date("m");
    $tahun = date("Y");
}

$query = mysqli_query($koneksi, "SELECT tp.*, a.kd_anggaran, d.nm_divisi, nm_item
                                        FROM transaksi_pettycash tp   
                                        LEFT JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran 
                                        LEFT JOIN divisi d
                                            ON tp.id_divisi = d.id_divisi
                                        WHERE tp.status_pettycash = '5'
                                        AND MONTH(pym_ksr) = '$bulan'
                                        AND YEAR(pym_ksr) = '$tahun'
                                        ORDER BY tp.pym_ksr DESC
                            ");

$jumlahData = mysqli_num_rows($query);

$tahunAyeuna = date("Y");

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Petty Cash</h3>
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
                        <button type="submit" name="cetak" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Cetak</button>
                    </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Tanggal Buat</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Kode Pettycash</th>
                                    <th>Divisi</th>
                                    <th>Keterangan</th>
                                    <th>Kode Anggaran</th>
                                    <th>Preview</th>
                                    <th>Total</th>
                                    <!-- <th>Aksi</th> -->
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
                                            <td> <?= formatTanggal($row['created_pettycash_on']); ?> </td>
                                            <td> <?= formatTanggal($row['pym_ksr']); ?> </td>
                                            <td title="Klik untuk detail">
                                                <a href="index.php?p=detail_pettycash&id=<?= $row['id_pettycash']; ?>"><u><?= $row['kd_pettycash']; ?></u></a>
                                            </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['keterangan_pettycash']; ?> </td>
                                            <td> <?= $row['kd_anggaran'] . " [" . $row['nm_item']; ?>]</td>
                                            <td><a target="_blank" title="Cetak LPD" onclick="window.open('cetak_petty.php?id=<?= enkripRambo($row['id_pettycash']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i></a></td>
                                            <td> <?= formatRupiah($row['total_pettycash']); ?> </td>
                                        </tr>

                                <?php
                                        $no++;
                                    endwhile;
                                }
                                ?>
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