<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$tahun = tahunSekarang();


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=kasbon_dproses&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k                                            
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = dbo.id_divisi                                            
                                            WHERE k.status_kasbon = 0
                                            AND k.from_user = 1
                                            AND dbo.id_divisi = '$idDivisi'
                                            ");




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
                    <button type="button" title="Tambah Data" class="btn btn-primary col-sm-offset-11" data-toggle="modal" data-target="#tambahKasbon"><i class="fa fa-plus"></i></button>
                    <h3 class="text-center">Draft Kasbon</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <br><br>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id=" ">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Kode </th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
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

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['id_kasbon']; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                                            <td> <?= $row['keterangan']; ?> </td>
                                            <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
                                            <td><?php if ($row['status_kasbon'] == 0) { ?>
                                                    <button type="button" class="btn btn-warning modalRelease" data-toggle="modal" data-target="#releaseKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-rocket"></i> Release</button>
                                                    <button type="button" class="btn btn-success modalEdit" data-toggle="modal" data-target="#editKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-edit"></i> Edit</button>
                                                    <button type="button" class="btn btn-danger modalHapus" data-toggle="modal" data-target="#hapusKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-trash"></i> Delete</button>
                                                <?php } else if ($row['status_kasbon'] == 1) { ?>
                                                    <span class="label label-primary">Verifikasi Pajak</span>
                                                <?php  } else if ($row['status_kasbon'] == 2) { ?>
                                                    <span class="label label-primary">Verifikasi Manager GA </span>
                                                <?php  } else if ($row['status_kasbon'] == 3) { ?>
                                                    <span class="label label-success">Approval Manager Finance </span>
                                                <?php  } else if ($row['status_kasbon'] == 4) { ?>
                                                    <span class="label label-success">Approval Direktur </span>
                                                <?php  } else if ($row['status_kasbon'] == 5) { ?>
                                                    <span class="label label-warning">Dana sudah bisa diambil </span>
                                                <?php  } else if ($row['status_kasbon'] == 6) { ?>
                                                    <span class="label label-info">Pengajuan sedang di belikan </span>
                                                <?php  } else if ($row['status_kasbon'] == 7) { ?>
                                                    <span class="label label-info">Pengajuan sedang di belikan </span>
                                                <?php  }  ?>
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

<!-- Modal Tambah  -->
<div id="tambahKasbon" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tambah Kasbon</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="create_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="id_divisi" value="<?= $idDivisi ?>">


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
                        <div class="form-group">
                            <label for="doc" class="col-sm-offset-1 col-sm-3 control-label">Document Pendukung </label>
                            <div class="col-sm-5">
                                <div class="input-group input-file" name="doc">
                                    <input type="text" class="form-control" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-choose" type="button">Browse</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="keterangan" class="control-label">Keterangan: </label>
                            <!-- <div class="col-sm-8"> -->
                            <textarea rows="7" type="textarea" required class="form-control" name="keterangan" placeholder="Keterangan Kebutuhan"></textarea>
                            <!-- </div> -->
                        </div>
                        <div class=" modal-footer">
                            <input type="submit" name="submit" class="btn btn-primary col-sm-offset-1 " value="Tambah">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Tambah Kasbon  -->

<!-- Modal Edit  -->
<div id="editKasbon" class="modal fade" role="dialog">
    <div class="modal-dialog lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Kasbon</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="edit_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="id" id="me_id">
                        <input type="hidden" name="id_dbo" id="me_id_dbo">
                        <div class="form-group ">
                            <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-5">
                                <select class="form-control select2" name="id_anggaran" id="me_id_anggaran" required>
                                    <option value="">--Kode Anggaran--</option>
                                    <?php
                                    $queryAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE id_divisi ='$idDivisi' AND tahun = '$tahun' ORDER BY nm_item ASC");
                                    if (mysqli_num_rows($queryAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryAnggaran)) :
                                    ?>
                                            <option value="<?= $rowAnggaran['id_anggaran']; ?>" type="checkbox"><?= $rowAnggaran['nm_item'] . ' ' . $rowAnggaran['kd_anggaran']; ?></option>
                                    <?php endwhile;
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="nominal" class="col-sm-offset-1 col-sm-3 control-label">Nominal </label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <span class="input-group-addon ">Rp.</span>
                                    <input type="text" class="form-control" id="me_nominal" name="nominal" autocomplete="off" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="keterangan" class="control-label">Keterangan: </label>
                            <!-- <div class="col-sm-8"> -->
                            <textarea rows="7" type="textarea" required class="form-control" name="keterangan" id="me_keterangan" placeholder="Keterangan Kebutuhan"></textarea>
                            <!-- </div> -->
                        </div>
                        <div class=" modal-footer">
                            <input type="submit" name="edit" class="btn btn-primary col-sm-offset-1 " value="Edit">
                            &nbsp;
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Edit Kasbon  -->

<!-- Modal hapus -->
<div id="hapusKasbon" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="delete_kasbon.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="md_id_kasbon">
                            <input type="hidden" name="id_dbo" value="" id="md_id_dbo">
                            <input type="hidden" name="url" value="buat_kasbon">

                            <h4>Apakah anda yakin ingin menghapus Kasbon <b><span id="md_keterangan"></b></span></h4>
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

<!-- Modal release -->
<div id="releaseKasbon" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="release_kasbon.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="mr_id_kasbon">
                            <input type="hidden" name="id_dbo" value="" id="mr_id_dbo">
                            <input type="hidden" name="vrf_pajak" value="bp" id="mr_id_dbo">

                            <h4>Apakah anda yakin ingin merelease Kasbon <b><span id="mr_keterangan"></b></span> ini ?</h4>
                            <h5>Setelah kasbon direlease akan terkirim ke supervisor/manager terkait.</h5>
                            <br>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="release">Kirim</button></span></a>
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
<!-- End release -->

<?php
$host = host();
?>

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

    console.log(host);

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
                }
            });
        });
    });

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/kasbon/getkasbonuser.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#me_id').val(data.id_kasbon);
                    $('#me_id_dbo').val(data.id_dbo);
                    $('#me_id_anggaran').val(data.id_anggaran);
                    $('#me_nominal').val(formatRibuan(data.harga_akhir));
                    $('#me_keterangan').val(data.keterangan);
                }
            });
        });
    });

    // Modal Delete
    $(function() {
        $('.modalHapus').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/kasbon/getkasbonuser.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#md_id_kasbon').val(data.id_kasbon);
                    $('#md_id_dbo').val(data.id_dbo);
                    $('#md_keterangan').text(data.keterangan);
                }
            });
        });
    });

    // Modal Release
    $(function() {
        $('.modalRelease').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/kasbon/getkasbonuser.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#mr_id_kasbon').val(data.id_kasbon);
                    $('#mr_id_dbo').val(data.id_dbo);
                    $('#mr_keterangan').text(data.keterangan);
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