<?php  
    include "../fungsi/fungsi.php";
    include "../fungsi/koneksi.php";

    if(!isset($_GET['id'])){
        header("location:index.php");
      }

    $id = $_GET['id'];

    $queryUser =  mysqli_query($koneksi, "SELECT area from user WHERE username  = '$_SESSION[username]'");
	$rowUser=mysqli_fetch_assoc($queryUser);
    $Area=$rowUser['area'];

    $querySubGolongan =  mysqli_query($koneksi, "SELECT * from sub_golongan WHERE id_subgolongan  = '$id'");
    $rowSubGolongan = mysqli_fetch_assoc($querySubGolongan);
    

        date_default_timezone_set('Asia/Jakarta');
        $waktuSekarang = date('d-m-Y H:i:s') ;
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">                       
                        <br><br>
                </div> 
                <div class="box-header with-border">
                    <h3 class="text-center">Edit Golongan</h3>
                </div>                
                <form method="post" name="form" action="rubah_subgolongan.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">                    
                        <input type="hidden" name="id_subgolongan" value="<?= $id?>" >			
                        <div class="form-group">
                            <label id="tes"for="nm_subgolongan" class="col-sm-5 control-label">Nama Sub Golongan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="nm_subgolongan" value="<?= $rowSubGolongan['nm_subgolongan'] ?>">
                            </div>                         
                        </div>
                        <div class="form-group">                                        
                            <label id="tes"for="id_golongan" class="col-sm-offset-2 col-sm-3 control-label">Nama Golongan</label>
                            <div class="col-sm-4">
                                <select name="id_golongan" class="form-control" >
                                    <option value="<?= $rowSubGolongan['id_golongan']?>">--Pilih Golongan--</option>
                                    <?php
                                        $querygolongan = mysqli_query($koneksi,"SELECT * FROM golongan ORDER BY nm_golongan ASC");
                                        if (mysqli_num_rows($querygolongan)) {
                                            while ($rowgolongan = mysqli_fetch_assoc($querygolongan)) :
                                    ?>
                                    <option value="<?= $rowgolongan['id_golongan']; ?>" type="checkbox"><?= $rowgolongan['nm_golongan']; ?></option>
                                <?php endwhile; } ?>
                                </select>
                            </div>
                        </div>                         
                        <div class="form-group">
                            <input type="submit" name="edit" class="btn btn-primary col-sm-offset-5 " value="Edit" > 
                            &nbsp;
                            <a href="index.php?p=sub_golongan" class="btn btn-danger">Batal</a>                            
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