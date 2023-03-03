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

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
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
                                            -- WHERE status_po <=7 AND bo.id_manager = '$idUser'
                                            WHERE (status_po BETWEEN 3 AND 7
                                            OR status_po IN ('101', '202', '303', '404') OR status_po IS NULL )
                                            ORDER BY p.id_po DESC   ");

$jumlahData  = mysqli_num_rows($query);

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">PO Proses</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Nomor PO </th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
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
                                            <td> <?= $row['po_number']; ?> </td>
                                            <td> <?= formatTanggalWaktu($row['tgl_po']); ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= formatRupiah($row['total_po']) ?> </td>
                                            <td>
                                                <?php if ($row['status_po'] == 3) { ?>
                                                    <span class="label label-success">Approval Manager</span>
                                                <?php  } else if ($row['status_po'] == 4) { ?>
                                                    <span class="label label-success">Approval GM Finance</span>
                                                <?php  } else if ($row['status_po'] == 5) { ?>
                                                    <span class="label label-info">Approvel Direktur</span>
                                                <?php  } else if ($row['status_po'] == 6) { ?>
                                                    <span class="label label-info">Proses Pembayaran Kasir</span>
                                                <?php  } else if ($row['status_po'] == 7) { ?>
                                                    <span class="label label-info">Pengajuan sedang di belikan</span>
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
                                            <td>
                                                <a href="?p=po_proses&aksi=lihat&id=<?= $row['id_po']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                                            </td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    }

                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                            <td colspan='8'> Tidak Ada Data</td>
                                        </tr>
                                        ";
                                    }
                        ?>
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