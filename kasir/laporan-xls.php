
<section class="content">
    <div class="box-header with-border">
        <h3 class="text-center">Pilih Periode BKK</h3>
    </div>
    <form method="post" enctype="multipart/form-data" action="cetak-xls.php" class="form-horizontal">
        <div class="box-body">
            <div class="form-group">
                <!-- <label id="tes" for="tgl_awal" class="col-sm-offset-0 col-sm-3 control-label">Dari Tanggal</label> -->
                <div class="col-sm-offset-4">
                    <input type="text" required class="tanggal" name="tgl_awal" autocomplete="off"> &nbsp;
                    <label> s/d </label> &nbsp;
                    <input type="text" required class="tanggal" name="tgl_akhir" autocomplete="off">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2">
                    <input type="submit" name="cetak" class="btn btn-primary col-sm-offset-4" value="Cetak"> 
                    <input type="reset" class="btn btn-danger" value="Batal">
                </div>
            </div>
        </div>
    </form>
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
</script>
