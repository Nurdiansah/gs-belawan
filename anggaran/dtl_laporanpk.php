<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id_pk = dekripRambo($_GET['id_pk']);

$tahun = date("Y");

$dataPK = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM program_kerja WHERE id_programkerja = '$id_pk'"));

$queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE programkerja_id = '$id_pk' AND tahun = '$tahun' ORDER BY nm_item ASC");

$sp = $_GET['sp'];
$no = 1;

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">

                <div class="box-header with-border">
                    <br>
                    <h3 class="text-center">Detail Pra Nota/Realisasi Kas</h3>
                    <br>
                    <h4>Program Kerja : <?= $dataPK['nm_programkerja'] . " <b>[" . $dataPK['kd_programkerja']; ?>]</b></h4>
                </div>
                <br>
                <ul class="nav nav-tabs">
                    <li role="presentation" class="<?= $sp == "pra_nota" ? 'active' : ''; ?>"><a href="index.php?p=dtl_laporanpk&sp=pra_nota&id_pk=<?= enkripRambo($id_pk); ?>"> <b>Pra Nota</b> </a></li>
                    <li role="presentation" class="<?= $sp == "realisasi_kas" ? 'active' : ''; ?>"><a href=" index.php?p=dtl_laporanpk&sp=realisasi_kas&id_pk=<?= enkripRambo($id_pk); ?>"> <b>Realisasi Kas</b> </a></li>
                </ul>

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

    $('tr[data-href]').on("click", function() {
        document.location = $(this).data('href');
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>