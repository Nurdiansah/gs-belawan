<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['cetak'])) {
    if ($_POST['jenis'] == 'Detail') {
        header('Location: cetak_pk_dtl.php?jenis=' . $_POST['jenis'] . '&tahun=' . $_POST['tahun'] . '&program_kerja=' . $_POST['program_kerja'] . '');
    } elseif ($_POST['jenis'] == 'COA') {
        header('Location: cetak_pk_coa.php?jenis=' . $_POST['jenis'] . '&tahun=' . $_POST['tahun'] . '&program_kerja=' . $_POST['program_kerja'] . '');
    }
}

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
                    <li role="presentation" class="<?= $sp == "lr_01" ? 'active' : ''; ?>"><a href="index.php?p=laporan_lr&sp=lr_01">Entitas <span class="badge label-primary">LR 01</span></a></li>
                    <li role="presentation" class="<?= $sp == "lr_02" ? 'active' : ''; ?>"><a href=" index.php?p=laporan_lr&sp=lr_02">Project <span class="badge label-primary">LR 02</span></a></li>
                    <li role="presentation" class="<?= $sp == "lr_03" ? 'active' : ''; ?>"><a href=" index.php?p=laporan_lr&sp=lr_03">Divisi <span class="badge label-primary">LR 03</span></a></li>
                    <li role="presentation" class="<?= $sp == "lr_04" ? 'active' : ''; ?>"><a href=" index.php?p=laporan_lr&sp=lr_04">Sub Divisi <span class="badge label-primary">LR 04</span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Laporan Laba Rugi</h3>
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
</script>