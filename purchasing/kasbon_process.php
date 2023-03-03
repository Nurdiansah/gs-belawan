<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$queryMR = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k
                                            JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            WHERE status_kasbon IN (1, 2, 3, 4, 5, 6, 7, 101, 202, 303, 404, 505)
                                            ORDER BY k.id_kasbon DESC
                                ");

$sp = $_GET['sp'];

$querySR = mysqli_query($koneksi, "SELECT * FROM kasbon k
                                    JOIN sr sr
                                        ON id_sr = sr_id
                                    JOIN divisi d
                                        ON divisi_id = d.id_divisi
                                    WHERE status_kasbon IN (1, 2, 3, 4, 5, 101, 303, 404)
                                    ORDER BY tgl_kasbon ASC
                ");

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
                    <li role="presentation" class="<?php echo $sp == "proses_kasbon_mr" ? 'active' : ''; ?>"><a href="index.php?p=kasbon_process&sp=proses_kasbon_mr">MR <span class="badge label-warning"><?php echo $dataKp['jumlah'] > 0 ? $dataKp['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "proses_kasbon_sr" ? 'active' : ''; ?>"><a href="index.php?p=kasbon_process&sp=proses_kasbon_sr">SR <span class="badge label-warning"><?php echo $dataKs['jumlah'] > 0 ? $dataKs['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Kasbon Process</h3>
                </div>
                <div class="box-body">
                    <!-- <div class="row">
                        <br><br>
                    </div> -->

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