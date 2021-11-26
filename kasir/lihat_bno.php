<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=lihat_detailbno&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}

$bulanSekarang =  date("m");
$tahunSekarang = date('Y');

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    $query = mysqli_query($koneksi,  "SELECT * 
                                        FROM bkk b
                                        JOIN divisi d
                                            ON d.id_divisi = b.id_divisi
                                        WHERE b.status_bkk='10'
                                        AND MONTH(tgl_pengajuan) = '$bulan'
                                        AND YEAR(tgl_pengajuan) = '$tahun'
                                        ORDER BY b.kd_transaksi DESC
                    ");
} else {
    $query = mysqli_query($koneksi,  "SELECT * 
                                        FROM bkk b
                                        JOIN divisi d
                                            ON d.id_divisi = b.id_divisi
                                        WHERE b.status_bkk='10'
                                        AND MONTH(tgl_pengajuan) = '$bulanSekarang'
                                        AND YEAR(tgl_pengajuan) = '$tahunSekarang'
                                        ORDER BY b.kd_transaksi DESC
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
                    <h3 class="text-center">Biaya Non OPS</h3>
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
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?= $jumlah > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Divisi</th>
                                    <th>Keterangan</th>
                                    <th>Nama Vendor</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :
                                            $angka_format = number_format($row['jml_bkk'], 2, ",", ".");

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['kd_transaksi']; ?> </td>
                                            <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <?= $row['nm_vendor']; ?> </td>
                                            <td> <?= "Rp." . $angka_format; ?> </td>
                                            <!-- <td>                
                                            <?php echo '0', ' %';
                                            ?>                                        
                                         </td> -->
                                            <td>
                                                <a href="?p=lihat_bno&aksi=edit&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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