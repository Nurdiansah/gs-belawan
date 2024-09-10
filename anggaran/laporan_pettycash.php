<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_POST['cetak'])) {
    header('Location: cetak_pettycash_excel.php?project=' . enkripRambo($_POST['project']) . '&tgl_1=' . enkripRambo($_POST['tgl_1']) . '&tgl_2=' . enkripRambo($_POST['tgl_2']));
}

if (isset($_POST['cari'])) {
    $tgl_1 = $_POST['tgl_1'];
    $tgl_2 = $_POST['tgl_2'];
    $project = $_POST['project'];
} elseif (isset($_GET['project']) && isset($_GET['tgl_1']) && isset($_GET['tgl_2'])) {
    $project = dekripRambo($_GET['project']);
    $tgl_1 = dekripRambo($_GET['tgl_1']);
    $tgl_2 = dekripRambo($_GET['tgl_2']);
} else {
    $tgl_1 = date("Y-m-d");
    $tgl_2 = date('Y-m-d');
    $project = "all";
}

if ($project == "all") {
    $query = mysqli_query($koneksi, "SELECT tp.*, a.kd_anggaran, d.nm_divisi, nm_item
                                        FROM transaksi_pettycash tp   
                                        LEFT JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran 
                                        LEFT JOIN divisi d
                                            ON tp.id_divisi = d.id_divisi
                                        WHERE tp.status_pettycash = '5'
                                        AND DATE(pym_ksr) BETWEEN '$tgl_1' AND '$tgl_2'
                                        ORDER BY tp.pym_ksr DESC
                            ");
} else {
    $query = mysqli_query($koneksi, "SELECT tp.*, a.kd_anggaran, d.nm_divisi, nm_item
                                    FROM transaksi_pettycash tp   
                                    LEFT JOIN anggaran a
                                        ON tp.id_anggaran = a.id_anggaran 
                                    LEFT JOIN divisi d
                                        ON tp.id_divisi = d.id_divisi
                                    WHERE tp.status_pettycash = '5'
                                    AND DATE(pym_ksr) BETWEEN '$tgl_1' AND '$tgl_2'
                                    AND a.programkerja_id IN (SELECT id_programkerja
                                                                    FROM program_kerja
                                                                    JOIN cost_center
                                                                        ON id_costcenter = costcenter_id
                                                                    WHERE pt_id = '$project'
                                                                    )
                                    ORDER BY tp.pym_ksr DESC
                        ");
}

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
                                <select name="project" class="form-control" required>
                                    <?php
                                    $queryProject = mysqli_query($koneksi, "SELECT * FROM pt WHERE id_pt <> 0 ORDER BY nm_pt ASC");
                                    while ($dataProject = mysqli_fetch_assoc($queryProject)) {
                                        // foreach (range(2021, $tahunSekarang) as $tahunAyeuna) { 
                                    ?>
                                        <!-- <option value="<?= $tahunAyeuna; ?>" <?= $tahunAyeuna == $tahun ? "selected" : ""; ?>><?= $tahunAyeuna; ?></option> -->
                                        <option value="<?= $dataProject['id_pt']; ?>" <?= $dataProject['id_pt'] == $project ? "selected" : ""; ?>><?= $dataProject['nm_pt']; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <input type="date" class="form-control" name="tgl_1" value="<?= $tgl_1; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <input type="date" class="form-control" name="tgl_2" value="<?= $tgl_2; ?>">
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
                                            <td>
                                                <?php if (!file_exists('../file/lampiran_temp/PETTY-' . $row['id_pettycash'] . '.pdf')) { ?>
                                                    <a title="Gabungkan LPD" href="cetak_petty.php?id=<?= enkripRambo($row['id_pettycash']); ?>&project=<?= enkripRambo($project); ?>&tgl_1=<?= enkripRambo($tgl_1); ?>&tgl_2=<?= enkripRambo($tgl_2); ?>" class="btn btn-primary"><i class="fa fa-repeat"></i></a>
                                                <?php } else { ?>
                                                    <a target="_blank" title="Cetak LPD" onclick="window.open('cetak_petty.php?id=<?= enkripRambo($row['id_pettycash']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i></a>
                                                <?php } ?>
                                            </td>
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