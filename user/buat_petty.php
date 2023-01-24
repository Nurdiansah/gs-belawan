<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsianggaran.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'revisi') {
        header("location:?p=revisi_petty&id=$id");
    } else if ($_GET['aksi'] == 'lpj') {
        header("location:?p=lpj_petty&id=$id");
    } else if ($_GET['aksi'] == 'release') {
        header("location:?p=release_petty&id=$id");
    }
}

$tahun = date("Y");

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];
$idDivisi = $rowUser['id_divisi'];

$query = mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp   
                                            JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran   
                                            WHERE tp.id_divisi = '$idDivisi'
                                            AND status_pettycash IN (0)
                                            AND `from` = 'user'
                                            ORDER BY tp.created_pettycash_on DESC   ");
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
                <br>
                <div class="box-header with-border">
                    <div class="col-sm-offset-11">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create"><i class="fa fa-envelope-o"></i> Create </button></span></a>
                    </div>
                    <h3 class="text-center">Petty Cash</h3>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="setuju_bkk2.php" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id=" ">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Kode Pettycash</th>
                                        <th>Tanggal</th>
                                        <th>Kode Anggaran</th>
                                        <th>Keterangan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        $no = 1;
                                        if (mysqli_num_rows($query)) {
                                            while ($row = mysqli_fetch_assoc($query)) :

                                                $sisaAnggaran = getSaldoAnggaran($row['id_anggaran']) - $row['total_pettycash'];
                                        ?>
                                                <td> <?= $no; ?> </td>
                                                <td><?= $row['kd_pettycash']; ?></td>
                                                <td> <?= formatTanggal($row['created_pettycash_on']); ?> </td>
                                                <td> <?= $row['nm_item'] . ' - [' . $row['kd_anggaran']; ?>]</td>
                                                <td> <?= batasiKata($row['keterangan_pettycash']); ?> </td>
                                                <td> <?= formatRupiah($row['total_pettycash']); ?> </td>
                                                <td> <?php if ($row['status_pettycash'] == 0) {

                                                            if ($sisaAnggaran < 0) {
                                                                # code...
                                                                echo "<button type='button' class='btn btn-dark ' data-toggle='modal' data-target='#notifBudget' data-id='" . $row['id_pettycash'] . "'><i class='fa fa-rocket'></i> Release </button>";
                                                            } else {
                                                                # code...
                                                                echo "<a href='?p=buat_petty&aksi=release&id=" . base64_encode($row['id_pettycash']) . "'><span data-placement='top' data-toggle='tooltip' title='Release'><button type='button' class='btn btn-warning'><i class='fa fa-rocket'> </i> Release</button></span></a>";
                                                            }
                                                        ?>

                                                        <!-- Modal notif -->
                                                        <div id="notifBudget" class="modal fade" role="dialog">
                                                            <div class="modal-dialog">
                                                                <!-- konten modal-->
                                                                <div class="modal-content">
                                                                    <!-- heading modal -->
                                                                    <div class="modal-header bg-danger ">
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        <h4 class="modal-title">Informasi !</h4>
                                                                    </div>
                                                                    <!-- body modal -->
                                                                    <div class="modal-body">
                                                                        <div class="perhitungan">
                                                                            <form class="form-horizontal">
                                                                                <div class="box-body">
                                                                                    <input type="hidden" name="id" value="" id="mr_id_kasbon">
                                                                                    <input type="hidden" name="id_dbo" value="" id="mr_id_dbo">

                                                                                    <h4> <span class="text-red"><i> Pengajuan pettycash ini tidak bisa di release karena saldo anggaran tersebut sudah terlimit! </i></span> silahkan kordinasi dengan team anggaran. </h4>

                                                                                    <div class=" modal-footer">
                                                                                        <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Tutup">
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                            <!-- div perhitungan -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End notif -->

                                                        <button type="button" class="btn btn-primary modalLihat" data-toggle="modal" data-target="#lihatPetty" data-id="<?= $row['id_pettycash']; ?>"><i class="fa fa-binoculars"></i> Show</button>
                                                        <button type="button" class="btn btn-success modalEdit" data-toggle="modal" data-target="#editPetty" data-id="<?= $row['id_pettycash']; ?>"><i class="fa fa-edit"></i> Edit</button>
                                                        <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#hapusPetty" data-id="<?= $row['id_pettycash']; ?>"><i class="fa fa-trash"></i> Delete</button>
                                                    <?php } else if ($row['status_pettycash'] == 1) { ?>
                                                        <span class="label label-primary">Menunggu Approve Manager </span>
                                                        <?php  } else if ($row['status_pettycash'] == 2) {
                                                            if ($row['from'] == 'user') { ?>
                                                            <span class="label label-warning">Dana Sudah Bisa diambil</span>
                                                            <br><br>
                                                            <!-- <a href="../html-link.htm" target="popup" onclick="window.open('../html-link.htm','name','width=600,height=400')"></a> -->
                                                            <a onclick="window.open('cetak_pengambilandana_petty.php?id=<?= enkripRambo($row['id_pettycash']); ?>','name','width=600,height=500')" href="" class="btn btn-success"><i class="fa fa-print"></i> Laporan Pengambilan Dana </a>
                                                        <?php } else { ?>
                                                            <span class="label label-warning">Dana sudah cair <br> dan purchasing akan memprosesnya</span>
                                                        <?php } ?>
                                                    <?php  } else if ($row['status_pettycash'] == 3) { ?>
                                                        <a href="?p=buat_petty&aksi=lpj&id=<?= $row['id_pettycash']; ?>"><span data-placement='top' data-toggle='tooltip' title='Revisi'><button type="button" class="btn btn-success"><i class="fa fa-edit"> </i> LPJ</button></span></a>
                                                    <?php  } else if ($row['status_pettycash'] == 4) { ?>
                                                        <span class="label label-default">Verifikasi LPJ </span>
                                                    <?php  } else if ($row['status_pettycash'] == 10) { ?>
                                                        <span class="label label-danger">Pengajuan Ditolak </span>
                                                        &nbsp; <br> <br> <a href="?p=buat_petty&aksi=revisi&id=<?= $row['id_pettycash']; ?>"><span data-placement='top' data-toggle='tooltip' title='Revisi'><button type="button" class="btn btn-success"><i class="fa fa-edit"> </i> Revisi</button></span></a>
                                                    <?php  } ?>
                                                </td>
                                    </tr>
                            <?php
                                                $no++;
                                                $idPK = $row['programkerja_id'];
                                            endwhile;
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

