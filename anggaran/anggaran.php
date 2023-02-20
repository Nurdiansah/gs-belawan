<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

// if (isset($_GET['aksi']) && isset($_GET['id'])) {
//     //die($id = $_GET['id']);
//     $id = $_GET['id'];
//     $divisi = $_GET['divisi'];
//     $tahun = $_GET['tahun'];

//     if ($_GET['aksi'] == 'lihat') {
//         header("location:?p=lihat_detailanggaran&id=$id&divisi=$divisi&tahun=$tahun");
//     } else if ($_GET['aksi'] == 'hapus') {
//         header("location:?p=hapus_anggaran&id=$id&divisi=$divisi&tahun=$tahun");
//     } else if ($_GET['aksi'] == 'rubah') {
//         header("location:?p=edit_anggaran&id=$id&divisi=$divisi&tahun=$tahun");
//     }
// }

if (isset($_POST['tahun']) && isset($_POST['divisi'])) {
    $tahun = $_POST['tahun'];
    $divisi = $_POST['divisi'];
} elseif (isset($_GET['tahun']) && isset($_GET['divisi'])) {
    $tahun = dekripRambo($_GET['tahun']);
    $divisi = dekripRambo($_GET['divisi']);
} else {
    $tahun = date("Y");
    $divisi = "";
}

$queryAnggaran = mysqli_query($koneksi, "SELECT id_anggaran, nm_item, nm_pt, nm_user, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi) AS cost_center, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_programkerja, kd_segmen, no_coa, nm_coa, kd_anggaran, nm_item, januari_nominal, januari_realisasi, februari_nominal, februari_realisasi, maret_nominal, maret_realisasi, april_nominal, april_realisasi, mei_nominal, mei_realisasi, juni_nominal, juni_realisasi, juli_nominal, juli_realisasi, agustus_nominal, agustus_realisasi, september_nominal, september_realisasi, november_nominal, november_realisasi, desember_nominal, desember_realisasi, jumlah_nominal, jumlah_realisasi
                                            FROM anggaran agg
                                            JOIN program_kerja
                                                ON programkerja_id = id_programkerja
                                            JOIN cost_center cc
                                                ON costcenter_id = id_costcenter
                                            JOIN pt pt
                                                ON pt_id = id_pt
                                            JOIN divisi dvs
                                                ON divisi_id = dvs.id_divisi
                                            JOIN parent_divisi pd
                                                ON parent_id = id_parent
                                            LEFT JOIN segmen sg
                                                ON sg.id_segmen = agg.id_segmen
                                            WHERE agg.tahun = '$tahun'
                                            AND agg.id_divisi = '$divisi'");

$totalData = mysqli_num_rows($queryAnggaran);
$tahunAyeuna = date("Y");

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Anggaran </h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="divisi" class="form-control" required>
                                    <?php
                                    $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi ORDER BY nm_divisi ASC");
                                    if (mysqli_num_rows($queryDivisi)) {
                                        while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                                    ?>
                                            <option value="<?= $rowDivisi['id_divisi']; ?>" type="checkbox" <?= $rowDivisi['id_divisi'] == $divisi ? "selected=selected" : ''; ?>><?= $rowDivisi['nm_divisi']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="tahun" class="form-control" required>
                                    <?php foreach (range(2019, $tahunAyeuna + 1) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahun ? "selected=selected" : ''; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="cari" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                    </form>
                </div>
                <div class="box-body">
                    <div class="row">
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <?php


                        if ($totalData == 0) {
                            echo "<table class='table text-center table table-striped table-hover'>";
                        } else {
                            echo "<table class='table text-center table table-striped table-hover' id='material'>";
                        }

                        ?>

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama PT</th>
                                <th>Nama User</th>
                                <!-- <th>Cost Center</th> -->
                                <th>Program Kerja</th>
                                <th>Segmen</th>
                                <th>No COA</th>
                                <th>Nama COA</th>
                                <th>Kode Transaksi</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $no = 1;
                                if ($totalData > 0) {
                                    while ($row = mysqli_fetch_assoc($queryAnggaran)) :
                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['nm_pt']; ?> </td>
                                        <td> <?= $row['nm_user']; ?> </td>
                                        <!-- <td> <?= $row['cost_center']; ?> </td> -->
                                        <td> <?= $row['nm_programkerja']; ?> </td>
                                        <td> <?= $row['kd_segmen']; ?> </td>
                                        <td> <?= $row['no_coa']; ?> </td>
                                        <td> <?= $row['nm_coa']; ?> </td>
                                        <td> <?= $row['kd_anggaran']; ?> </td>
                                        <td> <?= $row['nm_item']; ?> </td>
                                        <td>
                                            <!-- <a href="?p=anggaran&aksi=lihat&id=<?= $row['id_anggaran']; ?>&divisi=<?= $divisi; ?>&tahun=<?= $tahun; ?>"><span data-placement='top' title='Lihat'><button class="btn btn-primary"><i class="fa fa-search-plus"></i></button></span></a> -->
                                            <a href="index.php?p=edit_anggaran&id=<?= enkripRambo($row['id_anggaran']); ?>"><span data-placement='top' title='Rubah'><button class="btn btn-warning"><i class="fa fa-pencil"></i></button></span></a>
                                            <a href="hapus_anggaran.php?id=<?= enkripRambo($row['id_anggaran']); ?>&thn=<?= enkripRambo($tahun); ?>&dvs=<?= enkripRambo($divisi); ?>"><span data-placement='top' title='Hapus' onclick="javascript: return confirm('Anda yakin hapus anggaran <?= $row['nm_item']; ?>?')"><button class="btn btn-danger" onclick=”return confirm(‘Yakin Hapus?’)”><i class="fa fa-trash"></i></button></span></a>
                                        </td>
                            </tr>
                        <?php
                                        $no++;
                                    endwhile;
                                } else { ?>
                        <tr>
                            <td colspan="10">Data Kosong</td>
                        </tr>
                    <?php } ?>
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
</script>