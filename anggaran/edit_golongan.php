<?php
include "../fungsi/fungsi.php";
include "../fungsi/koneksi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT area from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];

$queryGolongan =  mysqli_query($koneksi, "SELECT * from golongan WHERE id_golongan  = '$id'");
$rowGolongan = mysqli_fetch_assoc($queryGolongan);


date_default_timezone_set('Asia/Jakarta');
$waktuSekarang = date('d-m-Y H:i:s');
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
                <form method="post" name="form" action="rubah_golongan.php" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="id_golongan" value="<?= $id ?>">
                        <div class="form-group">
                            <label id="tes" for="nm_golongan" class="col-sm-5 control-label">Nama Golongan</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="nm_golongan" value="<?= $rowGolongan['nm_golongan'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="edit" class="btn btn-primary col-sm-offset-5 " value="Edit">
                            &nbsp;
                            <a href="index.php?p=golongan" class="btn btn-danger">Batal</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });
    });



    // onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" 

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function() {
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function() {
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function() {
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor", "pointer");
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