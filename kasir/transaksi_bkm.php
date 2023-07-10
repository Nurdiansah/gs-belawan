<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahunSekarang = date("Y");

if (isset($_POST['cari'])) {
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];
    $jmlKarakter = strlen($_POST['bulan']) + 1;
} else {
    $bulan =  getRomawi(date("m"));
    $tahun = date('Y');
    $jmlKarakter = strlen($bulan) + 1;
}

$queryBKM = mysqli_query($koneksi, "SELECT * -- id_bkm, no_bkm, tgl_bkm, nm_divisi, keterangan, a.id_anggaran, nominal, nilai_ppn, nilai_pph, grand_total, doc_bkm, 'BKM Jakarta' AS bkm_from, 'success' AS warna
                                    FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    JOIN divisi c
                                        ON b.id_divisi = c.id_divisi
                                    WHERE status_bkm IN ('5')
                                    AND SUBSTRING(no_bkm, 11, $jmlKarakter) = '$bulan/'
                                    AND RIGHT(no_bkm, 4) = '$tahun'
                                    ORDER BY no_bkm DESC

                                    -- UNION ALL

                                    -- SELECT id_bkm, no_bkm, tgl_bkm, nm_divisi, keterangan, a.id_anggaran, nominal, nilai_ppn, nilai_pph, grand_total, doc_bkm, 'BKM Belawan' AS bkm_from, 'primary' AS warna
                                    -- FROM gs_belawan.bkm b
                                    -- JOIN gs_belawan.anggaran a
                                    --     ON a.id_anggaran = b.id_anggaran
                                    -- JOIN gs_belawan.divisi c
                                    --     ON b.id_divisi = c.id_divisi
                                    -- WHERE status_bkm IN ('7')
                                    ");

$totalBKM = mysqli_num_rows($queryBKM);

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
                <h3 class="text-center">Transaksi Bukti Kas Masuk</h3>
                <div class="box-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="bulan" class="form-control" required>
                                    <option value="I" <?= $bulan == "I" ? "selected" : ""; ?>>Januari</option>
                                    <option value="II" <?= $bulan == "II" ? "selected" : ""; ?>>Februari</option>
                                    <option value="III" <?= $bulan == "III" ? "selected" : ""; ?>>Maret</option>
                                    <option value="IV" <?= $bulan == "IV" ? "selected" : ""; ?>>April</option>
                                    <option value="V" <?= $bulan == "V" ? "selected" : ""; ?>>Mei</option>
                                    <option value="VI" <?= $bulan == "VI" ? "selected" : ""; ?>>Juni</option>
                                    <option value="VII" <?= $bulan == "VII" ? "selected" : ""; ?>>Juli</option>
                                    <option value="VIII" <?= $bulan == "VIII" ? "selected" : ""; ?>>Agustus</option>
                                    <option value="IX" <?= $bulan == "IX" ? "selected" : ""; ?>>September</option>
                                    <option value="X" <?= $bulan == "X" ? "selected" : ""; ?>>Oktober</option>
                                    <option value="XI" <?= $bulan == "XI" ? "selected" : ""; ?>>November</option>
                                    <option value="XII" <?= $bulan == "XII" ? "selected" : ""; ?>>Desember</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset- col-sm-2">
                                <select name="tahun" class="form-control" required>
                                    <?php
                                    foreach (range(2021, $tahunSekarang) as $tahunAyeuna) { ?>
                                        <option value="<?= $tahunAyeuna; ?>" <?= $tahunAyeuna == $tahun ? "selected" : ""; ?>><?= $tahunAyeuna; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="cari" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                        <!-- <button type="submit" name="cetak" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Cetak</button> -->
                    </form>
                </div>
                <div class="box-body">
                    <form action="" method="POST" enctype="multipart/form-data" class="form-horizontal" id="">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?= $totalBKM > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Divisi</th>
                                        <th>Nomor BKM</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Nominal</th>
                                        <!-- <th>Area</th> -->
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($dataBKM = mysqli_fetch_assoc($queryBKM)) { ?>
                                        <tr>
                                            <td><?= $no; ?></td>
                                            <td><?= formatTanggal($dataBKM['tgl_bkm']); ?></td>
                                            <td><?= $dataBKM['nm_divisi']; ?></td>
                                            <td><?= batasiKata($dataBKM['no_bkm']); ?></td>
                                            <td><?= batasiKata($dataBKM['keterangan']); ?></td>
                                            <td><?= $dataBKM['kd_anggaran'] . " [" . $dataBKM['nm_item'] . "]"; ?></td>
                                            <td><?= formatRupiah($dataBKM['grand_total']); ?></td>
                                            <!-- <td><span class="label label-<?= $dataBKM['warna']; ?>"><?= $dataBKM['bkm_from']; ?></span></td> -->
                                            <td>
                                                <a target="_blank" title="Cetak BKM" onclick="window.open('cetak_bkm.php?id=<?= enkripRambo($dataBKM['id_bkm']); ?>','name','width=800,height=600')" class="btn btn-success"><i class="fa fa-print"></i> </a>
                                                <button type="button" class="btn btn-info modalLihat" data-toggle="modal" data-target="#modalLihat" data-id="<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-search" title="Lihat" data-toggle="tooltip"></i></button>
                                                <!-- <button type="button" class="btn btn-info " data-toggle="modal" data-target="#lihat_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-search" title="Lihat" data-toggle="tooltip"></i></button> -->
                                                <!-- <button type="button" class="btn btn-success " data-toggle="modal" data-target="#verifikasi_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-check-square" title="Verifikasi" data-toggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#reject_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-close" title="Reject" data-toggle="tooltip"></i></button> -->
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
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Divisi</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control " value="<?= $dataBKM['nm_divisi']; ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">DPP</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control " value="<?= formatRupiah2($dataBKM['nominal']); ?>" readonly>
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
                                                                    <div class="form-group">
                                                                        <label id="tes" for="nilai_ppn" class="col-sm-2 control-label">PPN 11%</label>
                                                                        <div class="col-sm-2">
                                                                            <input type="checkbox" name="all" id="myCheck" onclick="checkBox()" <?= $dataBKM['nilai_ppn'] != 0 && !is_null($dataBKM['nilai_ppn']) ? 'checked' : ''; ?> disabled>
                                                                            <!-- <input type="checkbox" name="all" id="myCheck" onclick="checkBox()"> -->
                                                                        </div>
                                                                        <div class="col-sm-7">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon">Rp.</span>
                                                                                <input type="text" class="form-control " name="nilai_ppn" id="nilai_ppn" value="<?= formatRupiah2(round($dataBKM['nilai_ppn'])); ?>" readonly />
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label id="tes" for="id_pph" class="col-sm-2 control-label">Jenis PPh</label>
                                                                        <div class="col-sm-9">
                                                                            <select name="id_pph" class="form-control" id="id_pph" value="<?= $dataBKM['id_pph'] ?>" disabled>
                                                                                <option value="">--Jenis PPh--</option>
                                                                                <?php
                                                                                $queryPph = mysqli_query($koneksi, "SELECT * FROM pph ORDER BY nm_pph ASC");
                                                                                if (mysqli_num_rows($queryPph)) {
                                                                                    while ($rowPph = mysqli_fetch_assoc($queryPph)) :
                                                                                ?>
                                                                                        <option value="<?= $rowPph['id_pph']; ?>" data-id="<?= $rowPph['jenis']; ?>" <?= $dataBKM['id_pph'] == $rowPph['id_pph'] ? 'selected' : ''; ?>><?= $rowPph['nm_pph'] ?></option>
                                                                                <?php endwhile;
                                                                                } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <?php if ($dataBKM['id_pph'] == "2" || $dataBKM['id_pph'] == "3") {
                                                                        $pph_persen = ($dataBKM['nilai_pph'] / $dataBKM['grand_total']) * 100;
                                                                    ?>
                                                                        <div id="fixed">
                                                                            <div class="form-group">
                                                                                <!-- <label id="tes" for="nilai_ppn" class="col-sm-offset-1 col-sm-3 control-label" id="rupiah"></label> -->
                                                                                <label id="tes" for="nilai_ppn" class=" col-sm-2 control-label" id="rupiah">Nilai PPh</label>
                                                                                <div class="col-sm-3">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">PPh</span>
                                                                                        <input type="text" required class="form-control " name="pph_persen" value="<?= round($pph_persen); ?>" id="pph_persen" />
                                                                                        <span class="input-group-addon">%</span>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- </div>
                            <div class="form-group"> -->
                                                                                <div class="col-sm-6">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">Rp.</span>
                                                                                        <input type="text" readonly class="form-control " name="nilai_pph" value="<?= formatRupiah2($dataBKM['nilai_pph']); ?>" id="nilai_pph" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } elseif ($dataBKM['id_pph'] == "1") { ?>
                                                                        <div id="progresive">
                                                                            <div class="form-group">
                                                                                <label id="tes" for="nilai_pph2" class="col-sm-2 control-label" id="rupiah">Nilai PPh</label>
                                                                                <div class="col-sm-9">
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-addon">Rp.</span>
                                                                                        <input type="text" class="form-control " name="nilai_pph2" value="<?= $dataBKM['nilai_pph']; ?>" id="nilai_pph2">
                                                                                    </div>
                                                                                    <i><span id="pph_ui"></span></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <div class="col-auto">
                                                                        <div class="form-group">
                                                                            <label id="tes" for="grand_total" class=" col-sm-2 control-label">Grand Total</label>
                                                                            <div class="col-sm-9">
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon">Rp.</span>
                                                                                    <input type="text" required class="form-control" name="grand_total" id="grand_total" readonly value="<?= formatRupiah2($dataBKM['grand_total']); ?>" />
                                                                                </div>
                                                                            </div>
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

    <!-- Modal Lihat -->
    <div id="modalLihat" class="modal fade" role="dialog">
        <div class="modal-dialog " style="width: 90%;">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lihat Bukti Kas Masuk</h4>
                </div>
                <!-- body modal -->
                <form class="form-horizontal" enctype="multipart/form-data" action="" method="POST">
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="col-sm-5">
                                <input type="hidden" name="id_bkm" value="" id="me_id_bkm">
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="" id="me_tgl_bkm" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="" id="me_nm_item" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Nominal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " value="" id="me_nominal" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Divisi</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " value="" id="me_nm_divisi" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Rekening Koran</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " value="" id="me_remarks" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="validationTextarea" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                        <div class="col-sm-8">
                                            <textarea rows="5" class="form-control is-invalid" placeholder="Deskripsi" id="me_keterangan" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <!-- <h3 class="text-center">Document BKM</h3> -->
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="" id="me_doc"></iframe>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-5">
                            </div>
                            <div class="col-sm-7 ">
                                <div class="form-group ">
                                    <div class="table-responsive">
                                        <table class="table text-left table table-striped table-hover" border="2px" id="">
                                            <tr>
                                                <th>Nominal</th>
                                                <th id="dpp"></th>
                                            </tr>
                                            <tr>
                                                <th id="nm_ppn">PPN</th>
                                                <th id="ppn_nilai"></th>
                                            </tr>
                                            <tr>
                                                <th>PPh</th>
                                                <th id="pph_nilai"></th>
                                            </tr>
                                            <tr>
                                                <th>Potongan</th>
                                                <th id="potongan"></th>
                                            </tr>
                                            <tr>
                                                <th>Biaya Lain</th>
                                                <th id="biaya_lain"></th>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <th id="total"></th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="slip_setoran">
                                <div class="form-group">
                                    <h3 class="text-center">Slip Setoran</h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="" id="me_slip"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <!-- <button class="btn btn-success" type="submit" name="verifikasi">Simpan</button></span></a> -->
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    let host = "<?= host(); ?>"

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    $(function() {
        $('.modalLihat').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/bkm/getvrfbkm.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_bkm').val(data.id_bkm);
                    $('#me_tgl_bkm').val(data.tgl_bkm);
                    $('#me_nm_item').val(data.kd_anggaran + ' [' + data.nm_item + ']');
                    $('#me_nominal').val(formatRibuan(Math.round(data.nominal)));
                    $('#me_nm_divisi').val(data.nm_divisi);
                    $('#me_remarks').val(data.remarks);
                    $('#me_keterangan').val(data.keterangan);
                    $('#dpp').text(formatRibuan(Math.round(data.nominal)));
                    $('#ppn_nilai').text(formatRibuan(Math.round(data.nilai_ppn)));
                    $('#id_pph').text(data.id_pph);
                    $('#pph_nilai').text('(' + formatRibuan(Math.round(data.nilai_pph)) + ')');
                    $('#potongan').text(formatRibuan(Math.round(data.potongan)));
                    $('#biaya_lain').text(formatRibuan(Math.round(data.biaya_lain)));
                    $('#total').text(formatRibuan(Math.round(data.grand_total)));

                    let doc_bkm = '../file/bkm/' + data.doc_bkm;
                    $("#me_doc").attr("src", doc_bkm);
                    let bukti_pembayaran = '../file/bkm/' + data.bukti_pembayaran;
                    $("#me_slip").attr("src", bukti_pembayaran);

                }
            });
        });
    });


    function formatRibuan(angka) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');

        return ribuan;
    }

    function hilangkanTitik(data) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value))))); //input ke dalam angka tanpa titik

        return angka;
    }
</script>