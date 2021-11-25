<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryKasbon = mysqli_query($koneksi, "SELECT *, k.komentar as k_komentar  FROM kasbon k
                                        INNER JOIN sr sr
                                            ON id_sr = sr_id
                                        INNER JOIN anggaran a
                                            ON sr.id_anggaran = a.id_anggaran
                                        WHERE status_kasbon IN ('303', '404', '0')
                                        AND from_user = '0'
                                        ORDER BY id_kasbon DESC");
$totalKasbon = mysqli_num_rows($queryKasbon);

$querySO =  mysqli_query($koneksi, "SELECT * FROM so s
                                               JOIN anggaran a
                                               ON a.id_anggaran = s.id_anggaran 
                                               WHERE s.status IN ('303', '404')
                                               ");


$tahun = date("Y");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_so&id=$id&pg=" . $_GET['page'] . "");
    } else if ($_GET['aksi'] == 'release') {
        header("location:rls_sr.php?id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:del_sr.php?id=$id");
    }
}

$sp = $_GET['sp'];

$no = 1;

?>

<section class="content-header">
    <h1>
        SR Ditolak
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">SR Ditolak</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <ul class="nav nav-tabs">
                    <li role="presentation" class="<?php echo $sp == "ditolak_kasbon_sr" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_sr&sp=ditolak_kasbon_sr">Kasbon <span class="badge label-warning"><?php echo $dataTolakKSR['jumlah'] > 0 ? $dataTolakKSR['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "ditolak_so" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_sr&sp=ditolak_so">Service Order <span class="badge label-warning"><?php echo $dataTolakSO['jumlah'] > 0 ? $dataTolakSO['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">SR Ditolak</h3>
                </div>
                <div class="box-body">
                    <!-- Body -->
                    <?php include "sub_page.php"; ?>
                    <!-- End Body -->
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    $(function() {

        // add multiple select / deselect functionality
        $("#selectall2").click(function() {
            $('.case2').attr('checked', this.checked);
        });

        // if all checkbox are selected, check the selectall checkbox
        // and viceversa
        $(".case2").click(function() {

            if ($(".case2").length == $(".case2:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }

        });
    });

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

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' accept='application/pdf' style='visibility:hidden; height:0'>");
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