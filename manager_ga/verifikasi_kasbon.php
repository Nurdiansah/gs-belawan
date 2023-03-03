<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=verifikasi_dkasbon&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$sp = $_GET['sp'];

?>
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
                    <li role="presentation" class="<?php echo $sp == "vk_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=verifikasi_kasbon&sp=vk_purchasing">Kasbon Material Request <span class="badge label-warning"><?php echo $dataKV1['jumlah'] >= 1 ? $dataKV1['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "vk_sr" ? 'active' : ''; ?>"><a href=" index.php?p=verifikasi_kasbon&sp=vk_sr">Kasbon Service Request <span class="badge label-warning"><?php echo $dataKV3['jumlah'] >= 1 ? $dataKV3['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "vk_user" ? 'active' : ''; ?>"><a href=" index.php?p=verifikasi_kasbon&sp=vk_user">Kasbon User <span class="badge label-warning"><?php echo $dataKV2['jumlah'] >= 1 ? $dataKV2['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi Kasbon</h3>
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
        $("#selectall").click(function() {
            $('.case').attr('checked', this.checked);
        });

        // if all checkbox are selected, check the selectall checkbox
        // and viceversa
        $(".case").click(function() {

            if ($(".case").length == $(".case:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }

        });
    });
</script>