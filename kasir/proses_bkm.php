<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    WHERE status_bkm IN ('3', '4')
                                    ORDER BY id_bkm DESC");

$no = 1;

$totalBKM = mysqli_num_rows($queryBKM);

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="col-sm-offset-11">
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buat"><i class="fa fa-edit"></i> Buat </button></span></a> -->
                </div>
                <h3 class="text-center">Proses Bukti Kas Masuk</h3>
                <div class="box-body">
                    <form action="" method="POST" enctype="multipart/form-data" class="form-horizontal" id="">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?= $totalBKM > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Divisi</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($dataBKM = mysqli_fetch_assoc($queryBKM)) { ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= formatTanggal($dataBKM['tgl_bkm']); ?></td>
                                            <td><?= $dataBKM['nm_divisi']; ?></td>
                                            <td><?= batasiKata($dataBKM['keterangan']); ?></td>
                                            <td><?= kodeAnggaran($dataBKM['id_anggaran']); ?></td>
                                            <td><?= formatRupiah($dataBKM['grand_total']); ?></td>
                                            <td>
                                                <?php if ($dataBKM['status_bkm'] == "3") { ?>
                                                    <span class="label label-success">Approval Cost Control</span>
                                                <?php } elseif ($dataBKM['status_bkm'] == "4") { ?>
                                                    <span class="label label-primary">Approval Manager</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <!-- <a href="index.php?p=dtl_bkm&id=<?= enkripRambo($dataBKM['id_bkm']); ?>" class="btn btn-info"><i class="fa fa-search"></i></a> -->
                                                <button type="button" class="btn btn-info " data-toggle="modal" data-target="#lihat_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-search" title="Lihat" data-toggle="tooltip"></i></button>
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
                                                                            <input type="text" class="form-control" value="<?= kodeAnggaran($dataBKM['id_anggaran']) ?>" readonly>
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
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['ppn']); ?>" readonly>
                                                                        </div>
                                                                        <label for="id_anggaran" class="col-sm-1 control-label">PPh</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['pph']); ?>" readonly>
                                                                        </div>
                                                                    </div> -->
                                                                    <!-- <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Grand Total</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['grand_total']); ?>" readonly>
                                                                        </div>
                                                                    </div> -->
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
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Akhir modal lihat -->

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