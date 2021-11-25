<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

$queryData =  mysqli_query($koneksi, "SELECT * FROM sr s
                                               JOIN anggaran a
                                               ON a.id_anggaran = s.id_anggaran 
                                               WHERE s.status = '0'
                                               AND s.id_divisi = '$idDivisi'
                                               ");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=edit_sr&id=$id&pg=" . enkripRambo("buat_sr") . "");
    } else if ($_GET['aksi'] == 'release') {
        header("location:rls_sr.php?id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:del_sr.php?id=$id");
    }
}

if (isset($_POST['create'])) {
    $id_user = $_POST['id_user'];
    $user = $_POST['user'];
    $id_divisi = $_POST['id_divisi'];
    $id_manager = $_POST['id_manager'];
    $nm_barang = $_POST['nm_barang'];
    $id_anggaran = $_POST['id_anggaran'];
    $doc_ba = $_POST['doc_ba'];
    $keterangan = $_POST['keterangan'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    //baca lokasi file sementara dan nama file dari form (doc_ptw)		
    $lokasi_doc_ba = ($_FILES['doc_ba']['tmp_name']);
    $doc_ba = ($_FILES['doc_ba']['name']);
    $ekstensi = pathinfo($doc_ba, PATHINFO_EXTENSION);
    $namabaru = $id_divisi . "-" . time() . "-doc-ba." . $ekstensi;

    // Jika file yang di upload bukan pdf
    if ($ekstensi != 'pdf') {
        setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');

        header("location:index.php?p=buat_sr");
    } else {
        move_uploaded_file($lokasi_doc_ba, "../file/doc_pendukung/" . $namabaru);

        // Insert ke sr
        $query = "INSERT INTO sr ( id_user,id_divisi, id_manager,nm_barang, id_anggaran,doc_ba, keterangan, created_at, updated_at, created_by, updated_by, status) VALUES 
                                 ( '$id_user','$id_divisi', '$id_manager','$nm_barang', '$id_anggaran', '$namabaru', '$keterangan', '$tanggal', '$tanggal', '$user', '$user' , '0'); 
                                 ";
        $hasil = mysqli_query($koneksi, $query);

        if ($hasil) {
            setcookie('pesan', 'SO berhasil di buat !', time() + (3), '/');
            setcookie('warna', 'alert-success', time() + (3), '/');

            header("location:index.php?p=buat_sr");
        } else {
            die(mysqli_error($koneksi));
        }
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
                    <h3 class="text-center">Create Service Request</h3>
                </div>

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                    <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                </div>

                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-hover" id="material">
                        <tr style="background-color :#B0C4DE;">
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Barang</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Kode Anggaran</th>
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
                                    <td>
                                        <!-- <a href="?p=buat_sr&aksi=release&id=<?= enkripRambo($row['id_sr']); ?>" onclick="javascript: return confirm('Anda yakin ingin merelease pengajuan ini ?')"><span data-placement='top' data-toggle='tooltip' title='Realese'><button class="btn btn-warning"> <i class="fa fa-send"></i> Realese</button></span></a> -->
                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#releaseSr-<?= $row['id_sr']; ?>" title='Release'><i class="fa fa-send"></i> Release</button>
                                        <a href="?p=buat_sr&aksi=edit&id=<?= enkripRambo($row['id_sr']); ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success"> <i class="fa fa-edit"></i> Edit</button></span></a>
                                        <a href="?p=buat_sr&aksi=hapus&id=<?= enkripRambo($row['id_sr']); ?>" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-trash"></i> Hapus</button></span></a>
                                    </td>
                                    </tr>

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
                                                        <form method="post" name="form" enctype="multipart/form-data" action="rls_sr.php" class="form-horizontal">
                                                            <div class="box-body">
                                                                <input type="hidden" name="id" value="<?= $row['id_sr']; ?>">
                                                                <input type="hidden" name="id_user" value="<?= $row['id_user']; ?> ">
                                                                <input type="hidden" name="id_manager" value="<?= $row['id_manager']; ?> ">
                                                                <input type="hidden" name="nm_barang" value="<?= $row['nm_barang']; ?> ">
                                                                <h4>Apakah anda yakin ingin merelease SR <b><?= $row['nm_barang']; ?></b></h4>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-warning" type="submit" name="release">Release</button></span></a>
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


                <!-- Modal Tambah -->
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog lg">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Create Service Request</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id_user" value="<?= $idUser ?>">
                                        <input type="hidden" name="user" value="<?= $Nama ?>">
                                        <input type="hidden" name="id_divisi" value="<?= $idDivisi ?>">
                                        <input type="hidden" name="id_manager" value="<?= $idManager; ?>">
                                        <div class="form-group">
                                            <label for="nm_barang" class="col-sm-offset-1 col-sm-3 control-label">Nama Barang/Alat</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="nm_barang" placeholder="Nama Barang/Alat">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                            <div class="col-sm-5">
                                                <select class="form-control select2" name="id_anggaran" required>
                                                    <option value="">--Kode Anggaran--</option>
                                                    <?php
                                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$idDivisi' AND tahun = '$tahun' ORDER BY nm_item ASC");
                                                    if (mysqli_num_rows($queryAnggaran)) {
                                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                                    ?>
                                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['nm_item'] . ' ' . $rowAnggaran['kd_anggaran']; ?></option>
                                                    <?php endwhile;
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="foto" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung/BA/Foto </label>
                                            <div class="col-sm-5    ">
                                                <div class="input-group input-file" name="doc_ba">
                                                    <input type="text" class="form-control" />
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="keterangan" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                            <div class="col-sm-8">
                                                <textarea rows="7" type="textarea" required class="form-control" name="keterangan" placeholder="Keterangan Service"></textarea>
                                            </div>
                                        </div>
                                        <div class=" modal-footer">
                                            <input type="submit" name="create" class="btn btn-primary col-sm-offset-1 " value="Tambah">
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