<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=list_dpo&id=$id");
    } else if ($_GET['aksi'] == 'lihat_out') {
        header("location:?p=list_dpo_outstanding&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("Y-m-d");

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];


// $query = mysqli_query($koneksi, "SELECT * 
//                                     FROM po p
//                                     --JOIN biaya_ops bo
//                                     --ON p.kd_transaksi = bo.kd_transaksi
//                                     JOIN detail_biayaops dbo
//                                     ON p.id_dbo = dbo.id
//                                     JOIN divisi d
//                                     --ON d.id_divisi = bo.id_divisi                                            
//                                     --WHERE status_po = '7'
//                                     ORDER BY p.tgl_tempo1 DESC
//                 ");



$query = mysqli_query($koneksi, "SELECT tp.id_tagihan, p.id_po ,tp.tgl_tempo, p.po_number, p.tgl_po, d.nm_divisi, dbo.nm_barang,tp.nominal , tp.regulasi_tempo
                                    FROM tagihan_po tp
                                    JOIN po p
                                        ON p.id_po = tp.po_id
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = dbo.id_divisi
                                    WHERE tp.status_tagihan = '1'
                                    ORDER BY tp.tgl_tempo ASC
                ");


$query2 = mysqli_query($koneksi, "SELECT *
                                    FROM po p
                                    JOIN biaya_ops bo
                                        ON p.kd_transaksi = bo.kd_transaksi
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi
                                    WHERE status_po = '12'
                                    ORDER BY p.tgl_tempo2 DESC
                ");

if (isset($_POST['edit'])) {

    $regulasi_tempo = $_POST['regulasi'];
    $tgl_tempo = $_POST['tgl_tempo'];
    $id_tagihan = $_POST['id_tagihan'];

    // BEGIN/START TRANSACTION        
    mysqli_begin_transaction($koneksi);
    // UPDATE BKK
    $update = mysqli_query($koneksi, "UPDATE tagihan_po
                                        SET regulasi_tempo = '$regulasi_tempo' , tgl_tempo = '$tgl_tempo'
                                        WHERE id_tagihan= '$id_tagihan' ");

    if ($update) {
        # jika semua query berhasil di jalankan
        mysqli_commit($koneksi);

        setcookie('pesan', 'Edit Tagihan berhasil!', time() + (3), '/');
        setcookie('warna', 'alert-success', time() + (3), '/');
    } else {
        #jika ada query yang gagal
        mysqli_rollback($koneksi);

        setcookie('pesan', 'Edit Tagihan gagal!', time() + (3), '/');
        setcookie('warna', 'alert-danger', time() + (3), '/');
    }
    header("location:index.php?p=list_po");
}


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
                    <h3 class="text-center">PO Tempo</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id=" ">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Po Number</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Tempo</th>
                                    <th>Divisi</th>
                                    <th>Deskripsi</th>
                                    <th>Nominal Pembayaran</th>
                                    <th>Edit</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :

                                        if ($tanggal >= $row['tgl_tempo']) {
                                            echo "<tr style='background-color :#ff751a;'>";
                                        } else {
                                            echo "<tr>";
                                        }

                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['po_number']; ?> </td>
                                        <td> <?= formatTanggal($row['tgl_po']); ?> </td>
                                        <td> <?php echo $row['regulasi_tempo'] == 0 ? '-' : formatTanggal($row['tgl_tempo']); ?></td>
                                        <td> <?= $row['nm_divisi']; ?> </td>
                                        <td> <?= $row['nm_barang']; ?> </td>
                                        <td> <button class="btn btn-success"><?= formatRupiah($row['nominal']) ?> </button></td>
                                        <td>
                                            <button title="Edit Tempo" class="btn btn-success modalEdit" data-toggle="modal" data-target="#editTagihan" data-id="<?= enkripRambo($row['id_tagihan']); ?>"> <i class="fa fa-exchange"></i> </button>
                                        </td>
                                        <td>
                                            <a href="?p=list_po&aksi=lihat&id=<?= $row['id_tagihan']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                                        </td>
                                        </tr>
                                <?php
                                        $no++;
                                    endwhile;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="text-center">PO Tempo Outstanding</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id=" ">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Po Number</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tanggal Tempo</th>
                                        <th>Divisi</th>
                                        <th>Deskripsi</th>
                                        <th>Nominal Pembayaran</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query2)) {
                                        while ($row2 = mysqli_fetch_assoc($query2)) :

                                            if ($row2['tgl_tempo2'] <= $tanggal) {
                                                echo "<tr style='background-color :#ff751a;'>";
                                            } else {
                                                echo "<tr>";
                                            }

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row2['po_number']; ?> </td>
                                            <td> <?= formatTanggal($row2['tgl_po']); ?> </td>
                                            <td> <?php echo $row['regulasi_tempo2'] == 0 ? '-' : formatTanggal($row['tgl_tempo2']); ?></td>
                                            <td> <?= $row2['nm_divisi']; ?> </td>
                                            <td> <?= $row2['nm_barang']; ?> </td>
                                            <td> <span class="label label-success"><?= formatRupiah($row2['nominal_pembayaran2']) ?> </span></td>
                                            <td>
                                                <a href="?p=list_po&aksi=lihat_out&id=<?= $row2['id_po']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
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

<!-- Modal Edit -->
<div id="editTagihan" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Tanggal Tempo</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id_tagihan" value="" id="me_id_tagihan">
                            <div class="form-group ">
                                <label id="tes" for="dari_bank" class="col-sm-5 control-label">Regulasi Jatuh Tempo</label>
                                <div class="col-sm-4">
                                    <select name="regulasi" id="regulasi" class="form-control" required>
                                        <option value="">---Pilih Regulasi---</option>
                                        <option value="0">COD</option>
                                        <option value="7">1 - 7 Hari </option>
                                        <option value="14">1 - 14 Hari </option>
                                        <option value="30">1 - 30 Hari </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tgl_tempo" class="col-sm-offset-1 col-sm-4 control-label">Tanggal Tempo</label>
                                <div class="col-sm-4">
                                    <input type="date" required class="form-control tanggal" name="tgl_tempo" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <h5 class="col-sm-offset-1">Apakah anda yakin ingin merubah tanggal tempo po <b id="me_keterangan"> </b> ke Tempo ? </5>
                                    <br><br>
                                    <input type="checkbox" name="" id="konfirmasi" onclick="checkBox()"> Ya, saya yakin.
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="edit" id="btn-submit"><i class="fa fa-exchange"></i> Pindahkan</button></span></a>
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

<script>
    var host = '<?= host() ?>';

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    // Modal Edit
    $(function() {
        $('.modalEdit').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/tagihan_po/getdata.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_tagihan').val(data.id_tagihan);
                    $('#me_keterangan').html(data.nm_barang);
                    $('#regulasi').val(data.regulasi_tempo)
                }
            });
        });
    });

    $('#btn-submit').hide();

    function checkBox() {
        var checkBox = document.getElementById("konfirmasi");
        if (checkBox.checked == true) {

            $('#btn-submit').show();

        } else if (checkBox.checked == false) {
            $('#btn-submit').hide();
        }
    }
</script>