<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=verifikasi_dkasbonlpj&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k
                                            JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            WHERE k.status_kasbon = '9'
                                            AND k.sr_id IS NULL
                                            ORDER BY k.id_kasbon DESC   ");

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
                    <li role="presentation" class="<?php echo $sp == "vlk_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=verifikasi_kasbonlpj&sp=vlk_purchasing">Kasbon MR <span class="badge label-warning"><?php echo $dataKL1['jumlah'] >= 1 ? $dataKL1['jumlah'] : ''; ?></a></li>
                    <li role="presentation" class="<?php echo $sp == "vlk_sr" ? 'active' : ''; ?>"><a href=" index.php?p=verifikasi_kasbonlpj&sp=vlk_sr">Kasbon SR <span class="badge label-warning"><?php echo $dataKL3['jumlah'] >= 1 ? $dataKL3['jumlah'] : ''; ?></a></li>
                    <li role="presentation" class="<?php echo $sp == "vlk_user" ? 'active' : ''; ?>"><a href=" index.php?p=verifikasi_kasbonlpj&sp=vlk_user">Kasbon User <span class="badge label-warning"><?php echo $dataKL2['jumlah'] >= 1 ? $dataKL2['jumlah'] : ''; ?></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi LPJ Kasbon</h3>
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