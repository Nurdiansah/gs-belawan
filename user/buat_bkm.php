<?php
date_default_timezone_set("Asia/Jakarta");
// session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = date("Y");

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    WHERE status_bkm = '0'
                                    AND b.id_divisi = '$idDivisi'
                                    ORDER BY id_bkm DESC
                ");
$no = 1;

// query create BKM
if (isset($_POST['buat'])) {
    $id_anggaran = $_POST['id_anggaran'];
    $keterangan = $_POST['keterangan'];
    $nominal = penghilangTitik($_POST['nominal']);

    $doc_bkm = $_FILES['doc_bkm']['name'];
    $path_bkm = $_FILES['doc_bkm']['tmp_name'];
    $ekstensi = pathinfo($doc_bkm, PATHINFO_EXTENSION);
    $doc_bkm = time() . "-bkm." . $ekstensi;

    $querySimpan = mysqli_query($koneksi, "INSERT INTO bkm (id_anggaran, id_divisi, tgl_bkm, keterangan, nominal, grand_total, doc_bkm, waktu_dibuat_bkm, status_bkm) VALUES
                                            ('$id_anggaran', $idDivisi, NOW(), '$keterangan', '$nominal', '$nominal', '$doc_bkm', NOW(), '0')
                        ");

    if ($querySimpan) {
        move_uploaded_file($path_bkm, "../file/bkm/" . $doc_bkm);
        header('Location: index.php?p=buat_bkm');
    }
}

// query update BKM
if (isset($_POST['update'])) {
    $id_bkm = $_POST['id_bkm'];
    $id_anggaran = $_POST['id_anggaran'];
    $keterangan = $_POST['keterangan'];
    $nominal = penghilangTitik($_POST['nominal']);

    $cek_doc = $_FILES['doc_bkm']['name'];
    if ($cek_doc == '') {
        $doc_bkm = $_POST['doc_lama'];
    } else {
        $doc_lama = $_POST['doc_lama'];
        unlink("../file/bkm/" . $doc_lama);
        // $doc_bkm = $_FILES['doc_bkm']['name'];
        $path_bkm = $_FILES['doc_bkm']['tmp_name'];
        $ekstensi = pathinfo($_FILES['doc_bkm']['name'], PATHINFO_EXTENSION);
        $doc_bkm = time() . "-bkm." . $ekstensi;

        move_uploaded_file($path_bkm, "../file/bkm/" . $doc_bkm);
    }

    $queryUpdate = mysqli_query($koneksi, "UPDATE bkm SET id_anggaran = '$id_anggaran',
                                                keterangan = '$keterangan',
                                                nominal = '$nominal',
                                                grand_total = '$nominal',
                                                doc_bkm = '$doc_bkm'
                                            WHERE id_bkm = '$id_bkm'
                        ");

    if ($queryUpdate) {
        header('Location: index.php?p=buat_bkm');
    } else {
    }
}

// query hapus BKM
if (isset($_POST['hapus'])) {
    $id_bkm = $_POST['id_bkm'];
    $doc_bkm = $_POST['doc_bkm'];

    $queryHapus = mysqli_query($koneksi, "DELETE FROM bkm WHERE id_bkm = '$id_bkm'");

    unlink("../file/bkm/" . $doc_bkm);

    if ($queryHapus) {
        header('Location: index.php?p=buat_bkm');
    }
}

// query release BKM
if (isset($_POST['release'])) {
    $id_bkm = $_POST['id_bkm'];

    $queryRelease = mysqli_query($koneksi, "UPDATE bkm SET status_bkm = '1' WHERE id_bkm = '$id_bkm'");

    if ($queryRelease) {
        header('Location: index.php?p=buat_bkm');
    }
}

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="col-sm-offset-11">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buat"><i class="fa fa-edit"></i> Create </button></span></a>
                </div>
                <h3 class="text-center">Bukti Kas Masuk</h3>
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
                                            <td><?= batasiKata($dataBKM['keterangan']); ?></td>
                                            <td><?= kodeAnggaran($dataBKM['id_anggaran']); ?>]</td>
                                            <td><?= formatRupiah($dataBKM['grand_total']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning " data-toggle="modal" data-target="#release_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-rocket" title="Release" data-toggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-info " data-toggle="modal" data-target="#rubah_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-pencil" title="Edit" data-toggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#hapus_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-trash " title="Delete" data-toggle="tooltip"></i></button>
                                            </td>
                                        </tr>

                                        <!-- modal release -->
                                        <div id="release_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Konfirmasi</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="perhitungan">
                                                            <form action="" method="POST" enctype="multipart/form-data" class="form-hotizontal">
                                                                <input type="hidden" name="id_bkm" value="<?= $dataBKM['id_bkm']; ?>">
                                                                <div class="box-body">
                                                                    <div class="form-group">
                                                                        <h4 class="text-center">Apakah anda yakin ingin merelease BKM <b><?= $dataBKM['keterangan']; ?>?</b></h4>
                                                                    </div>
                                                                    <div class=" modal-footer">
                                                                        <button class="btn btn-primary" type="submit" name="release">Release</button></span></a>
                                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- modal delete -->
                                        <div id="hapus_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Konfirmasi</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="perhitungan">
                                                            <form action="" method="POST" enctype="multipart/form-data" class="form-hotizontal">
                                                                <input type="hidden" name="id_bkm" value="<?= $dataBKM['id_bkm']; ?>">
                                                                <input type="hidden" name="doc_bkm" value="<?= $dataBKM['doc_bkm']; ?>">
                                                                <div class="box-body">
                                                                    <div class="form-group">
                                                                        <h4 class="text-center">Apakah anda yakin ingin menghapus BKM <?= $dataBKM['keterangan']; ?>?</h4>
                                                                    </div>
                                                                    <div class=" modal-footer">
                                                                        <button class="btn btn-warning" type="submit" name="hapus">Hapus</button></span></a>
                                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Create  -->
                                        <div id="rubah_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Rubah Bukti Kas Masuk</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <div class="perhitungan">
                                                            <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                                                                <input type="hidden" name="id_bkm" value="<?= $dataBKM['id_bkm']; ?>">
                                                                <input type="hidden" name="doc_lama" value="<?= $dataBKM['doc_bkm']; ?>">
                                                                <?php
                                                                $idPk = $dataBKM['programkerja_id'];
                                                                ?>
                                                                <div class="box-body">
                                                                    <div class="form-group">
                                                                        <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                                                        <div class="col-sm-5">
                                                                            <select class="form-control select2 programkerja_id_edit" name="id_programkerja" id="id_programkerja_edit" required>
                                                                                <option value="">--Program Kerja--</option>
                                                                                <?php

                                                                                $queryProgramKerja = mysqli_query($koneksi, "SELECT DISTINCT id_programkerja, nm_programkerja
                                                                                        FROM program_kerja pk
                                                                                        JOIN cost_center cc
                                                                                            ON pk.costcenter_id = cc.id_costcenter
                                                                                        INNER JOIN anggaran
                                                                                            ON id_programkerja = programkerja_id
                                                                                        WHERE cc.divisi_id = '$idDivisi'
                                                                                        AND jenis_anggaran = 'PENDAPATAN'
                                                                                        AND pk.tahun = '$tahun'
                                                                                        ORDER BY pk.nm_programkerja ASC
                                                                                ");
                                                                                if (mysqli_num_rows($queryProgramKerja)) {
                                                                                    while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                                                                ?>
                                                                                        <option value="<?= $rowPK['id_programkerja']; ?>" <?= $rowPK['id_programkerja'] == $idPk ? "selected" : ""; ?>><?= $rowPK['nm_programkerja']; ?></option>
                                                                                <?php endwhile;
                                                                                } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="kotakAnggaran_edit">
                                                                        <div class="form-group">
                                                                            <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                                                            <div class="col-sm-5">
                                                                                <select class="form-control select2 id_anggaran_edit" name="id_anggaran" id="id_anggaran_edit" required>
                                                                                    <option value="">--Kode Anggaran--</option>
                                                                                    <?php
                                                                                    $queryAnggaran = mysqli_query($koneksi, "SELECT id_anggaran, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item, kd_anggaran
                                                                                                                            FROM anggaran agg
                                                                                                                            JOIN program_kerja pk
                                                                                                                                ON programkerja_id = id_programkerja
                                                                                                                            JOIN cost_center cc
                                                                                                                                ON costcenter_id = id_costcenter
                                                                                                                            JOIN pt pt
                                                                                                                                ON pt_id = id_pt
                                                                                                                            JOIN divisi dvs
                                                                                                                                ON divisi_id = dvs.id_divisi
                                                                                                                            JOIN parent_divisi pd
                                                                                                                                ON parent_id = id_parent
                                                                                                                            JOIN segmen sg
                                                                                                                                ON sg.id_segmen = agg.id_segmen
                                                                                                                            WHERE agg.id_divisi = '$idDivisi'
                                                                                                                            AND jenis_anggaran = 'PENDAPATAN'
                                                                                                                            AND agg.tahun = '$tahun'
                                                                                                                            AND programkerja_id = '$idPk'
                                                                                                                            ORDER BY nm_item ASC
                                                                                ");
                                                                                    if (mysqli_num_rows($queryAnggaran)) {
                                                                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                                                                    ?>
                                                                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" <?= $dataBKM['id_anggaran'] == $rowAnggaran['id_anggaran'] ? 'selected' : ''; ?>><?= $rowAnggaran['kd_anggaran'] . ' [' . $rowAnggaran['nm_item']; ?>]</option>
                                                                                    <?php endwhile;
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group ">
                                                                        <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal</label>
                                                                        <div class="col-sm-5">
                                                                            <div class="input-group">
                                                                                <span class="input-group-addon ">Rp.</span>
                                                                                <input type="text" class="form-control" name="nominal" id="edit_nominal" autocomplete="off" value="<?= formatRupiah2($dataBKM['grand_total']); ?>" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="doc_bkm" class="col-sm-offset-1 col-sm-3 control-label">Dokumen BKM</label>
                                                                        <div class="col-sm-5">
                                                                            <!-- <div class="input-group input-file" name="doc_bkm">
                                                                                <input type="text" class="form-control" placeholder="" />
                                                                                <span class="input-group-btn">
                                                                                    <button class="btn btn-default btn-choose" type="button">Browse</button>
                                                                                </span>
                                                                            </div> -->
                                                                            <input type="file" name="doc_bkm" class="form-control" accept="application/pdf">
                                                                            <p><i>Kosongkan jika tidak dirubah</i></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="validationTextarea">Keterangan</label>
                                                                        <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" placeholder="Deskripsi Kas Masuk"><?= $dataBKM['keterangan']; ?></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <h3 class="text-center">Document BKM</h3>
                                                                        <div class="embed-responsive embed-responsive-16by9">
                                                                            <iframe class="embed-responsive-item" src="../file/bkm/<?= $dataBKM['doc_bkm']; ?>"></iframe>
                                                                        </div>
                                                                    </div>
                                                                    <div class=" modal-footer">
                                                                        <button class="btn btn-success" type="submit" name="update">Simpan</button></span></a>
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
                                        <!-- Akhir modal create -->
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

<!-- Modal Create  -->
<div id="buat" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buat Bukti Kas Masuk</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2 programkerja_id" name="id_programkerja" required>
                                        <option value="">--Program Kerja--</option>
                                        <?php

                                        $queryProgramKerja = mysqli_query($koneksi, "SELECT DISTINCT id_programkerja, nm_programkerja
                                                                                        FROM program_kerja pk
                                                                                        JOIN cost_center cc
                                                                                            ON pk.costcenter_id = cc.id_costcenter
                                                                                        INNER JOIN anggaran
                                                                                            ON id_programkerja = programkerja_id
                                                                                        WHERE cc.divisi_id = '$idDivisi'
                                                                                        AND jenis_anggaran = 'PENDAPATAN'
                                                                                        AND pk.tahun = '$tahun'
                                                                                        ORDER BY pk.nm_programkerja ASC
                                                                                ");
                                        if (mysqli_num_rows($queryProgramKerja)) {
                                            while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                        ?>
                                                <option value="<?= $rowPK['id_programkerja']; ?>" type="checkbox"><?= $rowPK['nm_programkerja']; ?></option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="kotakAnggaran">
                                <div class="form-group">
                                    <label id="tes" for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                    <div class="col-sm-5">
                                        <select class="form-control select2 id_anggaran" name="id_anggaran" id="id_anggaran" required>
                                            <option>--Kode Anggaran--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" id="nominal" autocomplete="off" value="" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_bkm" class="col-sm-offset-1 col-sm-3 control-label">Dokumen BKM</label>
                                <div class="col-sm-5">
                                    <div class="input-group input-file" name="doc_bkm">
                                        <input type="text" class="form-control" placeholder="" required />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="validationTextarea">Keterangan</label>
                                <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" placeholder="Deskripsi Kas Masuk"></textarea>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="buat">Simpan</button></span></a>
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
<!-- Akhir modal create -->

<?php
$host = host();
?>

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../assets/plugins/alertify/lib/alertify.min.js"></script>
<script>
    var host = '<?= $host ?>';

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    // Browse
    function bs_input_file() {
        $(".input-file").before(
            function() {
                if (!$(this).prev().hasClass('input-ghost')) {
                    var element = $("<input type='file' class='input-ghost'  accept='application/pdf' style='visibility:hidden; height:0'>");
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

    // format rupiah edit
    // var edit_nominal = document.getElementById("edit_nominal");
    // edit_nominal.addEventListener("keyup", function(e) {
    //     edit_nominal.value = formatRupiah(this.value, "Rp. ");
    // });

    /* Fungsi formatRupiah */
    // function formatRupiah(angka, prefix) {
    //     var number_string = angka.replace(/[^,\d]/g, "").toString(),
    //         split = number_string.split(","),
    //         sisa = split[0].length % 3,
    //         edit_nominal = split[0].substr(0, sisa),
    //         ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    //     // tambahkan titik jika yang di input sudah menjadi angka ribuan
    //     if (ribuan) {
    //         separator = sisa ? "." : "";
    //         edit_nominal += separator + ribuan.join(".");
    //     }

    //     edit_nominal = split[1] != undefined ? edit_nominal + "," + split[1] : edit_nominal;
    //     return prefix == undefined ? edit_nominal : edit_nominal ? edit_nominal : "";
    // }

    var nominal = document.getElementById("nominal");
    nominal.addEventListener("keyup", function(e) {
        nominal.value = formatRupiah(this.value, "Rp. ");
    });

    /* Fungsi formatRupiah buat */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            nominal = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? "." : "";
            nominal += separator + ribuan.join(".");
        }

        nominal = split[1] != undefined ? nominal + "," + split[1] : nominal;
        return prefix == undefined ? nominal : nominal ? nominal : "";
    }
</script>