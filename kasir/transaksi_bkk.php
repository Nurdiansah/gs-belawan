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

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$bulanSekarang =  getRomawi(date("m"));
$tahunSekarang = date('Y');
$jmlKarakter = strlen($bulanSekarang) + 1;     // ngitung jumlah karakter romawi buat ngitung substring, dan ditambah 1 supaya tambahan simbol "/"

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $jmlKarakter = strlen($_POST['bulan']) + 1;     // ngitung jumlah karakter romawi buat ngitung substring, dan ditambah 1 supaya tambahan simbol "/"

    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                    JOIN anggaran a
                                        ON b.id_anggaran = a.id_anggaran
                                    WHERE b.status_bkk = '4'
                                    AND SUBSTRING(no_bkk, 11, $jmlKarakter) = '$bulan/'     -- ngambil bulan romawi ditambah /
                                    AND RIGHT(no_bkk, 4) = '$tahun'     -- ngambil tahun paling kanan dari field no_bkk, (minggir2 kanan kaya belek)
                                    ORDER BY no_bkk DESC  ");
} elseif (isset($_POST['cetak'])) {
    header('Location: cetak_bkk_excel.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                    JOIN anggaran a
                                        ON b.id_anggaran = a.id_anggaran
                                    WHERE b.status_bkk = '4'
                                    AND SUBSTRING(no_bkk, 11, $jmlKarakter) = '$bulanSekarang/'     -- ngambil bulan romawi ditambah /
                                    AND RIGHT(no_bkk, 4) = '$tahunSekarang'     -- ngambil tahun paling kanan dari field no_bkk, (minggir2 kanan kaya belek)
                                    ORDER BY no_bkk DESC  ");
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
                                <select name="bulan" class="form-control" required>
                                    <?php if (isset($_POST['cari'])) { ?>
                                        <option value="I" <?php if ("I" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Januari</option>
                                        <option value="II" <?php if ("II" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Februari</option>
                                        <option value="III" <?php if ("III" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Maret</option>
                                        <option value="IV" <?php if ("IV" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>April</option>
                                        <option value="V" <?php if ("V" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Mei</option>
                                        <option value="VI" <?php if ("VI" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Juni</option>
                                        <option value="VII" <?php if ("VII" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Juli</option>
                                        <option value="VIII" <?php if ("VIII" == $_POST['bulan']) {
                                                                    echo "selected=selected";
                                                                } ?>>Agustus</option>
                                        <option value="IX" <?php if ("IX" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>September</option>
                                        <option value="X" <?php if ("X" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Oktober</option>
                                        <option value="XI" <?php if ("XI" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>November</option>
                                        <option value="XII" <?php if ("XII" == $_POST['bulan']) {
                                                                echo "selected=selected";
                                                            } ?>>Desember</option>
                                    <?php } else { ?>
                                        <option value="I" <?php if ("I" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Januari</option>
                                        <option value="II" <?php if ("II" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Februari</option>
                                        <option value="III" <?php if ("III" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Maret</option>
                                        <option value="IV" <?php if ("IV" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>April</option>
                                        <option value="V" <?php if ("V" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Mei</option>
                                        <option value="VI" <?php if ("VI" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Juni</option>
                                        <option value="VII" <?php if ("VII" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Juli</option>
                                        <option value="VIII" <?php if ("VIII" == $bulanSekarang) {
                                                                    echo "selected=selected";
                                                                } ?>>Agustus</option>
                                        <option value="IX" <?php if ("IX" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>September</option>
                                        <option value="X" <?php if ("X" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>Oktober</option>
                                        <option value="XI" <?php if ("XI" == $bulanSekarang) {
                                                                echo "selected=selected";
                                                            } ?>>November</option>
                                        <option value="XII" <?php if ("XII" == $bulanSekarang) {
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
                                    ?>
                                            <tr data-href="index.php?p=detail_bkk&id=<?= $row['id']; ?>" style="cursor: pointer;" title="Klik untuk detail">
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['release_on_bkk']); ?> </td>
                                                <td> <?= $row['no_bkk']; ?> </td>
                                                <td> <?= $row['keterangan']; ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> [<?= $row['nm_item']; ?>]</td>
                                                <td>
                                                    <a target="_blank" onclick="window.open('bkk_new.php?id=<?= enkripRambo($row['id']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> BKK </a>
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

    $('tr[data-href]').on("click", function() {
        document.location = $(this).data('href');
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>