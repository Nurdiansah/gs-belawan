<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT tgl_po, po_number, nm_divisi, total_po, bf.nominal as nominal, id_po, tp.id_tagihan, bf.id as id_bkk
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
                                    AND status_bkk = '0'
                                    AND tp.status_tagihan = '2'
                                union all

                                SELECT tgl_po, po_number, nm_divisi, total_po, bf.nominal as nominal, id_po, tp.id_tagihan, bf.id as id_bkk
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
                                    AND status_bkk = '0'
                                    AND tp.status_tagihan = '2';
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
                    <h3 class="text-center">Verifikasi Invoice PO</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id=" ">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nomor PO</th>
                                    <th>Divisi</th>
                                    <th>Total</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_po']); ?> </td>
                                            <td> <?= $row['po_number']; ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <!-- <td> <?= 'Rp. ' . number_format($row['total_po'], 0, ",", "."); ?></td>                                         -->
                                            <td> <?= formatRupiah($row['nominal']); ?> </td>
                                            <td>
                                                <a href="index.php?p=verifikasi_dpo&id=<?= enkripRambo($row['id_po']); ?>&bkk=<?= enkripRambo($row['id_bkk']); ?>&id_tagihan=<?= enkripRambo($row['id_tagihan']); ?>"><span data-placement='top' data-toggle='tooltip' title='Detail'><button class="btn btn-success"><i class="fa fa-search-plus"></i></button></span></a>
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