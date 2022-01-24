<?php  



    include "../fungsi/koneksi.php";

    $queryUser =  mysqli_query($koneksi, "SELECT area from user WHERE username  = '$_SESSION[username]'");
	    $rowUser=mysqli_fetch_assoc($queryUser);
        $Area=$rowUser['area'];


        date_default_timezone_set('Asia/Jakarta');
        $waktuSekarang = date('d-m-Y H:i:s') ;
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                        <!-- <div class="col-md-2">
                            <a href="index.php?p=lihat_kaskeluar" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                        <br><br>
                </div> 
                <div class="box-header with-border">
                    <h3 class="text-center">Input Anggaran</h3>
                </div>                
                <form method="post" name="form" action="add_anggaran.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                    <div class="form-group">
                            <label id="tes"for="divisi" class="col-sm-offset-1 col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <select name="id_divisi" class="form-control" >
                                    <option value="">--Pilih Divisi--</option>
                                    <?php
                                        $queryDivisi = mysqli_query($koneksi,"SELECT * FROM divisi ORDER BY nm_divisi ASC");
                                        if (mysqli_num_rows($queryDivisi)) {
                                            while ($rowDivisi = mysqli_fetch_assoc($queryDivisi)) :
                                    ?>
                                    <option value="<?= $rowDivisi['id_divisi']; ?>" type="checkbox"><?= $rowDivisi['nm_divisi']; ?></option>
                                    <?php endwhile; } ?>
                                </select>
                            </div>
                    <!-- </div>
                    <div class="form-group"> -->
                            <label id="tes"for="tahun" class="col-sm-2 control-label">Anggaran Tahun</label>
                            <div class="col-sm-3">
                                <select name="tahun" class="form-control" >
                                    <option value="">--Tahun--</option>
                                    <?php
                                        $querytahun = mysqli_query($koneksi,"SELECT * FROM tahun");
                                        if (mysqli_num_rows($querytahun)) {
                                            while ($rowtahun = mysqli_fetch_assoc($querytahun)) :
                                    ?>
                                    <option value="<?= $rowtahun['nm_tahun']; ?>" type="checkbox"><?= $rowtahun['nm_tahun']; ?></option>
                                    <?php endwhile; } ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="waktu" value="<?php echo $waktuSekarang;?>" >			
                        <div class="form-group">
                            <label id="tes"for="no_coa" class="col-sm-2 control-label">Nomor Coa</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control" name="no_coa">
                            </div>  
                        <!-- </div>
                        <div class="form-group"> -->
                            <label id="tes"for="kd_anggaran" class="col-sm-2 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="kd_anggaran">
                            </div>  
                        </div>
                        <div class="form-group">
                            <label id="tes"for="id_golongan" class="col-sm-offset-1 col-sm-1 control-label">Golongan</label>
                            <div class="col-sm-3">
                                <select name="id_golongan" class="form-control" >
                                    <option value="">--Pilih Golongan--</option>
                                    <?php
                                        $querygolongan = mysqli_query($koneksi,"SELECT * FROM golongan");
                                        if (mysqli_num_rows($querygolongan)) {
                                            while ($rowgolongan = mysqli_fetch_assoc($querygolongan)) :
                                    ?>
                                    <option value="<?= $rowgolongan['id_golongan']; ?>" type="checkbox"><?= $rowgolongan['nm_golongan']; ?></option>
                                    <?php endwhile; } ?>
                                </select>
                            </div>
                        <!-- </div>
                        <div class="form-group"> -->
                            <label id="tes"for="id_subgolongan" class="col-sm-offset-0 col-sm-2 control-label">Sub Golongan</label>
                            <div class="col-sm-3">
                                <select name="id_subgolongan" class="form-control" >
                                    <option value="">--Pilih Sub Golongan--</option>
                                    <?php
                                        $querysubgolongan = mysqli_query($koneksi,"SELECT * FROM sub_golongan");
                                        if (mysqli_num_rows($querysubgolongan)) {
                                            while ($rowsubgolongan = mysqli_fetch_assoc($querysubgolongan)) :
                                    ?>
                                    <option value="<?= $rowsubgolongan['id_subgolongan']; ?>" type="checkbox"><?= $rowsubgolongan['nm_subgolongan']; ?></option>
                                    <?php endwhile; } ?>
                                </select>
                            </div>
                        </div>
                        <div class="perhitungan">
                        <div class="form-group">
                            <label id="tes" for="nm_item" class="col-sm-offset-1 col-sm-1 control-label">Deskripsi</label>
                            <div class="col-sm-3">
                                <input type="text" required class="form-control" name="nm_item">
                            </div>  
                        <!-- </div>
                        <div class="form-group"> -->
                                <label id="tes"for="harga" class="col-sm-offset-1 col-sm-1 control-label" id="hargal">Harga</label>
                                <div class="col-sm-3">
                                
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="harga"  id="harga_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  />
                                    </div>                                    
                                </div>  
                            </div>
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="Quantity" class="col-sm-offset-1 col-sm-3 control-label">Quantity</label>
                            <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes"for="nominal_januari" class="col-sm-offset-1 col-sm-4 control-label">Nominal</label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="januari_kuantitas" class="col-sm-offset- col-sm-2 control-label">Januari </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0" name="januari_kuantitas" id="januari_kuantitas" >
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes"for="nominal_januari" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  class="form-control"  value="0" name="nominal_januari"  id="nominal_januari" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="februari_kuantitas" class="col-sm-offset- col-sm-2 control-label">Februari </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="februari_kuantitas" id="februari_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_februari" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_februari" id="nominal_februari"   onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="maret_kuantitas" class="col-sm-offset- col-sm-2 control-label">Maret </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="maret_kuantitas" id="maret_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_maret" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_maret" id="nominal_maret" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="april_kuantitas" class="col-sm-offset- col-sm-2 control-label">April </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="april_kuantitas" id="april_kuantitas"> 
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_april" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_april"  id="nominal_april" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="mei_kuantitas" class="col-sm-offset- col-sm-2 control-label">Mei </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="mei_kuantitas" id="mei_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_mei" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control"  value="0" name="nominal_mei" id="nominal_mei" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"   />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="juni_kuantitas" class="col-sm-offset- col-sm-2 control-label">Juni </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="juni_kuantitas" id="juni_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_juni" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_juni" id="nominal_juni" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"   />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="juli_kuantitas" class="col-sm-offset- col-sm-2 control-label">Juli </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="juli_kuantitas" id="juli_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_juli" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_juli" id="nominal_juli" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"   />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="agustus_kuantitas" class="col-sm-offset- col-sm-2 control-label">Agustus </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="agustus_kuantitas" id="agustus_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_agustus" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_agustus" id="nominal_agustus" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"   />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="september_kuantitas" class="col-sm-offset- col-sm-2 control-label">September </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="september_kuantitas" id="september_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_september" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_september" id="nominal_september" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"   />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="oktober_kuantitas" class="col-sm-offset- col-sm-2 control-label">Oktober </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="oktober_kuantitas" id="oktober_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_oktober" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_oktober" id="nominal_oktober" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"   />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="november_kuantitas" class="col-sm-offset- col-sm-2 control-label">November </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="november_kuantitas" id="november_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_november" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_november" id="nominal_november" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="desember_kuantitas" class="col-sm-offset- col-sm-2 control-label">Desember </label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" value="0" min="0"  name="desember_kuantitas" id="desember_kuantitas">
                                </div>  
                            <!-- </div>
                            <div class="form-group"> -->
                            <label id="tes"for="nominal_desember" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">   
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" value="0" name="nominal_desember" id="nominal_desember" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"   />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-auto">
                            <div class="form-group">
                                <label id="tes"for="jml_bkk" class="col-sm-offset- col-sm-2 control-label">Jumlah Kuantitas</label>
                                <div class="col-sm-3">
                                        <input type="text"  required class="form-control" name="jml_kuantitas"  />                                    
                                </div>
                            <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes"for="jml_bkk" class="col-sm-offset- col-sm-2 control-label">Jumlah Nominal </label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  required class="form-control" name="jml_nominal" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <input type="submit" name="simpan" class="btn btn-primary col-sm-offset-4 " value="Tambah" > 
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

            //ambil inputan harga
            // var harga = parseInt($("#harga_nominal").val())

            var harga = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('harga_nominal').value))))); //input ke dalam angka tanpa titik

            // nominal januari
            var nominal_januari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_januari').value))))); //input ke dalam angka tanpa titik

            // nominal februari
            var nominal_februari = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_februari').value))))); //input ke dalam angka tanpa titik
            
            // nominal maret
            var nominal_maret = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_maret').value))))); //input ke dalam angka tanpa titik

            // nominal april
            var nominal_april = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_april').value))))); //input ke dalam angka tanpa titik

            // nominal mei
            var nominal_mei = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_mei').value))))); //input ke dalam angka tanpa titik

            // nominal juni
            var nominal_juni = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_juni').value))))); //input ke dalam angka tanpa titik

            // nominal juli
            var nominal_juli = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_juli').value))))); //input ke dalam angka tanpa titik

            // nominal agustus
            var nominal_agustus = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_agustus').value))))); //input ke dalam angka tanpa titik

            // nominal september
            var nominal_september = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_september').value))))); //input ke dalam angka tanpa titik

            // nominal oktober
            var nominal_oktober = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_oktober').value))))); //input ke dalam angka tanpa titik

            // nominal november
            var nominal_november = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_november').value))))); //input ke dalam angka tanpa titik

            // nominal desember
            var nominal_desember = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal_desember').value))))); //input ke dalam angka tanpa titik

            //ambil inputan kuantitas januari
            var jk = parseInt($("#januari_kuantitas").val())         

            //ambil inputan kuantitas februari
            var fk = parseInt($("#februari_kuantitas").val())            

            //ambil inputan kuantitas maret
            var mk = parseInt($("#maret_kuantitas").val())        

            //ambil inputan kuantitas april
            var apk = parseInt($("#april_kuantitas").val())


            //ambil inputan kuantitas mei
            var mek = parseInt($("#mei_kuantitas").val())


            //ambil inputan kuantitas juni
            var junk = parseInt($("#juni_kuantitas").val())


            //ambil inputan kuantitas juli
            var julk = parseInt($("#juli_kuantitas").val())



            //ambil inputan kuantitas agustus
            var agk = parseInt($("#agustus_kuantitas").val())


            //ambil inputan kuantitas september
            var sepk = parseInt($("#september_kuantitas").val())


            //ambil inputan kuantitas oktober
            var oktk = parseInt($("#oktober_kuantitas").val())


            //ambil inputan kuantitas november
            var novk = parseInt($("#november_kuantitas").val())


            //ambil inputan kuantitas desember
            var desk = parseInt($("#desember_kuantitas").val())


            // jumlah nominal
            var jmlKuantitas = jk+fk+mk+apk+mek+junk+julk+agk+sepk+oktk+novk+desk;
            $("#jml_kuantitas").attr("value",jmlKuantitas);    
            document.form.jml_kuantitas.value = jmlKuantitas;

            // jumlah nominal
            var jml_nominal = nominal_januari + nominal_februari + nominal_maret + nominal_april + nominal_mei + nominal_juni + nominal_juli + nominal_agustus + nominal_september + nominal_oktober + nominal_november + nominal_desember ;
            var jml_nominala = tandaPemisahTitik(jml_nominal);
            document.form.jml_nominal.value = jml_nominala;

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