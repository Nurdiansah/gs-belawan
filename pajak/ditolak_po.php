<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT *, bf.id as id_bkk
                                    FROM bkk_final bf
                                    INNER JOIN po po
                                        ON id_po = id_kdtransaksi
                                    INNER JOIN detail_biayaops dbo
                                        ON dbo.id = id_dbo
                                    INNER JOIN divisi d
                                        ON dbo.id_divisi = d.id_divisi
                                    WHERE status_bkk = '202'
                                    AND pengajuan = 'PO'
                ");

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Invoice PO Ditolak</h3>
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
                                    <th>Status</th>
                                    <th>Aksi</th>
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
                                            <td><span class="label label-danger">Ditolak Cost Control</span></td>
                                            <td>
                                                <a href="index.php?p=dtl_ditolakpo&id=<?= enkripRambo($row['id_po']); ?>&bkk=<?= enkripRambo($row['id_bkk']); ?>" class="btn btn-info">Lihat</a>
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