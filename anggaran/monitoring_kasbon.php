<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=kasbon_dproses&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}


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
                    <li role="presentation" class="<?php echo $sp == "mk_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=monitoring_kasbon&sp=mk_purchasing">Kasbon Purchasing <span class="badge label-warning"><?php echo $dataKV1['jumlah'] >= 1 ? $dataKV1['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "mk_user" ? 'active' : ''; ?>"><a href=" index.php?p=monitoring_kasbon&sp=mk_user">Kasbon User <span class="badge label-warning"><?php echo $dataKV2['jumlah'] >= 1 ? $dataKV2['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Monitoring Kasbon</h3>
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