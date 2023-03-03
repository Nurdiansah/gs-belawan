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
$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$id_divisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT * FROM bkk WHERE id_divisi = '$id_divisi' AND status_bkk = '0' ORDER BY tgl_bkk DESC  ");


?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Biaya Umum Non OPS Yang Di Tolak</h3>
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
                                <th>Alasan Penolakan</th>
                                <th>Aksi</th>
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
                                        <td> <?= $row['komentar']; ?> </td>
                                        <td>
                                            <a href="?p=proses_biayanonops&aksi=edit&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>

                                            <!-- <a target="_blank" href="cetak_jobreportvessel.php" class="btn btn-success"><i class="fa fa-print"></i> Cetak </a> -->
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