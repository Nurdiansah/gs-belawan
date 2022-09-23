<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=po_drtp&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];
$idDivisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT *
                                    FROM po p 
                                    JOIN biaya_ops bo
                                        ON p.kd_transaksi = bo.kd_transaksi
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi     
                                    LEFT JOIN bkk_final bf
									    ON id_po = id_kdtransaksi
                                        AND status_bkk = '17'
                                    WHERE status_po BETWEEN 6 AND 8 
                                    ORDER BY p.id_po DESC   ");

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">PO Ready To Pay</h3>
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
                                    <th>Nomor PO </th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                    <th>PO</th>
                                    <th>Status</th>
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
                                            <!-- <td> <?= $row['status_po']; ?> </td> -->
                                            <td> <?= $row['po_number']; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_po']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= formatRupiah($row['grand_totalpo']) ?> </td>
                                            <td>
                                                <a target="_blank" href="cetak_po.php?id=<?= $row['id_po']; ?>" class="btn btn-success"><i class="fa fa-print"></i> PO </a>
                                                <a target="_blank" onclick="window.open('cetak_lpdpo.php?id=<?= enkripRambo($row['id_po']); ?>','name','width=800,height=600')" class="btn btn-primary"><i class="fa fa-print"></i> LPD </a>
                                            </td>
                                            <td>

                                                <?php if ($row['status_po'] == 6) { ?>
                                                    <span class="label label-danger">Kasir-Verifikasi Term Payment</span>
                                                <?php  } else if ($row['status_po'] == 7) { ?>
                                                    <span class="label label-info">Kasir-List Tempo</span>
                                                <?php  } else if ($row['status_po'] == 8) { ?>
                                                    <span class="label label-warning">Proses Pengajuan BKK</span>
                                                <?php  } ?>

                                            </td>
                                            <td>
                                                <a href="?p=po_rtp&aksi=lihat&id=<?= $row['id_po']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
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