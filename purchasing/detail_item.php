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
        $Divisi=$rowUser['id_divisi'];

        $queryDetail =  mysqli_query($koneksi, "SELECT * FROM detail_biayaops WHERE id=$id ");
        $data=mysqli_fetch_assoc($queryDetail);

        $tanggalCargo=date("Y-m-d");

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">                        
                        <br><br>
                </div> 
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Item</h3>
                </div>                                
                <form method="post" name="form" action="#" enctype="multipart/form-data" class="form-horizontal">
                        <div class="box-body">                                               
                        <div class="form-group">
                                <label id="tes"for="nm_barang" class="col-sm-offset col-sm-2 control-label">Nama Barang</label>
                                <input type="hidden" disabled class="form-control is-valid"  name="id" value="<?=$data['id']; ?>">
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid"  name="nm_barang" value="<?=$data['nm_barang']; ?>">
                                </div>
                            <!-- </div>
                            <div class="form-group"> -->
                                <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                                    <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid"  name="id_anggaran" value="<?=$data['id_anggaran']; ?>">                                
                                    </div>                            
                        </div>       
                        <div class="form-group">
                                <label id="tes"for="merk" class="col-sm-offset col-sm-2 control-label">Merk </label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid"  name="merk" value="<?=$data['merk']; ?>">
                                </div>
                            <!-- </div>
                            <div class="form-group"> -->
                                <label for="type" class="col-sm-offset- col-sm-2 control-label">Type</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled class="form-control " name="type"  value="<?=$data['type']; ?>">
                                    </div>                            
                        </div>                 
                        <div class="form-group">
                                <label id="tes"for="jumlah" class="col-sm-offset col-sm-2 control-label">Jumlah</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid"  name="jumlah" value="<?=$data['jumlah']; ?>">
                                </div>
                            <!-- </div>
                            <div class="form-group"> -->
                                <label for="satuan" class="col-sm-offset- col-sm-2 control-label">Satuan</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled class="form-control " name="satuan"  value="<?=$data['satuan']; ?>">
                                    </div>                            
                        </div>
                        <div class="form-group">
                                <label id="tes"for="satuan" class="col-sm-offset col-sm-2 control-label">Spesifikasi</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid"  name="spesifikasi" value="<?=$data['spesifikasi']; ?>">
                                </div>
                            <!-- </div>
                            <div class="form-group"> -->
                                <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="keterangan" disabled class="form-control " > <?=$data['keterangan']; ?></textarea>
                                    </div>                            
                        </div>  
                        <br>
                   
                    </div>
                </form>

              
                <!-- Akhir Modal Tambah  -->

            </div>
        </div>
    </div>
</section>

<script>

</script>