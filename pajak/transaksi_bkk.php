<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


$queryUser =  mysqli_query($koneksi, "SELECT * FROM user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

if (isset($_POST['cetak'])) {
    header('Location: cetak_bkk_excel.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
}

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


if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
} elseif (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
} else {
    $bulan =  getRomawi(date("m"));
    $tahun = date('Y');
}

$query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                        LEFT JOIN anggaran a
                                            ON b.id_anggaran = a.id_anggaran
                                        WHERE no_bkk LIKE '%/$bulan/$tahun'
                                        ORDER BY no_bkk DESC");

$jumlahData = mysqli_num_rows($query);

$tahunSekarang = date("Y");

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
                                <select name="bulan" class="form-control" required>
                                    <option value="I" <?= "I" == $bulan ? "selected" : ""; ?>>Januari</option>
                                    <option value="II" <?= "II" == $bulan ? "selected" : ""; ?>>Februari</option>
                                    <option value="III" <?= "III" == $bulan ? "selected" : ""; ?>>Maret</option>
                                    <option value="IV" <?= "IV" == $bulan ? "selected" : ""; ?>>April</option>
                                    <option value="V" <?= "V" == $bulan ? "selected" : ""; ?>>Mei</option>
                                    <option value="VI" <?= "VI" == $bulan ? "selected" : ""; ?>>Juni</option>
                                    <option value="VII" <?= "VII" == $bulan ? "selected" : ""; ?>>Juli</option>
                                    <option value="VIII" <?= "VIII" == $bulan ? "selected" : ""; ?>>Agustus</option>
                                    <option value="IX" <?= "IX" == $bulan ? "selected" : ""; ?>>September</option>
                                    <option value="X" <?= "X" == $bulan ? "selected" : ""; ?>>Oktober</option>
                                    <option value="XI" <?= "XI" == $bulan ? "selected" : ""; ?>>November</option>
                                    <option value="XII" <?= "XII" == $bulan ? "selected" : ""; ?>>Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="tahun" class="form-control" required>
                                    <?php
                                    foreach (range(2021, $tahunSekarang) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahun ? "selected" : ""; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
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
                                                <td><a target="_blank" title="Cetak BKK" onclick="window.open('bkk_new.php?id=<?= enkripRambo($row['id']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> </a></td>
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