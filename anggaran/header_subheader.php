<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$username = $_SESSION['username_blw'];

// tambah Header
if (isset($_POST['tambah_header'])) {
    $nama_header = $_POST['nama_header'];

    $insertHeader = mysqli_query($koneksi, "INSERT INTO header (nm_header, dibuat_oleh, waktu_dibuat) VALUES
                                            ('$nama_header', '$username', NOW())
                                ");

    if ($insertHeader) {

        setcookie('pesan', 'Header Berhasil di buat!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Header Gagal di buat!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=header_subheader");
}

// rubah Header
if (isset($_POST['rubah_header'])) {
    $id_header = $_POST['id_header'];
    $nama_header = $_POST['nama_header'];

    $rubahHeader = mysqli_query($koneksi, "UPDATE header SET nm_header = '$nama_header',
                                                    dirubah_oleh = '$username',
                                                    waktu_dirubah = NOW()
                                            WHERE id_header = '$id_header'
                                    ");

    if ($rubahHeader) {

        setcookie('pesan', 'Header Berhasil di rubah!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Header Gagal di rubah!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=header_subheader");
}

// hapus Header
if (isset($_POST['hapus_header'])) {
    $id_header = $_POST['id_header'];

    $hapusHeader = mysqli_query($koneksi, "DELETE FROM header WHERE id_header = '$id_header'");
    $hapusSubHeader = mysqli_query($koneksi, "DELETE FROM sub_header WHERE id_header = '$id_header'");

    if ($hapusHeader) {

        setcookie('pesan', 'Header Berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');
    } else {

        setcookie('pesan', 'Header Gagal di hapus!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=header_subheader");
}


// tambah SUb header
if (isset($_POST['tambah_sub'])) {
    $id_header = $_POST['id_header'];
    $nama_sub = $_POST['nama_sub'];

    $insertSub = mysqli_query($koneksi, "INSERT INTO sub_header (id_header, nm_subheader, dibuat_oleh, waktu_dibuat) VALUES
                                            ('$id_header', '$nama_sub', '$username', NOW())");

    if ($insertSub) {

        setcookie('pesan', 'Sub Header Berhasil di buat!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Sub Header Gagal di buat!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=header_subheader");
}

// rubah Sub Header
if (isset($_POST['id_subheader'])) {
    $id_subheader = $_POST['id_subheader'];
    $nama_sub = $_POST['nama_sub'];

    $rubahSub = mysqli_query($koneksi, "UPDATE sub_header SET nm_subheader = '$nama_sub',
                                                    dirubah_oleh = '$username',
                                                    waktu_dirubah = NOW()
                                            WHERE id_subheader = '$id_subheader'
                                ");

    if ($rubahSub) {

        setcookie('pesan', 'Sub Header Berhasil di rubah!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {

        setcookie('pesan', 'Sub Header Gagal di rubah!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=header_subheader");
}

// hapus sub header
if (isset($_POST['hapus_sub'])) {
    $id_subheader = $_POST['id_subheader'];

    $hapusSub = mysqli_query($koneksi, "DELETE FROM sub_header WHERE id_subheader = '$id_subheader'");

    if ($hapusSub) {

        setcookie('pesan', 'Sub Header Berhasil di hapus!', time() + (3), '/');
        setcookie('warna', 'alert-warning', time() + (3), '/');
    } else {

        setcookie('pesan', 'Sub Header Gagal di hapus!<br>Karena : ' . mysqli_error($koneksi), time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("Location: index.php?p=header_subheader");
}

// NAMPILIN DATA HEADER
$queryHeader = mysqli_query($koneksi, "SELECT * FROM header ORDER BY nm_header ASC");

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
                    <h3 class="text-center">Header/Sub Header</h3>
                    <br>
                    <button type="button" title="Tambah Data" class="btn btn-success " data-toggle="modal" data-target="#tambahHeader"><i class="fa fa-plus"></i> Tambah Header</button>
                </div>
                <br>
                <div class="box-body">
                    <div class="panel-group" id="accordion">
                        <?php while ($dataHeader = mysqli_fetch_assoc($queryHeader)) { ?>
                            <div class="panel panel-default">
                                <div data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $no; ?>" style="cursor:pointer;" class="panel-heading">
                                    <h4 class="panel-title">
                                        <b><?= $no . ". " . $dataHeader['nm_header']; ?></b>
                                        <!-- <div class=""> -->
                                        <button type="button" title="Hapus Header <?= $dataHeader['nm_header']; ?>" class="btn btn-danger btn-xs  pull-right" data-toggle="modal" data-target="#hapusHeader_<?= $dataHeader['id_header']; ?>"><i class="fa fa-trash "></i> Hapus Header</button>&nbsp;&nbsp;
                                        <button type="button" title="Rubah Header <?= $dataHeader['nm_header']; ?>" class="btn btn-warning btn-xs  pull-right" data-toggle="modal" data-target="#rubahHeader_<?= $dataHeader['id_header']; ?>"><i class="fa fa-pencil "></i> Rubah Header</button>&nbsp;&nbsp;
                                        <button type="button" title="Tambah Sub Header <?= $dataHeader['nm_header']; ?>" class="btn btn-primary btn-xs  pull-right" data-toggle="modal" data-target="#tambahSub_<?= $dataHeader['id_header']; ?>"><i class="fa fa-plus "></i> Tambah Sub Header</button>&nbsp;&nbsp;
                                        <!-- </div> -->
                                    </h4>
                                </div>
                                <div id="collapse<?= $no; ?>" class="panel-collapse collapse">
                                    <!-- <div class="panel-body"> -->
                                    <ul class="list-group">
                                        <?php $id_header = $dataHeader['id_header'];
                                        $querySubHeader = mysqli_query($koneksi, "SELECT * FROM sub_header WHERE id_header = '$id_header'");

                                        while ($dataSubHeader = mysqli_fetch_assoc($querySubHeader)) {
                                        ?>
                                            <li class="list-group-item">
                                                <?= $dataSubHeader['nm_subheader']; ?>

                                                <a data-toggle="modal" data-target="#hapusSub_<?= $dataSubHeader['id_subheader']; ?>" href="" class="pull-right">Hapus</a>
                                                <p class="pull-right"> | </p>&nbsp;
                                                <a data-toggle="modal" data-target="#rubahSub_<?= $dataSubHeader['id_subheader']; ?>" href="" class="pull-right">Rubah</a>&nbsp;
                                            </li>

                                            <!-- Modal Rubah Sub  -->
                                            <div id="rubahSub_<?= $dataSubHeader['id_subheader']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog lg">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Rubah Sub Header</h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                                <input type="hidden" name="id_subheader" value="<?= $dataSubHeader['id_subheader']; ?>">
                                                                <div class="box-body">
                                                                    <div class="form-group ">
                                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Header</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" class="form-control" name="nama_header" autocomplete="off" required value="<?= $dataHeader['nm_header'] ?>" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Sub Header</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="text" class="form-control" name="nama_sub" autocomplete="off" required value="<?= $dataSubHeader['nm_subheader']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class=" modal-footer">
                                                                        <input type="submit" name="rubah_sub" class="btn btn-primary col-sm-offset-1 " value="Simpan">
                                                                        &nbsp;
                                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir Modal Rubah Sub -->

                                            <!-- Modal Hapus Sub  -->
                                            <div id="hapusSub_<?= $dataSubHeader['id_subheader']; ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog lg">
                                                    <!-- konten modal-->
                                                    <div class="modal-content">
                                                        <!-- heading modal -->
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Hapus Sub Header</h4>
                                                        </div>
                                                        <!-- body modal -->
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                                <input type="hidden" name="id_subheader" value="<?= $dataSubHeader['id_subheader']; ?>">
                                                                <div class="box-body">
                                                                    <h4 class="text-center">Anda yakin ingin menghapus Sub Header <b><?= $dataSubHeader['nm_subheader']; ?></b>?</h4>
                                                                    <br>
                                                                    <div class=" modal-footer">
                                                                        <input type="submit" name="hapus_sub" class="btn btn-warning col-sm-offset-1 " value="Hapus">
                                                                        &nbsp;
                                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Akhir Modal Hapus Sub -->

                                        <?php } ?>
                                    </ul>
                                    <div class="panel-footer"><b>(<?= mysqli_num_rows($querySubHeader); ?>)</b> Sub Header</div>
                                    <!-- </div> -->
                                </div>
                            </div>

                            <!-- Modal Input Sub  -->
                            <div id="tambahSub_<?= $dataHeader['id_header']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog lg">
                                    <!-- konten modal-->
                                    <div class="modal-content">
                                        <!-- heading modal -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Tambah Sub Header</h4>
                                        </div>
                                        <!-- body modal -->
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                <input type="hidden" name="id_header" value="<?= $dataHeader['id_header']; ?>">
                                                <div class="box-body">
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Header</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="nama_header" autocomplete="off" required value="<?= $dataHeader['nm_header'] ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Sub Header</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="nama_sub" autocomplete="off" required>
                                                        </div>
                                                    </div>
                                                    <div class=" modal-footer">
                                                        <input type="submit" name="tambah_sub" class="btn btn-primary col-sm-offset-1 " value="Simpan">
                                                        &nbsp;
                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Modal Input Sub -->

                            <!-- Modal Rubah  -->
                            <div id="rubahHeader_<?= $dataHeader['id_header']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog lg">
                                    <!-- konten modal-->
                                    <div class="modal-content">
                                        <!-- heading modal -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Rubah Header</h4>
                                        </div>
                                        <!-- body modal -->
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                <input type="hidden" name="id_header" value="<?= $dataHeader['id_header']; ?>">
                                                <div class="box-body">
                                                    <div class="form-group ">
                                                        <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Header</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control" name="nama_header" autocomplete="off" required value="<?= $dataHeader['nm_header'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class=" modal-footer">
                                                        <input type="submit" name="rubah_header" class="btn btn-warning col-sm-offset-1 " value="Simpan">
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
                            <div id="hapusHeader_<?= $dataHeader['id_header']; ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog lg">
                                    <!-- konten modal-->
                                    <div class="modal-content">
                                        <!-- heading modal -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Hapus Header</h4>
                                        </div>
                                        <!-- body modal -->
                                        <div class="modal-body">
                                            <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                                                <input type="hidden" name="id_header" value="<?= $dataHeader['id_header']; ?>">
                                                <div class="box-body">
                                                    <h4 class="text-center">Anda yakin ingin menghapus Header <b><?= $dataHeader['nm_header']; ?></b>?</h4>
                                                    <h5 class="text-center">Data <b>Sub Header</b> juga akan ikut terhapus!</h5>
                                                    <br>
                                                    <div class=" modal-footer">
                                                        <input type="submit" name="hapus_header" class="btn btn-warning col-sm-offset-1 " value="Hapus">
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
                    <h4 class="modal-title">Tambah Header</h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="nm_user" class="col-sm-offset-1 col-sm-3 control-label">Nama Header</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="nama_header" autocomplete="off" required>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <input type="submit" name="tambah_header" class="btn btn-success col-sm-offset-1 " value="Simpan">
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