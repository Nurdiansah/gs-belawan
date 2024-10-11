<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if ($idDivisi != "1") {
    header("Location: ../.");
}

// Tambah Bank
if (isset($_POST['tambah_bank'])) {
    $nm_bank = $_POST['nm_bank'];

    $insertBank = mysqli_query($koneksi, "INSERT INTO bank (nm_bank) VALUES
                                            ('$nm_bank')
                                ");

    if ($insertBank) {

        setcookie('pesan', 'Bank Berhasil di buat!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Bank Gagal di buat!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=rekening_bank");
}

// Rubah Bank
if (isset($_POST['rubah_bank'])) {
    $id_bank = $_POST['id_bank'];
    $nm_bank = $_POST['nm_bank'];

    $rubahBank = mysqli_query($koneksi, "UPDATE bank SET nm_bank = '$nm_bank'
                                            WHERE id_bank = '$id_bank'
                                    ");

    if ($rubahBank) {

        setcookie('pesan', 'Bank Berhasil di rubah!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Bank Gagal di rubah!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=rekening_bank");
}

// Hapus Bank
if (isset($_POST['hapus_bank'])) {
    $id_bank = $_POST['id_bank'];

    $hapusBank = mysqli_query($koneksi, "DELETE FROM bank WHERE id_bank = '$id_bank'");
    $hapusRekening = mysqli_query($koneksi, "DELETE FROM rekening WHERE id_bank = '$id_bank'");

    if ($hapusBank) {

        setcookie('pesan', 'Bank Berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');
    } else {

        setcookie('pesan', 'Bank Gagal di hapus!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=rekening_bank");
}


// Tambah Rekening
if (isset($_POST['tambah_rekening'])) {
    $id_bank = $_POST['id_bank'];
    $no_akun = $_POST['no_akun'];
    $no_rekening = enkripRambo($_POST['no_rekening']);
    $nm_rekening = $_POST['nm_rekening'];
    $aktif_rekening = $_POST['aktif_rekening'] == "1" ? "1" : "0";

    $insertRekening = mysqli_query($koneksi, "INSERT INTO rekening (id_bank, no_akun, no_rekening, nm_rekening, aktif_rekening) VALUES
                                            ('$id_bank', '$no_akun', '$no_rekening', '$nm_rekening', '$aktif_rekening')");

    if ($insertRekening) {

        setcookie('pesan', 'Rekening Berhasil di buat!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Rekening Gagal di buat!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=rekening_bank");
}

// rubah Rekening
if (isset($_POST['rubah_rekening'])) {
    $id_rekening = $_POST['id_rekening'];
    $id_bank = $_POST['id_bank'];
    $no_akun = $_POST['no_akun'];
    $no_rekening = enkripRambo($_POST['no_rekening']);
    $nm_rekening = $_POST['nm_rekening'];
    $aktif_rekening = $_POST['aktif_rekening'] == "1" ? "1" : "0";

    $rubahRekening = mysqli_query($koneksi, "UPDATE rekening SET id_bank = '$id_bank',
                                                    no_akun = '$no_akun',
                                                    no_rekening = '$no_rekening',
                                                    nm_rekening = '$nm_rekening',
                                                    aktif_rekening = '$aktif_rekening'
                                                WHERE id_rekening = '$id_rekening'
                                ");

    if ($rubahRekening) {

        setcookie('pesan', 'Rekening Berhasil di rubah!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Rekening Gagal di rubah!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=rekening_bank");
}

// hapus Rekening
if (isset($_POST['hapus_rekening'])) {
    $id_rekening = $_POST['id_rekening'];

    $hapusRekening = mysqli_query($koneksi, "DELETE FROM rekening WHERE id_rekening = '$id_rekening'");

    if ($hapusRekening) {

        setcookie('pesan', 'Rekening Berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');
    } else {

        setcookie('pesan', 'Rekening Gagal di hapus!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=rekening_bank");
}

// NAMPILIN DATA HEADER
$queryBank = mysqli_query($koneksi, "SELECT * FROM bank ORDER BY nm_bank ASC");

$no = 1;

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
                    <h3 class="text-center">Rekening Bank</h3>
                    <br>
                    <button type="button" title="Tambah Data" class="btn btn-success " data-toggle="modal" data-target="#tambahHeader"><i class="fa fa-plus"></i> Tambah Bank</button>
                </div>
                <br>
                <div class="box-body">
                    <div class="panel-group" id="accordion">
                        <?php while ($dataBank = mysqli_fetch_assoc($queryBank)) { ?>
                            <div class="panel panel-default">
                                <div data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $no; ?>" style="cursor:pointer;" class="panel-heading">
                                    <h4 class="panel-title">
                                        <b><?= $no . ". " . $dataBank['nm_bank']; ?></b>
                                        <!-- <div class=""> -->
                                        <button type="button" title="Hapus Bank <?= $dataBank['nm_bank']; ?>" class="btn btn-danger btn-xs  pull-right" data-toggle="modal" data-target="#hapusBank_<?= $dataBank['id_bank']; ?>"><i class="fa fa-trash "></i> Hapus Bank</button>&nbsp;&nbsp;
                                        <button type="button" title="Rubah Bank <?= $dataBank['nm_bank']; ?>" class="btn btn-warning btn-xs  pull-right" data-toggle="modal" data-target="#rubahBank_<?= $dataBank['id_bank']; ?>"><i class="fa fa-pencil "></i> Rubah Bank</button>&nbsp;&nbsp;
                                        <button type="button" title="Tambah Rekening <?= $dataBank['nm_bank']; ?>" class="btn btn-primary btn-xs  pull-right" data-toggle="modal" data-target="#tambahRekening<?= $dataBank['id_bank']; ?>"><i class="fa fa-plus "></i> Tambah Rekening</button>&nbsp;&nbsp;
                                        <!-- </div> -->
                                    </h4>
                                </div>
                                <div id="collapse<?= $no; ?>" class="panel-collapse collapse">
                                    <!-- <div class="panel-body"> -->
                                    <ul class="list-group">
                                        <?php $id_bank = $dataBank['id_bank'];
                                        $queryRekening = mysqli_query($koneksi, "SELECT * FROM rekening WHERE id_bank = '$id_bank'");

                                        while ($dataRekening = mysqli_fetch_assoc($queryRekening)) {
                                        ?>
                                            <li class="list-group-item">
                                                Nama Rekening : <?= $dataRekening['nm_rekening']; ?><br>
                                                No Rekening : <?= dekripRambo($dataRekening['no_rekening']); ?><br>
                                                No Akun : <?= $dataRekening['no_akun']; ?><br>
                                                Aktif : <i class="fa fa-<?= $dataRekening['aktif_rekening'] == "1" ? "check text-success" : "close text-danger"; ?>"></i>

                                                <a data-toggle="modal" data-target="#hapusRekening_<?= $dataRekening['id_rekening']; ?>" href="" class="pull-right">Hapus</a>
                                                <p class="pull-right"> | </p>&nbsp;
                                                <a data-toggle="modal" data-target="#rubahRekening_<?= $dataRekening['id_rekening']; ?>" href="" class="pull-right">Rubah</a>&nbsp;
                                            </li>

                                            <!-- Modal Rubah rekening  -->
                                            <div id="rubahRekening_<?= $dataRekening['id_rekening']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog lg">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Rubah Rekening</h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                                <input type="hidden" name="id_rekening" value="<?= $dataRekening['id_rekening']; ?>">
                                                                <div class="box-body">
                                                                    <div class="form-group ">
                                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Bank</label>
                                                                        <div class="col-sm-5">
                                                                            <select name="id_bank" class="form-control">
                                                                                <?php
                                                                                $Bank = mysqli_query($koneksi, "SELECT * FROM bank ORDER BY nm_bank ASC");
                                                                                while ($rowBank = mysqli_fetch_assoc($Bank)) { ?>
                                                                                    <option value="<?= $rowBank['id_bank']; ?>" <?= $dataBank['id_bank'] == $rowBank['id_bank'] ? "selected" : ""; ?>><?= $rowBank['nm_bank'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <!-- <input type="text" class="form-control" name="nm_bank" autocomplete="off" required value="<?= $dataBank['nm_bank'] ?>" disabled> -->
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">No Akun</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" class="form-control" name="no_akun" autocomplete="off" value="<?= $dataRekening['no_akun']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">No Rekening</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" class="form-control" name="no_rekening" autocomplete="off" required value="<?= dekripRambo($dataRekening['no_rekening']); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Rekening</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" class="form-control" name="nm_rekening" autocomplete="off" required value="<?= $dataRekening['nm_rekening']; ?>">
                                                                        </div>

                                                                        <label for="" class="col-sm-offset-1 col-sm-3 control-label"></label>
                                                                        <div class="col-sm-5">
                                                                            <input type="checkbox" name="aktif_rekening" id="aktif_rekening" <?= $dataRekening['aktif_rekening'] == "1" ? "checked" : ""; ?> value="1">
                                                                            <label for="aktif_rekening" class=" control-label">Rekening Aktif</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class=" modal-footer">
                                                                        <input type="submit" name="rubah_rekening" class="btn btn-primary col-sm-offset-1 " value="Simpan">
                                                                        &nbsp;
                                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir Modal Rubah rekening -->

                                            <!-- Modal Hapus rekening  -->
                                            <div id="hapusRekening_<?= $dataRekening['id_rekening']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog lg">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Hapus Rekening</h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                                <input type="hidden" name="id_rekening" value="<?= $dataRekening['id_rekening']; ?>">
                                                                <div class="box-body">
                                                                    <h4 class="text-center">Anda yakin ingin menghapus Rekening <b><?= dekripRambo($dataRekening['no_rekening']); ?></b>?</h4>
                                                                    <br>
                                                                    <div class=" modal-footer">
                                                                        <input type="submit" name="hapus_rekening" class="btn btn-warning col-sm-offset-1 " value="Hapus">
                                                                        &nbsp;
                                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir Modal Hapus rekening -->

                                        <?php } ?>
                                    </ul>
                                    <div class="panel-footer"><b>(<?= mysqli_num_rows($queryRekening); ?>)</b> Rekening</div>
                                    <!-- </div> -->
                                </div>
                            </div>

                            <!-- Modal InputRekening  -->
                            <div id="tambahRekening<?= $dataBank['id_bank']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog lg">
                                    <!-- konten modal-->
                                    <div class="modal-content">
                                        <!-- heading modal -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Tambah Rekening</h4>
                                        </div>
                                        <!-- body modal -->
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                <input type="hidden" name="id_bank" value="<?= $dataBank['id_bank']; ?>">
                                                <div class="box-body">
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Bank</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="nm_bank" autocomplete="off" required value="<?= $dataBank['nm_bank'] ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">No Akun</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="no_akun" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">No Rekening</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="no_rekening" autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Rekening</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="nm_rekening" autocomplete="off" required>
                                                        </div>

                                                        <label for="" class="col-sm-offset-1 col-sm-3 control-label"></label>
                                                        <div class="col-sm-5">
                                                            <input type="checkbox" name="aktif_rekening" id="aktif_rekening" checked value="1">
                                                            <label for="aktif_rekening" class=" control-label">Rekening Aktif</label>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                    </div> -->
                                                    <div class=" modal-footer">
                                                        <input type="submit" name="tambah_rekening" class="btn btn-primary col-sm-offset-1 " value="Simpan">
                                                        &nbsp;
                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Modal Input rekening -->

                            <!-- Modal Rubah  -->
                            <div id="rubahBank_<?= $dataBank['id_bank']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog lg">
                                    <!-- konten modal-->
                                    <div class="modal-content">
                                        <!-- heading modal -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Rubah Bank</h4>
                                        </div>
                                        <!-- body modal -->
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                <input type="hidden" name="id_bank" value="<?= $dataBank['id_bank']; ?>">
                                                <div class="box-body">
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Bank</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="nm_bank" autocomplete="off" required value="<?= $dataBank['nm_bank'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class=" modal-footer">
                                                        <input type="submit" name="rubah_bank" class="btn btn-warning col-sm-offset-1 " value="Simpan">
                                                        &nbsp;
                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Modal Rubah -->

                            <!-- Modal Hapus  -->
                            <div id="hapusBank_<?= $dataBank['id_bank']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog lg">
                                    <!-- konten modal-->
                                    <div class="modal-content">
                                        <!-- heading modal -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Hapus Bank</h4>
                                        </div>
                                        <!-- body modal -->
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                <input type="hidden" name="id_bank" value="<?= $dataBank['id_bank']; ?>">
                                                <div class="box-body">
                                                    <h4 class="text-center">Anda yakin ingin mengHapus Bank <b><?= $dataBank['nm_bank']; ?></b>?</h4>
                                                    <h5 class="text-center">Data <b>Rekening</b> juga akan ikut terhapus!</h5>
                                                    <br>
                                                    <div class=" modal-footer">
                                                        <input type="submit" name="hapus_bank" class="btn btn-warning col-sm-offset-1 " value="Hapus">
                                                        &nbsp;
                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Modal Hapus -->

                        <?php $no++;
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Tambah  -->
    <div id="tambahHeader" class="modal fade" role="dialog">
        <div class="modal-dialog lg">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Bank</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Bank</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="nm_bank" autocomplete="off" required>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <input type="submit" name="tambah_bank" class="btn btn-success col-sm-offset-1 " value="Simpan">
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir Modal Tambah -->

</section>