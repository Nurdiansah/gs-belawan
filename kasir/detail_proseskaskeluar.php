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
                            <a href="index.php?p=data_jovessel" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div>
                        <br><br>
                </div>
                
                <!-- Detail Job Order -->
                
                <div class="box-header with-border">
                    <h3 class="text-center">VERIFIKASI KASIR</h3>
                </div>
                <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">
                        <?php 
                        if (mysqli_num_rows($queryBkk)) {
                            while($row2=mysqli_fetch_assoc($queryBkk)):
                            // query Total_cargo
                            $nilai_bkk = number_format($row2['nilai_bkk'],2,",",".");
                            $jml_bkk = number_format($row2['jml_bkk'],2,",",".");
                            $bll_bkk = number_format($row2['bll_bkk'],2,",",".");
                            $queryTc =  mysqli_query($koneksi, "SELECT sum(m3_cargo) as total_cargo FROM detail_joborder WHERE id_joborder='$id' AND cargo_final='1'");
                            $rowTc=mysqli_fetch_assoc($queryTc);
                            $Tc=$rowTc['total_cargo'];
                        ?>
                    <div class="box-body">
                    <div class="form-group ">
                            <label for="id_joborder" class=" col-sm-4 control-label">ID bkk</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['id_bkk']; ?>" disabled class="form-control" name="id_bkk" >
                            </div>
                    </div>
                    <div class="form-group ">
                            <label id="tes"for="tgl_bkk" class=" col-sm-4 control-label">Tanggal Pengajuan BKK</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['tgl_pengajuan']; ?>" disabled class="form-control" name="tgl_bkk">
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
                                <input type="text" value="<?= "Rp.".$bll_bkk; ?>" readonly class="form-control" name="nilai_ppn" > 
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
                    <div class="col-sm-offset-9 col-sm-3 control-label">
                    <h4> Verifikasi </h4>                    
                    <button type="button"  class="btn btn-success" data-toggle="modal" data-target="#kirim" >Kirim</button></span></a>                                                                                       
                    <button type="button"  class="btn btn-danger" data-toggle="modal" data-target="#tolak" >Revisi</button></span></a>            
                    </div>
                <!-- </div> -->
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
                            <h4 class="modal-title">Rubah Redaksi</h4>
                        </div>
                        <!-- body modal -->
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group ">                                        
                                        <div class="col-sm-4">
                                            <input type="hidden" value="<?= $row2['id_bkk']; ?>" class="form-control" name="id_bkk" readonly>
                                        </div>
                                    </div>
                                   
                                    <div class="mb-3">
                                        <label for="validationTextarea">Redaksi</label>
                                        <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required></textarea>
                                        <div class="invalid-feedback">
                                            Please enter a message in the textarea.
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

<!-- Modal Revisi -->
    <div id="tolak" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- konten modal-->
                    <div class="modal-content">
                        <!-- heading modal -->
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Alasan Penolakan </h4>
                        </div>
                        <!-- body modal -->
                        <div class="modal-body">
                            <form method="post" enctype="multipart/form-data" action="tolak_joborder.php" class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group ">
                                        
                                        <div class="col-sm-4">
                                            <input type="hidden" value="<?= $row2['id_joborder']; ?>" class="form-control" name="id_joborder" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="validationTextarea">Komentar</label>
                                        <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
                                        <div class="invalid-feedback">
                                            Please enter a message in the textarea.
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
</script>


