<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_paymentkaskeluar&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}

$tanggal = date("Y-m-d");

$query = mysqli_query($koneksi,  "SELECT * 
                                FROM bkk b
                                JOIN bkk_ke_pusat bk
                                ON bk.id_kdtransaksi = b.kd_transaksi
                                JOIN divisi d
                                ON d.id_divisi = b.id_divisi
                                WHERE b.status_bkk='17' AND b.jenis='kontrak' ORDER BY b.kd_transaksi DESC  ");

$jumlahData = mysqli_num_rows($query);
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
                    <h3 class="text-center">Proses Payment</h3>
                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead class="bg-primary">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">No</th>
                                    <th rowspan="2" style="vertical-align: middle;">Kode Transaksi</th>
                                    <th colspan="2">Tanggal</th>
                                    <th rowspan="2" style="vertical-align: middle;">Jenis</th>
                                    <th rowspan="2" style="vertical-align: middle;">Divisi</th>
                                    <th rowspan="2" style="vertical-align: middle;">Vendor</th>
                                    <th rowspan="2" style="vertical-align: middle;">Jumlah</th>
                                    <th rowspan="2" style="vertical-align: middle;">status</th>
                                    <th rowspan="2" style="vertical-align: middle;">Aksi</th>
                                </tr>
                                <tr>
                                    <th>Pengajuan</th>
                                    <th>Tempo</th>
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
                                            <td> <?= tanggal_indo($row['tgl_tempo']); ?> </td>
                                            <td> <?= $row['jenis']; ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['nm_vendor']; ?> </td>
                                            <td> <?= "Rp." . $angka_format; ?> </td>
                                            <!-- <td>                
                                            <?php echo '0', ' %';
                                            ?>                                        
                                         </td> -->
                                            <td>
                                                <?php
                                                if ($row['status_bkk'] == '0') {
                                                    echo "<button class='btn btn-primary'> Verifikasi Pajak</button>";
                                                } else if ($row['status_bkk'] == '1') {
                                                    echo "<button class='btn btn-primary'> Approval GM Finance</button>";
                                                } else if ($row['status_bkk'] == '2') {
                                                    echo "<button class='btn btn-primary'> Approval Direksi</button>";
                                                } else if ($row['status_bkk'] == '17') {
                                                    echo "<button class='btn btn-warning'> Ready To Pay</button>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="?p=payment_kaskeluar&aksi=edit&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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