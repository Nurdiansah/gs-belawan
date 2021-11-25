<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    $divisi = $_GET['divisi'];
    $tahun = $_GET['tahun'];

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=lihat_detailanggaran&id=$id&divisi=$divisi&tahun=$tahun");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_anggaran&id=$id&divisi=$divisi&tahun=$tahun");
    } else if ($_GET['aksi'] == 'rubah') {
        header("location:?p=edit_anggaran&id=$id&divisi=$divisi&tahun=$tahun");
    }
}


$totalData = 0;

if (isset($_POST['tahun']) || isset($_GET['tahun'])) {

    if (isset($_POST['tahun'])) {
        $divisi = $_POST['divisi'];
        $tahun = $_POST['tahun'];
    } elseif (isset($_GET['tahun'])) {
        $divisi = $_GET['divisi'];
        $tahun = $_GET['tahun'];
    }

    $queryAnggaran =  mysqli_query($koneksi, "SELECT * FROM anggaran a        
        JOIN divisi d
        ON a.id_divisi = d.id_divisi
        LEFT JOIN golongan g
        ON a.id_golongan = g.id_golongan
        LEFT JOIN sub_golongan sb
        ON a.id_subgolongan = sb.id_subgolongan 
        WHERE a.id_divisi = '$divisi' 
        AND a.tahun = '$tahun'");

    $totalData = mysqli_num_rows($queryAnggaran);
}

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->

    <h3> <b>Anggaran Divisi</b></h3>
    <form action="" method="POST">
        <div class="col-sm-2">
            <div class="form-group">
                <label for="exampleInputEmail1"></label><br>
                <select name="divisi" class="form-control" required>
                    <option value="">--Pilih Divisi--</option>
                    <?php
                    $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi ORDER BY nm_divisi ASC");
                    if (mysqli_num_rows($queryDivisi)) {
                        while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                    ?>
                            <option value="<?= $rowDivisi['id_divisi']; ?>" type="checkbox"><?= $rowDivisi['nm_divisi']; ?></option>
                    <?php endwhile;
                    } ?>
                </select>

            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
                <label for="tahun"></label><br>
                <select name="tahun" class="form-control" required>
                    <option value="">--Pilih Tahun--</option>
                    <?php
                    $tahunSekarang = date('Y');
                    foreach (range(2019, $tahunSekarang) as $tahun) {
                        echo "<option value=" . $tahun . ">" . $tahun . "</option > ";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="exampleInputEmail1">Mulai Pencarian</label><br>
                <input type="submit" value="Pencarian" class="btn btn-primary">
            </div>
        </div>
    </form>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Anggaran </h3>
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
                                <th>NO COA</th>
                                <th>Kode Anggaran</th>
                                <th>Golongan</th>
                                <th>Sub Golongan</th>
                                <th>Description</th>
                                <th>Harga</th>
                                <th>Quantity</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $no = 1;
                                if ($totalData > 0) {
                                    while ($row = mysqli_fetch_assoc($queryAnggaran)) :

                                        $jml_qty = $row['januari_kuantitas'] + $row['februari_kuantitas'] + $row['maret_kuantitas'] + $row['april_kuantitas'] + $row['mei_kuantitas'] + $row['juni_kuantitas']
                                            + $row['juli_kuantitas'] + $row['agustus_kuantitas'] + $row['september_kuantitas'] + $row['oktober_kuantitas'] + $row['november_kuantitas'] + $row['desember_kuantitas'];
                                        $jml_nominal = $jml_qty * $row['harga'];

                                        $hargaFormat = number_format($row['harga'], 0, ",", ".");
                                        $jmlFormat = number_format($jml_nominal, 0, ",", ".");
                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['no_coa']; ?> </td>
                                        <td> <?= $row['kd_anggaran']; ?> </td>
                                        <td> <?= $row['nm_golongan']; ?> </td>
                                        <td> <?= $row['nm_subgolongan']; ?> </td>
                                        <td> <?= $row['nm_item']; ?> </td>
                                        <td> <?= "Rp." . $hargaFormat; ?> </td>
                                        <td> <?= $row['jumlah_kuantitas']; ?> </td>
                                        <td> <?= "Rp." . number_format($row['jumlah_nominal'], 0, ",", ".");  ?> </td>
                                        <td>
                                            <a href="?p=anggaran&aksi=lihat&id=<?= $row['id_anggaran']; ?>&divisi=<?= $divisi; ?>&tahun=<?= $tahun; ?>"><span data-placement='top' title='Lihat'><button class="btn btn-primary"><i class="fa fa-search-plus"></i></button></span></a>
                                            <a href="?p=anggaran&aksi=rubah&id=<?= $row['id_anggaran']; ?>&divisi=<?= $divisi; ?>&tahun=<?= $tahun; ?>"><span data-placement='top' title='Rubah'><button class="btn btn-warning"><i class="fa fa-pencil"></i></button></span></a>
                                            <a href="?p=anggaran&aksi=hapus&id=<?= $row['id_anggaran']; ?>&divisi=<?= $divisi; ?>&tahun=<?= $tahun; ?>"><span data-placement='top' title='Hapus' onclick="javascript: return confirm('Anda yakin hapus ?')"><button class="btn btn-danger" onclick=”return confirm(‘Yakin Hapus?’)”><i class="fa fa-trash"></i></button></span></a>
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