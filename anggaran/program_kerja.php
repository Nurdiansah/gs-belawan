<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

if (isset($_POST['tambah'])) {
    $cost_center = $_POST['cost_center'];
    $nm_programkerja = $_POST['nm_programkerja'];
    $nm_user = $_POST['nm_user'];

    // ngambil max nomor program kerja
    $queryNomor = mysqli_query($koneksi, "SELECT MAX(kd_programkerja) FROM program_kerja WHERE costcenter_id = '$cost_center' ");
    $nomorMax = mysqli_fetch_array($queryNomor);
    if ($nomorMax) {

        $nilaikode = substr($nomorMax[0], 0);
        $kode = (int) $nilaikode;

        //setiap kode ditambah 1
        $kode = $kode + 1;
        $kd_pk = "" . str_pad($kode, 2, "0", STR_PAD_LEFT);
    } else {
        $kd_pk = "01";
    }

    $tambahPK = mysqli_query($koneksi, "INSERT INTO program_kerja (costcenter_id, kd_programkerja, nm_programkerja, nm_user) VALUES
                                                                    ('$cost_center', '$kd_pk', '$nm_programkerja', '$nm_user')
    ");

    if ($tambahPK) {
        header('Location: index.php?p=program_kerja');
    }
}


if (isset($_POST['cari'])) {
    $divisi = $_POST['divisi'];

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
                                        ORDER BY program_kerja ASC
                            ");
} else {
    $divisi = "";

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
                                        ORDER BY program_kerja ASC
                            ");
}

$no = 1;
$totalRow = mysqli_num_rows($queryPK);
?>


<!-- Main content -->
<section class="content">
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
                                    <th>Program Kerja</th>
                                    <th>Nama Program Kerja</th>
                                    <th>Nama User</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if ($totalRow > 0) {
                                        while ($row = mysqli_fetch_assoc($queryPK)) :
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['program_kerja']; ?> </td>
                                            <td> <?= $row['nm_programkerja']; ?> </td>
                                            <td> <?= $row['nm_user']; ?> </td>
                                            <td>
                                                <a href="" disabled><span data-placement='top' title='Lihat'><button class="btn btn-primary" disabled><i class="fa fa-search-plus"></i></button></span></a>
                                                <a href=""><span data-placement='top' title='Rubah'><button class="btn btn-warning" disabled><i class="fa fa-pencil"></i></button></span></a>
                                                <a href=""><span data-placement='top' title='Hapus' onclick="javascript: return confirm('Anda yakin hapus ?')"><button class="btn btn-danger" onclick="return confirm('Yakin Hapus?')" disabled><i class="fa fa-trash"></i></button></span></a>
                                            </td>
                                </tr>
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
                        <input type="hidden" name="id_divisi" value="<?= $idDivisi ?>">

                        <div class="form-group">
                            <label id="tes" for="cost_center" class="col-sm-offset-1 col-sm-3 control-label">Cost Center</label>
                            <div class="col-sm-5">
                                <select class="form-control select2 costcenter_id" name="cost_center" required>
                                    <option value="">-- Cost Center --</option>
                                    <?php

                                    $queryCC = mysqli_query($koneksi, "SELECT id_costcenter, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi) AS cost_center
                                                                        FROM `cost_center`
                                                                        JOIN pt
                                                                            ON id_pt = pt_id
                                                                        JOIN divisi
                                                                            ON id_divisi = divisi_id
                                                                        JOIN parent_divisi
                                                                            ON id_parent = parent_id
                                                                        WHERE id_costcenter <> '0'
                                                                        ORDER BY cost_center ASC
                                                            ");
                                    while ($dataCC = mysqli_fetch_assoc($queryCC)) {
                                    ?>
                                        <option value="<?= $dataCC['id_costcenter']; ?>" type="checkbox"><?= $dataCC['cost_center']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="kotakPK">
                            <div class="form-group ">
                                <label for="program_kerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="program_kerja" id="program_kerja" autocomplete="off" readonly required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="doc" class="col-sm-offset-1 col-sm-3 control-label">Nama Program Kerja</label>
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
<!-- Akhir Modal Tambah Kasbon  -->

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