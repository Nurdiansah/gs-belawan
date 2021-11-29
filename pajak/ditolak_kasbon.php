<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryPurchasing = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                    LEFT JOIN tolak_kasbon
                                        ON id_kasbon = kasbon_id
                                    LEFT JOIN divisi d
                                        ON ks.divisi_id = d.id_divisi
                                    WHERE status_kasbon = '505'
                                    AND from_user = '0'
                                    AND sr_id IS NULL
                                    ORDER BY id_kasbon DESC");
$totalPurchasing = mysqli_num_rows($queryPurchasing);

$queryUser = mysqli_query($koneksi, "SELECT * FROM kasbon
                                    LEFT JOIN tolak_kasbon
                                        ON id_kasbon = kasbon_id
                                    WHERE status_kasbon = '202'
                                    AND from_user = '1'
                                    ORDER BY id_kasbon DESC");
$totalUser = mysqli_num_rows($queryUser);

$sp = $_GET['sp'];

$no = 1;

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <ul class="nav nav-tabs">
                    <li role="presentation" class="<?php echo $sp == "tolak_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing">Kasbon Purchasing <span class="badge label-warning"><?php echo $totalPurchasing > 0 ? $totalPurchasing : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "tolak_user" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_kasbon&sp=tolak_user">Kasbon User <span class="badge label-warning"><?php echo $totalUser > 0 ? $totalUser : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Kasbon Ditolak</h3>
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
</script>