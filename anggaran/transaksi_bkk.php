<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['sp'])) {
    $anggaran = dekripRambo($_GET['sp']);
}

$dataAnggaran = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_anggaran = '$anggaran'"));

$queryBKK = mysqli_query($koneksi, "SELECT * FROM bkk_final b
                                    LEFT JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    WHERE a.id_anggaran = '$anggaran'");

$no = 1;

?>


<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi BKK</h3>
                </div>
                <div class="box-body">
                    <br>
                    <h4>Anggaran <b><?= $dataAnggaran['nm_item'] . " [" . $dataAnggaran['kd_anggaran']; ?>]</b></h4>
                    <br>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?= $jumlahData > 0 ? 'material' : '' ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Pengajuan</th>
                                    <th>No BKK</th>
                                    <th>Nilai Barang</th>
                                    <th>Nilai Jasa</th>
                                    <th>Nilai PPN</th>
                                    <th>PPh</th>
                                    <th>Nilai PPh</th>
                                    <th>Nominal</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($dataBKK = mysqli_fetch_assoc($queryBKK)) { ?>
                                    <tr>
                                        <td><?= $no; ?></td>
                                        <td><?= $dataBKK['pengajuan']; ?></td>
                                        <td><?= $dataBKK['no_bkk']; ?></td>
                                        <td><?= formatRupiah2($dataBKK['nilai_barang']); ?></td>
                                        <td><?= formatRupiah2($dataBKK['nilai_jasa']); ?></td>
                                        <td><?= formatRupiah2($dataBKK['nilai_ppn']); ?></td>
                                        <td><?= $dataBKK['id_pph']; ?></td>
                                        <td><?= formatRupiah2($dataBKK['nilai_pph']); ?></td>
                                        <td><?= formatRupiah2($dataBKK['nominal']); ?></td>
                                        <td>
                                            <?php if (!file_exists('../file/bkk_temp/BKK-' . $dataBKK['id'] . '.pdf')) { ?>
                                                <a href="bkk_new.php?id=<?= enkripRambo($dataBKK['id']); ?>&sp=<?= enkripRambo($anggaran); ?>" class="btn btn-primary"><i class="fa fa-repeat"></i> Gabungkan BKK</a>
                                            <?php } else { ?>
                                                <a target="_blank" title="Cetak BKK" onclick="window.open('bkk_new.php?id=<?= enkripRambo($dataBKK['id']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> Cetak BKK</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php $no++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>