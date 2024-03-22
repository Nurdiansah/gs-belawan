<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=detail_mr&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("#");
    }
}
$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$id_divisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT * 
                                        FROM biaya_ops b     
                                        JOIN detail_biayaops dbo                                                                       
                                         ON b.kd_transaksi = dbo.kd_transaksi
                                        WHERE b.status_biayaops <= '2'AND b.status_biayaops != '0' AND dbo.status <= '2' AND b.id_divisi = '$id_divisi' GROUP BY b.kd_transaksi ORDER BY b.kd_transaksi DESC ");


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
                    <h3 class="text-center">Proses Material Request</h3>
                </div>
                <div class="box-body">
                    <br>
                </div>
                <div id="my-timeline"></div>
                <div class="table-responsive">
                    <table class="table text-center table table-striped table-hover" id=" ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Tanggal Pengajuan</th>
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

                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['kd_transaksi']; ?> </td>
                                        <td> <?= formatTanggal($row['created_on']); ?> </td>
                                        <td> <?php if ($row['status_biayaops'] == 1) {

                                                    if ($row['id_manager'] == '17' || $row['id_manager'] == '20' || $row['id_manager'] == '33') {
                                                        echo "<span class='label label-success'>Menunggu Approve Manager </span>";
                                                    } else {
                                                        echo "<span class='label label-success'>Menunggu Approve Supervisor </span>";
                                                    }
                                                } else if ($row['status_biayaops'] == 2) { ?>
                                                <span class="label label-success">Bidding Process Purchasing</span>
                                            <?php  } else if ($row['status_biayaops'] == 3) { ?>
                                                <span class="label label-warning">Verifikasi Tax</span>
                                            <?php  } else if ($row['status_biayaops'] == 4) { ?>
                                                <span class="label label-warning">Verifikasi Manager GA</span>
                                            <?php  } else if ($row['status_biayaops'] == 5) { ?>
                                                <span class="label label-primary">Approval Manager Finance</span>
                                            <?php  } ?>

                                        </td>
                                        <td>
                                            <a href="?p=proses_mr&aksi=lihat&id=<?= $row['kd_transaksi']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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