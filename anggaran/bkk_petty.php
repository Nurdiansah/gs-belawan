<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


$queryUser =  mysqli_query($koneksi, "SELECT * FROM en_fin.user WHERE username  = '$_SESSION[username_en]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

// if (isset($_POST['cetak'])) {
//     header('Location: cetak_bkk_excel.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
// }

// $bulanSekarang =  getRomawi(date("m"));
// $tahunSekarang = date('Y');
// $jmlKarakter = strlen($bulanSekarang) + 1;     // ngitung jumlah karakter romawi buat ngitung substring, dan ditambah 1 supaya tambahan simbol "/"

// if (isset($_POST['cari'])) {
//     $bulan = $_POST['bulan'];
//     $tahun = $_POST['tahun'];
//     $jmlKarakter = strlen($_POST['bulan']) + 1;     // ngitung jumlah karakter romawi buat ngitung substring, dan ditambah 1 supaya tambahan simbol "/"
// } elseif (isset($_GET['bulan']) && isset($_GET['tahun'])) {
//     $bulan = dekripRambo($_GET['bulan']);
//     $tahun = dekripRambo($_GET['tahun']);
//     $jmlKarakter = strlen($bulan) + 1;
// } else {
//     $bulan =  getRomawi(date("m"));
//     $tahun = date('Y');
//     $jmlKarakter = strlen($bulan) + 1;
// }

if (isset($_POST['cetak'])) {
    header('Location: cetak_bkk_excel.php?project=' . enkripRambo($_POST['project']) . '&tgl_1=' . enkripRambo($_POST['tgl_1']) . '&tgl_2=' . enkripRambo($_POST['tgl_2']));
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
    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                        LEFT JOIN anggaran a
                                            ON b.id_anggaran = a.id_anggaran
                                        WHERE DATE(release_on_bkk) BETWEEN '$tgl_1'  AND '$tgl_2'
                                        -- WHERE SUBSTRING(no_bkk, 12, $jmlKarakter) = '$bulan/'     -- ngambil bulan romawi ditambah /
                                        -- AND RIGHT(no_bkk, 4) = '$tahun'     -- ngambil tahun paling kanan dari field no_bkk, (minggir2 kanan kaya belek)
                                        AND pengajuan <> 'REFILL FUND'
                                        ORDER BY no_bkk DESC");
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                        LEFT JOIN anggaran a
                                            ON b.id_anggaran = a.id_anggaran
                                        WHERE DATE(release_on_bkk) BETWEEN '$tgl_1'  AND '$tgl_2'
                                        AND pengajuan <> 'REFILL FUND'
                                        AND a.programkerja_id IN (SELECT id_programkerja
                                                                    FROM program_kerja
                                                                    JOIN cost_center
                                                                        ON id_costcenter = costcenter_id
                                                                    WHERE pt_id = '$project'
                                                                    )
                                        ORDER BY no_bkk DESC");
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
                    <h3 class="text-center">Transaksi BKK</h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="project" class="form-control" required>
                                    <!-- <option value="all" <?= $project == "all" ? "selected" : ""; ?>>Semua Project</option> -->
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
                    <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?= $jumlahData > 0 ? 'material' : '' ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Pengajuan</th>
                                        <th>No BKK</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Preview</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :
                                            $id_bkk = $row['id'];
                                            $dataTagihan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tagihan_po WHERE bkk_id = '$id_bkk'"));
                                    ?>
                                            <!-- <tr data-href="index.php?p=detail_bkk&id=<?= $row['id']; ?>" style="cursor: pointer;" title="Klik untuk detail"> -->
                                            <tr <?= $dataTagihan['invoice_asli'] == "0" ? "style='background-color: orange;'" : ""; ?>>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['release_on_bkk']); ?> </td>
                                                <td> <?= $row['pengajuan']; ?> </td>
                                                <td title="Klik untuk detail"><a href="index.php?p=detail_bkk&id=<?= $row['id']; ?>"><u><?= $row['no_bkk']; ?></u></a> </td>
                                                <td> <?= $row['keterangan']; ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> [<?= $row['nm_item']; ?>]</td>
                                                <td>
                                                    <?php if (!file_exists('../file/bkk_temp/BKK-' . $row['id'] . '.pdf')) { ?>
                                                        <a title="Gabungkan BKK" href="bkk_new.php?id=<?= enkripRambo($row['id']); ?>&project=<?= enkripRambo($project); ?>&tgl_1=<?= enkripRambo($tgl_1); ?>&tgl_2=<?= enkripRambo($tgl_2); ?>" class="btn btn-primary"><i class="fa fa-repeat"></i></a>
                                                        <?php } else {

                                                        if (!is_null($row['v_mgr_finance']) && !is_null($row['v_direktur'])) { ?>
                                                            <a target="_blank" title="Cetak BKK" onclick="window.open('bkk_new.php?id=<?= enkripRambo($row['id']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> </a>
                                                    <?php } else {
                                                            echo "<span class='badge badge'>Approval BKK</span>";
                                                        }
                                                    } ?>
                                                </td>
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

<!-- <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../assets/plugins/alertify/lib/alertify.min.js"></script> -->
<script>
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    $('tr[data-href]').on("click", function() {
        document.location = $(this).data('href');
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>