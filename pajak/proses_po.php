<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
// echo enkripRambo("28");
// echo enkripRambo("2717");
$queryUser =  mysqli_query($koneksi, "SELECT * FROM gs.user WHERE username  = '$_SESSION[username_gs]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT *, tgl_po, po_number, nm_divisi, total_po, bf.nominal as nominal, id_po, tp.id_tagihan, bf.id as id_bkk, status_bkk
                                    FROM bkk_final bf
                                    JOIN po po
                                        ON id_po = id_kdtransaksi
                                    JOIN detail_biayaops dbo
                                        ON id_dbo = dbo.id
                                    JOIN divisi dvs
                                        ON dvs.id_divisi = dbo.id_divisi
                                    JOIN tagihan_po tp
                                        ON tp.bkk_id = bf.id
                                    WHERE pengajuan = 'PO'
                                    AND status_bkk IN ('1', '2', '17')
                                    AND tp.status_tagihan IN ('2')
                                union all

                                SELECT *, tgl_po, po_number, nm_divisi, total_po, bf.nominal as nominal, id_po, tp.id_tagihan, bf.id as id_bkk, status_bkk
                                    FROM bkk_ke_pusat bf
                                    JOIN po po
                                        ON id_po = id_kdtransaksi
                                    JOIN detail_biayaops dbo
                                        ON id_dbo = dbo.id
                                    JOIN divisi dvs
                                        ON dvs.id_divisi = dbo.id_divisi
                                    JOIN tagihan_po tp
                                        ON tp.bkk_id = bf.id
                                    WHERE pengajuan = 'PO'
                                    AND status_bkk IN ('1', '2', '17')
                                    AND tp.status_tagihan IN ('2');
                ");

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
                <div class="box-header with-border">
                    <h3 class="text-center">Proses Invoice PO</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nomor PO</th>
                                    <th>Divisi</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :

                                ?>
                                        <tr>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_po']); ?> </td>
                                            <td> <?= $row['po_number']; ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <!-- <td> <?= 'Rp. ' . number_format($row['total_po'], 0, ",", "."); ?></td>                                         -->
                                            <td> <?= formatRupiah($row['nominal']); ?> </td>
                                            <td>
                                                <?php
                                                if ($row['metode_pembayaran'] == "Transfer") {
                                                    if ($row['status_bkk'] == '1') {
                                                        echo "<span class='label label-warning'>Verifikasi GM Finance</span>";
                                                    } else if ($row['status_bkk'] == '2') {
                                                        echo "<span class='label label-primary'>Verifikasi Direktur</span>";
                                                    } else  if ($row['status_bkk'] == '17') {
                                                        echo "<span class='label label-success'>Outstanding Cek Kasir JKT</span>";
                                                    }
                                                } else {
                                                    if ($row['status_bkk'] == '1') {
                                                        echo "<span class='label label-warning'>Verifikasi Cost Control</span>";
                                                    } else if ($row['status_bkk'] == '2') {
                                                        echo "<span class='label label-primary'>Verifikasi Manager</span>";
                                                    } else  if ($row['status_bkk'] == '17') {
                                                        echo "<span class='label label-success'>Proses Pembayaran Kasir</span>";
                                                    }
                                                } ?>
                                            </td>
                                            <td>
                                                <a href="index.php?p=detail_prosespo&id=<?= enkripRambo($row['id_po']); ?>&bkk=<?= enkripRambo($row['id_bkk']); ?>&id_tagihan=<?= enkripRambo($row['id_tagihan']); ?>"><span data-placement='top' data-toggle='tooltip' title='Detail'><button class="btn btn-success"><i class="fa fa-search-plus"></i></button></span></a>
                                            </td>
                                        </tr>
                                <?php
                                        $no++;
                                    endwhile;
                                } ?>
                            </tbody>
                        </table>
                    </div>
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