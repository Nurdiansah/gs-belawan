<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['id_divisi'];

$queryDetail =  mysqli_query($koneksi, "SELECT * FROM detail_biayaops WHERE status  = '0' AND id_divisi='$Divisi' ");

$tanggalCargo = date("Y-m-d");


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=edit_item&id=$id");
    } else if ($_GET['aksi'] == 'lihat') {
        header("location:?p=detail_item&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        hapusItemMr($id);
    }
}
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=dashboard" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Create Material Request</h3>
                </div>

                <div class="box-header with-border">
                    <!-- Tombol untuk menampilkan modal-->
                    <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i></button>
                </div>

                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-hover">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Anggaran</th>
                            <th>Merk</th>
                            <th>Type</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <!-- <th>Keterangan</th> -->
                            <th>Aksi</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($queryDetail)) {
                                        while ($row = mysqli_fetch_assoc($queryDetail)) :
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['id_anggaran']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['type']; ?> </td>
                                            <td> <?= $row['spesifikasi']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['jumlah']; ?> </td>
                                            <td>
                                                <a href="?p=buat_mr&aksi=lihat&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info"> <i class="fa fa-search-plus"></i> </button></span></a>
                                                <a href="?p=buat_mr&aksi=edit&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Edit'><button class="btn btn-success"> <i class="fa fa-edit"></i> </button></span></a>
                                                <a href="?p=buat_mr&aksi=hapus&id=<?= $row['id']; ?>" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')"><span data-placement='top' data-toggle='tooltip' title='Hapus'><button class="btn btn-danger"> <i class="fa fa-remove"></i> </button></span></a>
                                            </td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                        </tr>
                    </table>
                </div>

                <form method="post" name="form" action="add_mr.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="id_divisi" class="col-sm-offset- col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="nm_divisi" value="<?= $rowUser['nm_divisi']; ?>">
                            </div>
                            <!-- </div>
                    <div class="form-group"> -->
                            <label for="tgl_bkk" class="col-sm-offset-3 col-sm-2 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control tanggal" name="tgl_pengajuan" value="<?= $tanggalCargo ?>">
                            </div>
                        </div>

                        <br>
                        <div class="form-group">
                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-5 " value="Submit">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">
                        </div>
                    </div>
                </form>

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
                                <form method="post" enctype="multipart/form-data" action="add_itempengajuan.php" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id_divisi" value="<?= $Divisi ?>">
                                        <div class="form-group">
                                            <label for="nm_barang" class="col-sm-offset-1 col-sm-3 control-label">Nama Barang</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="nm_barang" placeholder="Nama Barang">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                            <div class="col-sm-5">
                                                <select class="form-control select2" name="id_anggaran">
                                                    <option value="">--Kode Anggaran--</option>
                                                    <?php
                                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' ORDER BY nm_item ASC");
                                                    if (mysqli_num_rows($queryAnggaran)) {
                                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                                    ?>
                                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                                    <?php endwhile;
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="merk" class="col-sm-offset-1 col-sm-3 control-label">Merk</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="merk" placeholder="Merk Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="type" class="col-sm-offset-1 col-sm-3 control-label">Type</label>
                                            <div class="col-sm-5 ">
                                                <input type="text" required class="form-control" name="type" placeholder="Type Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="spesifikasi" class="col-sm-offset-1 col-sm-3 control-label">Spesifikasi </label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control " name="spesifikasi" placeholder="Spesifikasi Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="jumlah" class="col-sm-offset-1 col-sm-3 control-label">Jumlah</label>
                                            <div class="col-sm-5">
                                                <input type="number" min="0" value="0" required class="form-control" name="jumlah">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="satuan" class="col-sm-offset-1 col-sm-3 control-label">Satuan</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="satuan" placeholder="Satuan Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="keterangan" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                            <div class="col-sm-8">
                                                <textarea rows="7" type="textarea" required class="form-control" name="keterangan" placeholder="Detail spesifikasi barang"></textarea>
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

                <!-- Modal Edit -->
                <div id="Modal<?php echo $data['id']; ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog ">
                        <!-- konten modal-->
                        <div class="modal-content">
                            <!-- heading modal -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Tambah Barang</h4>
                            </div>
                            <!-- body modal -->
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data" action="add_itempengajuan.php" class="form-horizontal">
                                    <div class="box-body">
                                        <input type="hidden" name="id_divisi" value="<?= $Divisi ?>">
                                        <div class="form-group">
                                            <label for="nm_barang" class="col-sm-offset-1 col-sm-3 control-label">Nama Barang</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="nm_barang" placeholder="Nama Barang">
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                            <div class="col-sm-5">
                                                <select class="form-control select2" name="id_anggaran">
                                                    <option value="">--Kode Anggaran--</option>
                                                    <?php
                                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$Divisi' ORDER BY nm_item ASC");
                                                    if (mysqli_num_rows($queryAnggaran)) {
                                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                                    ?>
                                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                                    <?php endwhile;
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <label for="merk" class="col-sm-offset-1 col-sm-3 control-label">Merk</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="merk" placeholder="Merk Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="type" class="col-sm-offset-1 col-sm-3 control-label">Type</label>
                                            <div class="col-sm-5 ">
                                                <input type="text" required class="form-control" name="type" placeholder="Type Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="spesifikasi" class="col-sm-offset-1 col-sm-3 control-label">Spesifikasi </label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control " name="spesifikasi" placeholder="Spesifikasi Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="jumlah" class="col-sm-offset-1 col-sm-3 control-label">Jumlah</label>
                                            <div class="col-sm-5">
                                                <input type="number" min="0" value="0" required class="form-control" name="jumlah">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="satuan" class="col-sm-offset-1 col-sm-3 control-label">Satuan</label>
                                            <div class="col-sm-5">
                                                <input type="text" required class="form-control" name="satuan" placeholder="Satuan Barang">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label id="tes" for="keterangan" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                            <div class="col-sm-8">
                                                <textarea rows="4" type="textarea" required class="form-control" name="keterangan" placeholder="Detail spesifikasi barang">  </textarea>
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

    //     $(document).ready(function() {
    //     $('.datatab').DataTable();
    //         } );



    //      $(document).ready(function(){  
    //       $('#add').click(function(){  
    //            $('#insert').val("Insert");  
    //            $('#insert_form')[0].reset();  
    //       });  
    //       $(document).on('click', '.edit_data', function(){  
    //            var employee_id = $(this).attr("id");  
    //            $.ajax({  
    //                 url:"fetch.php",  
    //                 method:"POST",  
    //                 data:{employee_id:employee_id},  
    //                 dataType:"json",  
    //                 success:function(data){  
    //                      $('#name').val(data.name);  
    //                      $('#address').val(data.address);  
    //                      $('#gender').val(data.gender);  
    //                      $('#designation').val(data.designation);  
    //                      $('#age').val(data.age);  
    //                      $('#employee_id').val(data.id);  
    //                      $('#insert').val("Update");  
    //                      $('#add_data_Modal').modal('show');  
    //                 }  
    //            });  
    //       });  
    //       $('#insert_form').on("submit", function(event){  
    //            event.preventDefault();  
    //            if($('#name').val() == "")  
    //            {  
    //                 alert("Name is required");  
    //            }  
    //            else if($('#address').val() == '')  
    //            {  
    //                 alert("Address is required");  
    //            }  
    //            else if($('#designation').val() == '')  
    //            {  
    //                 alert("Designation is required");  
    //            }  
    //            else if($('#age').val() == '')  
    //            {  
    //                 alert("Age is required");  
    //            }  
    //            else  
    //            {  
    //                 $.ajax({  
    //                      url:"insert.php",  
    //                      method:"POST",  
    //                      data:$('#insert_form').serialize(),  
    //                      beforeSend:function(){  
    //                           $('#insert').val("Inserting");  
    //                      },  
    //                      success:function(data){  
    //                           $('#insert_form')[0].reset();  
    //                           $('#add_data_Modal').modal('hide');  
    //                           $('#employee_table').html(data);  
    //                      }  
    //                 });  
    //            }  
    //       });  
    //       $(document).on('click', '.view_data', function(){  
    //            var employee_id = $(this).attr("id");  
    //            if(employee_id != '')  
    //            {  
    //                 $.ajax({  
    //                      url:"select.php",  
    //                      method:"POST",  
    //                      data:{employee_id:employee_id},  
    //                      success:function(data){  
    //                           $('#employee_detail').html(data);  
    //                           $('#dataModal').modal('show');  
    //                      }  
    //                 });  
    //            }            
    //       });  
    //  }); 
</script>