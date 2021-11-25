<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                FROM bkk b
                                JOIN divisi d
                                ON d.id_divisi = b.id_divisi
                                WHERE b.status_bkk='404' ORDER BY b.kd_transaksi DESC");



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
                    <h3 class="text-center">Ditolak Biaya Non OPS</h3>
                </div>
                <div class="box-body">
                    <div class="row">

                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id=" ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Divisi</th>
                                    <th>Keterangan</th>
                                    <th>Nama Vendor</th>
                                    <th>Jumlah</th>
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
                                            $angka_format = number_format($row['jml_bkk'], 0, ",", ".");

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['kd_transaksi']; ?> </td>
                                            <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <?= $row['nm_vendor']; ?> </td>
                                            <td> <?= "Rp." . $angka_format; ?> </td>
                                            <td><?= $row['komentar_direktur']; ?></td>
                                            <!-- <td>                
                                            <?php echo '0', ' %';
                                            ?>                                        
                                         </td> -->


                                            <td>
                                                <a href="?p=dtl_ditolakbno&aksi=detail&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Detail</button></span></a>

                                                <!-- <a target="_blank" href="cetak_jobreportvessel.php" class="btn btn-success"><i class="fa fa-print"></i> Cetak </a> -->
                                            </td>
                                </tr>
                        <?php

                                            $no++;
                                        endwhile;
                                    }

                                    $jumlahData  = mysqli_num_rows($query);

                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                                <td colspan='9'> Tidak Ada Data</td>
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