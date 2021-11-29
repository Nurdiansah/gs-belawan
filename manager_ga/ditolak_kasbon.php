<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryPurchasing = mysqli_query($koneksi, "SELECT * FROM kasbon
                                    LEFT JOIN tolak_kasbon
                                        ON id_kasbon = kasbon_id
                                    WHERE status_kasbon = '404'
                                    AND from_user = '0'
                                    AND sr_id IS NULL
                                    ORDER BY id_kasbon DESC");
$totalPurchasing = mysqli_num_rows($queryPurchasing);


$querySR = mysqli_query($koneksi, "SELECT *, id_kasbon, tgl_kasbon,nm_divisi, nm_barang , harga_akhir, sr_id, k.komentar as k_komentar
                                        FROM kasbon k
                                        JOIN sr s
                                            ON k.sr_id = s.id_sr
                                        JOIN divisi d
                                            ON d.id_divisi = k.divisi_id
                                        WHERE k.status_kasbon = '505'
                                        ORDER BY k.id_kasbon DESC");
$totalSR = mysqli_num_rows($querySR);

$queryUser = mysqli_query($koneksi, "SELECT * 
                                    FROM kasbon k                                            
                                    JOIN detail_biayaops dbo
                                        ON k.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = dbo.id_divisi     
                                    LEFT JOIN tolak_kasbon
                                        ON id_kasbon = kasbon_id                                       
                                    WHERE k.status_kasbon = '404'
                                    AND from_user = '1'
                                    AND sr_id IS NULL
                                    -- AND id_manager='$idUser'
                                    ");
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
                    <li role="presentation" class="<?php echo $sp == "tolak_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing">Kasbon Material Request <span class="badge label-warning"><?php echo $totalPurchasing > 0 ? $totalPurchasing : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "tolak_sr" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_kasbon&sp=tolak_sr">Kasbon Service Request <span class="badge label-warning"><?php echo $totalSR > 0 ? $totalSR : ''; ?></span></a></li>
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