<?php  
    include "../fungsi/koneksi.php";
    include "../fungsi/fungsi.php";

        $id = $_GET['id'];

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
                            <a href="index.php?p=lihat_kaskeluar" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div>
                        <br><br>
                </div>

                <!-- Detail Job Order -->
                
                <div class="box-header with-border">
                    <h3 class="text-center">Kas Keluar</h3>
                </div>
                <?php 
                        if (mysqli_num_rows($queryBkk)) {
                            while($row2=mysqli_fetch_assoc($queryBkk)):
                            // query Total_cargo
                            $nilai_bkk = number_format($row2['nilai_bkk'],2,",",".");
                            $jml_bkk = number_format($row2['jml_bkk'],2,",",".");
                            $queryTc =  mysqli_query($koneksi, "SELECT sum(m3_cargo) as total_cargo FROM detail_joborder WHERE id_joborder='$id' AND cargo_final='1'");
                            $rowTc=mysqli_fetch_assoc($queryTc);
                            $Tc=$rowTc['total_cargo'];
                        ?>
                
                
                
                <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">                        
                    <div class="box-body">
                    
                    <div class="form-group ">
                            <label for="id_joborder" class=" col-sm-4 control-label">ID bkk</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['id_bkk']; ?>" disabled class="form-control" name="id_bkk" >
                            </div>
                    </div>
                    <div class="form-group ">
                            <label id="tes"for="tgl_bkk" class=" col-sm-4 control-label">Tanggal</label>
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
                                <input type="text" value="<?=  $row2['bll_bkk'];  ?> " readonly class="form-control" name="nilai_ppn" > 
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
                    <div class="box-header with-border">
                    <h3 class="text-center">Invoice </h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/<?php echo $row2['invoice']; ?> "></iframe>
                    </div>

                <!-- Embed Document               -->            
                <!-- <?php
                    if ($row2['doc_lpj']!=0) { ?>
                        <div class="box-header with-border">
                            <h3 class="text-center">LPJ</h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="../file/lpj/<?php echo $row2['doc_lpj']; ?> "></iframe>
                        </div>
                                                                        
                            <br>
                            <br>
                <?php    } ?> -->
                
                    
                <!-- </div> -->
                </div>   
            </div>
        </div>
    </div>


                <?php endwhile; } ?>
</section>

<script>

    $(document).ready(function(){
        $('.tanggal').datepicker({
            format:"yyyy-mm-dd",
            autoclose:true
        });
    });
</script>


