<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=verifikasi_dmr&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nm_supplier ASC");




?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Daftar Supplier</h3>
                </div>

                <!-- <div class="box-body">
                <div class="row">
                    <br><br>
                </div>                         -->

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                    <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                </div>

                <div class="table-responsive">
                    <table class="table text-center table table-striped table-hover" id=" ">
                        <thead>
                            <tr style="background-color :#B0C4DE;">
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Kota</th>
                                <th>No Telpon</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :

                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['nm_supplier']; ?> </td>
                                        <td> <?= $row['kota_supplier']; ?> </td>
                                        <td> <?= $row['no_telponsupplier']; ?> </td>
                                        <td> <?= $row['email_supplier']; ?> </td>
                                        <td>
                                            <a href="?p=list_order&aksi=lihat&id=<?= $row['id_supplier']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info"> <i class="fa fa-ellipsis-h"></i></button></span></a>
                                            <a href="?p=list_order&aksi=lihat&id=<?= $row['id_supplier']; ?>"><span data-placement='top' data-toggle='tooltip' title='Rubah'><button class="btn btn-warning"> <i class="fa fa-edit"></i></button></span></a>
                                            <a href="#" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-remove"></i> </button></span></a>
                                        </td>
                            </tr>
                    <?php
                                        $no++;
                                    endwhile;
                                } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Tambah -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog lg">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tambah Barang</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="add_supplier.php" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id_divisi" value="<?= $Divisi ?>">
                                        <div class="form-group">
                                            <label for="nm_supplier" class="col-sm-offset- col-sm-3 control-label">Nama Supplier</label>
                                            <div class="col-sm-8">
                                                <input type="text" required class="form-control" name="nm_supplier" placeholder="Nama Supplier">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="kota_supplier" class="col-sm-offset- col-sm-3 control-label">Kota</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="kota_supplier" placeholder="Kota Supplier">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="no_telpon" for="no_telpon" class="col-sm-offset- col-sm-3 control-label">No Telepon</label>
                                            <div class="col-sm-8 ">
                                                <input type="text" class="form-control" name="no_telponsupplier" placeholder="No Telepon">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="no_fax" class="col-sm-offset- col-sm-3 control-label">No Fax </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control " name="no_faxsupplier" placeholder="No Fax">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="email_supplier" class="col-sm-offset- col-sm-3 control-label">Email</label>
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control" name="email_supplier" placeholder="email@example.com">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="alamat_supplier" class="col-sm-offset- col-sm-3 control-label">Alamat</label>
                                            <div class="col-sm-8">
                                                <textarea rows="7" type="textarea" class="form-control" name="alamat_supplier" placeholder="Isikan alamat lengkap supplier"></textarea>
                                            </div>
                                        </div>
                                        <div class=" modal-footer">
                                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-1 " value="Tambah">
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