<?php  
    include "../fungsi/koneksi.php";
    include "../fungsi/fungsi.php";

        $id = $_GET['id'];

        if (isset($_GET['aksi']) && isset($_GET['id'])) {
        //die($id = $_GET['id']);
        $id = $_GET['id'];
        echo $id;

        if ($_GET['aksi'] == 'edit') {
            header("location:?p=cetak_bkk&id=$id");
        } else if ($_GET['aksi'] == 'hapus') {
            header("location:?p=hapus_joborder&id=$id");
        } 
    }

        $queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
	    $rowNama=mysqli_fetch_assoc($queryNama);
        $Nama=$rowNama['nama'];

        $queryBkk = mysqli_query($koneksi, "SELECT * FROM bkk WHERE id_bkk = '$id' ");
    
?>
<section class="content">   
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                        <div class="col-md-2">
                            <a href="index.php?p=data_jovessel" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div>
                        <br><br>
                </div>
                
                <!-- Detail Job Order -->
                
                <div class="box-header with-border">
                    <h3 class="text-center">PEMBAYARAN KASIR</h3>
                </div>

                <?php 
                        if (mysqli_num_rows($queryBkk)) {
                            while($row2=mysqli_fetch_assoc($queryBkk)):
                            // query 
                            $nilai_bkk = number_format($row2['nilai_bkk'],2,",",".");
                            $jml_bkk = number_format($row2['jml_bkk'],2,",",".");
                            $bll = number_format($row2['bll_bkk'],2,",",".");
                            // $queryTc =  mysqli_query($koneksi, "SELECT sum(m3_cargo) as total_cargo FROM detail_joborder WHERE id_joborder='$id' AND cargo_final='1'");
                            // $rowTc=mysqli_fetch_assoc($queryTc);
                            // $Tc=$rowTc['total_cargo'];
                        ?>

                     <!-- form -->
                     <form method="post" enctype="multipart/form-data" action="cetak_bkk.php" class="form-horizontal"> 
                        <div class="col-sm-offset- col-sm-1 control-label">                                      
                                <input type="hidden"  class="form-control" name="id_bkk" value="<?= $row2['id_bkk']; ?>" >                                                
                                <input type="submit" name="simpan" class="btn btn-success " value="Cetak BKK "   >                                                                            
                        </div>
                     </form> 
                    <!-- akhir form -->

                <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                        
                    <div class="box-body">
                    <div class="form-group ">
                        <label  class="col-sm-offset-9   control-label"></label>                            
                            <!-- <a target="_blank"  href="cetak_bkk.php&id=<?= $row2['id_bkk']; ?>" class="btn btn-success"> Cetak BKK <i class="fa fa-print"></i> </a> -->
                            <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#lengkapi" >Isi no BKK</button></span>                                                                                      
                            <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#kirim" >Kirim LPJ</button></span>                                                                                      
                    </div>
                    <div class="form-group ">
                            <label for="id_joborder" class=" col-sm-4 control-label">ID bkk</label>
                            <div class="col-sm-3">
                                <input  type="text" value="<?= $row2['id_bkk']; ?>" disabled class="form-control" name="id_bkk" >
                            </div>
                    </div>
                    <div class="form-group ">
                            <label id="tes"for="tgl_bkk" class=" col-sm-4 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['tgl_bkk']; ?>" disabled class="form-control" name="tgl_bkk">
                            </div>
                    </div>
                    <div class="form-group">
                        <label id="tes"for="nm_vendor"  class=" col-sm-4 control-label">Nama Vendor</label>
                            <div class="col-sm-3">
                                <input type="text"  value="<?= $row2['nm_vendor']; ?>" disabled class="form-control" name="nm_vendor">
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="terbilang_bkk" class=" col-sm-4 control-label">Terbilang</label>
                            <div class="col-sm-4">
                                <input type="text" value="<?= $row2['terbilang_bkk']; ?>" disabled class="form-control tanggal" name="terbilang_bkk">
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="keterangan" class="col-sm-4 control-label">Keterangan</label>
                            <div class="col-sm-4">
                                <input type="text" value="<?= $row2['keterangan']; ?>" class="form-control " name="keterangan" readonly>
                            </div>
                    </div>
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-4 control-label">Nilai</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$nilai_bkk; ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                    </div>
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-4 control-label">PPN</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?=  $row2['ppn_bkk'];  ?> %" readonly class="form-control" name="nilai_ppn" > 
                            </div>
                    </div>
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-4 control-label">Biaya Lain Lain</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$bll; ?>" readonly class="form-control" name="nilai_ppn" > 
                            </div>
                    </div>
                    <div class="form-group">
                            <label id="tes"for="jml_bkk" class="col-sm-4 control-label">Jumlah</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$jml_bkk; ?>" readonly class="form-control" name="jml_bkk">
                            </div>  
                    </div>
                    </div>                              
                </form>


               <!-- Embed Document               -->
               <!-- Document PTW -->
                    <div class="box-header with-border">
                    <h3 class="text-center">Invoice </h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/<?php echo $row2['invoice']; ?> "></iframe>
                    </div>
                                                                  
                    <br>
                    <br>
                    
                </div>   
            </div>
        </div>
    </div>


<!-- Modal Kirim  -->
<div id="kirim" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- konten modal-->
                    <div class="modal-content">
                        <!-- heading modal -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Submit LPJ</h4>
                        </div>
                        <!-- body modal -->
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="add_lpj.php" class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group ">                                        
                                        <div class="col-sm-4">
                                            <input type="hidden" value="<?= $row2['id_bkk']; ?>" class="form-control" name="id_bkk" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="doc_lpj" class="col-sm-offset-1 col-sm-3 control-label">LPJ</label>
                                        <div class="col-sm-5">
                                            <div class="input-group input-file" name="doc_lpj">
                                                <input type="text" class="form-control" required />			
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" modal-footer">
                                        <button class="btn btn-success" type="submit" name="simpan">Kirim</button></span></a>            
                                        &nbsp;
                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">                                                                              
                                    </div>
                                </div>
                            </form> 
                        </div>

            </div>
            </div>
            </div>

<!-- Modal Lengkapi -->
    <div id="lengkapi" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- konten modal-->
                    <div class="modal-content">
                        <!-- heading modal -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Lengkapi Document BKK </h4>
                        </div>
                        <!-- body modal -->
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="add_lengkapibkk.php" class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group ">                                        
                                        <div class="col-sm-4">
                                            <input type="hidden" value="<?= $row2['id_bkk']; ?>" class="form-control" name="id_bkk" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label id="tes"for="no_bkk" class="col-sm-4 control-label">No. BKK</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="no_bkk" >
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label id="tes"for="tgl_bkk" class="col-sm-4 control-label">Tanggal BKK</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control tanggal" name="tgl_bkk" >
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label id="tes"for="nocek_bkk" class="col-sm-4 control-label">No. Cek/Giro</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control " name="nocek_bkk" >
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label id="tes"for="dari_bank" class="col-sm-4 control-label">Bank</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control " name="dari_bank" >
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <label id="tes" for="dari_rekening" class="col-sm-4 control-label">Rekening</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control " name="dari_rekening" >
                                        </div>
                                    </div>                                    
                                    <div class=" modal-footer">
                                        <button class="btn btn-success" type="submit" name="simpan">Kirim</button></span></a>            
                                        
                                        &nbsp;
                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">                                                                              
                                    </div>
                                </div>
                            </form> 
                        </div>

                <?php endwhile; } ?>
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
    });

    
    function bs_input_file() {
        $(".input-file").before(
            function() {
                if ( ! $(this).prev().hasClass('input-ghost') ) {
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                    element.attr("name",$(this).attr("name"));
                    element.change(function(){
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function(){
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function(){
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor","pointer");
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


