<?php  
    include "../fungsi/koneksi.php";
    include "../fungsi/fungsi.php";
    
    if($_POST['rowid']) {
        $id = $_POST['rowid'];
        // mengambil data berdasarkan id
        $query2 = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran='$id' ");
        if (mysqli_num_rows($query2)) {
            while($row2=mysqli_fetch_assoc($query2)): ?>
            
            <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                                                        <div class="box-body">                                                                                                                			
                                                            <div class="form-group">
                                                                <label id="tes"for="no_coa" class="col-sm-offset- col-sm-2 control-label">Nomor Coa</label>
                                                                <div class="col-sm-3">
                                                                    <input type="text" class="form-control" name="no_coa" value="<?= $row2['no_coa']; ?>" disabled>
                                                                </div>  
                                                            <!-- </div>
                                                            <div class="form-group"> -->
                                                                <label id="tes"for="kd_transaksi" class="col-sm-offset- col-sm-2 control-label">Kode Transaksi</label>
                                                                <div class="col-sm-3">
                                                                    <input type="text" class="form-control" name="kd_transaksi"  value="<?= $row2['kd_transaksi']; ?>" disabled>
                                                                </div>  
                                                            </div>
                                                            <br>
                                                            <br>
                                                            <div class="form-group">
                                                                <label id="tes"for="id_golongan" class="col-sm-offset- col-sm-2 control-label">Golongan</label>
                                                                <div class="col-sm-3">
                                                                    <input type="text" class="form-control" name="id_golongan" value="<?= $row2['nm_golongan']; ?>" disabled>
                                                                </div>  
                                                            <!-- </div>
                                                            <div class="form-group"> -->
                                                                <label id="tes"for="id_subgolongan" class="col-sm-offset- col-sm-2 control-label">Sub Golongan</label>
                                                                <div class="col-sm-3">
                                                                    <input type="text" class="form-control" name="id_subgolongan"  value="<?= $row2['nm_subgolongan']; ?>" disabled>
                                                                </div>  
                                                            </div>
                                                            <br>                                                            
                                                            <div class="perhitungan">
                                                            <div class="form-group">
                                                                <label id="tes" for="nm_item" class="col-sm-offset- col-sm-2 control-label">Deskripsi</label>
                                                                <div class="col-sm-3">
                                                                    <input type="text" class="form-control" name="nm_item" value="<?= $row2['nm_item']; ?>" disabled>
                                                                </div>  
                                                            <!-- </div>
                                                            <div class="form-group"> -->
                                                                    <label id="tes"for="harga" class="col-sm-offset- col-sm-2 control-label" id="hargal">Harga</label>
                                                                    <div class="col-sm-3">                                                                    
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">Rp.</span>
                                                                            <input type="text"  required class="form-control " name="harga" value="<?= $row2['harga']; ?>" disabled/>
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

        <?php endwhile; } 
    }
    $koneksi->close();
?>
                                                    
                                                    
                                                    
                                                    