<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$query = mysqli_query($koneksi, "SELECT *, k.komentar as kkomentar
                                FROM kasbon k
                                JOIN detail_biayaops dbo
                                    ON k.id_dbo = dbo.id
                                JOIN divisi d
                                    ON d.id_divisi = dbo.id_divisi
                                WHERE k.status_kasbon = '808'
                                -- AND from_user = '0'
                                ORDER BY k.id_kasbon DESC   ");
?>

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
                    <h3 class="text-center">Verifikasi LPJ</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Kode </th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                    <th>Remarks</th>
                                    <th>Aksi</th>
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
                                            <td> <?= $row['id_kasbon']; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
                                            <td> <?= $row['kkomentar']; ?> </td>
                                            <td>
                                                <a href="index.php?p=verifikasi_dkasbon_lpj&id=<?= enkripRambo($row['id_kasbon']); ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
                                            </td>
                                        </tr>
                                <?php
                                        $no++;
                                    endwhile;
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