<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=po_dproses&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM po p 
                                            JOIN biaya_ops bo
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON p.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            WHERE status_po BETWEEN 1 AND 7
                                            OR status_po IN ('0', '101', '202', '303', '404')
                                            ORDER BY p.id_po DESC   ");

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi PO</h3>
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
                                    <th>Deskripsi</th>
                                    <th>Divisi</th>
                                    <th>Total</th>
                                    <th>Status</th>
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
                                            <td> <?= $row['po_number']; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_po']); ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= formatRupiah($row['total_po']) ?> </td>
                                            <td>
                                                <?php if ($row['status_po'] == 1) { ?>
                                                    <span class="label label-primary">Submit Quatation Purchasing</span>
                                                <?php  } else if ($row['status_po'] == 2) { ?>
                                                    <span class="label label-primary">Verifikasi Manager GA</span>
                                                <?php  } else if ($row['status_po'] == 3) { ?>
                                                    <span class="label label-success">Verifikasi Pajak</span>
                                                <?php  } else if ($row['status_po'] == 4) { ?>
                                                    <span class="label label-success">Approval Manager Finance</span>
                                                <?php  } else if ($row['status_po'] == 5) { ?>
                                                    <span class="label label-info">Approval Direktur</span>
                                                <?php  } else if ($row['status_po'] == 6) { ?>
                                                    <span class="label label-info">Proses Pembayaran Kasir</span>
                                                <?php  } else if ($row['status_po'] == 7) { ?>
                                                    <span class="label label-info">Pengajuan sedang di belikan</span>
                                                <?php  } else if ($row['status_po'] == 0) { ?>
                                                    <span class="label label-danger">Ditolak Purchasing</span>
                                                <?php  } else if ($row['status_po'] == 101) { ?>
                                                    <span class="label label-danger">Ditolak Manager GA</span>
                                                <?php  } else if ($row['status_po'] == 202) { ?>
                                                    <span class="label label-danger">Ditolak Pajak</span>
                                                <?php  } else if ($row['status_po'] == 303) { ?>
                                                    <span class="label label-danger">Ditolak Manager Finance</span>
                                                <?php  } else if ($row['status_po'] == 404) { ?>
                                                    <span class="label label-danger">Ditolak Direktur</span>
                                                <?php  }  ?>
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