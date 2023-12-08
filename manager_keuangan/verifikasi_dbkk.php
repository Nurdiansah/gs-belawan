<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$query =  mysqli_query($koneksi, "SELECT * FROM bkk_final b     
                                            LEFT JOIN anggaran a
                                            ON b.id_anggaran = a.id_anggaran 
                                            -- JOIN supplier s
                                            -- ON b.id_supplier = s.id_supplier                                                                         
                                            WHERE b.id ='$id' ");
$data2 = mysqli_fetch_assoc($query);
$id_kdtransaksi = $data2['id_kdtransaksi'];

$id_tagihan = $data2['id_tagihan'];


// query jika pengajuan bkk kasbon
if ($data2['pengajuan'] == 'KASBON') {
    $queryKasbon =  mysqli_query($koneksi, "SELECT *
                                                    FROM kasbon k
                                                         JOIN biaya_ops bo
                                                         ON k.kd_transaksi = bo.kd_transaksi
                                                         JOIN divisi d
                                                         ON bo.id_divisi = d.id_divisi
                                                         JOIN detail_biayaops db 
                                                         ON k.id_dbo = db.id
                                                         LEFT JOIN anggaran a
                                                         ON db.id_anggaran = a.id_anggaran 
                                                         JOIN supplier s
                                                         ON s.id_supplier = db.id_supplier
                                                         WHERE k.id_kasbon = '$id_kdtransaksi' ");
    $data = mysqli_fetch_assoc($queryKasbon);
    $id_dbo = $data['id'];

    $querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo ");

    // query kasbon user
    $queryKU =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                        JOIN detail_biayaops db 
                                            ON k.id_dbo = db.id
                                        JOIN divisi d
                                            ON d.id_divisi = db.id_divisi
                                        LEFT JOIN anggaran a
                                            ON db.id_anggaran = a.id_anggaran 
                                        JOIN supplier s
                                            ON s.id_supplier = db.id_supplier
                                        WHERE k.id_kasbon = '$id_kdtransaksi' ");
    $dataKU = mysqli_fetch_assoc($queryKU);

    $vrf_pajak = $dataKU['vrf_pajak'];
}

// Ketika pengajuan jenis biaya umum
if ($data2['pengajuan'] == 'BIAYA UMUM') {
    $queryBU = mysqli_query($koneksi, "SELECT * 
                                            FROM bkk b
                                            LEFT JOIN anggaran a
                                            ON a.id_anggaran = b.id_anggaran
                                            WHERE b.kd_transaksi = '$id_kdtransaksi' ");

    $dataBU = mysqli_fetch_assoc($queryBU);
}
// Akhir

// ketika pengajuan PO
if ($data2['pengajuan'] == 'PO') {
    $query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi 
                                            JOIN po p
                                            ON p.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON p.id_dbo = dbo.id
                                            LEFT JOIN anggaran a
                                            ON dbo.id_anggaran = a.id_anggaran
                                            LEFT JOIN pph pp
                                            ON p.id_pph = pp.id_pph
                                            WHERE p.id_po ='$id_kdtransaksi' ");
    $dataPO = mysqli_fetch_assoc($query);

    $id_po = $dataPO['id_po'];

    // var_dump($dataPO['id_pph']); die;

    $id_supplier = $dataPO['id_supplier'];
    $id_anggaran = $dataPO['id_anggaran'];
    $totalPengajuan = $dataPO['grand_totalpo'];

    $id_dbo = $dataPO['id_dbo'];
    $id_divisi = $dataPO['id_divisi'];

    $querySPO =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo");
}
// akhir query PO

// ngecek alasan reapprove sama kasir
$reApprove = mysqli_query($koneksi, "SELECT * FROM reapprove_bkk_final WHERE id_bkk_final = '$id'");
$dataReapp = mysqli_fetch_assoc($reApprove);
$jmlReapp = mysqli_num_rows($reApprove);

?>
<section class="content">
    <div class="row">
        <div class="col-md-2">
            <a href="index.php?p=verifikasi_bkk" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <br><br>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">


                <!-- Detail Job Order -->

                <div class="box-header with-border bg-info">
                    <h3 class="text-center">Verifikasi BKK</h3>
                </div>
                <br>
                <form method="post" action="" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="id_bkk" value="<?= $data2['id'] ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Status</label>
                            <div class="col-sm-3">
                                <?php
                                if ($data2['pengajuan'] == 'KASBON') {
                                    echo "<button class='btn btn-success'> Dibayar</button>";
                                } else {

                                    if (isset($dataBU['jenis']) == 'umum') {
                                        echo "<button class='btn btn-success'> Dibayar</button>";
                                    } else {
                                        echo "<button class='btn btn-warning'> Belum dibayar</button>";
                                    }
                                }

                                ?>
                                <!-- <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['pengajuan'];  ?>"> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['pengajuan'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['created_on_bkk']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan']; ?></textarea>
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">DPP Barang</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= formatRupiah($data2['nilai_barang']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">DPP Jasa</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= formatRupiah($data2['nilai_jasa']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Nilai PPN</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= formatRupiah($data2['nilai_ppn']); ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Nilai PPh</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="id_anggaran" value="<?= "( " . formatRupiah($data2['nilai_pph']) . " )"; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">Kode Anggaran </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_anggaran'] . ' [' . $data2['nm_item'] . ']'; ?>">
                            </div>
                            <label for="pengembalian" class="col-sm-offset-2 col-sm-3 control-label">Pengembalian </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="pengembalian" value="<?= formatRupiah($data2['pengembalian']); ?>">
                            </div>
                            <label for="penambahan" class="col-sm-offset-5 col-sm-3 control-label">Penambahan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="penambahan" value="<?= formatRupiah($data2['penambahan']); ?>">
                            </div>
                            <label for="total" class="col-sm-offset-6 col-sm-3 control-label">Total </label>
                            <div class="col-sm-3">
                                <b><input type="text" disabled class="form-control is-valid" name="total" value="<?= formatRupiah($data2['nominal']); ?>"> </b>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarks" class="col-sm-offset- col-sm-1 control-label">Remarks</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="remarks" disabled class="form-control "> <?= $data2['remarks']; ?></textarea>
                            </div>
                        </div>

                        <?php if ($data2['pengajuan'] == "KASBON") { ?>
                            <div class="form-group">
                                <?php if ($jmlReapp > 0) { ?>
                                    <label for="remarks" class="col-sm-offset- col-sm-1 control-label">Alasan Pengajuan Kembali</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="remarks" class="form-control" disabled><?= $dataReapp['alasan_reapprove_kasir']; ?></textarea>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } elseif ($data2['pengajuan'] == 'BIAYA KHUSUS') { ?>
                            <div class="form-group">
                                <label for="remarks" class="col-sm-offset- col-sm-1 control-label">Remarks</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="remarks" class="form-control "><?php
                                                                                                        if (isset($data2['remarks'])) {
                                                                                                            echo $data2['remarks'];
                                                                                                        }
                                                                                                        ?></textarea>
                                </div>

                                <?php if ($jmlReapp > 0) { ?>
                                    <label for="remarks" class="col-sm-offset-4 col-sm-1 control-label">Alasan Pengajuan Kembali</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="remarks" class="form-control" disabled><?= $dataReapp['alasan_reapprove_kasir']; ?></textarea>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php
                            if (isset($_COOKIE['pesan'])) {
                                echo "<div class='form-group'>
                                        <label class='col-sm-offset- col-sm-2'></label>
                                        <span class='text-success'>" . $_COOKIE['pesan'] . "</span>
                                      </div>";
                            }
                            ?>
                            <div class="form-group">
                                <button type="submit" name="simpan" class="col-xs-offset-3 btn bg-primary"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                            <?php }

                        $doc = "../file/doc_pendukung/" . $data2['doc_pendukung'];
                        if (!is_null($data2['doc_pendukung'])) {
                            if (file_exists($doc)) { ?>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe src="../file/pdfjs/web/viewer.html?file=../../doc_pendukung/<?= $data2['doc_pendukung']; ?> " frameborder="0" width="100%" height="550"></iframe>
                                            <!-- <iframe class="embed-responsive-item" src="<?= $doc; ?>"></iframe> -->
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </form>

                <br>
                <!-- 
                    #escalte : Persetujuan dengan pemilihan direktur
                    #Approve : Persetujuan normal 
                    #Approve2 : Persetujuan tanpa melalui direktur

                 -->

                <?php if ($data2['pengajuan'] == 'BIAYA KHUSUS') { ?>
                    <div class="form-group ">
                        <!-- <button type="button" class="btn btn-primary col-sm-offset-1" data-toggle="modal" data-target="#escalate"> Escalate </button></span></a>
                        &nbsp; -->
                        <!-- <button type="button" class="btn btn-primary col-sm-offset-9" data-toggle="modal" data-target="#approve"> Approve </button></span></a> -->
                        &nbsp;
                        <button type="button" class="btn btn-success col-sm-offset-9" data-toggle="modal" data-target="#approve2"> Approve </button></span></a>
                        &nbsp;
                        <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak">Reject </button></span></a>
                        &nbsp;
                        <a target="_blank" href="bkk_new.php?id=<?= enkripRambo($data2['id']); ?>" class="btn btn-primary"><i class="fa fa-print"></i> BKK</a>
                    </div>
                <?php } else { ?>
                    <div class="form-group ">
                        <!-- <button type="button" class="btn btn-primary col-sm-offset-1" data-toggle="modal" data-target="#escalate"> Escalate </button></span></a>
                        &nbsp; -->
                        <button type="button" class="btn btn-primary col-sm-offset-9" data-toggle="modal" data-target="#approve"> Approve </button></span></a>
                        &nbsp;
                        <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#tolak"> Reject </button></span></a>
                        &nbsp;
                        <a target="_blank" href="bkk_new.php?id=<?= enkripRambo($data2['id']); ?>" class="btn btn-success"><i class="fa fa-print"></i> BKK</a>
                    </div>
                <?php } ?>

                <!-- Tombol detail kasbon purchasing  -->
                <?php
                if (isset($dataKU['from_user'])) {
                    if ($data['from_user'] == '0') { ?>
                        <div class="row">
                            <div class="col-sm-offset-10 col-sm-1">
                                <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-kasbon-purchasing" aria-expanded="false" aria-controls="collapseExample">
                                    <i id="logo" class=""></i>
                                    <span id="tmlKp"></span>
                                </button>
                            </div>
                        </div>
                <?php }
                } ?>
                <!-- Akhir tombol detail kasbon purchasing  -->

                <!-- Tombol detail kasbon user -->
                <?php
                if (isset($dataKU['from_user'])) {
                    if ($dataKU['from_user'] == '1') { ?>
                        <div class="row">
                            <div class="col-sm-offset-10 col-sm-1">
                                <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-kasbon-user" aria-expanded="false" aria-controls="collapseExample">
                                    <i id="logo" class=""></i>
                                    <span id="tmlKp"></span>
                                </button>
                            </div>
                        </div>
                <?php }
                } ?>
                <!-- Akhir tombol detail kasbon user -->

                <!-- Tombol detail biaya umum -->
                <?php
                if ($data2['pengajuan'] == 'BIAYA UMUM') {
                ?>
                    <div class="row">
                        <div class="col-sm-offset-10 col-sm-1">
                            <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-biaya-umum" aria-expanded="false" aria-controls="collapseExample">
                                <i id="logo" class=""></i>
                                <span id="tmlKp"></span>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <!-- Akhir tombol detail biaya umum -->

                <!-- Tombol detail po -->
                <?php if ($data2['pengajuan'] == 'PO') { ?>
                    <div class="row">
                        <div class="col-sm-offset-9 col-sm-3">
                            <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#clp-tagihan" aria-expanded="false" aria-controls="collapseExample">
                                <i id="logoTagihan" class=""></i>
                                <span id="tblTagihan">Riwayat Tagihan</span>
                            </button>
                            <button class="btn btn-warning" type="button" data-toggle="collapse" data-target="#clp-po" aria-expanded="false" aria-controls="collapseExample">
                                <i id="logo" class=""></i>
                                <span id="tmlKp"></span>
                            </button>
                        </div>
                    </div>
                <?php } ?>
                <!-- Akhir tombol detail po -->

                <br>
            </div>
        </div>
    </div>


    <!-- 
/
/
/
 -->
    <!-- Box untuk detail kasbon purchasing -->

    <div class="collapse" id="clp-kasbon-purchasing">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="box box-warning">
                    <!-- Detail Kasbon Purchasing -->
                    <hr>
                    <div class="box-header with-border bg-danger ">
                        <h3 class="text-center">Detail Kasbon</h3>
                    </div>
                    <br><br>
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-1 control-label">Nama Barang</label>
                            <input type="hidden" required class="form-control is-valid" name="id_kasbon" value="<?= $data['id_kasbon']; ?>">
                            <input type="hidden" required class="form-control is-valid" name="id" value="<?= $data['id']; ?>">
                            <div class="col-sm-3">
                                <input type="text" readonly class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="divisi" class="col-sm-offset-1 col-sm-3 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control " name="divisi" value="<?= $data['nm_divisi']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="merk" class="col-sm-offset col-sm-1 control-label">Merk </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="merk" value="<?= $data['merk']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="type" class="col-sm-offset-1 col-sm-3 control-label">Type</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control " name="type" value="<?= $data['type']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="jumlah" class="col-sm-offset col-sm-1 control-label">Jumlah</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="jumlah" value="<?= $data['jumlah']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="satuan" class="col-sm-offset-1 col-sm-3 control-label">Satuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control " name="satuan" value="<?= $data['satuan']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="satuan" class="col-sm-offset col-sm-1 control-label">Spesifikasi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="spesifikasi" value="<?= $data['spesifikasi']; ?>">
                            </div>
                            <!-- </div>
                            <div class="form-group"> -->
                            <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" name="id_anggaran" disabled>
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>

                            <!-- JIKA DITABEL REAPPROVE ADA DATANYA MAKA NAMPILIN ALASAN RE APPROVE -->
                            <?php if ($dataReapp['alasan_reapprove_mgrga'] != NULL) { ?>
                                <label for="alasan_reapprove" class="col-sm-offset-1 col-sm-3 control-label">Alasan Approve Kembali</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "><?= $dataReapp['alasan_reapprove_mgrga']; ?></textarea>
                                </div>
                            <?php } ?>
                            <!-- END REAPPROVE -->
                        </div>
                        <hr>
                        <div class="box-header with-border">
                            <h3 class="text-center">Rincian Barang</h3>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-dark table-hover ">
                                <thead style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Deskripsi</th>
                                    <th>QTY</th>
                                    <th>Unit</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                </thead>
                                <tr>
                                    <tbody>
                                        <tr>
                                            <?php
                                            $no = 1;
                                            $total = 0;
                                            if (mysqli_num_rows($querySbo)) {
                                                while ($row = mysqli_fetch_assoc($querySbo)) :

                                            ?>
                                                    <td> <?= $no; ?> </td>
                                                    <td> <?= $row['sub_deskripsi']; ?> </td>
                                                    <td> <?= $row['sub_qty']; ?> </td>
                                                    <td> <?= $row['sub_unit']; ?> </td>
                                                    <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
                                                    <td><?= formatRupiah(round($row['total_price'])); ?></td>
                                        </tr>
                                <?php
                                                    $total += $row['total_price'];
                                                    $no++;
                                                endwhile;
                                            } ?>
                                <tr style="background-color :#B0C4DE;">
                                    <td colspan="5"><b>Total</b></td>
                                    <td><b><?= formatRupiah($total); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>PPN</b></td>
                                    <td><b><?= formatRupiah(round($data['nilai_ppn'])); ?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>PPh</b></td>
                                    <td><b>(<?= formatRupiah(round($data['nilai_pph'])); ?>)</b></td>
                                </tr>
                                <?php if ($data['pengembalian'] > 0) { ?>
                                    <tr>
                                        <td colspan="5"><b>Pengembalian</b></td>
                                        <td><b>(<?= formatRupiah(round($data['pengembalian'])); ?>)</b></td>
                                    </tr>
                                <?php } ?>
                                <tr style="background-color :#B0C4DE;">
                                    <td colspan="5"><b>Grand Total</b></td>
                                    <td><b><?= formatRupiah(round($data['harga_akhir'])); ?></b></td>
                                </tr>
                                    </tbody>
                            </table>
                        </div>
                        <!-- Akhir table -->
                        <div class="row">
                            <?php
                            $foto = $data['foto_item'];

                            $doc_penawaran = $data['doc_penawaran'];
                            $harga_estimasi = number_format($data['harga_estimasi'], 0, ",", ".");

                            if ($foto === '0') { ?>
                                <h3 class="text-center">Foto Barang</h3>
                                <br>
                                <div class="col-sm-offset-">
                                    <h5 class="text-center">Tidak Ada Foto</h5>
                                </div>
                            <?php } else { ?>
                                <div class="col-sm-6">
                                    <h3 class="text-center">BA/Foto Barang</h3>
                                    <!-- format pdf baru -->
                                    <iframe src="../file/pdfjs/web/viewer.html?file=../../foto/<?php echo $data['foto_item']; ?> " frameborder="0" width="100%" height="550"></iframe>
                                    <!-- format pdf lama  -->
                                    <!-- <div class="embed-responsive embed-responsive-4by3">
                                        <iframe class="embed-responsive-item" src="../file/foto/< $data['foto_item']; ?>"></iframe>
                                    </div> -->
                                </div>
                            <?php } ?>

                            <!-- Embed Document               -->
                            <?php

                            if (!is_null($doc_penawaran)) { ?>
                                <div class="col-sm-6">
                                    <h3 class="text-center">Document Penawaran</h3>
                                    <!-- format pdf baru -->
                                    <iframe src="../file/pdfjs/web/viewer.html?file=../../doc_penawaran/<?php echo $data['doc_penawaran']; ?> " frameborder="0" width="100%" height="550"></iframe>
                                    <!-- format pdf lama  -->
                                    <!-- <div class="embed-responsive embed-responsive-4by3">
                                        <iframe class="embed-responsive-item" src="../file/doc_penawaran/< echo $data['doc_penawaran'] ?> "></iframe>
                                    </div> -->
                                </div>
                            <?php    } ?>

                        </div>

                        <?php
                        if ($data2['pengajuan'] == 'KASBON') { ?>
                            <?php if (!empty($data['doc_lpj'])) {

                                $doc =  "../file/doc_lpj/" . $data['doc_lpj'];
                                if (file_exists($doc)) { ?>
                                    <h3 class="text-center">Document LPJ</h3>
                                    <!-- format pdf baru -->
                                    <iframe src="../file/pdfjs/web/viewer.html?file=../../doc_lpj/<?php echo $data['doc_lpj']; ?> " frameborder="0" width="100%" height="550"></iframe>
                                    <!-- format pdf lama  -->
                                    <!-- <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="../file/doc_lpj/< echo $data['doc_lpj']; ?> "></iframe>                                        
                                    </div> -->
                                <?php } else {
                                    echo "";
                                } ?>

                            <?php } ?>
                        <?php } ?>

                        <!-- Akhir kasbon purchasing -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir detail kasbon purchasing -->

    <!-- Box untuk detail kasbon user -->
    <div class="collapse" id="clp-kasbon-user">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="box box-warning">
                    <!-- Detail Kasbon user -->
                    <hr>
                    <div class="box-header with-border bg-danger ">
                        <h3 class="text-center">Detail Kasbon</h3>
                    </div>
                    <br><br>
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="tanggal" class="col-sm-offset col-sm-2 control-label">Tanggal Pengajuan</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="tanggal" value="<?= formatTanggal($dataKU['tgl_kasbon']); ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label for="satuan" class="col-sm-offset- col-sm-2 control-label">Divisi</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control " name="satuan" value="<?= $dataKU['nm_divisi']; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="nominal" for="nominal" class="col-sm-offset col-sm-2 control-label">Nominal</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="nominal" value="<?= formatRupiah($dataKU['harga_akhir']); ?>">
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->
                                <label for="id_anggaran" class="col-sm-offset- col-sm-2 control-label">Kode Anggaran</label>
                                <div class="col-sm-3">
                                    <select class="form-control select2" name="id_anggaran" disabled>
                                        <option value="<?= $dataKU['id_anggaran']; ?>"><?= $dataKU['kd_anggaran'] . ' ' . $dataKU['nm_item']; ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label id="tes" for="nm_barang" class="col-sm-offset col-sm-2 control-label"></label>
                                <div class="col-sm-3">
                                    <!-- <input type="text" readonly class="form-control is-valid" name="nm_barang"> -->
                                </div>
                                <!-- </div>
                            <div class="form-group"> -->

                                <label for="keterangan" class="col-sm-offset- col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $dataKU['keterangan']; ?></textarea>
                                </div>
                            </div>
                            <?php if ($dataReapp['alasan_reapprove_mgr'] != NULL) { ?>
                                <div class="form-group">
                                    <label for="alasan_reapprove" class="col-sm-offset- col-sm-2 control-label">Alasan Reapprove</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "> <?= $dataReapp['alasan_reapprove_mgr']; ?></textarea>
                                    </div>

                                    <label for="waktu_reapprove" class="col-sm-offset- col-sm-2 control-label">Waktu Reapprove</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="waktu_reapprove" disabled class="form-control "> <?= $dataReapp['waktu_reapprove_mgr']; ?></textarea>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- <div class="form-group "> -->
                            <div class="col-sm-8">
                                <!-- <div class="box-header with-border"> -->
                                <h3 class="text-center">Document Pendukung </h3>
                                <iframe src="../file/pdfjs/web/viewer.html?file=../../doc_pendukung/<?= $dataKU['doc_pendukung']; ?>" frameborder="0" width="100%" height="550"></iframe>
                                <!-- <div class="embed-responsive embed-responsive-4by3">
                                    <iframe class="embed-responsive-item" src="../file/doc_pendukung/ $dataKU['doc_pendukung']; ?>" id="ml_doc"></iframe>
                                </div> -->
                                <!-- </div> -->
                            </div>
                            <!-- </div> -->
                            <!-- Rincian Harga -->
                            <?php if ($vrf_pajak == 'bp') { ?>
                                <div class="col-sm-4">
                                    <h3 class="text-center">Rincian Harga</h3>
                                    <div class="table-responsive">
                                        <table class="table" border="2px">
                                            <tr style="background-color :#B0C4DE;">
                                                <td colspan="5"><b>Nilai Barang</b></td>
                                                <td><b><?= formatRupiah($dataKU['nilai_barang']); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>Nilai Jasa</b></td>
                                                <td><b><?= formatRupiah($dataKU['nilai_jasa']); ?></b></td>
                                            </tr>
                                            <tr style="background-color :#B0C4DE;">
                                                <td colspan="5"><b>PPN</b></td>
                                                <td><b><?= formatRupiah($dataKU['nilai_ppn']); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>PPh</b></td>
                                                <td><b>(<?= formatRupiah($dataKU['nilai_pph']); ?>)</b></td>
                                            </tr>
                                            <tr style="background-color :#B0C4DE;">
                                                <td colspan="5"><b>Grand Total</b></td>
                                                <td style="float-right"><b><?= formatRupiah($dataKU['harga_akhir']); ?></b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-sm-12">
                                <!-- <div class="box-header with-border"> -->
                                <h3 class="text-center">Document LPJ </h3>
                                <iframe src="../file/pdfjs/web/viewer.html?file=../../doc_lpj/<?= $dataKU['doc_lpj']; ?>" frameborder="0" width="100%" height="550"></iframe>
                                <!-- <div class="embed-responsive embed-responsive-4by3">
                                    <iframe class="embed-responsive-item" src="../file/doc_pendukung/ $dataKU['doc_pendukung']; ?>" id="ml_doc"></iframe>
                                </div> -->
                                <!-- </div> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir detail kasbon user -->

    <!-- Box untuk detail biaya umum -->
    <div class="collapse" id="clp-biaya-umum">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="box box-warning">
                    <!-- Detail biaya umum -->
                    <hr>
                    <div class="box-header with-border bg-danger ">
                        <h3 class="text-center">Detail Biaya Umum</h3>
                    </div>
                    <br><br>
                    <form method="post" enctype="multipart/form-data" action="approval.php" class="form-horizontal">
                        <div class="box-body">

                            <div class="form-group ">
                                <label for="id_joborder" class=" col-sm-2 control-label">Kode Transaksi</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $dataBU['kd_transaksi']; ?>" disabled class="form-control" name="id_bkk">
                                </div>
                                <!-- </div>
                    <div class="form-group "> -->
                                <label id="tes" for="tgl_bkk" class=" col-sm-2 control-label">Tanggal Pengajuan</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $dataBU['tgl_pengajuan']; ?>" disabled class="form-control" name="tgl_bkk">
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="tes" for="nm_vendor" class=" col-sm-2 control-label">Nama Vendor</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $dataBU['nm_vendor']; ?>" disabled class="form-control" name="nm_vendor">
                                </div>
                                <!-- </div>
                    <div class="form-group"> -->
                                <label for="kd_transaksi" class="col-sm-2 control-label">Kode Anggaran</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $dataBU['kd_anggaran']; ?>" class="form-control " name="kd_transaksi" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $dataBU['keterangan']; ?>" class="form-control " name="keterangan" readonly>
                                </div>
                                <!-- </div>
                    <div class="form-group"> -->
                                <label for="terbilang_bkk" class=" col-sm-2 control-label">Terbilang</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= $dataBU['terbilang_bkk'] . ' Rupiah'; ?>" disabled class="form-control tanggal" name="terbilang_bkk">
                                </div>
                            </div>
                            <!-- <?php if ($totalReapp > 0) { ?>
                                <div class="form-group">
                                    <label for="reapprove_manager" class="col-sm-2 control-label">Alasan Reapprove Manager</label>
                                    <div class="col-sm-3">
                                        <textarea class="form-control" name="reapprove_manager" disabled><?= $dataReapp['alasan_reapprove_mgr']; ?></textarea>
                                    </div>
                                </div>
                            <?php } ?> -->
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Jenis</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= strtoupper($dataBU['jenis']) ?>" readonly class="form-control" name="nilai_bkk">
                                </div>
                            </div>
                            <?php
                            if ($dataBU['jenis'] == 'kontrak') { ?>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Tanggal Tempo</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= formatTanggalHari($dataBU['tgl_tempo']);  ?>" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Tanggal Pembayaran </label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= formatTanggalHari($dataBU['tgl_payment']);  ?>" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                </div>
                            <?php } ?>
                            <hr>
                            <div class="form-group">
                                <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Metode Pembayaran</label>
                                <div class="col-sm-3">
                                    <input type="text" value="<?= strtoupper($dataBU['metode_pembayaran']) ?>" readonly class="form-control" name="nilai_bkk">
                                </div>
                            </div>
                            <?php
                            if ($dataBU['metode_pembayaran'] == 'transfer') { ?>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Bank Tujuan</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $dataBU['bank_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">No Rekening</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $dataBU['norek_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label id="tes" for="nilai_bkk" class="col-sm-2 control-label">Nama Penerima</label>
                                    <div class="col-sm-3">
                                        <input type="text" value="<?= $dataBU['penerima_tujuan'];  ?>" readonly class="form-control" name="nilai_ppn">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </form>


                    <!-- Embed Document               -->
                    <!-- Document PTW -->
                    <!-- <div class="box-header with-border"> -->
                    <div class="row">
                        <div class="col-sm-7">
                            <h3 class="text-center">Invoice </h3>
                            <!-- format pdf baru -->
                            <iframe src="../file/pdfjs/web/viewer.html?file=../../<?php echo $dataBU['invoice']; ?> " frameborder="0" width="100%" height="550"></iframe>

                            <!--  format pdf lama -->
                            <!-- <div class="embed-responsive embed-responsive-4by3">
                                <iframe class="embed-responsive-item" src="../file/echo $dataBU['invoice']; ?> "></iframe>
                            </div> -->
                        </div>
                        <div class="col-sm-4">
                            <h3 class="text-center">Rincian </h3>
                            <div class="table-responsive">
                                <table class="table text-right table-striped table-hover" id=" ">
                                    <thead style="background-color: royalblue;">
                                        <tr>
                                            <th class="text-center">Deskripsi</th>
                                            <th class="text-center">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">Nilai Barang</td>
                                            <td><?= formatRupiah($dataBU['nilai_barang']);  ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Nilai Jasa</td>
                                            <td><?= formatRupiah($dataBU['nilai_jasa']);  ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Nilai PPN</td>
                                            <td><?= formatRupiah($dataBU['ppn_nilai']);  ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Nilai PPh</td>
                                            <td><?= formatRupiah($dataBU['pph_nilai']);  ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Biaya Lain</td>
                                            <td><?= formatRupiah($dataBU['biaya_lain']);  ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">Potongan</td>
                                            <td>(<?= formatRupiah($dataBU['potongan']);  ?>)</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">Grand Total</th>
                                            <td><?= formatRupiah($dataBU['jml_bkk']);  ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php
                    if ($dataBU['doc_lpj'] != '') {
                        if (file_exists("../file/bukti_pembayaran/" . $dataBU['doc_lpj'] . "")) {
                    ?>
                            <div class="row">
                                <!--  -->
                                <div class="col-sm-7">
                                    <h3 class="text-center">Bukti Pembayaran </h3>
                                    <iframe src="../file/pdfjs/web/viewer.html?file=../../bukti_pembayaran/<?php echo $dataBU['doc_lpj']; ?> " frameborder="0" width="100%" height="550"></iframe>
                                </div>
                                <!--  -->
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir detail biaya umum -->


    <!-- Box untuk detail po-->
    <div class="collapse" id="clp-tagihan">
        <div class="box box-warning">
            <!-- Detail po-->
            <hr>
            <div class="box-header with-border bg-danger ">
                <h3 class="text-center">Riwayat Tagihan</h3>
            </div>
            <div class="table-responsive datatab">
                <table class="table text-center table table-striped table-dark table-hover ">
                    <thead style="background-color :#B0C4DE;">
                        <tr>
                            <th>No</th>
                            <th>Tgl Invoice</th>
                            <th>Tgl Tempo</th>
                            <th>Nominal</th>
                            <th>%</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $queryTagihan =  mysqli_query($koneksi, "SELECT *, tp.persentase AS tppersentase
                                                                    FROM tagihan_po tp
                                                                    JOIN po p
                                                                        ON p.id_po = tp.po_id
                                                                        AND metode_pembayaran = 'Transfer'
                                                                    JOIN bkk_ke_pusat bf
                                                                        ON id = bkk_id
                                                                    WHERE tp.po_id = '$id'
                                                                    
                                                                    UNION ALL

                                                                    SELECT *, tp.persentase AS tppersentase
                                                                    FROM tagihan_po tp
                                                                    JOIN po p
                                                                        ON p.id_po = tp.po_id
                                                                        AND metode_pembayaran = 'Tunai'
                                                                    JOIN bkk_final bf
                                                                        ON id = bkk_id
                                                                    WHERE tp.po_id = '$id'
                                                        ");

                        $no = 1;
                        // $total = 0;
                        if (mysqli_num_rows($queryTagihan)) {
                            while ($row = mysqli_fetch_assoc($queryTagihan)) :

                        ?>
                                <tr>
                                    <td> <?= $no; ?> </td>
                                    <td> <?= formatTanggal($row['tgl_buat']); ?> </td>
                                    <td> <?= formatTanggal($row['tgl_tempo']); ?> </td>
                                    <td> <?= formatRupiah(round($row['nominal'])); ?> </td>
                                    <td><?= $row['tppersentase']; ?></td>
                                    <td>
                                        <?php
                                        if ($row['status_tagihan'] < 4) {
                                            # code...
                                            echo "<button class='btn btn-warning'>Belum di bayar</button>";
                                        } else if ($row['status_tagihan'] == 5) {
                                            echo "<button class='btn btn-success'>Terbayar</button>";
                                        }

                                        ?>

                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#lihat_<?= $row['id_tagihan']; ?>"><i class="fa fa-folder-open" title="Lihat" data-toggle="tooltip"></i></button>
                                    </td>
                                </tr>

                                <!-- Modal Lihat -->
                                <div id="lihat_<?= $row['id_tagihan']; ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <!-- konten modal-->
                                        <div class="modal-content">
                                            <!-- heading modal -->
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Invoice PO [<?= $row['po_number']; ?>], pembayaran ke-<?= $no . " (" .  $row['tppersentase']; ?>%)</h4>
                                            </div>
                                            <!-- body modal -->
                                            <form class="form-horizontal">
                                                <div class="modal-body">
                                                    <div class="perhitungan">
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <?php if (file_exists("../file/invoice/" . $row['doc_faktur']) && !empty($row['doc_faktur'])) { ?>
                                                                    <div class="embed-responsive embed-responsive-16by9">
                                                                        <iframe class="embed-responsive-item" src="../file/invoice/<?= $row['doc_faktur']; ?>"></iframe>
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <h4 class="text-center">Document tidak ada</h4>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class=" modal-footer">
                                                            <input type="reset" value="Close" data-dismiss="modal" class="btn btn-default">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php
                                // $total += $row['total_price'];
                                $no++;
                            endwhile;
                        }

                        $queryT =  mysqli_query($koneksi, "SELECT * FROM tagihan_po WHERE id_tagihan ='$id_tagihan' ");
                        $dataTagihan =  mysqli_fetch_assoc($queryT);


                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Akhir detail po-->

    <!-- Box untuk detail po-->
    <div class="collapse" id="clp-po">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="box box-warning">
                    <!-- Detail po-->
                    <hr>
                    <div class="box-header with-border bg-danger ">
                        <h3 class="text-center">Detail PO</h3>
                    </div>
                    <br><br>

                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $dataPO['nm_divisi'];  ?>">
                                </div>
                                <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($dataPO['tgl_pengajuan']); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                                <div class="col-sm-3">
                                    <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $dataPO['keterangan']; ?></textarea>
                                </div>
                                <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">PO Number</label>
                                <div class="col-sm-3">
                                    <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $dataPO['po_number']; ?>">
                                </div>
                            </div>
                            <?php if ($dataReapp['alasan_reapprove_pajak'] != NULL) { ?>
                                <div class="form-group">
                                    <label for="alasan_reapprove" class="col-sm-offset- col-sm-1 control-label">Alasan Setuju Kembali</label>
                                    <div class="col-sm-3">
                                        <textarea rows="5" type="text" name="alasan_reapprove" disabled class="form-control "> <?= $dataReapp['alasan_reapprove_pajak']; ?></textarea>
                                    </div>
                                    <label for="waktu_reapprove" class="col-sm-offset-2 col-sm-3 control-label">Waktu Setuju Kembali</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled class="form-control is-valid" name="waktu_reapprove" value="<?= $dataReapp['waktu_reapprove_pajak']; ?>">
                                    </div>
                                </div>
                            <?php } ?>
                            <br>
                        </div>
                    </form>

                    <!--  -->
                    <div class="box-header with-border">
                        <h3 class="text-center">Rincian Barang</h3>
                    </div>
                    <div class="table-responsive datatab">
                        <table class="table text-center table table-striped table-dark table-hover ">
                            <thead style="background-color :#B0C4DE;">
                                <th>No</th>
                                <th>Deskripsi</th>
                                <th>QTY</th>
                                <th>Unit</th>
                                <th>Unit Price</th>
                                <th style="text-align: right;">Total Price</th>
                            </thead>
                            <tr>
                                <tbody>
                                    <tr>
                                        <?php
                                        $no = 1;
                                        $total = 0;
                                        if (mysqli_num_rows($querySPO)) {
                                            while ($row = mysqli_fetch_assoc($querySPO)) :

                                        ?>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= $row['sub_deskripsi']; ?> </td>
                                                <td> <?= $row['sub_qty']; ?> </td>
                                                <td> <?= $row['sub_unit']; ?> </td>
                                                <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
                                                <td style="text-align: right;"> <?= formatRupiah($row['total_price']); ?></td>
                                    </tr>
                            <?php
                                                $total += $row['total_price'];
                                                $no++;
                                            endwhile;
                                        } ?>
                            <tr style="background-color :#B0C4DE;">
                                <td style="text-align: right;" colspan="5"><b>Sub Total</b></td>
                                <td style="text-align: right;"><b> <?= formatRupiah($dataPO['sub_totalpo']); ?></b></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" colspan="5"><b>Diskon </b></td>
                                <td style="text-align: right;"><b> <?= formatRupiah($dataPO['diskon_po']); ?></b></td>
                            </tr>
                            <?php
                            $total = $dataPO['sub_totalpo'] - $dataPO['diskon_po'];
                            $grandTotal = $total + $dataPO['nilai_ppn'];
                            ?>
                            <tr style="background-color :#B0C4DE;">
                                <td style="text-align: right;" colspan="5"><b>Total </b></td>
                                <td style="text-align: right;"><b> <?= formatRupiah($dataPO['total_po']); ?></b></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" colspan="5"><b> PPN 10% </b></td>
                                <td style="text-align: right;"><b> <?= formatRupiah($dataPO['nilai_ppn']); ?></b></td>
                            </tr>
                            <tr>
                                <td style="text-align: right;" colspan="5"><b> Nilai <?= $dataPO['nm_pph']; ?> </b></td>
                                <td style="text-align: right;"><b> (<?= formatRupiah($dataPO['nilai_pph']); ?>)</b></td>
                            </tr>
                            <tr style="background-color :#B0C4DE;">
                                <td style="text-align: right;" colspan="5"><b> Grand Total </b></td>
                                <td style="text-align: right;"><b> <?= formatRupiah($dataPO['grand_totalpo']); ?></b></td>
                            </tr>
                                </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <?php
                        $foto = $dataPO['foto_item'];
                        if ($foto === '0') { ?>
                            <h3 class="text-center">Foto Barang</h3>
                            <br>
                            <div class="row ">
                                <div class="col-sm-offset-">
                                    <h5 class="text-center">Tidak Ada Foto</h5>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-sm-12">
                                <div class="box-header with-border">
                                    <h3 class="text-center">BA/Foto</h3>
                                    <div class="embed-responsive embed-responsive-4by3">
                                        <iframe class="embed-responsive-item" src="../file/pdfjs/web/viewer.html?file=../../foto/<?= $dataPO['foto_item']; ?>"></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <br>
                    <!-- Embed Document -->
                    <?php if ($dataPO['doc_quotation'] != '') { ?>
                        <div class="col-sm-12">
                            <div class="box-header with-border">
                                <h3 class="text-center">Document Quotation</h3>
                                <div class="embed-responsive embed-responsive-4by3">
                                    <iframe class="embed-responsive-item" src="../file/pdfjs/web/viewer.html?file=../../doc_quotation/<?php echo $dataPO['doc_quotation']; ?> "></iframe>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <br>
                    <div class="box-header with-border">
                        <h3 class="text-center">Document Penawaran</h3>
                        <div class="embed-responsive embed-responsive-4by3">
                            <iframe class="embed-responsive-item" src="../file/pdfjs/web/viewer.html?file=../../doc_penawaran/<?php echo $dataPO['doc_penawaran']; ?> "></iframe>
                        </div>
                    </div>
                    <br>

                    <!-- Akhir -->
                </div>
            </div>
        </div>
    </div>
    <!-- Akhir detail po-->

    <!-- Approved dengan pemilihan direktur  -->
    <div id="escalate" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Konfirmasi </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk3.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <h4 class="text-center">Apakah anda yakin ingin menyetujui bkk ini? <br> jika ya silahkan pilih direktur pada kolom di bawah .</h4>
                            </div>
                            <input type="hidden" name="id" value="<?= $dataPO['id']; ?>">
                            <div class="form-group">
                                <label id="tes" for="id_direktur" class="col-sm-offset-1 col-sm-3 control-label">Direktur</label>
                                <div class="col-sm-5">
                                    <select name="id_direktur" class="form-control" required>
                                        <option value="">-- Pilih direktur --</option>
                                        <?php
                                        $queryDirektur = mysqli_query($koneksi, "SELECT * FROM user WHERE level = 'direktur' ORDER BY nama ASC");
                                        if (mysqli_num_rows($queryDirektur)) {
                                            while ($rowDirektur = mysqli_fetch_assoc($queryDirektur)) :
                                        ?>
                                                <option value="<?= $rowDirektur['id_user']; ?>" type="checkbox"><?= $rowDirektur['nama']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Kirim</button></span>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  -->

    <!-- Approved normal -->
    <div id="approve" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Konfirmasi </h4>
                </div>
                <form method="post" enctype="multipart/form-data" action="setuju_bkk4.php" class="form-horizontal">
                    <!-- body modal -->
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $data2['id']; ?>">
                        <div class="box-body">
                            <h4 class="text-center">Apakah anda yakin ingin menyetujui BKK ini ?</h4>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit"> Yes </button>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->

    <!-- Approved tanpa persetujuan direktur -->
    <div id="approve2" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Konfirmasi Persetujuan BKK </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk_direct.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="<?= $data2['id']; ?>">
                            <h4 class="text-center">Apakah anda yakin ingin menyetujui pengajuan ini ?</h4>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Ya</button></span>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="No">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  -->

    <!--  -->
    <div id="tolak" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Alasan Penolakan </h4>
                </div>
                <!-- body modal -->
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" action="tolak_bkk.php" class="form-horizontal">
                        <input type="hidden" value="verifikasi_bkk" class="form-control" name="url" readonly>
                        <input type="hidden" value="<?= $Nama; ?>" class="form-control" name="Nama" readonly>
                        <input type="hidden" value="<?= $data2['pengajuan']; ?>" class="form-control" name="pengajuan" readonly>
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['id']; ?>" class="form-control" name="id" readonly>
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
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--  -->

</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        var logo = 'fa fa-arrow-down';
        var tmlKp = 'Detail';

        $("#tmlKp").html(tmlKp);
        $("#logo").addClass(logo);


        $("#tmlKp").click(function() {
            if (tmlKp == 'Detail') {
                $("#tmlKp").html('Hide');
                tmlKp = 'Hide';

                $("#logo").removeClass(logo);
                $("#logo").addClass("fa fa-arrow-up");


            } else {
                $("#tmlKp").html('Detail');
                tmlKp = 'Detail';

                $("#logo").removeClass(logo);
                $("#logo").addClass("fa fa-arrow-down");
            }
        });
    });
</script>