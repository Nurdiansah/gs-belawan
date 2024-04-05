<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahunAyeuna = date("Y");

// tambah program kerja
if (isset($_POST['tambah'])) {
    $cost_center = $_POST['cost_center'];
    $kd_programkerja = $_POST['program_kerja'];
    $nm_programkerja = $_POST['nm_programkerja'];
    $nm_user = $_POST['nm_user'];
    $tahun = $_POST['tahun'];

    // ngambil max nomor program kerja
    $queryNomor = mysqli_query($koneksi, "SELECT MAX(kd_programkerja) FROM program_kerja WHERE costcenter_id = '$cost_center' ");
    $nomorMax = mysqli_fetch_array($queryNomor);
    if ($nomorMax) {

        $nilaikode = substr($nomorMax[0], 0);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $kd_pk = "" . str_pad($kode, 3, "0", STR_PAD_LEFT);
    } else {
        $kd_pk = "001";
    }

    $tambahPK = mysqli_query($koneksi, "INSERT INTO program_kerja (costcenter_id, kd_programkerja, nm_programkerja, nm_user, tahun) VALUES
                                                                    ('$cost_center', '$kd_programkerja', '$nm_programkerja', '$nm_user', '$tahun')
    ");

    if ($tambahPK) {
        setcookie('pesan', 'Program Kerja Berhasil ditambah !', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        setcookie('pesan', 'Program Kerja Gagal ditambah !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }

    header('Location: index.php?p=program_kerja&tahun=' . enkripRambo($_POST['tahun']) . '&divisi=' . enkripRambo($_POST['divisi']));
}

// hapus program kerja
if (isset($_POST['hapus'])) {
    $id_programkerja = $_POST['id_programkerja'];

    $totalAgg = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_anggaran FROM anggaran WHERE programkerja_id = '$id_programkerja'"));

    if ($totalAgg > 0) {
        setcookie('pesan', 'Program Kerja Gagal dihapus, karena Program Kerja tersebut mempunyai ' . $totalAgg . ' Anggaran.<br>Silahkan hapus terlebih dahulu Anggaran dari Program Kerja ini atau pindahkan ke Program Kerja lain!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');
    } else {
        $hapus = mysqli_query($koneksi, "DELETE FROM program_kerja WHERE id_programkerja = '$id_programkerja'");

        if ($hapus) {
            setcookie('pesan', 'Program Kerja Berhasil dihapus !', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');
        } else {
            setcookie('pesan', 'Program Kerja Gagal dihapus !', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');
        }
    }
    header('Location: index.php?p=program_kerja&tahun=' . enkripRambo($_POST['tahun']) . '&divisi=' . enkripRambo($_POST['divisi']));
}

// update program kerja
if (isset($_POST['rubah'])) {
    $id_programkerja = $_POST['id_programkerja'];
    $cost_center = $_POST['cost_center'];
    $kd_programkerja = $_POST['program_kerja'];
    $nm_programkerja = $_POST['nm_programkerja'];
    $nm_user = $_POST['nm_user'];
    $tahun = $_POST['tahun'];

    $rubah = mysqli_query($koneksi, "UPDATE program_kerja SET id_programkerja = '$id_programkerja',
                                                costcenter_id = '$cost_center',
                                                kd_programkerja = '$kd_programkerja',
                                                nm_programkerja = '$nm_programkerja',
                                                nm_user = '$nm_user',
                                                tahun = '$tahun'
                                        WHERE id_programkerja = '$id_programkerja'
                            ");

    if ($rubah) {
        setcookie('pesan', 'Program Kerja Berhasil dirubah !', time() + (3), '/');
        setcookie('warna', 'alert-primary', time() + (3), '/');
    } else {
        setcookie('pesan', 'Program Kerja Gagal dirubah !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header('Location: index.php?p=program_kerja&tahun=' . enkripRambo($_POST['thn']) . '&divisi=' . enkripRambo($_POST['divisi']));
}


// kondisi query
if (isset($_POST['tahun']) && isset($_POST['divisi'])) {
    $tahun = $_POST['tahun'];
    $divisi = $_POST['divisi'];
} elseif (isset($_GET['tahun']) && isset($_GET['divisi'])) {
    $tahun = dekripRambo($_GET['tahun']);
    $divisi = dekripRambo($_GET['divisi']);
} else {
    $tahun = date("Y");
    $divisi = "1";
}

$queryPK = mysqli_query($koneksi, "SELECT *, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja
                                        FROM cost_center
                                        JOIN pt
                                            ON id_pt = pt_id
                                        JOIN divisi
                                            ON id_divisi = divisi_id
                                        JOIN parent_divisi
                                            ON id_parent = parent_id
                                        JOIN program_kerja
                                            ON id_costcenter = costcenter_id
                                        WHERE divisi_id = '$divisi'
                                        AND tahun = '$tahun'
                                        ORDER BY kd_programkerja ASC
                            ");

$no = 1;
$totalRow = mysqli_num_rows($queryPK);
?>


<!-- Main content -->
<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Program Kerja</h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="divisi" class="form-control" required>
                                    <?php
                                    $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi <> '0' ORDER BY nm_divisi ASC");
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
                                    <?php foreach (range(2021, $tahunAyeuna + 1) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahun ? "selected=selected" : ''; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="cari" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                        <button type="button" name="tambah" class="btn btn-success" data-toggle="modal" data-target="#tambahPK"><i class="fa fa-plus"></i> Tambah</button>
                    </form>
                </div>
                <div class="box-body">
                    <div class="row">
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?= $totalRow > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Divisi</th>
                                    <th>Kode Program Kerja</th>
                                    <th>Program Kerja</th>
                                    <th>Nama User</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if ($totalRow > 0) {
                                    while ($row = mysqli_fetch_assoc($queryPK)) :
                                ?>
                                        <tr>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['kd_programkerja']; ?> </td>
                                            <td> <?= $row['nm_programkerja']; ?> </td>
                                            <td> <?= $row['nm_user']; ?> </td>
                                            <td><?= $row['tahun']; ?></td>
                                            <td>
                                                <!-- <a href="" disabled><span data-placement='top' title='Lihat'><button class="btn btn-primary" disabled><i class="fa fa-search-plus"></i></button></span></a> -->
                                                <button type="button" name="tambah" class="btn btn-warning" data-toggle="modal" data-target="#rubahPK_<?= $row['id_programkerja']; ?>"><i class="fa fa-pencil"></i> </button>
                                                <button type="button" name="tambah" class="btn btn-danger" data-toggle="modal" data-target="#hapusPK_<?= $row['id_programkerja']; ?>"><i class="fa fa-trash"></i> </button>
                                            </td>
                                        </tr>

                                        <!-- Modal Hapus  -->
                                        <div id="hapusPK_<?= $row['id_programkerja']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog lg">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Hapus Program Kerja</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                            <div class="box-body">
                                                                <input type="hidden" name="id_programkerja" value="<?= $row['id_programkerja']; ?>">
                                                                <input type="hidden" name="tahun" value="<?= $tahun; ?>">
                                                                <input type="hidden" name="divisi" value="<?= $divisi; ?>">

                                                                <div class="form-group">
                                                                    <h4 class="text-center">Anda yakin ingin mengahapus Program Kerja <b><?= $row['nm_programkerja']; ?></b>?</h4>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <input type="submit" name="hapus" class="btn btn-warning col-sm-offset-1 " value="Hapus">
                                                                    &nbsp;
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Akhir Modal Hapus  -->

                                        <!-- Modal Edit  -->
                                        <div id="rubahPK_<?= $row['id_programkerja']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog lg">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Rubah Program Kerja</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                            <div class="box-body">
                                                                <input type="hidden" name="id_programkerja" value="<?= $row['id_programkerja']; ?>">
                                                                <input type="hidden" name="thn" value="<?= $tahun; ?>">
                                                                <input type="hidden" name="divisi" value="<?= $divisi; ?>">

                                                                <div class="form-group">
                                                                    <label id="tes" for="cost_center" class="col-sm-offset-1 col-sm-3 control-label">Divisi</label>
                                                                    <div class="col-sm-5">
                                                                        <select class="form-control select2 costcenter_id" name="cost_center" required>
                                                                            <?php

                                                                            $queryCC = mysqli_query($koneksi, "SELECT * FROM cost_center
                                                                                                        JOIN divisi
                                                                                                            ON id_divisi = divisi_id
                                                                                                        WHERE divisi_id <> 0
                                                                                                        ORDER BY nm_divisi ASC
                                                                                                ");
                                                                            while ($dataCC = mysqli_fetch_assoc($queryCC)) {
                                                                            ?>
                                                                                <option value="<?= $dataCC['id_costcenter']; ?>" <?= $dataCC['id_costcenter'] == $row['costcenter_id'] ? "selected" : ""; ?>><?= $dataCC['nm_divisi']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group ">
                                                                    <label for="program_kerja" class="col-sm-offset-1 col-sm-3 control-label">Kode Program Kerja</label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" class="form-control" name="program_kerja" id="program_kerja" value="<?= $row['kd_programkerja']; ?>" autocomplete="off" placeholder="HW00-****-**" required>
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group">
                                                                    <label for="doc" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                                                    <div class="col-sm-5">
                                                                        <textarea class="form-control" name="nm_programkerja" id="nm_programkerja" required><?= $row['nm_programkerja']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                                <br><br><br>
                                                                <div class="form-group ">
                                                                    <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama User</label>
                                                                    <div class="col-sm-5">
                                                                        <input type="text" class="form-control" name="nm_user" value="<?= $row['nm_user']; ?>" autocomplete="off" required>
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group">
                                                                    <label id="tes" for="tahun" class="col-sm-offset-1 col-sm-3 control-label">Tahun</label>
                                                                    <div class="col-sm-5">
                                                                        <select name="tahun" class="form-control" required>
                                                                            <?php foreach (range($row['tahun'] - 1, $row['tahun'] + 1) as $tahunLoop) { ?>
                                                                                <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $row['tahun'] ? "selected=selected" : ''; ?>><?= $tahunLoop; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                            </div>
                                                            <div class=" modal-footer">
                                                                <input type="submit" name="rubah" class="btn btn-primary col-sm-offset-1 " value="Simpan">
                                                                &nbsp;
                                                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Akhir Modal Edit  -->

                                    <?php
                                        $no++;
                                    endwhile;
                                } else { ?>
                                    <tr>
                                        <td colspan="5">Data Kosong</td>
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

<!-- Modal Tambah  -->
<div id="tambahPK" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Program Kerja</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="tahun" value="<?= $tahun; ?>">
                        <input type="hidden" name="divisi" value="<?= $divisi; ?>">

                        <div class="form-group">
                            <label id="tes" for="cost_center" class="col-sm-offset-1 col-sm-3 control-label">Divisi</label>
                            <div class="col-sm-5">
                                <select class="form-control select2 costcenter_id" name="cost_center" required>
                                    <?php

                                    $queryCC = mysqli_query($koneksi, "SELECT * FROM cost_center
                                                                        JOIN divisi
                                                                            ON id_divisi = divisi_id
                                                                        WHERE divisi_id <> 0
                                                                        ORDER BY nm_divisi ASC
                                                            ");
                                    while ($dataCC = mysqli_fetch_assoc($queryCC)) {
                                    ?>
                                        <option value="<?= $dataCC['id_costcenter']; ?>" type="checkbox"><?= $dataCC['nm_divisi']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="kotakPK"> -->
                        <div class="form-group ">
                            <label for="program_kerja" class="col-sm-offset-1 col-sm-3 control-label">Kode Program Kerja</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="program_kerja" id="program_kerja" autocomplete="off" placeholder="GS01-****-**" required>
                            </div>
                        </div>
                        <!-- </div> -->
                        <div class="form-group">
                            <label for="doc" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                            <div class="col-sm-5">
                                <textarea class="form-control" name="nm_programkerja" id="nm_programkerja" required></textarea>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama User</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="nm_user" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="tahun" class="col-sm-offset-2 col-sm-2 control-label">Tahun</label>
                            <div class="col-sm-5">
                                <select name="tahun" class="form-control" required>
                                    <?php foreach (range($tahunAyeuna - 1, $tahunAyeuna + 1) as $tahunLoop) { ?>
                                        <option value="<?= $tahunLoop; ?>" <?= $tahunLoop == $tahunAyeuna ? "selected=selected" : ''; ?>><?= $tahunLoop; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <input type="submit" name="tambah" class="btn btn-primary col-sm-offset-1 " value="Tambah">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Tambah  -->

<script>
    var host = '<?= host() ?>';

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });
</script>