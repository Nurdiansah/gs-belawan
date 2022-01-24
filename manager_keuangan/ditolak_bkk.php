<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT *, b.id as id_bkk_final FROM bkk_final b    
                                                JOIN anggaran a
                                                    ON b.id_anggaran = a.id_anggaran
                                                LEFT JOIN tolak_bkk_final t
                                                    ON b.id = id_bkk_final
                                                WHERE b.status_bkk = '303'
                                                -- AND pengajuan = 'KASBON'
                                                ORDER BY b.tgl_bkk DESC");

?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (isset($_COOKIE['pesan'])) {
                echo "<div class='alert alert-success' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
            }
            ?>
        </div>
    </div>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">BKK Ditolak</h3>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id=" ">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
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
                                                <td> <?= formatTanggalWaktu($row['created_on_bkk']); ?> </td>
                                                <td> <?= $row['pengajuan']; ?> </td>
                                                <td> <?= $row['keterangan']; ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> </td>
                                                <td> <?= formatRupiah($row['nominal']); ?> </td>
                                                <td>
                                                    <a href="?p=dtl_bkkditolak&id=<?= $row['id_bkk_final']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
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
                    </form>
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