<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'detail') {
        header("location:?p=dtl_prosesbno&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                FROM bkk b
                                JOIN divisi d
                                ON d.id_divisi = b.id_divisi
                                WHERE b.status_bkk IN ('6', '7', '8', '9')
                                ORDER BY b.kd_transaksi DESC  ");



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
                    <h3 class="text-center">Proses Biaya Non OPS</h3>
                </div>
                <div class="box-body">
                    <div class="row">

                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Divisi</th>
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
                                            <td> <?= $row['kd_transaksi']; ?> </td>
                                            <td> <?= formatTanggalWaktu($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <?= $row['nm_vendor']; ?> </td>
                                            <td>
                                                <?php if ($row['status_bkk'] == '6') {
                                                    echo "<h4><span class='label label-primary'> Approval Manager </span></h4>";
                                                } else if ($row['status_bkk'] == '7') {
                                                    echo "<h4><span class='label label-primary'> Approval GM Finance </span></h4>";
                                                } else if ($row['status_bkk'] == '8') {
                                                    echo "<h4><span class='label label-primary'> Approval Direksi </span></h4>";
                                                } elseif ($row['status_bkk'] == '9') {
                                                    if ($row['jenis'] == 'umum') {
                                                        echo "<h4><span class='label label-warning'>Proses Payment</span></h4>";
                                                    } else {
                                                        echo "<h4><span class='label label-warning'>Proses Tempo</span></h4>";
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td> <button class="btn btn-success"><?= formatRupiah(round($row['jml_bkk'])) ?> </button></td>
                                            <!-- <td> < "Rp." . $angka_format; ?> </td> -->
                                            <!-- <td>                
                                            <?php echo '0', ' %';
                                            ?>                                        
                                         </td> -->


                                            <td>
                                                <a href="?p=proses_bno&aksi=detail&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Detail</button></span></a>

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