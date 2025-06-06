<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=approval_dpettycash&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];
$idDivisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp   
                                            JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran 
                                            JOIN divisi d
                                            ON tp.id_divisi = d.id_divisi
                                            WHERE tp.id_manager = '$idUser' AND tp.status_pettycash = '5'
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
                    <h3 class="text-center">Transaksi PettyCash</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id=" ">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Kode Pettycash</th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th>Kode Anggaran</th>
                                    <th>Keterangan</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :
                                ?>
                                        <tr>
                                            <td> <?= $no; ?> </td>
                                            <td><?= $row['kd_pettycash']; ?></td>
                                            <td> <?= formatTanggal($row['created_pettycash_on']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['nm_item'] . ' - [' . $row['kd_anggaran']; ?>]</td>
                                            <td> <?= $row['keterangan_pettycash']; ?> </td>
                                            <td> <?= formatRupiah($row['total_pettycash']); ?> </td>
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