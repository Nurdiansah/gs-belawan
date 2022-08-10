<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$id_divisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT * FROM bkk
                                    WHERE id_divisi = '$id_divisi'
                                    AND status_bkk IN ('101', '202', '303')
                                    ORDER BY tgl_bkk DESC");


?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Biaya Umum Di Tolak</h3>
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
                                <th>Alasan Ditolak</th>
                                <th>Alasan Ditolak Cost Control</th>
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
                                        <td> <?= $row['komentar_mgrfin']; ?> </td>
                                        <td>
                                            <a href="releaselagi_biayanonops.php?id=<?= $row['id_bkk']; ?>" onclick="return confirm('Yakin ajukan kembali pengajuan <?= $row['keterangan']; ?>?')" class="btn btn-warning" title="Release Kembali" data-placement="top" data-toggle="tooltip"><i class="fa fa-rocket"></i></a>
                                            <a href="index.php?p=rubah_biayanonops&id=<?= enkripRambo($row['id_bkk']); ?>&pg=<?= enkripRambo("ditolak_biayanonops"); ?>" class="btn btn-success" title="Rubah" data-placement="top" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                            <a href="hapus_biayanonops.php?id=<?= enkripRambo($row['id_bkk']); ?>&inv=<?= enkripRambo($row['invoice']); ?>&pg=<?= enkripRambo("ditolak_biayanonops"); ?>" class="btn btn-danger" onclick="javascript: return confirm('Yakin biaya umum <?= $row['keterangan']; ?> dihapus ?')" title="Hapus" data-placement="top" data-toggle="tooltip"><i class="fa fa-trash"></i></a>

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