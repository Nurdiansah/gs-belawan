<?php  
    include "../fungsi/koneksi.php";
    include "../fungsi/fungsi.php";

        $id = $_GET['id'];

        $queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
	    $rowNama=mysqli_fetch_assoc($queryNama);
        $Nama=$rowNama['nama'];

        $queryBkk = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.id_bkk = '$id' ");
    
?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                        <br><br>
                </div>

                <!-- Detail Job Order -->
                
                <div class="box-header with-border">
                    <h3 class="text-center">Biaya Umum Non OPS</h3>
                </div>
                <?php 
                        if (mysqli_num_rows($queryBkk)) {
                            while($row2=mysqli_fetch_assoc($queryBkk)):
                            // query Total_cargo
                            $nilai_barang = number_format($row2['nilai_barang'],0,",",".");
                            $nilai_jasa = number_format($row2['nilai_jasa'],0,",",".");
                            $ppn_nilai = number_format($row2['ppn_nilai'],0,",",".");
                            $pph_nilai = number_format($row2['pph_nilai'],0,",",".");
                            $jml_bkk = number_format($row2['jml_bkk'],0,",",".");
                            $bll_bkk = number_format($row2['bll_bkk'],0,",",".");

                        ?>
                
                
                
                <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">                        
                    <div class="box-body">
                    
                    <div class="form-group ">
                            <label for="id_joborder" class=" col-sm-2 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['kd_transaksi']; ?>" disabled class="form-control" name="id_bkk" >
                            </div>
                    <!-- </div>
                    <div class="form-group "> -->
                            <label id="tes"for="tgl_bkk" class=" col-sm-2 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['tgl_pengajuan']; ?>" disabled class="form-control" name="tgl_bkk">
                            </div>
                    </div>
                    <div class="form-group">
                        <label id="tes"for="nm_vendor"  class=" col-sm-2 control-label">Nama Vendor</label>
                            <div class="col-sm-3">
                                <input type="text"  value="<?= $row2['nm_vendor']; ?>" disabled class="form-control" name="nm_vendor">
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label for="kd_transaksi" class="col-sm-2 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['kd_anggaran']; ?>" class="form-control " name="kd_transaksi" readonly>
                            </div>                            
                    </div>
                    <div class="form-group">
                            <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['keterangan']; ?>" class="form-control " name="keterangan" readonly>
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label for="terbilang_bkk" class=" col-sm-2 control-label">Terbilang</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $row2['terbilang_bkk'].' Rupiah'; ?>" disabled class="form-control tanggal" name="terbilang_bkk">
                            </div>
                    </div>
                    <hr>
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">Nilai Barang</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$nilai_barang; ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">Nilai Jasa</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$nilai_jasa; ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                    </div>
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">PPN</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?=  $row2['ppn_persen'];  ?> %" readonly class="form-control" name="nilai_ppn" > 
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">PPh</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?=  $row2['pph_persen'];  ?> %" readonly class="form-control" name="nilai_ppn" > 
                            </div>
                    </div>
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">Nilai PPN</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$ppn_nilai; ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">Nilai PPh</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$pph_nilai; ?>" readonly class="form-control" name="nilai_bkk">
                            </div>
                    </div>
                    <hr>
                    <div class="form-group">
                            <label id="tes"for="jml_bkk" class="col-sm-4 control-label">Jumlah</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$jml_bkk; ?>" readonly class="form-control" name="jml_bkk">
                            </div>  
                    </div>
                    <hr>
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