<!-- Modal Buat -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create Petty Cash</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="create_pettycash.php" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2 programkerja_id" name="id_programkerja" required>
                                        <option value="">--Program Kerja--</option>
                                        <?php

                                        $queryProgramKerja = mysqli_query($koneksi, "SELECT *
                                                                                    FROM program_kerja pk
                                                                                    JOIN cost_center cc
                                                                                        ON pk.costcenter_id = cc.id_costcenter
                                                                                    WHERE cc.divisi_id = '$idDivisi'
                                                                                    AND tahun = '$tahun'
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
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_lpj" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                                <div class="col-sm-5">
                                    <div class="input-group input-file" name="doc_lpj" required>
                                        <input type="text" class="form-control" required />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="validationTextarea">Deskripsi : </label>
                                <textarea rows="8" class="form-control is-invalid" name="keterangan" id="validationTextarea" required placeholder="Deskripsi"></textarea>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="submit">Create</button></span></a>
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
<!--  -->

<!-- Modal Lihat -->
<div id="lihatPetty" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail Petty Cash</h4>
            </div>
            <!-- body modal -->
            <form class="form-horizontal">
                <div class="modal-body">
                    <div class="perhitungan">
                        <div class="box-body">
                            <div class="form-group ">
                                <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="ml_kd_anggaran" readonly>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" id="ml_nominal" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="validationTextarea">Deskripsi : </label>
                                <textarea rows="8" class="form-control is-invalid" id="ml_keterangan" placeholder="Deskripsi" readonly></textarea>
                            </div>
                            <div class="form-group">
                                <h3 class="text-center">Document Pendukung </h3>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="" id="ml_doc"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                        </div>
                    </div>
                    <!-- div perhitungan -->
                </div>
        </div>
        </form>
    </div>
</div>
</div>
<!-- End Modal Lihat -->

<!-- Modal Edit -->
<div id="editPetty" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Petty Cash</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="edit_pettycash.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="me_id_pettycash">
                            <input type="hidden" name="kd_pettycash" value="" id="me_kd_pettycash">
                            <input type="hidden" name="doc_lpj_lama" value="" id="me_doc_lpj_lama">
                            <div class="form-group">

                                <label id="tes" for="id_programkerja" class="col-sm-offset-1 col-sm-3 control-label">Program Kerja</label>
                                <div class="col-sm-5">
                                    <select class="form-control select2 programkerja_id_edit" name="id_programkerja" id="id_programkerja_edit" required>
                                        <!-- <option value="">--Program Kerja--</option> -->
                                        <?php

                                        $queryProgramKerja = mysqli_query($koneksi, "SELECT id_programkerja, id_costcenter, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi) AS cost_center, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_programkerja
                                                                                                    FROM cost_center
                                                                                                    JOIN pt
                                                                                                        ON id_pt = pt_id
                                                                                                    JOIN divisi
                                                                                                        ON id_divisi = divisi_id
                                                                                                    JOIN parent_divisi
                                                                                                        ON id_parent = parent_id
                                                                                                    JOIN program_kerja
                                                                                                        ON id_costcenter = costcenter_id
                                                                                                    WHERE divisi_id = '$idDivisi'
                                                                                                    AND tahun = '$tahun'
                                                                                                    ORDER BY program_kerja ASC
                                                                                ");
                                        if (mysqli_num_rows($queryProgramKerja)) {
                                            while ($rowPK = mysqli_fetch_assoc($queryProgramKerja)) :
                                        ?>
                                                <option value="<?= $rowPK['id_programkerja']; ?>" <?= $rowPK['id_programkerja'] == $idPK ? 'selected' : ''; ?>><?= $rowPK['program_kerja'] . " [" . $rowPK['nm_programkerja']; ?>]</option>
                                        <?php endwhile;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="kotakAnggaran_edit">
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                                    <div class="col-sm-5">
                                        <select class="form-control select2 id_anggaran_edit" name="id_anggaran" id="id_anggaran_edit" required>
                                            <option value="">--Kode Anggaran--</option>
                                            <?php
                                            $queryAnggaran = mysqli_query($koneksi, "SELECT id_anggaran, CONCAT(kd_pt, '.', kd_parent, '.', kd_divisi, '.', kd_programkerja) AS program_kerja, nm_item
                                                                                    FROM anggaran agg
                                                                                    JOIN program_kerja
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
                                                                                    WHERE id_programkerja = '$idPK'
                                                                                    ORDER BY nm_item ASC
                                                                                ");
                                            if (mysqli_num_rows($queryAnggaran)) {
                                                while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                            ?>
                                                    <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['nm_item'] . ' - [' . $rowAnggaran['program_kerja']; ?>]</option>
                                            <?php endwhile;
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon ">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" id="me_nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="doc_lpj" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                                <div class="col-sm-5">
                                    <div class="input-group input-file" name="doc_lpj">
                                        <input type="text" class="form-control" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default btn-choose" type="button">Browse</button>
                                        </span>
                                    </div>
                                    <span class="text-danger"> <i>*Kosongkan jika tidak dirubah</i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="validationTextarea">Deskripsi : </label>
                                <textarea rows="8" class="form-control is-invalid" name="keterangan" id="me_keterangan" required placeholder="Deskripsi"></textarea>
                            </div>
                            <div class="form-group">
                                <h3 class="text-center">Document Pendukung </h3>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="" id="me_doc"></iframe>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="edit">Edit</button></span></a>
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
<!-- End edit -->

<!-- Modal hapus -->
<div id="hapusPetty" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="delete_pettycash.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="md_id_pettycash">
                            <h4>Apakah anda yakin ingin menghapus Pettycash <b><span id="md_keterangan"></b></span></h4>
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

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="../assets/plugins/alertify/lib/alertify.min.js"></script>
<script>
    var host = '<?= host(); ?>'

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

    // Modal Lihat
    $(function() {
        $('.modalLihat').on('click', function() {

            const id = $(this).data('id');

            console.log(host);

            $.ajax({
                url: host + 'api/pettycash/getdatapetty.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#ml_kd_anggaran').val(data.kd_anggaran);
                    $('#ml_nominal').val(formatRibuan(data.total_pettycash));
                    $('#ml_keterangan').val(data.keterangan_pettycash);
                    $('#ml_doc').attr('src', '../file/doc_lpj/' + data.doc_lpj_pettycash);
                }
            });
        });
    });

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/pettycash/getdatapetty.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#me_id_pettycash').val(data.id_pettycash);
                    $('#me_kd_pettycash').val(data.kd_pettycash);
                    $('#id_anggaran_edit').val(data.id_anggaran);
                    $('#me_doc_lpj_lama').val(data.doc_lpj_pettycash);
                    $('#me_nominal').val(formatRibuan(data.total_pettycash));
                    $('#me_keterangan').val(data.keterangan_pettycash);
                    $('#me_doc').attr('src', '../file/doc_lpj/' + data.doc_lpj_pettycash);
                }
            });
        });
    });

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/pettycash/getdatapetty.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#md_id_pettycash').val(data.id_pettycash);
                    $('#md_keterangan').text(data.keterangan_pettycash);
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
</script>