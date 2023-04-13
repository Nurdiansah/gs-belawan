<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahunAyeuna = date("Y");
$sp = $_GET['sp'];

?>

<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <ul class="nav nav-tabs">
                    <li role="presentation" class="<?= $sp == "rk_01" ? 'active' : ''; ?>"><a href="index.php?p=laporan_rk&sp=rk_01">Entitas <span class="badge label-success">RK 01</span></a></li>
                    <li role="presentation" class="<?= $sp == "rk_02" ? 'active' : ''; ?>"><a href=" index.php?p=laporan_rk&sp=rk_02">Project <span class="badge label-success">RK 02</span></a></li>
                    <li role="presentation" class="<?= $sp == "rk_03" ? 'active' : ''; ?>"><a href=" index.php?p=laporan_rk&sp=rk_03">Divisi <span class="badge label-success">RK 03</span></a></li>
                    <li role="presentation" class="<?= $sp == "rk_04" ? 'active' : ''; ?>"><a href=" index.php?p=laporan_rk&sp=rk_04">Sub Divisi <span class="badge label-success">RK 04</span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Laporan Rencana Kerja</h3>
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

<!-- <script>
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });
</script> -->