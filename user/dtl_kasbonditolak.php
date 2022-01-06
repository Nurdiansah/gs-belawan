<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];
// echo $id;
// die;
$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u                                                     
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['id_divisi'];

$queryDetail =  mysqli_query($koneksi, "SELECT * 
                                        FROM detail_biayaops db
                                        JOIN anggaran a
                                            ON a.id_anggaran = db.id_anggaran
                                        JOIN kasbon ks
                                            ON db.id = ks.id_dbo
                                        WHERE id_kasbon = '$id'");
$data = mysqli_fetch_assoc($queryDetail);

$querySbo =  mysqli_query($koneksi, "SELECT * FROM sub_dbo WHERE id_dbo='$data[id_dbo]'");

// if (isset($_GET['aksi']) && isset($_GET['id'])) {
//     //die($id = $_GET['id']);
//     $id = $_GET['id'];

//     if ($_GET['aksi'] == 'lihat') {
//         header("location:?p=lihat_detailanggaran&id=$id");
//     } else if ($_GET['aksi'] == 'hapus') {
//         header("location:?p=hapus_sdbo&id=$id&url=dtl_kasbonditolak");
//     }
// }
?>
<!-- index.php?p=dtl_kasbonditolak&id= -->
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Pengajuan Ditolak</h3>
                </div>
                <form method="post" name="form" action="auto_edit_item.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label">Nama Barang</label>
                            <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                            <input type="hidden" required class="form-control is-valid" name="url" value="ditolak_kasbon&sp=tolak_purchasing">
                            <input type="hidden" name="doc_pendukung_lama" value="<?= $data['foto_item']; ?>">
                            <div class="col-sm-3">
                                <input type="text" required class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran">
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT id_anggaran, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item
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
                                                                                WHERE agg.id_divisi = '$idDivisi'
                                                                                AND jenis_anggaran = 'BIAYA'
                                                                                AND tahun = '$tahun'
                                                                                ORDER BY nm_item ASC
                                                                            ");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox" <?= $rowAnggaran['id_anggaran'] == $data['id_anggaran'] ? 'selected=selected' : ''; ?>><?= $rowAnggaran['nm_item'] . ' - [' . $rowAnggaran['program_kerja']; ?>]</option>
                                    <?php endwhile;
                                    } ?>
                                </select>
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
                            <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" required class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="foto" class="col-sm-offset- col-sm-2 control-label">Doc Pendukung</label>
                            <div class="col-sm-3">
                                <div class="input-group input-file" name="doc_pendukung">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <p style="color: red;"><i>Kosongkan jika tidak dirubah</i></p>
                            </div>

                            <label for="alasan_ditolak" class="col-sm-offset- col-sm-2 control-label">Alasan Penolakan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="alasan_ditolak" required class="form-control " disabled> <?= $data['komentar']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-5 " value="Update">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div>
                        <?php if (!file_exists("../file/foto/" . $data['foto_item']) || $data['foto_item'] != '') { ?>
                            <div class="form-group">
                                <h3 class="text-center">Foto Barang</h3>
                                <br>
                                <!-- <div class="row "> -->
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="../file/foto/<?= $data['foto_item']; ?>"></iframe>
                                </div>
                            </div>
                        <?php } ?>
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
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($querySbo)) {
                                        while ($row = mysqli_fetch_assoc($querySbo)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['sub_deskripsi']; ?> </td>
                                            <td> <?= $row['sub_qty']; ?> </td>
                                            <td> <?= $row['sub_unit']; ?> </td>
                                            <td> <a href="hapus_sdbo.php?id=<?= $id; ?>&id_subdbo=<?= $row['id_subdbo']; ?>&url=dtl_kasbonditolak"><span data-placement='top' title='Hapus' onclick="javascript: return confirm('Anda yakin hapus ?')"><button class="btn btn-danger" onclick=”return confirm(‘Yakin Hapus?’)”><i class="fa fa-trash"></i></button></span></a> </td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                    </table>
                    <a href="ajukan_kembali_kasbon.php?id=<?= $id; ?>" class="btn btn-success col-sm-offset-10 " onclick="return confirm('Yakin ingin mengajuan kembali pengajuan ini?')"><i class="fa fa-send"></i> Ajukan Kembali</a>
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
                                        <input type="hidden" name="id_dbo" value="<?= $data['id_dbo']; ?>">
                                        <input type="hidden" name="url" value="dtl_kasbonditolak">
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
                                                <input type="number" required class="form-control" name="sub_qty">
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