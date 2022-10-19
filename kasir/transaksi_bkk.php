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

if (isset($_POST['pindah_bkk'])) {
    $id_bkk = $_POST['id_bkk'];
    $no_bkk = $_POST['no_bkk'];
    $tgl_bkk = datetimeHtml($_POST['tgl_bkk']);
    $id_anggaran = $_POST['id_anggaran'];
    $nominal = $_POST['nominal'];

    mysqli_begin_transaction($koneksi);

    // get no bkk berdasarkan tanggal yg dipilih
    $noBKK = nomorBkkNew($tgl_bkk);
    $noAwal = substr($noBKK, 0, 3);

    // pindah bkk ke bulan yg baru
    $pindahBKK = mysqli_query($koneksi, "UPDATE bkk_final SET nomor = '$noAwal',
                                                        no_bkk = '$noBKK',
                                                        created_on_bkk = '$tgl_bkk',
                                                        release_on_bkk = '$tgl_bkk'
                                                WHERE id = '$id_bkk'
                                ");

    // get bulan romawi & tahun dari no bkk
    $jml_karakter = strlen($no_bkk) - 15;       // total karakter bulan romawi
    $romawi_bulan = substr($no_bkk, 10, $jml_karakter);     // total karakter bulan romawi
    $bulan = getNumberRomawi($romawi_bulan);
    $tahun = substr($no_bkk, -4, 4);

    // get max periode BKK existing, untuk mengisi no yg kosong
    $queryMax = mysqli_query($koneksi, "SELECT id, MAX(nomor) AS maks FROM bkk_final
                                        WHERE MONTH(release_on_bkk) = '$bulan'
                                        AND YEAR(release_on_bkk) = '$tahun'
                                        AND nomor IN (SELECT MAX(nomor) 
                                                        FROM bkk_final
                                                            WHERE MONTH(release_on_bkk) = '$bulan'
                                                            AND YEAR(release_on_bkk) = '$tahun')
                            ");
    $dataMax = mysqli_fetch_assoc($queryMax);
    $id_bkk_max = $dataMax['id'];
    $noAwal_max = substr($no_bkk, 0, 3);

    // nomor paling ujung diupdate dirubah ke nomor kosong
    $updateKosong = mysqli_query($koneksi, "UPDATE bkk_final SET nomor = '$noAwal_max', no_bkk = '$no_bkk'
                                                    WHERE id = '$id_bkk_max'
                                ");

    // update realisasi
    $field_sebelum = fieldRealisasi($bulan);
    $field_sesudah =  fieldRealisasi(date("m", strtotime($tgl_bkk)));
    $bulan_pindah = date("m", strtotime($tgl_bkk));
    $tahun_pindah = date("Y", strtotime($tgl_bkk));

    $updateRealisasi = mysqli_query($koneksi, "UPDATE anggaran SET $field_sebelum = $field_sebelum - $nominal,
                                                                    $field_sesudah = $field_sesudah + $nominal
                                                    WHERE id_anggaran = '$id_anggaran'
                            ");

    $logPindahBKK = mysqli_query($koneksi, "INSERT INTO pindah_bkk_final (id_bkk_final, keterangan, dari_bulan, dari_tahun, ke_bulan, ke_tahun, nominal, tanggal_update) VALUES
                                                ('$id_bkk', 'Pindah Periode BKK $no_bkk ke $noBKK', '$bulan', '$tahun', '$bulan_pindah', '$tahun_pindah', '$nominal', NOW())
                                            ");

    if ($pindahBKK && $updateKosong && $updateRealisasi) {
        mysqli_commit($koneksi);
        header("Location: index.php?p=transaksi_bkk");
    } else {
        mysqli_rollback($koneksi);
        echo mysqli_error($koneksi);
        die;
    }
}

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $jmlKarakter = strlen($_POST['bulan']) + 1;     // ngitung jumlah karakter romawi buat ngitung substring, dan ditambah 1 supaya tambahan simbol "/"

    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                        LEFT JOIN anggaran a
                                            ON IFNULL(b.id_anggaran, '0') = a.id_anggaran
                                        WHERE b.status_bkk = '4'
                                        AND SUBSTRING(no_bkk, 11, $jmlKarakter) = '$bulan/'     -- ngambil bulan romawi ditambah /
                                        AND RIGHT(no_bkk, 4) = '$tahun'     -- ngambil tahun paling kanan dari field no_bkk, (minggir2 kanan kaya belek)
                                        ORDER BY no_bkk DESC  ");
} elseif (isset($_POST['cetak'])) {
    header('Location: cetak_bkk_excel.php?bulan=' . enkripRambo($_POST['bulan']) . '&tahun=' . enkripRambo($_POST['tahun']) . '');
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                        LEFT JOIN anggaran a
                                            ON  IFNULL(b.id_anggaran, '0') = a.id_anggaran
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
                                            <!-- <tr data-href="index.php?p=detail_bkk&id=<?= $row['id']; ?>" style="cursor: pointer;" title="Klik untuk detail"> -->
                                            <tr>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['release_on_bkk']); ?> </td>
                                                <td title="Klik untuk detail"><a href="index.php?p=detail_bkk&id=<?= $row['id']; ?>"><u><?= $row['no_bkk']; ?></u></a> </td>
                                                <td> <?= $row['keterangan']; ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> [<?= $row['nm_item']; ?>]</td>
                                                <td>
                                                    <a target="_blank" title="Cetak BKK" onclick="window.open('bkk_new.php?id=<?= enkripRambo($row['id']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> </a>
                                                    <button title="Pindahkan BKK" class="btn btn-primary modalEdit" data-toggle="modal" data-target="#editNoBKK_<?= $row['id']; ?>" data-id="<?= $row['id']; ?>"> <i class="fa fa-exchange"></i> </button>
                                                </td>
                                                <td> <?= formatRupiah($row['nominal']); ?> </td>
                                            </tr>
                                            <!-- Modal Edit -->
                                            <div id="editNoBKK_<?= $row['id']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog modal-md">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Pindahkan BKK <b><?= $row['no_bkk']; ?></b> </h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <div class="modal-body">
                                                            <div class="perhitungan">
                                                                <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                                                                    <input type="hidden" name="id_bkk" value="<?= $row['id']; ?>">
                                                                    <input type="hidden" name="no_bkk" value="<?= $row['no_bkk']; ?>">
                                                                    <input type="hidden" name="id_anggaran" value="<?= $row['id_anggaran']; ?>">
                                                                    <input type="hidden" name="nominal" value="<?= $row['nominal']; ?>">

                                                                    <div class="box-body">
                                                                        <div class="form-group">
                                                                            <label for="tgl_tempo" class="col-sm-offset- col-sm-4 control-label">Tanggal</label>
                                                                            <div class="col-sm-offset- col-sm-5">
                                                                                <input type="datetime-local" name="tgl_bkk" id="tgl_bkk" class="form-control" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <h5 class="col-sm-offset-1">No BKK (romawi bulan & tahun) akan mengikuti periode tanggal yang dipilih </5>
                                                                                <br><br>
                                                                                <!-- <input type="checkbox" name="" id="konfirmasi" onclick="checkBox()"> &nbsp; <label for="konfirmasi" class="control-label"> Ya, saya yakin.</label> -->
                                                                        </div>
                                                                        <div class=" modal-footer">
                                                                            <button class="btn btn-success" type="submit" name="pindah_bkk" id="btn_edit"><i class="fa fa-exchange"></i> Pindahkan</button></span></a>
                                                                            &nbsp;
                                                                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                                <!-- div perhitungan -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End edit -->
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