<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=pending_dkasbon&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$querySR = mysqli_query($koneksi, "SELECT * -- id_kasbon, tgl_kasbon,nm_divisi, nm_barang , harga_akhir, sr_id
                                        FROM kasbon k
                                        JOIN sr s
                                        ON k.sr_id = s.id_sr
                                        JOIN divisi d
                                        ON d.id_divisi = k.divisi_id
                                        WHERE k.status_kasbon IN ('6', '0')
                                        AND k.sr_id IS NOT NULL
                                        ORDER BY k.id_kasbon DESC");

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k
                                            JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            WHERE k.status_kasbon = '6'
                                            AND k.sr_id IS NULL
                                            AND from_user = '0'
                                            ORDER BY k.id_kasbon DESC");
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
                    <li role="presentation" class="<?php echo $sp == "pnk_purchasing" ? 'active' : ''; ?>"><a href="index.php?p=pending_kasbon&sp=pnk_purchasing">Kasbon MR <span class="badge label-warning"><?php echo $dataKPL1['jumlah'] >= 1 ? $dataKPL1['jumlah'] : ''; ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "pnk_sr" ? 'active' : ''; ?>"><a href="index.php?p=pending_kasbon&sp=pnk_sr">Kasbon SR <span class="badge label-warning"><?php if ($dataKPL3['jumlah'] > 0) {
                                                                                                                                                                                                    echo $dataKPL3['jumlah'];
                                                                                                                                                                                                } ?></span></a></li>
                    <li role="presentation" class="<?php echo $sp == "pnk_user" ? 'active' : ''; ?>"><a href=" index.php?p=pending_kasbon&sp=pnk_user">Kasbon User <span class="badge label-warning"><?php echo $dataKPL2['jumlah'] >= 1 ? $dataKPL2['jumlah'] : ''; ?></span></a></li>
                </ul>
                <div class="box-header with-border">
                    <h3 class="text-center">Pending LPJ Kasbon</h3>
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

    window.onload = function() {
        jam();
    }

    function jam() {
        var e = document.getElementById('jam'),
            d = new Date(),
            h, m, s;
        h = d.getHours();
        m = set(d.getMinutes());
        s = set(d.getSeconds());

        e.innerHTML = h + ':' + m + ':' + s;

        console.log(m);

        setTimeout('jam()', 1000);
    }
</script>