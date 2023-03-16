<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'detail') {
        header("location:?p=approval_detailbiayanonops&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            JOIN divisi d
                                            ON d.id_divisi = b.id_divisi
                                            WHERE b.id_manager='$idUser' AND  b.status_bkk='1'  ORDER BY b.kd_transaksi DESC  ");



?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Approval Biaya Non OPS</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <!-- <div class="col-md-2">
                        <a href="index.php?p=tambahmaterial" class=" btn btn-primary"><i class="fa fa-plus"></i> Tambah Data Stok</a><br>						
                </div>
					 -->
                        <!-- <div class="col-md-2 pull-right">
						<a target="_blank" href="cetakstok.php?idjenis=<?= $id_jenis;  ?>" class="btn btn-success"><i class="fa fa-print"></i> Cetak Job Order</a><br>
					</div> -->
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Anggaran</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Divisi</th>
                                    <th>Keterangan</th>
                                    <th>Nama Vendor</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :
                                        $angka_format = number_format($row['jml_bkk'], 2, ",", ".");

                                ?>
                                        <tr>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['kd_transaksi']; ?> </td>
                                            <td> <?= formatTanggalWaktu($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <?= $row['nm_vendor']; ?> </td>
                                            <td> <button class="btn btn-success"><?= formatRupiah(round($row['jml_bkk'])) ?> </button></td>
                                            <!-- <td>                
                                            <?php echo '0', ' %';
                                            ?>                                        
                                         </td> -->


                                            <td>
                                                <a href="?p=approval_biayanonops&aksi=detail&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Detail</button></span></a>

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