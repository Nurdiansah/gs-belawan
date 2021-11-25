<?php  
      if (isset($_GET['aksi']) && isset($_GET['id'])) {
        //die($id = $_GET['id']);
        $id = $_GET['id'];
        echo $id;

        if ($_GET['aksi'] == 'edit') {
            header("location:?p=edit_golongan&id=$id");
        } else if ($_GET['aksi'] == 'hapus') {
            header("location:?p=hapus_golongan&id=$id");
        } 
    }
	
	$query = mysqli_query($koneksi, "SELECT * FROM golongan ORDER BY nm_golongan ASC");	

?>

<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-sm-12">
			 <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Golongan</h3>
                </div>                
                <div class="box-body">
               <div class="row">
                    <div class="col-md-2">
                    <button type="button" class="btn btn-primary col-sm-offset- " data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Tambah Data</button>
                    </div>
                    <br><br>
                </div>                  
                	<div class="table-responsive">
                		<table class="table text-center  table table-striped table-hover" id="alat_berat">
                			<thead  > 
	                			<tr>
	                				<th>No</th>	                				            				
                                    <th>Nama Golongan</th>              				
                                    <th>Aksi</th>
	                			</tr>
                			</thead>
                			<tbody>
                				<tr>
                					<?php 
                						$no =1 ;
                						if (mysqli_num_rows($query)) {
                							while($row=mysqli_fetch_assoc($query)):
                					 ?>
                						<td> <?= $no; ?> </td>                					
                                        <td> <?= $row['nm_golongan']; ?> </td>            					
                                        <td>
                                            <a href="?p=golongan&aksi=edit&id=<?= $row['id_golongan']; ?>" ><span data-placement='top' title='Edit' ><button  class="btn btn-success" >Edit</button></span></a>                                                                 
                                            <a href="?p=golongan&aksi=hapus&id=<?= $row['id_golongan']; ?>" onclick="javascript: return confirm('Anda yakin hapus ?')"><span data-placement='top' title='Hapus' ><button  class="btn btn-danger" >Hapus</button></span></a>                                                                 
                                        </td>
                				</tr>
                			<?php $no++; endwhile; } ?>
                			</tbody>
                		</table>
                	</div>                	
                </div>
                  <!-- Awal Modal -->
                  <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- konten modal-->
                    <div class="modal-content">
                        <!-- heading modal -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Tambah Satuan</h4>
                        </div>
                        <!-- body modal -->
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="add_golongan.php" class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label id="tes"for="nm_golongan" class="col-sm-offset-1 col-sm-3 control-label">Nama Golongan</label>
                                        <div class="col-sm-4">
                                            <input type="text" required class="form-control" name="nm_golongan" >
                                        </div>
                                    </div>
                                    <div class=" modal-footer">
                                        <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-1 " value="Tambah" > 
                                        &nbsp;
                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">                                                                              
                                    </div>
                                </div>
                            </form> 
                        </div>
                    </div>
                </div>
                </div>
                <!-- Akhir Modal -->
            </div>
		</div>
	</div>

</section>

<script>
    $(function(){
        $("#user").DataTable({
             "language": {
            "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
            "sEmptyTable": "Tidak ada data di database"
            }
        });
    });
</script> 
