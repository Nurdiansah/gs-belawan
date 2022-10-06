<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = dekripRambo($_GET['id']);

$queryData =  mysqli_query($koneksi, "SELECT *  FROM sr s
                                        JOIN anggaran a
                                            ON a.id_anggaran = s.id_anggaran
                                        JOIN kasbon k
                                            ON id_sr = sr_id
                                        WHERE s.id_sr = $id ");
$data = mysqli_fetch_assoc($queryData);
$total = $data['nilai_jasa'] - $data['potongan'];

$isiDoc =  "../file/doc_penawaran/" . $data['doc_penawaran'];
if (file_exists($isiDoc)) {
    $isiDoc = 1;
} else {
    $isiDoc = 0;
}


$isiDocQt =  "../file/doc_quotation/" . $data['doc_quotation'];

if (file_exists($isiDocQt)) {
    $isiDocQt = 1;
} else {
    $isiDocQt = 0;
}


$queryDSR =  mysqli_query($koneksi, "SELECT *  FROM detail_sr
                                                WHERE sr_id = $id ");

$jumlahData  = mysqli_num_rows($queryDSR);

if (isset($_POST['update'])) {
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d H:i:s");

    $id = $_POST['id'];
    $id_supplier = $_POST['id_supplier'];
    $nominal = penghilangTitik($_POST['nominal']);
    $diskon = penghilangTitik($_POST['diskon_sr']);
    $total = penghilangTitik($_POST['total_sr']);
    $nilai_ppn = penghilangTitik($_POST['nilai_ppn']);
    $grand_total = penghilangTitik($_POST['grand_totalsr']);
    $note = penghilangTitik($_POST['note_sr']);

    // cek doc
    $cek_doc = ($_FILES['doc_penawaran']['name']);
    if ($cek_doc == '') {
        // Pakai doc lama 
        $namabaru = $_POST['doc_penawaran_lama'];
    } else {
        // pakai doc baru

        //baca lokasi file sementara dan nama file dari form (doc_ptw)		
        $lokasi_doc_pendukung = ($_FILES['doc_penawaran']['tmp_name']);
        $doc_penawaran = ($_FILES['doc_penawaran']['name']);
        $ekstensi = pathinfo($doc_penawaran, PATHINFO_EXTENSION);

        // Jika file yang di upload bukan pdf
        if ($ekstensi != 'pdf') {
            setcookie('pesan', 'File yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');

            header("location:index.php?p=detail_sr&id=" . enkripRambo($id));
            die;
        }

        // Document Penawaran
        // Hapus document lama
        $del_invoice = $_POST['doc_penawaran_lama'];
        if (isset($del_invoice)) {
            unlink("../file/doc_penawaran/$del_invoice");
        }
        // Upload Document
        $namabaru = enkripRambo($id) . "-" . time() . "-doc-penawaran." . $ekstensi;
    }


    // Doc Quotation

    // cek doc
    $cek_doc = ($_FILES['doc_quotation']['name']);
    if ($cek_doc == '') {
        // Pakai doc lama 
        $namadq = $_POST['doc_quotation_lama'];
    } else {
        // pakai doc baru

        //baca lokasi file sementara dan nama file dari form (doc_ptw)		
        $lokasi_doc_quotation = ($_FILES['doc_quotation']['tmp_name']);
        $doc_quotation = ($_FILES['doc_quotation']['name']);
        $ekstensidq = pathinfo($doc_quotation, PATHINFO_EXTENSION);

        // Jika file yang di upload bukan pdf
        if ($ekstensidq != 'pdf' && $ekstensidq != 'PDF') {
            setcookie('pesan', 'File Document Quotation yang anda upload bukan berbentuk pdf , silahkan upload ulang dengan extensi pdf !', time() + (3), '/');
            setcookie('warna', 'alert-danger', time() + (3), '/');

            header("location:index.php?p=detail_sr&id=" . enkripRambo($id));
            die;
        }

        // Hapus document lama
        $del_quotation = $_POST['doc_qutation_lama'];
        if (isset($del_quotation)) {
            unlink("../file/doc_qutation/$del_quotation");
        }
        // Upload Document
        $namadq = enkripRambo($id) . "-doc-quotation." . $ekstensidq;
    }


    // Update sr
    $return = mysqli_query($koneksi, "UPDATE sr SET id_supplier = '$id_supplier',                                                                                                        
                                                    nominal = '$nominal',
                                                    diskon = '$diskon',
                                                    total = '$total',
                                                    nilai_ppn = '$nilai_ppn',
                                                    grand_total = '$grand_total',
                                                    note = '$note',
                                                    doc_penawaran = '$namabaru',
                                                    doc_quotation = '$namadq'
                                            WHERE id_sr = '$id'");

    if ($return) {
        move_uploaded_file($lokasi_doc_pendukung, "../file/doc_penawaran/" . $namabaru);
        move_uploaded_file($lokasi_doc_quotation, "../file/doc_quotation/" . $namadq);

        setcookie('pesan', 'SR berhasil di update !', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');

        header("location:index.php?p=detail_sr&id=" . enkripRambo($id) . "&pg=" . $_GET['pg']);
    } else {
        die("Ada kesalahan " . mysqli_error($koneksi));
    }
}

?>

<section class="content">
    <?php
    if (isset($_COOKIE['pesan'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="col-md-2">
            <a href="index.php?p=<?= dekripRambo($_GET['pg']); ?>" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
        </div>
        <br><br>
    </div>
    <!-- SR -->
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Form Penawaran</h3>
                </div>
                <form method="post" name="form" action="" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" required class="form-control is-valid" name="id" value="<?= $id; ?>">
                    <input type="hidden" required name="doc_penawaran_lama" value="<?= $data['doc_penawaran']; ?>">
                    <input type="hidden" required name="doc_quotation_lama" value="<?= $data['doc_quotation']; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="nm_barang" class="col-sm-offset col-sm-3 control-label">Supplier</label>
                            <div class="col-sm-6">
                                <select id="idSupplier" class="form-control" name="id_supplier" required>
                                    <option value="">--- Pilih Supplier ---</option>
                                    <?php
                                    $querySupplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier != '$id_supplier' ORDER BY nm_supplier ASC");
                                    if (mysqli_num_rows($querySupplier)) {
                                        while ($rowSupplier = mysqli_fetch_assoc($querySupplier)) :
                                    ?>
                                            <option <?php if ($rowSupplier['id_supplier'] == $data['id_supplier']) echo 'selected="selected"'; ?> value="<?= $rowSupplier['id_supplier']; ?>" type="checkbox"><?= $rowSupplier['nm_supplier']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="perhitungan">
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="<?= formatRupiah2($data['nilai_jasa']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required readonly>
                                        <!-- <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="<?= formatRupiah2($data['total']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required> -->
                                    </div>
                                </div>
                            </div>
                            <!-- Tambahan  -->
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Diskon </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="diskon_sr" id="diskon_sr" value="<?= formatRupiah2($data['potongan']); ?>" placeholder="0" required onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Total </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="total_sr" id="total_sr" value="<?= formatRupiah2($total); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">PPN 10 %</label>
                                <div class="col-sm-6">
                                    <input type="checkbox" name="all" id="myCheck" onclick="checkBox()">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Nilai PPN</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nilai_ppn" id="nilai_ppn" value="<?= formatRupiah2($data['nilai_ppn']); ?>" placeholder="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_quotation" class="col-sm-3 control-label">Grand Total</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" id="grand_totalsr" name="grand_totalsr" value="<?= formatRupiah2($data['harga_akhir']); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="doc_penawaran" class="col-sm-3 control-label">Document Penawaran </label>
                            <div class="col-sm-6">
                                <div class="input-group input-file" name="doc_penawaran">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <?php if ($data['doc_quotation'] != '') { ?>
                                    <span class="text-danger"> <i>*Kosongkan jika tidak ingin dirubah</i> </span>
                                <?php } ?>

                            </div>
                        </div>
                        <!-- <div class="form-group ">
                            <label for="doc_quotation" class="col-sm-3 control-label">Document Quotation </label>
                            <div class="col-sm-6">
                                <div class="input-group input-file" name="doc_quotation">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                                <?php if ($data['doc_quotation'] != '') { ?>
                                    <span class="text-danger"> <i>*Kosongkan jika tidak ingin dirubah</i> </span>
                                <?php } ?>

                            </div>
                        </div> -->
                        <div class="form-group ">
                            <label for="validationTextarea" class="col-sm-3 control-label">Note</label>
                            <div class="col-sm-6">
                                <textarea rows="5" class="form-control is-invalid" name="note_sr" id="validationTextarea" placeholder="DP 30% dibayar 1 minggu, Pelunasan 70% di bayar 2 minggu, Pekerjaan harus dilaksanakan terhitung sejak uang DP diterima, Apabila pekerjaan belum dilaksanakn maka akan dilakukan pemotongan sebesar 1/m/hari dari nilai PO"><?= $data['note']; ?></textarea>
                            </div>
                        </div>
                        <!-- End Tambahan -->
                        <div class="form-group">
                            <div class="col-sm-offset-6">
                                <button class="btn btn-primary" type="submit" name="update">Update</button></span></a>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Document Penawaran</h3>
                </div>
                <div class="box-body">
                    <?php

                    // print_r($isiDoc);
                    // die;

                    if ($data['doc_penawaran'] != '') {
                        // if (!file_exists("../file/doc_penawaran/" . $data['doc_penawaran'] . "")) { 
                    ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?= $data['doc_penawaran'] ?> "></iframe>
                        </div>
                    <?php
                    } else {
                        echo "<h4 class='text-center'>-- Document Kosong --</h4>";
                    } ?>

                </div>
                <br>
            </div>
            <!-- <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Document Quotation</h3>
                </div>
                <div class="box-body">
                    <?php if ($data['doc_quotation'] != '') { ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="../file/doc_quotation/<?= $data['doc_quotation'] ?> "></iframe>
                        </div>
                    <?php } else {
                        echo "<h4 class='text-center'>-- Document Kosong --</h4>";
                    } ?>

                </div>
                <br>
            </div> -->
        </div>
    </div>
    <!-- End SR -->

    <!-- SR -->
    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Detail Service Request</h3>
                </div>
                <form class="form-horizontal">
                    <input type="hidden" required class="form-control is-valid" name="id" value="<?= $id; ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="hidden" for="nm_barang" class="col-sm-offset col-sm-3 control-label">Nama Barang</label>
                            <input type="hidden" required class="form-control is-valid" name="url" value="buat_sr">
                            <div class="col-sm-6">
                                <input type="text" required class="form-control is-valid" name="nm_barang" value="<?= $data['nm_barang']; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="id_anggaran" readonly>
                                    <option value="<?= $data['id_anggaran']; ?>"><?= $data['kd_anggaran'] . ' ' . $data['nm_item']; ?></option>
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$idDivisi' AND tahun = '$tahun' ORDER BY nm_item ASC");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['kd_anggaran'] . ' ' . $rowAnggaran['nm_item']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-6">
                                <textarea rows="5" type="text" readonly name="keterangan" required class="form-control "> <?= $data['keterangan']; ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Document BA</h3>
                </div>
                <div class="box-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/doc_pendukung/<?= $data['doc_ba'] ?> "></iframe>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>


    <!-- Detail sr -->
    <?php
    if (isset($_COOKIE['pesan2'])) {
        echo "<div class='alert " . $_COOKIE['warna'] . "' role='alert'><b>" . $_COOKIE['pesan2'] . "</b></div>";
    }
    ?>
    <div class="row">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="text-center">Rincian Service Request</h3>
                    </div>
                    <div class="box-body">
                        <div class="box-header with-border">
                            <!-- Tombol untuk menampilkan modal-->
                            <button type="button" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus" title="Tambah Data" data-placement="top" data-toggle="tooltip"></i></button>
                        </div>
                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-hover" id="material">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Deskripsi</th>
                                        <th>Merk</th>
                                        <th>Type</th>
                                        <th>Spesifikasi</th>
                                        <th>Satuan</th>
                                        <th>Keterangan</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>Update</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total = 0;
                                    if (mysqli_num_rows($queryDSR)) {
                                        while ($row = mysqli_fetch_assoc($queryDSR)) :

                                    ?>
                                            <form action="update_rincian_sr.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
                                                <input type="hidden" name="pg" value="<?= $_GET['pg']; ?>">
                                                <tr>
                                                    <td> <?= $no; ?> </td>
                                                    <td> <?= $row['deskripsi']; ?> </td>
                                                    <td> <?= $row['merk']; ?> </td>
                                                    <td> <?= $row['type']; ?> </td>
                                                    <td> <?= $row['spesifikasi']; ?> </td>
                                                    <td> <?= $row['satuan']; ?> </td>
                                                    <td> <?= $row['keterangan']; ?> </td>
                                                    <td> <?= $row['qty']; ?> </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <span class="input-group-addon ">Rp.</span>
                                                            <input type="text" class="form-control" value="<?= $row['sub_total']; ?>" name="sub_total" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                                        </div>
                                                    </td>
                                                    <td><?= formatRupiah($row['total']); ?></td>
                                                    <td>
                                                        <input type="hidden" name="id_dsr" value="<?= $row['id_dsr']; ?>">
                                                        <input type="hidden" name="sr_id" value="<?= $row['sr_id']; ?>">
                                                        <input type="hidden" name="qty" value="<?= $row['qty']; ?>">
                                                        <input type="submit" name="update" class="btn btn-<?= $row['sub_total'] > 0 ? 'warning' : 'success'; ?>" value="Bidding">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary modalEdit" data-toggle="modal" data-target="#editDsr" data-id="<?= $row['id_dsr']; ?>"><i class="fa fa-edit" title="Rubah" data-placement="top" data-toggle="tooltip"></i></button>
                                                        <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#hapusDsr" data-id="<?= $row['id_dsr']; ?>"><i class="fa fa-trash" title="Hapus" data-placement="top" data-toggle="tooltip"></i></button>
                                                    </td>
                                                </tr>
                                            </form>
                                    <?php
                                            $no++;
                                            $total += $row['total'];
                                        endwhile;
                                    }

                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                            <td colspan='11'> Tidak Ada Data</td>
                                        </tr>
                                        ";
                                    }
                                    ?>
                                    <tr style="background-color :#B0C4DE;">
                                        <td colspan="9"><b>Total</b></td>
                                        <td><b><?= formatRupiah($total); ?></b></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Rincian</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="add_dsr.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="sr_id" value="<?= $id ?>">
                        <input type="hidden" name="url" value="detail_sr&id=<?= $_GET['id']; ?>&pg=<?= $_GET['pg']; ?>">
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="deskripsi" placeholder="Deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Merk</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="merk" placeholder="Merk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Type</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="type" placeholder="Type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Spesifikasi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="spesifikasi" placeholder="Spesifikasi"></textarea>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                            <div class="col-sm-8">
                                <input type="number" required class="form-control" name="qty">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Satuan</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="satuan" placeholder="Satuan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="keterangan" placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button type="submit" name="create" class="btn btn-primary col-sm-offset-1 "><i class="fa fa-add"></i>Tambah</button>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Tambah  -->

<!-- Modal Edit -->
<div id="editDsr" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rubah Rincian</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="upd_dsr.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="sr_id" value="<?= $id ?>">
                        <input type="hidden" name="id_dsr" id="me_id_dsr">
                        <input type="hidden" name="sub_total" id="me_sub_total">
                        <input type="hidden" name="url" value="detail_sr&id=<?= $_GET['id']; ?>&pg=<?= $_GET['pg']; ?>">
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Deskripsi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="deskripsi" id="me_deskripsi"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Merk</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="merk" placeholder="Merk" id="me_merk">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Type</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="type" placeholder="Type" id="me_type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Spesifikasi</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="spesifikasi" id="me_spesifikasi"></textarea>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="merk" class="col-sm-offset- col-sm-3 control-label">QTY</label>
                            <div class="col-sm-8">
                                <input type="number" required class="form-control" name="qty" id="me_qty">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="type" class="col-sm-offset- col-sm-3 control-label">Satuan</label>
                            <div class="col-sm-8 ">
                                <input type="text" required class="form-control" name="satuan" placeholder="Satuan" id="me_satuan">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nm_barang" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                            <div class="col-sm-8">
                                <textarea rows="4" type="textarea" required class="form-control" name="keterangan" placeholder="Keterangan" id="me_keterangan"></textarea>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <button type="submit" name="update" class="btn btn-primary col-sm-offset-1 "><i class="fa fa-add"></i>Update</button>
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Edit  -->

<!-- Modal hapus -->
<div id="hapusDsr" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Konfirmasi</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" enctype="multipart/form-data" action="del_dsr.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="sr_id" value="" id="md_sr_id">
                            <input type="hidden" name="id" value="" id="md_id_dsr">
                            <input type="hidden" name="url" value="detail_sr&id=<?= $_GET['id']; ?>&pg=<?= $_GET['pg']; ?>">
                            <h4>Apakah anda yakin ingin menghapus item <b><span id="md_deskripsi"></b></span></h4>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="delete">Delete</button></span></a>
                                &nbsp;
                                <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                            </div>
                        </div>
                    </form>
                    <!-- div perhitungan -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End hapus -->

<?php
$host = host();

?>

<script>
    var host = '<?= $host ?>';

    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
        $(".add-more").click(function() {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });
        $("body").on("click", ".remove", function() {
            $(this).parents(".control-group").remove();
        });

        // $('.js-example-basic-single').select2();
    });

    // Format Select 2
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }

        var $state = $(
            '<span> <span></span></span>'
        );

        // Use .text() instead of HTML string concatenation to avoid script injection issues
        $state.find("span").text(state.text);

        return $state;
    };

    $("#idSupplier").select2({
        templateSelection: formatState
    });

    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost' accept='application/pdf' style='visibility:hidden; height:0'>");
                    element.attr("name", $(this).attr("name"));
                    element.change(function() {
                        element.next(element).find('input').val((element.val()).split('\\').pop());
                    });
                    $(this).find("button.btn-choose").click(function() {
                        element.click();
                    });
                    $(this).find("button.btn-reset").click(function() {
                        element.val(null);
                        $(this).parents(".input-file").find('input').val('');
                    });
                    $(this).find('input').css("cursor", "pointer");
                    $(this).find('input').mousedown(function() {
                        $(this).parents('.input-file').prev().click();
                        return false;
                    });
                    return element;
                }
            }
        );
    }
    $(function() {
        bs_input_file();
    });

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_dsr').val(data.id_dsr);
                    $('#me_deskripsi').val(data.deskripsi);
                    $('#me_merk').val(data.merk);
                    $('#me_type').val(data.type);
                    $('#me_spesifikasi').val(data.spesifikasi);
                    $('#me_qty').val(data.qty);
                    $('#me_sub_total').val(data.sub_total);
                    $('#me_satuan').val(data.satuan);
                    $('#me_keterangan').val(data.keterangan);
                }
            });
        });
    });
    // Akhir modal edit

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/sr/getdetailsr.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#md_id_dsr').val(data.id_dsr);
                    $('#md_sr_id').val(data.sr_id);
                    $('#md_deskripsi').text(data.deskripsi);
                }
            });
        });
    });
    // Akhir modal delete


    var nilai_ppn = parseInt($("#nilai_ppn").val())
    console.log(nilai_ppn);
    if (nilai_ppn > 0) {
        $('#myCheck').prop('checked', true);
    } else {
        $('#myCheck').prop('checked', false);
    }

    // Perhitungan
    $(".perhitungan").keyup(function() {


        //ambil inputan harga            

        var diskon_sr = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('diskon_sr').value)))));

        var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik
        // var nominal = parseInt($("#nominal").val())

        var total_sr = nominal - diskon_sr;

        var total_sra = tandaPemisahTitik(total_sr);
        document.form.total_sr.value = total_sra;

        var grand_totalsr = total_sr;
        var grand_totalsra = tandaPemisahTitik(grand_totalsr);

        document.form.grand_totalsr.value = grand_totalsra;

    });

    function checkBox() {
        var checkBox = document.getElementById("myCheck");
        if (checkBox.checked == true) {

            var diskon_sr = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('diskon_sr').value))))); //input ke dalam angka tanpa titik

            var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik

            var total_sr = nominal - diskon_sr;

            var total_sra = tandaPemisahTitik(total_sr);

            var nilai_ppn = Math.floor(0.1 * total_sr);

            var nilai_ppna = tandaPemisahTitik(nilai_ppn);

            document.form.nilai_ppn.value = nilai_ppna;

            var grand_totalsr = total_sr + nilai_ppn;
            var grand_totalsra = tandaPemisahTitik(grand_totalsr);

            document.form.grand_totalsr.value = grand_totalsra;


        } else if (checkBox.checked == false) {
            var diskon_sr = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('diskon_sr').value))))); //input ke dalam angka tanpa titik

            var nominal = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById('nominal').value))))); //input ke dalam angka tanpa titik

            var total_sr = nominal - diskon_sr;

            var total_sra = tandaPemisahTitik(total_sr);

            var nilai_ppn = 0;

            document.form.nilai_ppn.value = 0;

            var grand_totalsr = total_sr;
            var grand_totalsra = tandaPemisahTitik(grand_totalsr);

            document.form.grand_totalsr.value = grand_totalsra;
        }
    }
</script>