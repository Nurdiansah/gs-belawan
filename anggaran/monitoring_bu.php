<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_biayanonops&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}

$query = mysqli_query($koneksi, "SELECT * FROM bkk WHERE status_bkk >='1' AND status_bkk <='8' ORDER BY kd_transaksi DESC  ");


?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Biaya Umum </h3>
                </div>
                <div class="box-body">
                    <br>
                </div>
                <div class="table-responsive">
                    <table class="table text-center table table-striped table-hover" id=" ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Keterangan</th>
                                <th>Nama Vendor</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :
                                        $angka_format = number_format($row['jml_bkk'], 2, ",", ".");

                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['kd_transaksi']; ?> </td>
                                        <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                                        <td> <?= $row['keterangan']; ?> </td>
                                        <td> <?= $row['nm_vendor']; ?> </td>
                                        <td> <?= "Rp." . $angka_format; ?> </td>
                                        <td> <?php if ($row['status_bkk'] == 1) { ?>
                                                <span class="label label-success">Menunggu Approve Manager </span>
                                            <?php  } else if ($row['status_bkk'] == 2) { ?>
                                                <span class="label label-success">Verifikasi Anggaran</span>
                                            <?php   } else if ($row['status_bkk'] == 3) { ?>
                                                <span class="label label-success">Verifikasi Pajak</span>
                                            <?php   } else if ($row['status_bkk'] == 4) { ?>
                                                <span class="label label-success">Verifikasi Kordinator Pajak </span>
                                            <?php   } else if ($row['status_bkk'] == 5) { ?>
                                                <span class="label label-primary">Approval Manager Keuangan </span>
                                            <?php   } else if ($row['status_bkk'] == 6) { ?>
                                                <span class="label label-primary">Approval Direktur </span>
                                            <?php   } else if ($row['status_bkk'] == 7) { ?>
                                                <span class="label label-primary">Payment Kasir</span>
                                            <?php   } else if ($row['status_bkk'] == 8) { ?>
                                                <span class="label label-warning">Proses Payment</span>
                                            <?php   } ?>
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