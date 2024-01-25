<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];

if (isset($_POST['simpan_edit'])) {
    $id = $_POST['id'];
    $sub_deskripsi = $_POST['sub_deskripsi'];
    $sub_qty = $_POST['sub_qty'];
    $sub_unit = $_POST['sub_unit'];

    $update = mysqli_query($koneksi, "UPDATE sub_dbo SET sub_deskripsi = '$sub_deskripsi', 
                                                sub_qty = '$sub_qty',
                                                sub_unit = '$sub_unit'
                                        WHERE id_subdbo = '$id'
                            ");

    if ($update) {
        header("Location: index.php?p=edit_item&id=" . $_POST['id_dbo']);
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u                                                     
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['id_divisi'];

$queryDetail =  mysqli_query($koneksi, "SELECT * 
                                                        FROM detail_biayaops db
                                                        JOIN anggaran a
                                                        ON a.id_anggaran = db.id_anggaran
                                                        WHERE id=$id ");
$data = mysqli_fetch_assoc($queryDetail);
$idPK = $data['programkerja_id'];

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id ");

$tanggalCargo = date("Y-m-d");

// if (isset($_GET['aksi']) && isset($_GET['id'])) {
//     //die($id = $_GET['id']);
//     $id = $_GET['id'];
//     echo $id;

//     if ($_GET['aksi'] == 'lihat') {
//         header("location:?p=lihat_detailanggaran&id=$id");
//     } else if ($_GET['aksi'] == 'hapus') {
//         header("location:?p=hapus_sdbo&id=$id&url=edit_item");
//     }
// }

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=buat_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Edit Item</h3>
                </div>
                <form method="post" name="form" action="auto_edit_item.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label">Nama Barang</label>
                            <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                            <input type="hidden" required class="form-control is-valid" name="url" value="buat_mr">
                            <input type="hidden" value="<?= $data['foto_item']; ?>" name="doc_pendukung_lama">
                            <div class="col-sm-3">
                                <input type="text" required class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes" for="id_programkerja" class="col-sm-offset- col-sm-2 control-label">Program Kerja</label>
                            <div class="col-sm-3">
                                <select class="form-control select2 programkerja_id_edit" name="id_programkerja" id="id_programkerja_edit" required>
                                    <!-- <option value="">--Program Kerja--</option> -->
                                    <?php

                                    $queryProgramKerja = mysqli_query($koneksi, "SELECT id_programkerja, id_costcenter, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi) AS cost_center, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_programkerja, kd_programkerja
                                                                                                    FROM cost_center
                                                                                                    JOIN pt
                                                                                                        ON id_pt = pt_id
                                                                                                    JOIN divisi
                                                                                                        ON id_divisi = divisi_id
                                                                                                    JOIN parent_divisi
                                                                                                        ON id_parent = parent_id
                                                                                                    JOIN program_kerja
                                                                                                        ON id_costcenter = costcenter_id
                                                                                                    WHERE divisi_id = '$idDivisi'
                                                                                                    AND tahun = '$tahun'
                                                                                                    ORDER BY program_kerja ASC
                                                                                ");
                                    if (mysqli_num_rows($queryProgramKerja)) {
                                        while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                    ?>
                                            <option value="<?= $rowPK['id_programkerja']; ?>" <?= $rowPK['id_programkerja'] == $idPK ? 'selected' : ''; ?>><?= $rowPK['kd_programkerja'] . " [" . $rowPK['nm_programkerja']; ?>]</option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                            <br><br>
                            <div class="kotakAnggaran_edit">
                                <label for="id_anggaran" class="col-sm-offset-5 col-sm-2 control-label">Kode Anggaran</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2 id_anggaran_edit" name="id_anggaran" id="id_anggaran_edit" required>
                                        <?php
                                        $queryAnggaran = mysqli_query($koneksi, "SELECT id_anggaran, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item, kd_anggaran
                                                                                FROM anggaran agg
                                                                                JOIN program_kerja
                                                                                    ON programkerja_id = id_programkerja
                                                                                JOIN cost_center cc
                                                                                    ON costcenter_id = id_costcenter
                                                                                JOIN pt pt
                                                                                    ON pt_id = id_pt
                                                                                JOIN divisi dvs
                                                                                    ON divisi_id = dvs.id_divisi
                                                                                JOIN parent_divisi pd
                                                                                    ON parent_id = id_parent
                                                                                JOIN segmen sg
                                                                                    ON sg.id_segmen = agg.id_segmen
                                                                                WHERE id_programkerja = '$idPK'
                                                                                ORDER BY nm_item ASC
                                                                            ");
                                        if (mysqli_num_rows($queryAnggaran)) {
                                            while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                        ?>
                                                <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox" <?= $rowAnggaran['id_anggaran'] == $data['id_anggaran'] ? 'selected=selected' : ''; ?>><?= $rowAnggaran['kd_anggaran'] . ' - [' . $rowAnggaran['nm_item']; ?>]</option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="merk" class="col-sm-offset col-sm-2 control-label">Merk </label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control is-valid" name="merk" value="<?= $data['merk']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="type" class="col-sm-offset- col-sm-2 control-label">Type</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control " name="type" value="<?= $data['type']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="jumlah" class="col-sm-offset col-sm-2 control-label">QTY</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control is-valid" name="jumlah" value="<?= $data['jumlah']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="satuan" class="col-sm-offset- col-sm-2 control-label">Satuan</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control " name="satuan" value="<?= $data['satuan']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="satuan" class="col-sm-offset col-sm-2 control-label">Spesifikasi</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control is-valid" name="spesifikasi" value="<?= $data['spesifikasi']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="foto" class="col-sm-offset- col-sm-2 control-label">Doc Pendukung/BA/Foto</label>
                            <div class="col-sm-3">
                                <div class="input-group input-file" name="doc_pendukung">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <p style="color: red;"><i>Kosongkan jika tidak dirubah</i></p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" required class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-5 " value="Update">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div>
                        <div class="form-group">
                            <h3 class="text-center">Foto Barang</h3>
                            <br>
                            <!-- <div class="row "> -->
                            <div class="col-sm-12 col-xs-12">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/foto/<?= $data['foto_item']; ?>"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="box-header with-border">
                    <h3 class="text-center">Rincian Barang</h3>
                </div>

                <!--  -->
                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                    <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                </div>
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Deskripsi</th>
                            <th>QTY</th>
                            <th>Satuan</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            if (mysqli_num_rows($querySbo)) {
                                while ($row = mysqli_fetch_assoc($querySbo)) :

                            ?>
                                    <tr>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['sub_deskripsi']; ?> </td>
                                        <td> <?= $row['sub_qty']; ?> </td>
                                        <td> <?= $row['sub_unit']; ?> </td>
                                        <td>
                                            <button type="button" title="Edit" class="btn btn-warning " data-toggle="modal" data-target="#modal_edit_<?= $row['id_subdbo']; ?>"><i class="fa fa-edit"></i></button>
                                            <a href="hapus_sdbo.php?id=<?= $id; ?>&id_subdbo=<?= $row['id_subdbo']; ?>&url=edit_item"><span data-placement='top' title='Hapus' onclick="javascript: return confirm('Anda yakin hapus ?')"><button class="btn btn-danger" onclick=”return confirm(‘Yakin Hapus?’)”><i class="fa fa-trash"></i></button></span></a>
                                        </td>
                                    </tr>

                                    <!-- Modal Tambah -->
                                    <div id="modal_edit_<?= $row['id_subdbo']; ?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog lg">
                                            <!-- konten modal-->
                                            <div class="modal-content">
                                                <!-- heading modal -->
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Edit Barang</h4>
                                                </div>
                                                <!-- body modal -->
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                                                        <div class="box-body">
                                                            <input type="hidden" name="id" value="<?= $row['id_subdbo']; ?>">
                                                            <input type="hidden" name="id_dbo" value="<?= $id; ?>">
                                                            <input type="hidden" name="url" value="edit_item">
                                                            <div class="form-group">
                                                                <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                                                                <div class="col-sm-8">
                                                                    <textarea rows="6" type="textarea" required class="form-control" name="sub_deskripsi" placeholder="Deskripsi"><?= $row['sub_deskripsi']; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <br><br><br><br><br><br><br>
                                                            <div class="form-group ">
                                                                <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                                                                <div class="col-sm-5">
                                                                    <input type="number" step="any" required class="form-control" name="sub_qty" value="<?= $row['sub_qty']; ?>">
                                                                </div>
                                                            </div>
                                                            <br><br>
                                                            <div class="form-group">
                                                                <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Unit</label>
                                                                <div class="col-sm-5 ">
                                                                    <input type="text" required class="form-control" name="sub_unit" placeholder="Unit" value="<?= $row['sub_unit']; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class=" modal-footer">
                                                            <input type="submit" name="simpan_edit" class="btn btn-primary col-sm-offset-1 " value="Simpan">
                                                            &nbsp;
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
                <br>

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
                                <form method="post" enctype="multipart/form-data" action="add_subdbo.php" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id_dbo" value="<?= $id ?>">
                                        <input type="hidden" name="url" value="edit_item">
                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                        <div class="form-group">
                                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                                            <div class="col-sm-8">
                                                <textarea rows="6" type="textarea" required class="form-control" name="sub_deskripsi" placeholder="Deskripsi"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                                            <div class="col-sm-5">
                                                <input type="number" step="any" required class="form-control" name="sub_qty">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Unit</label>
                                            <div class="col-sm-5 ">
                                                <input type="text" required class="form-control" name="sub_unit" placeholder="Unit">
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
</section>

<script>
    var host = '<?= host(); ?>'

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

    $(document).ready(function() {
        $('.datatab').DataTable();
    });

    // batas script baru

    $(document).ready(function() {
        $('#add').click(function() {
            $('#insert').val("Insert");
            $('#insert_form')[0].reset();
        });
        $(document).on('click', '.edit_data', function() {
            var employee_id = $(this).attr("id");
            $.ajax({
                url: "fetch.php",
                method: "POST",
                data: {
                    employee_id: employee_id
                },
                dataType: "json",
                success: function(data) {
                    $('#name').val(data.name);
                    $('#address').val(data.address);
                    $('#gender').val(data.gender);
                    $('#designation').val(data.designation);
                    $('#age').val(data.age);
                    $('#employee_id').val(data.id);
                    $('#insert').val("Update");
                    $('#add_data_Modal').modal('show');
                }
            });
        });
        $('#insert_form').on("submit", function(event) {
            event.preventDefault();
            if ($('#name').val() == "") {
                alert("Name is required");
            } else if ($('#address').val() == '') {
                alert("Address is required");
            } else if ($('#designation').val() == '') {
                alert("Designation is required");
            } else if ($('#age').val() == '') {
                alert("Age is required");
            } else {
                $.ajax({
                    url: "insert.php",
                    method: "POST",
                    data: $('#insert_form').serialize(),
                    beforeSend: function() {
                        $('#insert').val("Inserting");
                    },
                    success: function(data) {
                        $('#insert_form')[0].reset();
                        $('#add_data_Modal').modal('hide');
                        $('#employee_table').html(data);
                    }
                });
            }
        });
        $(document).on('click', '.view_data', function() {
            var employee_id = $(this).attr("id");
            if (employee_id != '') {
                $.ajax({
                    url: "select.php",
                    method: "POST",
                    data: {
                        employee_id: employee_id
                    },
                    success: function(data) {
                        $('#employee_detail').html(data);
                        $('#dataModal').modal('show');
                    }
                });
            }
        });
    });
</script>