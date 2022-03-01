<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    JOIN divisi c
                                        ON b.id_divisi = c.id_divisi
                                    WHERE status_bkm IN ('2')");

if (isset($_POST['verifikasi'])) {
    $id_bkm = $_POST['id_bkm'];

    // buat ngambil data anggaran
    $cekBKM = mysqli_query($koneksi, "SELECT * FROM bkm WHERE id_bkm = '$id_bkm'");
    $cekData = mysqli_fetch_assoc($cekBKM);
    $nominal = $cekData['nominal'];
    $tgl_bkm = $cekData['tgl_bkm'];
    $id_anggaran = $cekData['id_anggaran'];

    mysqli_begin_transaction($koneksi);

    $verifikasi = mysqli_query($koneksi, "UPDATE bkm SET status_bkm = '3', app_costcontrol = NOW()
                                            WHERE id_bkm = '$id_bkm'
                        ");


    if ($verifikasi) {
        mysqli_commit($koneksi);
    } else {
        mysqli_rollback($koneksi);
        echo mysqli_error($koneksi);
    }
    header("Location: index.php?p=verifikasi_bkm");
}

if (isset($_POST['tolak'])) {
    $id_bkm = $_POST['id_bkm'];
    $komentar = "@" . $Nama . " : " . $_POST['komentar'];

    $reject = mysqli_query($koneksi, "UPDATE bkm SET status_bkm = '101', komentar_costcontrol = '$komentar'
                                        WHERE id_bkm = '$id_bkm'
                ");

    if ($reject) {
        header("Location: index.php?p=verifikasi_bkm");
    }
}

$no = 1;

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="col-sm-offset-11">
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buat"><i class="fa fa-edit"></i> Buat </button></span></a> -->
                </div>
                <h3 class="text-center">Verifikasi Bukti Kas Masuk</h3>
                <div class="box-body">
                    <form action="" method="POST" enctype="multipart/form-data" class="form-horizontal" id="">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Nominal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($dataBKM = mysqli_fetch_assoc($queryBKM)) { ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= formatTanggal($dataBKM['tgl_bkm']); ?></td>
                                            <td><?= $dataBKM['keterangan']; ?></td>
                                            <td><?= kodeAnggaran($dataBKM['id_anggaran']); ?>]</td>
                                            <td><?= formatRupiah($dataBKM['grand_total']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info " data-toggle="modal" data-target="#lihat_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-search" title="Lihat" data-toggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-success " data-toggle="modal" data-target="#verifikasi_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-check-square" title="Verifikasi" data-toggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#reject_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-close" title="Reject" data-toggle="tooltip"></i></button>
                                            </td>
                                        </tr>

                                        <!-- Modal Lihat -->
                                        <div id="lihat_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Detail Bukti Kas Masuk</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <form class="form-horizontal">
                                                        <div class="modal-body">
                                                            <div class="perhitungan">
                                                                <div class="box-body">
                                                                    <!-- <div class="form-group">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label"></label>
                                                                        <div class="col-sm-9">
                                                                            <fieldset class="form-control">
                                                                                <div class="col-sm-4">
                                                                                    <input type="checkbox" id="accounting" disabled checked> <label for="accounting"> Verifikasi Accounting<br>2021-11-22 17:17</label>
                                                                                </div>ml_pengajuan"
                                                                    </div> -->
                                                                    <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Tanggal</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control" value="<?= formatTanggal($dataBKM['tgl_bkm']); ?>" readonly>
                                                                        </div>
                                                                        <label for="id_anggaran" class="col-sm-1 control-label">Kode Anggaran</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control" value="<?= kodeAnggaran($dataBKM['id_anggaran']); ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Nominal</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['nominal']); ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">PPN</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['nilai_ppn']); ?>" readonly>
                                                                        </div>
                                                                        <label for="id_anggaran" class="col-sm-1 control-label">PPh</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['nilai_pph']); ?>" readonly>
                                                                        </div>
                                                                    </div> -->
                                                                    <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Divisi</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control text-right" value="<?= $dataBKM['nm_divisi']; ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <div class="form-group">
                                                                            <label for="validationTextarea" class="col-sm-2 control-label">Keterangan</label>
                                                                            <div class="col-sm-9">
                                                                                <textarea rows="8" class="form-control is-invalid" placeholder="Deskripsi" readonly><?= $dataBKM['keterangan']; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="doc">
                                                                        <div class="form-group">
                                                                            <h3 class="text-center">Document BKM</h3>
                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                <iframe class="embed-responsive-item" src="../file/bkm/<?= $dataBKM['doc_bkm']; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <!-- <button class="btn btn-success" type="submit" name="buat">Simpan</button></span></a> -->
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Akhir modal lihat -->

                                        <!-- Modal approve -->
                                        <div id="verifikasi_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog ">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Verifikasi Bukti Kas Masuk</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                                                        <div class="modal-body">
                                                            <div class="perhitungan">
                                                                <div class="box-body">
                                                                    <div class="form-group">
                                                                        <h4 class="text-center">Apakah anda yakin ingin memverifikasi pengajuan <b><?= $dataBKM['keterangan']; ?>?</b></h4>
                                                                        <input type="hidden" value="<?= $dataBKM['id_bkm']; ?>" class="form-control" name="id_bkm">
                                                                    </div>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-success" type="submit" name="verifikasi">Verifikasi</button></span>
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end modal approve -->

                                        <!-- Modal reject -->
                                        <div id="reject_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Alasan Penolakan</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                                                            <div class="box-body">
                                                                <div class="form-group ">
                                                                    <div class="col-sm-4">
                                                                        <input type="hidden" value="<?= $dataBKM['id_bkm']; ?>" class="form-control" name="id_bkm">
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="validationTextarea">Komentar</label>
                                                                    <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required></textarea>
                                                                    <div class="invalid-feedback">
                                                                        Please enter a message in the textarea.
                                                                    </div>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end modal reject -->
                                    <?php $no++;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>