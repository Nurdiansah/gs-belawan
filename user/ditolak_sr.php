<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

$queryData =  mysqli_query($koneksi, "SELECT id_sr as id_sr, nm_barang, keterangan, kd_anggaran, nm_item, komentar, 'Service Request' as jenis, 'primary' as warna
                                        FROM sr s
                                        JOIN anggaran a
                                            ON a.id_anggaran = s.id_anggaran 
                                        WHERE s.status IN ('101', '202')
                                        AND s.id_divisi = '$idDivisi'

                                        UNION ALL

                                        SELECT id_so as id_sr, nm_barang, keterangan, kd_anggaran, nm_item, komentar, 'Service Order' as jenis, 'success' as warna
                                        FROM so s
                                        JOIN anggaran a
                                            ON a.id_anggaran = s.id_anggaran 
                                        WHERE s.status IN ('202')
                                        AND s.id_divisi = '$idDivisi';
                                ");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=edit_sr&id=$id&pg=" . enkripRambo("ditolak_sr") . "");
    } else if ($_GET['aksi'] == 'release') {
        header("location:rls_sr.php?id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:del_sr.php?id=$id");
    }
}


?>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">

                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Service Request Ditolak</h3>
                </div>

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                    <!-- <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button> -->
                </div>

                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-hover" id="material">
                        <tr style="background-color :#B0C4DE;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Kode Anggaran</th>
                            <th rowspan="">Keterangan</th>
                            <th rowspan="">Jenis Pengajuan</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <!-- <tr> -->
                        <tbody>

                            <?php
                            $no = 1;
                            if (mysqli_num_rows($queryData)) {
                                while ($row = mysqli_fetch_assoc($queryData)) :

                            ?>
                                    <td> <?= $no; ?> </td>
                                    <td> <?= $row['nm_barang']; ?> </td>
                                    <td> <?= $row['keterangan']; ?> </td>
                                    <td> <?= $row['kd_anggaran'] . " - " . $row['nm_item']; ?> </td>
                                    <td><?= $row['komentar']; ?></td>
                                    <td><span class="label label-<?= $row['warna']; ?>"><?= $row['jenis']; ?></span></td>
                                    <td>
                                        <?php if ($row['jenis'] == "Service Order") { ?>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#releaseSo-<?= $row['id_sr']; ?>" title='Ajukan Kembali'><i class="fa fa-undo"></i> Ajukan Kembali</button>
                                            <a href="index.php?p=edit_so&id=<?= enkripRambo($row['id_sr']); ?>&pg=<?= enkripRambo("ditolak_sr"); ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success"> <i class="fa fa-edit"></i> Edit</button></span></a>
                                            <!-- <a href="?p=ditolak_sr&aksi=hapus&id=<?= enkripRambo($row['id_sr']); ?>" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-trash"></i> Hapus</button></span></a> -->
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#releaseSr-<?= $row['id_sr']; ?>" title='Ajukan Kembali'><i class="fa fa-undo"></i> Ajukan Kembali</button>
                                            <a href="?p=ditolak_sr&aksi=edit&id=<?= enkripRambo($row['id_sr']); ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success"> <i class="fa fa-edit"></i> Edit</button></span></a>
                                            <a href="?p=ditolak_sr&aksi=hapus&id=<?= enkripRambo($row['id_sr']); ?>" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-trash"></i> Hapus</button></span></a>
                                        <?php } ?>
                                    </td>
                                    </tr>

                                    <!-- Modal release -->
                                    <div id="releaseSo-<?= $row['id_sr']; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- konten modal-->
                                            <div class="modal-content">
                                                <!-- heading modal -->
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Konfirmasi</h4>
                                                </div>
                                                <!-- body modal -->
                                                <div class="modal-body">
                                                    <div class="perhitungan">
                                                        <form method="post" name="form" enctype="multipart/form-data" action="ajukan_ulang_so.php" class="form-horizontal">
                                                            <div class="box-body">
                                                                <input type="hidden" name="id" value="<?= $row['id_sr']; ?>">
                                                                <h4>Apakah anda yakin ingin mengajukan kembali SR <b><?= $row['nm_barang']; ?></b></h4>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-warning" type="submit" name="kirim">Kirim</button></span></a>
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
                                    <!-- End release -->
                                    <!-- Modal release -->
                                    <div id="releaseSr-<?= $row['id_sr']; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <!-- konten modal-->
                                            <div class="modal-content">
                                                <!-- heading modal -->
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Konfirmasi</h4>
                                                </div>
                                                <!-- body modal -->
                                                <div class="modal-body">
                                                    <div class="perhitungan">
                                                        <form method="post" name="form" enctype="multipart/form-data" action="ajukan_ulang_sr.php" class="form-horizontal">
                                                            <div class="box-body">
                                                                <input type="hidden" name="id" value="<?= $row['id_sr']; ?>">
                                                                <!-- <input type="hidden" name="id_user" value="<?= $row['id_user']; ?> ">
                                                                <input type="hidden" name="id_manager" value="<?= $row['id_manager']; ?> ">
                                                                <input type="hidden" name="nm_barang" value="<?= $row['nm_barang']; ?> "> -->
                                                                <h4>Apakah anda yakin ingin mengajukan kembali SR <b><?= $row['nm_barang']; ?></b></h4>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-warning" type="submit" name="kirim">Kirim</button></span></a>
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
                                    <!-- End release -->
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
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' accept='application/pdf' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function() {
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function() {
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function() {
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor", "pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }
    $(function() {
        bs_input_file();
    });
</script>