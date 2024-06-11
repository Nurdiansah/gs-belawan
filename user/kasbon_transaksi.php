<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=kasbon_dtransaksi&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];
$idDivisi = $rowUser['id_divisi'];

// $query = mysqli_query($koneksi, "SELECT * 
//                                     FROM kasbon k
//                                     JOIN biaya_ops bo
//                                     ON k.kd_transaksi = bo.kd_transaksi
//                                     JOIN detail_biayaops dbo
//                                     ON k.id_dbo = dbo.id
//                                     JOIN divisi d
//                                     ON d.id_divisi = bo.id_divisi                                            
//                                     WHERE status_kasbon = 8 AND bo.id_divisi = '$idDivisi'
//                                     ORDER BY k.id_kasbon DESC   ");

if ($idDivisi == "6") {
    $query = mysqli_query($koneksi, "SELECT * FROM kasbon
                                        JOIN detail_biayaops
                                            ON id = id_dbo
                                        WHERE (id_divisi = '$idDivisi' OR id_anggaran IN (SELECT id_anggaran FROM anggaran WHERE spj = '1'))
                                        AND status_kasbon = '10'
                                        -- AND from_user = '1'
                                        -- AND sr_id IS NULL
                                        ORDER BY tgl_kasbon DESC
                        ");
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM kasbon
                                    JOIN detail_biayaops
                                        ON id = id_dbo
                                    WHERE id_divisi = '$idDivisi'
                                    AND status_kasbon = '10'
                                    -- AND from_user = '1'
                                    -- AND sr_id IS NULL
                                    ORDER BY tgl_kasbon DESC
                    ");
}

?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Kasbon</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Kode </th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
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
                                            <td> <?= $row['id_kasbon']; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
                                            <td>
                                                <a href="?p=kasbon_transaksi&aksi=lihat&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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