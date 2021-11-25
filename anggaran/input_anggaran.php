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
                                        $querysubgolongan = mysqli_query($koneksi,"SELECT * FROM sub_golongan ORDER BY nm_subgolongan ASC");
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
                                        <input type="text"  required class="form-control " name="harga"  id="harga_nominal" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" />
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
                                    <label for="">All </label> <input type="checkbox" name="all" id="myCheck"  onclick="checkBox()">
                                </div>                                  
                            <!-- </div>
                            <div class="form-group"> -->
                                <label id="tes"for="nominal_januari" class="col-sm-offset- col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp.</span>
                                        <input type="text"  class="form-control"  name="nominal_januari" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_februari" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_maret" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_april" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_mei" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_juni" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_juli" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_agustus" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_september" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_oktober" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_november" readonly />
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
                                        <input type="text"  required class="form-control" name="nominal_desember" readonly />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-auto">
                            <div class="form-group">
                                <label id="tes"for="jml_bkk" class="col-sm-offset- col-sm-2 control-label">Jumlah Kuantitas</label>
                                <div class="col-sm-3">
                                        <input type="text"  required class="form-control" name="jml_kuantitas" readonly />                                    
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

            //ambil inputan kuantitas januari
            var jk = parseInt($("#januari_kuantitas").val())
            var nj = jk*harga;
            var nja = tandaPemisahTitik(nj);
            $("#januari").attr("value",nja);    
            document.form.nominal_januari.value = nja;

            //ambil inputan kuantitas februari
            var fk = parseInt($("#februari_kuantitas").val())
            var nf = fk*harga;
            var nfa = tandaPemisahTitik(nf);
            $("#nominal").attr("value",nfa);    
            document.form.nominal_februari.value = nfa;

            //ambil inputan kuantitas maret
            var mk = parseInt($("#maret_kuantitas").val())
            var nm = mk*harga;
            var nma = tandaPemisahTitik(nm);
            $("#nominal").attr("value",nma);    
            document.form.nominal_maret.value = nma;

            //ambil inputan kuantitas april
            var apk = parseInt($("#april_kuantitas").val())
            var nap = apk*harga;
            var napa = tandaPemisahTitik(nap);
            $("#nominal").attr("value",napa);    
            document.form.nominal_april.value = napa;

            //ambil inputan kuantitas mei
            var mek = parseInt($("#mei_kuantitas").val())
            var nme = mek*harga;
            var nmea = tandaPemisahTitik(nme);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_mei.value = nmea;

            //ambil inputan kuantitas juni
            var junk = parseInt($("#juni_kuantitas").val())
            var njun = junk*harga;
            var njuna = tandaPemisahTitik(njun);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_juni.value = njuna;

            //ambil inputan kuantitas juli
            var julk = parseInt($("#juli_kuantitas").val())
            var njul = julk*harga;
            var njula = tandaPemisahTitik(njul);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_juli.value = njula;


            //ambil inputan kuantitas agustus
            var agk = parseInt($("#agustus_kuantitas").val())
            var nag = agk*harga;
            var naga = tandaPemisahTitik(nag);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_agustus.value = naga;

            //ambil inputan kuantitas september
            var sepk = parseInt($("#september_kuantitas").val())
            var nsep = sepk*harga;
            var nsepa = tandaPemisahTitik(nsep);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_september.value = nsepa;

            //ambil inputan kuantitas oktober
            var oktk = parseInt($("#oktober_kuantitas").val())
            var nokt = oktk*harga;
            var nokta = tandaPemisahTitik(nokt);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_oktober.value = nokta;

            //ambil inputan kuantitas november
            var novk = parseInt($("#november_kuantitas").val())
            var nnov = novk*harga;
            var nnova = tandaPemisahTitik(nnov);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_november.value = nnova;

            //ambil inputan kuantitas desember
            var desk = parseInt($("#desember_kuantitas").val())
            var ndes = desk*harga;
            var ndesa = tandaPemisahTitik(ndes);
            $("#nominal").attr("value",nmea);    
            document.form.nominal_desember.value = ndesa;

            // jumlah nominal
            var jmlKuantitas = jk+fk+mk+apk+mek+junk+julk+agk+sepk+oktk+novk+desk;
            $("#jml_kuantitas").attr("value",jmlKuantitas);    
            document.form.jml_kuantitas.value = jmlKuantitas;

            // jumlah nominal
            var jml_nominal = harga*jmlKuantitas;
            var jml_nominala = tandaPemisahTitik(jml_nominal);
            document.form.jml_nominal.value = jml_nominala;

    });

    function checkBox() {
        var checkBox = document.getElementById("myCheck");    
        if (checkBox.checked == true){

            var harga = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('harga_nominal').value))))); //input ke dalam angka tanpa titik

            //ambil inputan kuantitas januari
            var jk = parseInt($("#januari_kuantitas").val()) ;
            var nj = jk*harga;
            var nja = tandaPemisahTitik(nj);
            $("#januari").attr("value",nja); 
            
            var totalQty = 12 * jk;
            var totalNominal = tandaPemisahTitik(12 * nj);

            //masukan ke semua kolom bulan
            document.form.februari_kuantitas.value = jk;
            document.form.nominal_februari.value = nja;    
            
            //masukan ke semua kolom maret
            document.form.maret_kuantitas.value = jk;
            document.form.nominal_maret.value = nja;    

            //masukan ke semua kolom april
            document.form.april_kuantitas.value = jk;
            document.form.nominal_april.value = nja;    

            //masukan ke semua kolom mei
            document.form.mei_kuantitas.value = jk;
            document.form.nominal_mei.value = nja;    

            //masukan ke semua kolom juni
            document.form.juni_kuantitas.value = jk;
            document.form.nominal_juni.value = nja;    

            //masukan ke semua kolom juli
            document.form.juli_kuantitas.value = jk;
            document.form.nominal_juli.value = nja;    

            //masukan ke semua kolom agustus
            document.form.agustus_kuantitas.value = jk;
            document.form.nominal_agustus.value = nja;    

            //masukan ke semua kolom september
            document.form.september_kuantitas.value = jk;
            document.form.nominal_september.value = nja;    

            //masukan ke semua kolom oktober
            document.form.oktober_kuantitas.value = jk;
            document.form.nominal_oktober.value = nja;    

            //masukan ke semua kolom november
            document.form.november_kuantitas.value = jk;
            document.form.nominal_november.value = nja;    

            //masukan ke semua kolom desember
            document.form.desember_kuantitas.value = jk;
            document.form.nominal_desember.value = nja;  

            //masukan ke semua kolom desember
            document.form.jml_kuantitas.value = totalQty;
            document.form.jml_nominal.value = totalNominal; 
            

        } else {
            text.style.display = "none";
        }
    }


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