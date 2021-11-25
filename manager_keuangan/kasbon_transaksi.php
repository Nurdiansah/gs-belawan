<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=proses_dkasbon&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];
$idDivisi = $rowUser['id_divisi'];


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
                    <li role="presentation" class="<?php echo $sp == "kt_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=kasbon_transaksi&sp=kt_purchasing">Kasbon Purchasing <span class="badge label-warning"><?php echo $dataKTP['jumlah'] >= 1 ? $dataKTP['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "kt_user" ? 'active' : ''; ?>"><a href=" index.php?p=kasbon_transaksi&sp=kt_user">Kasbon User <span class="badge label-warning"><?php echo $dataKTU['jumlah'] >= 1 ? $dataKTU['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Kasbon Transaksi</h3>
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