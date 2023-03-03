<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=detail_tolakmr&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_multi_query($koneksi, "DELETE FROM biaya_ops WHERE kd_transaksi = '$id';
                                    DELETE FROM detail_biayaops WHERE kd_transaksi = '$id';");

        if ($hapus) {
            header('Location: index.php?p=tolak_mr');
        }
    }
}
$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$id_divisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT * FROM biaya_ops WHERE id_divisi = '$id_divisi' AND status_biayaops= '0' ");


?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Daftar MR Ditolak</h3>
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
                                <th>Komentar</th>
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
                                        <th><?= batasiKata($row['komentar']); ?> </th>
                                        <td>
                                            <a href="?p=tolak_mr&aksi=lihat&id=<?= $row['kd_transaksi']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
                                            <a href="?p=tolak_mr&aksi=hapus&id=<?= $row['kd_transaksi']; ?>" class="btn btn-danger" onclick="javascript: return confirm('Anda yakin ingin menghapus ?')">Hapus</a>
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