<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryKasbon = mysqli_query($koneksi, "SELECT * FROM kasbon ks
                                        JOIN detail_biayaops db
                                            ON ks.kd_transaksi = db.kd_transaksi
                                        LEFT JOIN tolak_kasbon
                                            ON id_kasbon = kasbon_id
                                        WHERE status_kasbon IN ('202', '606')
                                        AND from_user = '0'
                                        ORDER BY id_kasbon DESC");
$totalKasbon = mysqli_num_rows($queryKasbon);

$queryPO = mysqli_query($koneksi, "SELECT * FROM po ks
                                    JOIN detail_biayaops db
                                        ON ks.id_dbo = db.id
                                        JOIN divisi d
                                            ON db.id_divisi = d.id_divisi
                                    LEFT JOIN tolak_po
                                        ON id_po = po_id
                                    WHERE status_po = '101'
                                    ORDER BY id_po DESC");
$totalPO = mysqli_num_rows($queryPO);

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
                    <li role="presentation" class="<?php echo $sp == "ditolak_kasbon" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_mr&sp=ditolak_kasbon">Kasbon <span class="badge label-warning"><?php echo $dataKasbonTolak['jumlah'] > 0 ? $dataKasbonTolak['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "ditolak_po" ? 'active' : ''; ?>"><a href="index.php?p=ditolak_mr&sp=ditolak_po">PO <span class="badge label-warning"><?php echo $dataTolakPO['jumlah'] > 0 ? $dataTolakPO['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">MR Ditolak</h3>
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

    // sembunyikan nominal
    $("#nml").hide();

    $('#aksi').on('change', function() {
        let aksi = this.value;

        if (aksi == 'pengembalian' || aksi == 'penambahan') {
            $("#nml").show();
        } else {
            $("#nml").hide();
        }
    });
</script>