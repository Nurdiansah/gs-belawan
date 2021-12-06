<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_biayanonops&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}
$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$id_divisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT * FROM bkk WHERE id_divisi = '$id_divisi' AND status_bkk IN (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 202, 303, 404) ORDER BY kd_transaksi DESC  ");

$jumlahData = mysqli_num_rows($query);


?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (isset($_COOKIE['pesan'])) {
                echo "<div class='alert alert-success' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
            }
            ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Proses Biaya Umum </h3>
                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Keterangan</th>
                                    <th>Nama Vendor</th>
                                    <th>Jumlah</th>
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
                                            $angka_format = number_format($row['jml_bkk'], 0, ",", ".");

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= strtoupper($row['jenis']); ?> </td>
                                            <td> <?= $row['kd_transaksi']; ?> </td>
                                            <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <?= $row['nm_vendor']; ?> </td>
                                            <td> <?= "Rp." . $angka_format; ?> </td>
                                            <td> <?php if ($row['status_bkk'] == 0) {
                                                        echo "<h4><span class='label label-warning'> Draft </span></h4>";
                                                    } else if ($row['status_bkk'] == 1) {
                                                        if ($row['id_manager'] == '17') {
                                                            echo "<h4><span class='label label-primary'> Approval Manager </span></h4>";
                                                        } else {
                                                            echo "<h4><span class='label label-primary'> Approval Supervisor </span></h4>";
                                                        }
                                                    } else if ($row['status_bkk'] == '4') {
                                                        echo "<h4><span class='label label-primary'> Verifikasi Pajak </span></h4>";
                                                    } else if ($row['status_bkk'] == '5') {
                                                        echo "<h4><span class='label label-primary'> Approval Cost Control </span></h4>";
                                                    } else if ($row['status_bkk'] == '6') {
                                                        echo "<h4><span class='label label-primary'> Approval Manager </span></h4>";
                                                    } else if ($row['status_bkk'] == '7') {
                                                        echo "<h4><span class='label label-primary'> Approval GM Finance </span></h4>";
                                                    } else if ($row['status_bkk'] == '8') {
                                                        echo "<h4><span class='label label-primary'> Approval Direksi </span></h4>";
                                                    } else if ($row['status_bkk'] == 404) { ?>
                                                    <span class="label label-danger">Ditolak Direktur </span>
                                                <?php   } else if ($row['status_bkk'] == 303) { ?>
                                                    <span class="label label-danger">Ditolak Manager Finance</span>
                                                <?php   } else if ($row['status_bkk'] == 202) { ?>
                                                    <span class="label label-danger">Ditolak Pajak</span>
                                                <?php   } else if ($row['status_bkk'] == 101) { ?>
                                                    <span class="label label-danger">Ditolak Manager</span>
                                                    <?php   } else if ($row['status_bkk'] == 9) {
                                                        if ($row['metode_pembayaran'] == 'tunai') { ?>
                                                        <a target="_blank" onclick="window.open('cetak_lpd_biayaumum.php?id=<?= enkripRambo($row['id_bkk']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> LPD </a>
                                                <?php   }
                                                        if ($row['jenis'] == 'umum') {
                                                            echo "<span class='label label-warning'>Proses Payment</span>";
                                                        } else {
                                                            echo "<span class='label label-warning'>Proses Tempo</span>";
                                                        }
                                                    } ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status_bkk'] == 0) { ?>
                                                    <a href="release_bu.php?id=<?= base64_encode($row['id_bkk']); ?>" class="btn btn-warning" title="Release" data-placement="top" data-toggle="tooltip"><i class="fa fa-rocket"></i></a>
                                                    <a href="index.php?p=rubah_biayanonops&id=<?= enkripRambo($row['id_bkk']); ?>&pg=<?= enkripRambo("proses_biayanonops"); ?>" class="btn btn-success" title="Rubah" data-placement="top" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                                    <a href="hapus_biayanonops.php?id=<?= enkripRambo($row['id_bkk']); ?>&inv=<?= enkripRambo($row['invoice']); ?>&pg=<?= enkripRambo("proses_biayanonops"); ?>" class="btn btn-danger" onclick="javascript: return confirm('Yakin biaya umum <?= $row['keterangan']; ?> dihapus ?')" title="Hapus" data-placement="top" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
                                                <?php } ?>
                                                <a href="?p=proses_biayanonops&aksi=edit&id=<?= $row['id_bkk']; ?>" class="btn btn-info" title="Lihat" data-placement="top" data-toggle="tooltip"><i class="fa fa-search"></i></a>
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
    </div>
</section>
<!-- Modal release -->
<div id="releaseKasbon" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="release_kasbon.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="mr_id_kasbon">
                            <input type="hidden" name="id_dbo" value="" id="mr_id_dbo">

                            <h4>Apakah anda yakin ingin merelease Kasbon <b><span id="mr_keterangan"></b></span> ini ?</h4>
                            <h5>Setelah kasbon direlease akan terkirim ke manager, silahkan pilih aturan verifikasi pajak untuk kasbon yang di ajukan </h5>
                            <br>
                            <div class="form-group ">
                                <label for="vrf_pajak" class="col-sm-offset-1 col-sm-3 control-label">Verifikasi Pajak</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2" name="vrf_pajak" id="me_vrf_pajak" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="bp"> Sebelum Pembayaran</option>
                                        <option value="as"> Setelah LPJ</option>
                                    </select>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="release">Kirim</button></span></a>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                    <!-- div perhitungan -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End release -->
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