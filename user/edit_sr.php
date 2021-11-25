<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);

$queryData =  mysqli_query($koneksi, "SELECT *  FROM sr s
                                                JOIN anggaran a
                                                ON a.id_anggaran = s.id_anggaran
                                                WHERE s.id_sr = $id ");
$data = mysqli_fetch_assoc($queryData);

$queryDSR =  mysqli_query($koneksi, "SELECT *  FROM detail_sr
                                                WHERE sr_id = $id ");

$jumlahData  = mysqli_num_rows($queryDSR);

if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $nm_barang = $_POST['nm_barang'];
    $id_anggaran = $_POST['id_anggaran'];
    $id_divisi = $_POST['id_divisi'];
    $keterangan = $_POST['keterangan'];

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    //baca lokasi file sementara dan nama file dari form (doc_ptw)		
    $lokasi_doc_ba = ($_FILES['doc_ba']['tmp_name']);
    $doc_ba = ($_FILES['doc_ba']['name']);
    $ekstensi = pathinfo($doc_ba, PATHINFO_EXTENSION);



    // Hapus file yang lama terlebih dahulu         
    // Cek dulu jika ada perubahan document
    if ($doc_ba == '') {
        $namabaru = $_POST['doc_ba_lama'];
    } else {

        // Jika file yang di upload bukan pdf tolak

        if ($ekstensi != 'pdf') {
            setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');

            header("location:index.php?p=edit_sr&id=" . enkripRambo($id) . "&pg=" . $_GET['pg']);
        }

        // hapus
        $del_ba = $_POST['doc_ba_lama'];
        if (isset($del_ba)) {
            unlink("../file/doc_pendukung/$del_ba");
        }

        // Upload Document
        $namabaru = $id_divisi . "-" . time() . "-doc-ba." . $ekstensi;
        move_uploaded_file($lokasi_doc_ba, "../file/doc_pendukung/" . $namabaru);
    }

    $nm_barang = $_POST['nm_barang'];
    $id_anggaran = $_POST['id_anggaran'];
    $id_divisi = $_POST['id_divisi'];
    $keterangan = $_POST['keterangan'];
    // Update sr
    $return = mysqli_query($koneksi, "UPDATE sr SET nm_barang = '$nm_barang',                                                                                                        
                                                    id_anggaran = '$id_anggaran',
                                                    keterangan = '$keterangan',                                                    
                                                    doc_ba = '$namabaru'
                                            WHERE id_sr = '$id'");

    if ($return) {
        setcookie('pesan', 'SR berhasil di update !', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:index.php?p=edit_sr&id=" . enkripRambo($id) . "&pg=" . $_GET['pg']);
    } else {
        die("Ada kesalahan " . mysqli_error($koneksi));
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
        <div class="col-md-2">
            <a href="index.php?p=<?= dekripRambo($_GET['pg']); ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <br><br>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Edit Service Request</h3>
                </div>
                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" required class="form-control is-valid" name="id" value="<?= $id; ?>">
                    <input type="hidden" required class="form-control is-valid" name="id_divisi" value="<?= $idDivisi; ?>">
                    <input type="hidden" required class="form-control is-valid" name="doc_ba_lama" value="<?= $data['doc_ba']; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-3 control-label">Nama Barang</label>
                            <input type="hidden" required class="form-control is-valid" name="url" value="buat_sr">
                            <div class="col-sm-6">
                                <input type="text" required class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="id_anggaran">
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$idDivisi' AND tahun = '$tahun' ORDER BY nm_item ASC");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-6">
                                <textarea rows="5" type="text" name="keterangan" required class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="foto" class="col-sm-offset- col-sm-3 control-label">Document Pendukung/BA/Foto </label>
                            <div class="col-sm-6">
                                <div class="input-group input-file" name="doc_ba">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <span class="text-danger"><i> *Kosongkan jika tidak dirubah </i></span>
                            </div>
                        </div>
                        <div class="form-group ">
                            <button type="submit" name="update" class="btn btn-primary col-sm-offset-5 col-xs-offset-1">Update</button>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Document BA</h3>
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_ba'] ?> "></iframe>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    <!-- Detail sr -->
    <?php
    if (isset($_COOKIE['pesan2'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan2'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Rincian Service Request</h3>
                    </div>
                    <div class="box-body">
                        <div class="box-header with-border">
                            <!-- Tombol untuk menampilkan modal-->
                            <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                        </div>

                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-hover" id="material">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Merk</th>
                                        <th>Type</th>
                                        <th>Spesifikasi</th>
                                        <th>Qty</th>
                                        <th>Satuan</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($queryDSR)) {
                                        while ($row = mysqli_fetch_assoc($queryDSR)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['deskripsi']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['type']; ?> </td>
                                            <td> <?= $row['spesifikasi']; ?> </td>
                                            <td> <?= $row['qty']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td>
                                                <button type="button" class="btn btn-success modalEdit" data-toggle="modal" data-target="#editDsr" data-id="<?= $row['id_dsr']; ?>"><i class="fa fa-edit"></i> Edit</button>
                                                <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#hapusDsr" data-id="<?= $row['id_dsr']; ?>"><i class="fa fa-trash"></i> Delete</button>
                                            </td>
                                            </tr>
                                    <?php
                                            $no++;
                                        endwhile;
                                    }

                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                            <td colspan='9'> Tidak Ada Data</td>
                                        </tr>
                                        ";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Rincian</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="add_dsr.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="sr_id" value="<?= $id ?>">
                        <input type="hidden" name="id_dsr">
                        <input type="hidden" name="url" value="edit_sr&pg=<?= $_GET['pg'] ?>">
                        <div class=" form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="deskripsi" placeholder="Deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Merk</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="merk" placeholder="Merk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Type</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="type" placeholder="Type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Spesifikasi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="spesifikasi" placeholder="Spesifikasi"></textarea>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                            <div class="col-sm-8">
                                <input type="number" required class="form-control" name="qty">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Satuan</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="satuan" placeholder="Satuan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button type="submit" name="create" class="btn btn-primary col-sm-offset-1 "><i class="fa fa-add"></i>Tambah</button>
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

<!-- Modal Edit -->
<div id="editDsr" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rubah Rincian</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="upd_dsr.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="sr_id" value="<?= $id ?>">
                        <input type="hidden" name="id_dsr" id="me_id_dsr">
                        <input type="hidden" name="url" value="edit_sr&pg=<?= $_GET['pg'] ?>">
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="deskripsi" id="me_deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Merk</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="merk" placeholder="Merk" id="me_merk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Type</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="type" placeholder="Type" id="me_type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Spesifikasi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="spesifikasi" id="me_spesifikasi"></textarea>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                            <div class="col-sm-8">
                                <input type="number" required class="form-control" name="qty" id="me_qty">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Satuan</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="satuan" placeholder="Satuan" id="me_satuan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="keterangan" placeholder="Keterangan" id="me_keterangan"></textarea>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button type="submit" name="update" class="btn btn-primary col-sm-offset-1 "><i class="fa fa-add"></i>Tambah</button>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Edit  -->

<!-- Modal hapus -->
<div id="hapusDsr" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="del_dsr.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="sr_id" value="" id="md_sr_id">
                            <input type="hidden" name="id" value="" id="md_id_dsr">
                            <input type="hidden" name="url" value="edit_sr&pg=<?= $_GET['pg'] ?>">
                            <h4>Apakah anda yakin ingin menghapus item <b><span id="md_deskripsi"></b></span></h4>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="delete">Delete</button></span></a>
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
<!-- End hapus -->

<?php
$host = host();

?>

<script>
    var host = '<?= $host ?>';

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

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_dsr').val(data.id_dsr);
                    $('#me_deskripsi').val(data.deskripsi);
                    $('#me_merk').val(data.merk);
                    $('#me_type').val(data.type);
                    $('#me_spesifikasi').val(data.spesifikasi);
                    $('#me_qty').val(data.qty);
                    $('#me_satuan').val(data.satuan);
                    $('#me_keterangan').val(data.keterangan);
                }
            });
        });
    });
    // Akhir modal edit

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#md_id_dsr').val(data.id_dsr);
                    $('#md_sr_id').val(data.sr_id);
                    $('#md_deskripsi').text(data.deskripsi);
                }
            });
        });
    });
    // Akhir modal delete
</script>