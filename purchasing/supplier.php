<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT * FROM user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier <> '0' ORDER BY nm_supplier ASC");

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
                <br>
                <!-- <div class="box-body">
                <div class="row">
                    <br><br>
                </div>                         -->

                <!-- <div class="box-header with-border"> -->
                <!-- Tombol untuk menampilkan modal-->
                <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                <!-- </div> -->

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Nama Supplier</th>
                                    <th>Nama PIC</th>
                                    <th>No Telpon</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :

                                ?>
                                        <tr>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_supplier']; ?> </td>
                                            <td> <?= $row['pic_supplier']; ?> </td>
                                            <td> <?= $row['no_telponsupplier']; ?> </td>
                                            <td> <?= $row['email_supplier']; ?> </td>
                                            <td> <?= $row['alamat_supplier']; ?> </td>
                                            <td>
                                                <button type="button" title="Tambah Data" class="btn btn-warning" data-toggle="modal" data-target="#rubah_supplier_<?= $row['id_supplier']; ?>"><i class="fa fa-edit"></i></button>
                                                <a href="hapus_supplier.php?id=<?= enkripRambo($row['id_supplier']); ?>" onclick="javascript: return confirm('Yakin ingin menghapus supplier <?= $row['nm_supplier']; ?> ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-remove"></i> </button></span></a>
                                            </td>
                                        </tr>
                                        <!-- Modal Rubah -->
                                        <div id="rubah_supplier_<?= $row['id_supplier']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog lg">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Rubah Supplier <?= $row['nm_supplier']; ?></h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="edit_supplier.php" class="form-horizontal">
                                                            <input type="hidden" name="id_supplier" value="<?= $row['id_supplier']; ?>">
                                                            <div class="box-body">
                                                                <div class="form-group">
                                                                    <label for="nm_supplier" class="col-sm-offset- col-sm-3 control-label">Nama Supplier</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" required class="form-control" name="nm_supplier" placeholder="PT. Angin Ribut" value="<?= $row['nm_supplier']; ?>">
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class=" form-group ">
                                                                    <label for=" kota_supplier" class="col-sm-offset- col-sm-3 control-label">Nama PIC</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="pic_supplier" placeholder="Kokoh PC" value="<?= $row['pic_supplier']; ?>">
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group">
                                                                    <label id="no_telpon" for="no_telpon" class="col-sm-offset- col-sm-3 control-label">No Telepon</label>
                                                                    <div class="col-sm-8 ">
                                                                        <input type="number" class="form-control" name="no_telponsupplier" placeholder="088 xxx" value="<?= $row['no_telponsupplier']; ?>">
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class=" form-group">
                                                                    <label id="tes" for="no_fax" class="col-sm-offset- col-sm-3 control-label">No Fax </label>
                                                                    <div class="col-sm-8">
                                                                        <input type="number" class="form-control " name="no_faxsupplier" placeholder="021 xxx" value="<?= $row['no_faxsupplier']; ?>">
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group">
                                                                    <label id="tes" for="email_supplier" class="col-sm-offset- col-sm-3 control-label">Email</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="email" class="form-control" name="email_supplier" placeholder="nama@email.com" value="<?= $row['email_supplier']; ?>">
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group">
                                                                    <label id="tes" for="kategori_supplier" class="col-sm-offset- col-sm-3 control-label">Kategori</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="kategori_supplier" placeholder="Toko Kelontong" value="<?= $row['kategori_supplier']; ?>">
                                                                    </div>
                                                                </div>
                                                                <br><br>
                                                                <div class="form-group">
                                                                    <label id="tes" for="alamat_supplier" class="col-sm-offset- col-sm-3 control-label">Alamat</label>
                                                                    <div class="col-sm-8">
                                                                        <textarea rows="7" type="textarea" class="form-control" name="alamat_supplier" placeholder="JL. Penuh dengan kenangan, No. 22"><?= $row['alamat_supplier']; ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class=" modal-footer">
                                                                <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-1 " value="Simpan">
                                                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Akhir Modal Tambah  -->
                                <?php
                                        $no++;
                                    endwhile;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Tambah -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog lg">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tambah Supplier</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="add_supplier.php" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id_divisi" value="<?= $Divisi ?>">
                                        <div class="form-group">
                                            <label for="nm_supplier" class="col-sm-offset- col-sm-3 control-label">Nama Supplier</label>
                                            <div class="col-sm-8">
                                                <input type="text" required class="form-control" name="nm_supplier" placeholder="PT. Angin  Ribut">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="kota_supplier" class="col-sm-offset- col-sm-3 control-label">Nama PIC</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="pic_supplier" placeholder="Kokoh PC">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="no_telpon" for="no_telpon" class="col-sm-offset- col-sm-3 control-label">No Telepon</label>
                                            <div class="col-sm-8 ">
                                                <input type="number" class="form-control" name="no_telponsupplier" placeholder="088 xxx">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="no_fax" class="col-sm-offset- col-sm-3 control-label">No Fax </label>
                                            <div class="col-sm-8">
                                                <input type="number" class="form-control " name="no_faxsupplier" placeholder="021 xxx">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="email_supplier" class="col-sm-offset- col-sm-3 control-label">Email</label>
                                            <div class="col-sm-8">
                                                <input type="email" class="form-control" name="email_supplier" placeholder="nama@email.com">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="kategori_supplier" class="col-sm-offset- col-sm-3 control-label">Kategori</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="kategori_supplier" placeholder="Toko Kelontong">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="alamat_supplier" class="col-sm-offset- col-sm-3 control-label">Alamat</label>
                                            <div class="col-sm-8">
                                                <textarea rows="7" type="textarea" class="form-control" name="alamat_supplier" placeholder="JL. Penuh dengan kenangan, No. 22"></textarea>
                                            </div>
                                        </div>
                                        <div class=" modal-footer">
                                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-1 " value="Tambah">
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