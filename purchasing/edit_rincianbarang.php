<?php  



    include "../fungsi/koneksi.php";
    include "../fungsi/fungsi.php";

        if(!isset($_GET['id'])){
            header("location:index.php");
        }

        $id = $_GET['id']; 

        $queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $Area=$rowUser['area'];

        $querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_subdbo=$id ");
        $row=mysqli_fetch_assoc($querySbo);        
        

        $queryDetail =  mysqli_query($koneksi, "SELECT * FROM detail_biayaops db 
                                                              JOIN anggaran a
                                                              ON db.id_anggaran = a.id_anggaran 
                                                              JOIN supplier s
                                                              ON s.id_supplier = db.id_supplier
                                                              WHERE db.id=$id ");
        $data=mysqli_fetch_assoc($queryDetail);
        $id_supplier = $data['id_supplier'];
        $Divisi=$data['id_divisi'];

        $queryAnggaran = mysqli_query($koneksi,"SELECT * FROM anggaran WHERE id_divisi ='$Divisi' AND id_anggaran !='$data[id_anggaran]' ORDER BY nm_item ASC");        

        

        $tanggalCargo=date("Y-m-d");

        if (isset($_GET['aksi']) && isset($_GET['id'])) {
            //die($id = $_GET['id']);
            $id = $_GET['id'];

            if ($_GET['aksi'] == 'edit') {
                header("location:?p=edit_rincianbarang&id=$id");
            } else if ($_GET['aksi'] == 'lihat') {
                header("location:?p=detail_item&id=$id");
            } 
        }

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                        <div class="col-md-2">
                            <a href="index.php?p=bidding_itemmr&id=<?=$row['id_dbo']; ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div>
                        <br><br>
                </div>                                                                        
                <div class="box-header with-border">
                    <h3 class="text-center">Edit Rincian Barang</h3>
                </div>                                  
                <div class="table-responsive datatab"> 
                            <table class="table text-center table table-striped table-dark table-hover ">
                                <thead style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Deskripsi</th>
                                    <th>QTY</th>                                    
                                    <th>Unit</th>  
                                    <th>Unit Price</th>                                                                                                      
                                    <th>Total Price</th>
                                    <th>Edit</th>
                                </thead>
                                <tr>
                                    <tbody>
                                        <tr>
                                           
                                                <td> <?= "1"; ?> </td>      
                                                <td> <?= $row['sub_deskripsi']; ?> </td>
                                                <td> <?= $row['sub_qty']; ?> </td>                                                
                                                <td> <?= $row['sub_unit']; ?> </td>                                                                                                
                                                <td> <?= $row['sub_unitprice']; ?> </td>
                                                <td><?= $row['total_price']; ?></td>
                                                <td>                                                                                        
                                                    <form method="post" name="form" enctype="multipart/form-data" action="add_jv.php" class="form-horizontal">                                                
                                                        <input type="hidden" id="id_joborder" name="id_joborder" value="<?= $idJoborder?>">                                                                                                                            
                                                        <input type="hidden" id="id_cargo" name="id_cargo" value="<?= $row['id_cargo']; ?>">                                                                                                                            
                                                        <input type="number" min="0" max="<?= $row['qty_cargo']; ?>" required class="form-control" placeholder="<?= $row['qty_cargo']; ?> " name="qty_as">
                                                        <?php if ($kegiatan == 'Offloading') { ?>
                                                            <select id="keterangan" name="keterangan" class="form-control">
                                                                <option value="To Jetty">To Jetty</option>
                                                                <option value="To Yard">To Yard</option>
                                                                <option value="To Trucking">To Trucking</option>                                                    
                                                                <option value="To Warehouse">To Warehouse</option>                                                                                                                                                            
                                                                <option value="Not Available">Not Available</option>
                                                            </select>
                                                        <?php } else if($kegiatan == 'Loading'){  ?>
                                                            <select id="keterangan" name="keterangan" class="form-control">                                                        
                                                                <option value="From Jetty">From Jetty</option>
                                                                <option value="From Yard">From Yard</option>
                                                                <option value="From Trucking">From Trucking</option>                                                                                                                                                                                                                                                                      
                                                                <option value="From Warehouse">From Warehouse</option>  
                                                                <option value="Not Available">Not Available</option>
                                                            </select>
                                                        <?php } ?>                                                
                                                            <input type="submit" name="update" class="btn btn-success  " value="Update" > 
                                                    </form>
                                                    <?php } ?>                                            
                                                </td>  
                                        </tr>                                    
                                    </tbody>
                            </table>
                        </div>
                    <br>
              
                <!-- Akhir Modal Tambah  -->

            </div>
        </div>
    </div>        
</section>

<script>
    $(document).ready(function(){
        $('.tanggal').datepicker({
            format:"yyyy-mm-dd",
            autoclose:true
        });
        $(".add-more").click(function(){ 
          var html = $(".copy").html();
          $(".after-add-more").after(html);
        });
        $("body").on("click",".remove",function(){ 
          $(this).parents(".control-group").remove();
        });
    });

    $(document).ready(function() {
    $('.datatab').DataTable();
        } );
    
    // batas script baru
    
     $(document).ready(function(){  
      $('#add').click(function(){  
           $('#insert').val("Insert");  
           $('#insert_form')[0].reset();  
      });  
    
</script>