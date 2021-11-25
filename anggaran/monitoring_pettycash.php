<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'revisi') {
        header("location:?p=revisi_petty&id=$id");
    } else if ($_GET['aksi'] == 'lpj') {
        header("location:?p=lpj_petty&id=$id");
    } else if ($_GET['aksi'] == 'release') {
        header("location:?p=release_petty&id=$id");
    }
}

$tahun = date("Y");

$query = mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp   
                                            JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran   
                                            JOIN divisi d
                                            ON d.id_divisi = tp.id_divisi
                                            WHERE status_pettycash <=4 OR status_pettycash = 10
                                            ORDER BY tp.created_pettycash_on DESC   ");
?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Petty Cash</h3>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id=" ">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
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
                                                <td> <?= formatTanggal($row['created_pettycash_on']); ?> </td>
                                                <td> <?= $row['keterangan_pettycash']; ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> </td>
                                                <td> <?= $row['nm_divisi']; ?> </td>
                                                <td> <?= formatRupiah($row['total_pettycash']); ?> </td>
                                                <td> <?php if ($row['status_pettycash'] == 0) { ?>
                                                        <a href="?p=buat_petty&aksi=release&id=<?= base64_encode($row['id_pettycash']); ?>"><span data-placement='top' data-toggle='tooltip' title='Release'><button type="button" class="btn btn-warning"><i class="fa fa-rocket"> </i> Release</button></span></a>
                                                    <?php } else if ($row['status_pettycash'] == 1) { ?>
                                                        <span class="label label-primary">Menunggu Approve Manager </span>
                                                    <?php  } else if ($row['status_pettycash'] == 2) { ?>
                                                        <span class="label label-warning">Dana Sudah Bisa diambil</span>
                                                    <?php  } else if ($row['status_pettycash'] == 3) { ?>
                                                        <a href="?p=buat_petty&aksi=lpj&id=<?= $row['id_pettycash']; ?>"><span data-placement='top' data-toggle='tooltip' title='Revisi'><button type="button" class="btn btn-success"><i class="fa fa-edit"> </i> LPJ</button></span></a>
                                                    <?php  } else if ($row['status_pettycash'] == 4) { ?>
                                                        <span class="label label-default">Verifikasi LPJ </span>
                                                    <?php  } else if ($row['status_pettycash'] == 10) { ?>
                                                        <span class="label label-danger">Pengajuan Ditolak </span>
                                                    <?php  } ?>
                                                </td>
                                    </tr>
                            <?php
                                                $no++;
                                            endwhile;
                                        } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>