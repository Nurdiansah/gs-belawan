<?php  
    include "../fungsi/koneksi.php";
    include "../fungsi/fungsi.php";


        if (isset($_POST['submit'])) {
            //die($id = $_GET['id']);
            

            $id_bkk = $_POST['id_bkk'];
            $id_pph = $_POST['id_pph'];
            $nilai_baranga = $_POST['nilai_barang'];
            $nilai_barang= str_replace(".", "", $nilai_baranga);
            $nilai_jasaa = $_POST['nilai_jasa'];
            $nilai_jasa = str_replace(".", "", $nilai_jasaa);
            $ppn_persen = $_POST['ppn_persen'];
            $ppn_nilaia = $_POST['ppn_nilai'];
            $ppn_nilai = str_replace(".", "", $ppn_nilaia);
            $pph_persen = $_POST['pph_persen'];
            $pph_nilaia = $_POST['pph_nilai'];
            $pph_nilai = str_replace(".", "", $pph_nilaia);
            $jml_bkka = $_POST['jml_bkk'];
            $jml_bkk = str_replace(".", "", $jml_bkka);
            $terbilang_bkk = Terbilang($jml_bkk);

            $queryUbah = mysqli_query($koneksi, "UPDATE bkk SET nilai_barang='$nilai_barang', nilai_jasa='$nilai_jasa',
                                                ppn_persen='$ppn_persen', ppn_nilai='$ppn_nilai', pph_persen='$pph_persen',
                                                pph_nilai='$pph_nilai', id_pph='$id_pph', jml_bkk='$jml_bkk', terbilang_bkk='$terbilang_bkk', status_bkk= 4
            WHERE id_bkk ='$id_bkk' ");

            if ($queryUbah) {
            header("location:index.php?p=pjk_verifikasibno");
            } else {
            echo 'error' . mysqli_error($koneksi);
            }
            
        } 

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
                    <h3 class="text-center">Verifikasi Biaya Non OPS</h3>
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
                            $Divisi = $row2['id_divisi'];
                            $idBkk = $row2['id_bkk'];

                            $budget = $row2['januari_nominal'] + $row2['februari_nominal'] + $row2['maret_nominal'] + $row2['april_nominal'] + $row2['mei_nominal'] + $row2['juni_nominal'] + $row2['juli_nominal'] + $row2['agustus_nominal'] + $row2['september_nominal'] + $row2['oktober_nominal'] + $row2['november_nominal'] + $row2['desember_nominal'] ;
                            $realisasi = $row2['januari_realisasi'] + $row2['februari_realisasi'] + $row2['maret_realisasi'] + $row2['april_realisasi'] + $row2['mei_realisasi'] + $row2['juni_realisasi'] + $row2['juli_realisasi'] + $row2['agustus_realisasi'] + $row2['september_realisasi'] + $row2['oktober_realisasi'] + $row2['november_realisasi'] + $row2['desember_realisasi'] ;
                            $saldoAnggaranb = $budget - $realisasi;                              
                            $saldoAnggaran = 'Rp. '.number_format($saldoAnggaranb,2,",",".") ;

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
                            <label id="tes"for="jml_bkk" class="col-sm-4 control-label">Saldo Anggaran</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= $saldoAnggaran ; ?>" readonly class="form-control" name="jml_bkk">
                            </div>  
                    </div>
                    <hr>                     
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">Nilai Barang</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$nilai_barang; ?>" readonly class="form-control" >
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">Nilai Jasa</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?= "Rp.".$nilai_jasa; ?>" readonly class="form-control" >
                            </div>
                    </div>
                    <div class="form-group">
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">PPN</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?=  $row2['ppn_persen'];  ?> %" readonly class="form-control" " > 
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes"for="nilai_bkk" class="col-sm-2 control-label">PPh</label>
                            <div class="col-sm-3">
                                <input type="text" value="<?=  $row2['pph_persen'];  ?> %" readonly class="form-control"  > 
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
                                              
                </form>
                </div>
                
                <hr>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Tax</h3>
                </div>

                <div class="perhitungan">

                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                            <input type="hidden" name="id_bkk" value="<?= $idBkk?>">
                            <div class="form-group">
                                <label id="tes"for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Barang</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nilai_barang"  id="nilai_barang"  />
                                    </div>                                    
                                </div>  
                            </div>
                            <div class="form-group">
                                <label id="tes"for="nilai_bkk" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah">Nilai Jasa</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nilai_jasa"  id="nilai_jasa" />
                                    </div>                                    
                                </div>  
                            </div>
                            <div class="form-group">
                                <label id="tes"for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">PPN</span>
                                        <input type="text"  required  min="0" max="10" class="form-control " name="ppn_persen"  value =0 id="ppn_persen"  />
                                        <span class="input-group-addon">%</span>
                                    </div>                                    
                                </div>                                                            
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  readonly class="form-control " name="ppn_nilai"  id="ppn_nilai" />                                        
                                    </div>                                    
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label id="tes"for="id_pph" class="col-sm-offset-1 col-sm-3 control-label">Jenis PPh</label>
                                <div class="col-sm-2">
                                <select name="id_pph" class="form-control" >
                                        <option value="">--Jenis PPh--</option>
                                        <?php
                                            $queryPph = mysqli_query($koneksi,"SELECT * FROM pph ORDER BY nm_pph ASC");
                                            if (mysqli_num_rows($queryPph)) {
                                                while ($rowPph = mysqli_fetch_assoc($queryPph)) :
                                        ?>
                                        <option value="<?= $rowPph['id_pph']; ?>" type="checkbox"><?= $rowPph['nm_pph'] ?></option>
                                        <?php endwhile; } ?>
                                    </select>
                                </div>  
                            </div>
                            <div class="form-group">
                                <label id="tes"for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">PPh</span>
                                        <input type="text"  required class="form-control " name="pph_persen"  value =0 id="pph_persen"  />
                                        <span class="input-group-addon">%</span>
                                    </div>                                    
                                </div>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  readonly class="form-control " name="pph_nilai"  id="pph_nilai" />
                                    </div>                                    
                                </div>                            
                            </div>
                            <div class="col-auto">
                            <div class="form-group">
                                <label id="tes"for="jml_bkk" class="col-sm-offset-1 col-sm-3 control-label">Jumlah</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" name="jml_bkk" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-primary col-sm-offset-9 " value="Submit" > 
                                &nbsp;
                                <button type="button"  class="btn btn-danger" data-toggle="modal" data-target="#tolak" >Reject </button></span></a>                                                                                                                     
                            </div>
                        </div>
                    </form>
                </div>

                <hr>
                <br>

                
                <hr>            
                                
                <hr>

                                
               <!-- Embed Document               -->
               <!-- Document PTW -->
                    <div class="box-header with-border">
                    <h3 class="text-center">Invoice </h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/<?php echo $row2['invoice']; ?> "></iframe>
                    </div>
                                                                  
                    <br>
                    <br>
                    
                <!-- </div> -->
                </div>   
            </div>
        </div>
    </div>

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
                            <form method="post" enctype="multipart/form-data" action="tolak_biayanonops.php" class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group ">
                                        
                                        <div class="col-sm-4">
                                            <input type="hidden" value="<?= $row2['id_bkk']; ?>" class="form-control" name="id_bkk" readonly>
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
                                        <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>            
                                        <!-- <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-1 " value="kirim" >  -->
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

    $(".perhitungan").keyup(function(){
            

            var nilaiJasa = parseInt($("#nilai_jasa").val())
            var pph_persen = parseInt($("#pph_persen").val())
            var pph_nilai = nilaiJasa*pph_persen/100;
            var pph_nilaia = tandaPemisahTitik(pph_nilai);
            $("#pph").attr("value",pph_nilaia); 
            document.form.pph_nilai.value = pph_nilaia; 



            var nilaiBarang = parseInt($("#nilai_barang").val())
            var ppn_persen = parseInt($("#ppn_persen").val())
            var ppn_nilai = (nilaiJasa+nilaiBarang)*ppn_persen/100;
            var ppn_nilaia = tandaPemisahTitik(ppn_nilai);
            $("#ppn").attr("value",ppn_nilaia); 
            document.form.ppn_nilai.value = ppn_nilaia; 


            var jmla = nilaiBarang+nilaiJasa+ppn_nilai-pph_nilai;
            var jml= tandaPemisahTitik(jmla);
            $("#jml").attr("value",jml);    
            document.form.jml_bkk.value = jml;  

    });

    
</script>


