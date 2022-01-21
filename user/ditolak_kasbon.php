<?php

if (isset($_COOKIE['pesan'])) {
    echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
}

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser = mysqli_query($koneksi, "SELECT * 
                                        FROM kasbon k                                            
                                        JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                        JOIN divisi d
                                            ON d.id_divisi = dbo.id_divisi                                            
                                        WHERE k.status_kasbon IN ('101', '202', '303', '505', '606')
                                        AND from_user = '1'
                                        AND dbo.id_divisi = '$idDivisi'");

$queryPurchasing = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                            JOIN detail_biayaops db
                                                ON ks.kd_transaksi = db.kd_transaksi
                                            LEFT JOIN tolak_kasbon
                                                ON id_kasbon = kasbon_id
                                            WHERE status_kasbon = '0'
                                            AND from_user = '0'
                                            AND db.id_divisi = '$idDivisi'
                                            ORDER BY id_kasbon DESC");
$totalPurchasing = mysqli_num_rows($queryPurchasing);

$querySR = mysqli_query($koneksi, "SELECT *, k.komentar as k_komentar 
                                    FROM kasbon k
                                    INNER JOIN sr sr
                                        ON id_sr = sr_id
                                    INNER JOIN anggaran a
                                        ON sr.id_anggaran = a.id_anggaran
                                    WHERE status_kasbon IN ('202')
                                    AND from_user = '0'
                                    AND k.divisi_id = '$idDivisi'
                                    ORDER BY id_kasbon DESC");


$sp = $_GET['sp'];

$tahun = tahunSekarang();

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
                    <li role="presentation" class="<?php echo $sp == "tolak_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing">Kasbon Purchasing <span class="badge label-warning"><?php echo $dataTKP['jumlah'] > 0 ? $dataTKP['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "tolak_sr" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_kasbon&sp=tolak_sr">Kasbon Service Request <span class="badge label-warning"><?php echo $dataTolakTKS['jumlah'] > 0 ? $dataTolakTKS['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "tolak_user" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_kasbon&sp=tolak_user">Kasbon User <span class="badge label-warning"><?php echo $dataTKU['jumlah'] > 0 ? $dataTKU['jumlah'] : ''; ?></span></a></li>
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