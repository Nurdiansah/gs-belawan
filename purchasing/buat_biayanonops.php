<?php  



    include "../fungsi/koneksi.php";

        $queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $Area=$rowUser['area'];
        $Divisi=$rowUser['id_divisi'];

        $tanggalCargo=date("Y-m-d");
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
                    <h3 class="text-center">Biaya Umum Non OPS</h3>
                </div>

                <form method="post" name="form" action="add_biayanonops.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">                                                            
                    <div class="form-group">
                            <label id="tes"for="nm_vendor" class="col-sm-offset-1 col-sm-3 control-label">Dibayarkan Kepada</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control is-valid"  name="nm_vendor" placeholder="Input Nama Vendor">
                            </div>

                    </div>
					<div class="form-group">
                            <label for="tgl_bkk" class="col-sm-offset-1 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control tanggal" name="tgl_pengajuan"  value="<?=$tanggalCargo ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes"for="keterangan" class="col-sm-offset-1 col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="keterangan">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label id="tes"for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-4">
                            <select  class="form-control select2" name="id_anggaran" >
                                    <option value="">--Kode Anggaran--</option>
                                    <?php
                                        $queryAnggaran = mysqli_query($koneksi,"SELECT * FROM anggaran WHERE id_divisi ='$Divisi' ORDER BY nm_item ASC");
                                        if (mysqli_num_rows($queryAnggaran)) {
                                            while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                    <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'].' '. $rowAnggaran['nm_item']; ?></option>
                                    <?php endwhile; } ?>
                                </select>
                            </div>  
                        </div>
                        <div class="perhitungan">
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
                        </div>
                        <div class="form-group">
                            <label id="tes"for="bank_tujuan" class="col-sm-offset-1 col-sm-3 control-label">Bank Tujuan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="bank_tujuan">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label id="tes"for="norek_tujuan" class="col-sm-offset-1 col-sm-3 control-label">No Rekening</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="norek_tujuan">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label id="tes"for="penerima_tujuan" class="col-sm-offset-1 col-sm-3 control-label">Nama Penerima</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="penerima_tujuan">
                            </div>  
                        </div>
                         <div class="form-group">
                            <label for="invoice" class="col-sm-offset-1 col-sm-3 control-label">Invoice</label>
                            <div class="col-sm-4">
                                <div class="input-group input-file" name="invoice">
                                    <input type="text" class="form-control" required />			
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-4 " value="Buat" > 
                            &nbsp;
                            <input type="reset" class="btn btn-danger" value="Batal">                                                                              
                        </div>
                    </div>
                </form>

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

            // var nilaia = tandaPemisahTitik(nilai);
            // $("#nilai").attr("value",nilaia) 
            // var ppn = parseInt($("#ppn").val())
            // var bll = parseInt($("#bll_bkk").val())
            // var ppna =nilai*ppn/100; 
            // var jml = nilai + ppna + bll ;
            // var jmlb = Math.floor( jml);
            // var jmla = tandaPemisahTitik(jmlb);
            // $("#jml").attr("value",jmla);    
            // document.form.jml_bkk.value = jmla;        
    });
    


    // onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" 

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