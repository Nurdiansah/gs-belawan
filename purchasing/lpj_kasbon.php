<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];


    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_sr&id=$id&pg=" . enkripRambo("ditolak_sr&sp=ditolak_so") . "");
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
        LPJ Kasbon
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">LPJ Kasbon</li>
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
                    <li role="presentation" class="<?php echo $sp == "lpj_kmr" ? 'active' : ''; ?>"><a href="index.php?p=lpj_kasbon&sp=lpj_kmr">Kasbon MR <span class="badge label-warning"><?php echo $dataKl['jumlah'] > 0 ? $dataKl['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "lpj_ksr" ? 'active' : ''; ?>"><a href="index.php?p=lpj_kasbon&sp=lpj_ksr">Kasbon SR <span class="badge label-warning"><?php echo $dataKl2['jumlah'] > 0 ? $dataKl2['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">LPJ Kasbon </h3>
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