<?php



include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

$id = $_GET['id'];

$queryUser =  mysqli_query($koneksi, "SELECT *
                                                     from user u
                                                     JOIN divisi d
                                                     ON u.id_divisi=d.id_divisi
                                                     WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$Area = $rowUser['area'];
$Divisi = $rowUser['nm_divisi'];

$query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops 
                                            WHERE kd_transaksi='$id' ");
$data2 = mysqli_fetch_assoc($query);
$status = $data2['status_biayaops'];

if ($status == '2') {
    $queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                                     RIGHT JOIN detail_biayaops dbo
                                                     ON dbo.kd_transaksi = bo.kd_transaksi
                                                     JOIN anggaran a
                                                     ON a.id_anggaran = dbo.id_anggaran
                                                     WHERE bo.kd_transaksi='$id' AND dbo.status = '2' ");
} else {

    $queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                                     RIGHT JOIN detail_biayaops dbo
                                                     ON dbo.kd_transaksi = bo.kd_transaksi
                                                     JOIN anggaran a
                                                     ON a.id_anggaran = dbo.id_anggaran
                                                     WHERE bo.kd_transaksi='$id' ");
}





// $data=mysqli_fetch_assoc($queryBo);

$tanggalCargo = date("Y-m-d");


?>

<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=proses_mr" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                </div>
                <div class="box-header with-border">
                    <h3 class="text-center">Process Material Request</h3>
                </div>
                <br>
                <div id="my-timeline"></div>
                <br>
                <form method="post" name="form" action="#" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $Divisi ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['tgl_pengajuan']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-9 control-label">Kode Transaksi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_transaksi']; ?>">
                            </div>
                        </div>
                        <br>
                    </div>
                </form>

                <!--  -->
                <div class="table-responsive datatab">
                    <table class="table text-center table table-striped table-dark table-hover ">
                        <thead style="background-color :#B0C4DE;">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Anggaran</th>
                            <th>Merk</th>
                            <th>Type</th>
                            <th>Spesifikasi</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </thead>
                        <tr>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($queryBo)) {
                                        while ($row = mysqli_fetch_assoc($queryBo)) :
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <?= $row['kd_anggaran']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['type']; ?> </td>
                                            <td> <?= $row['spesifikasi']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['jumlah']; ?> </td>
                                            <td><a href="index.php?p=detail_dmr&id=<?= enkripRambo($row['id']); ?>" class="btn btn-info" title="Detail"><i class="fa fa-search"></i></a></td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                        </tr>
                    </table>
                </div>

                <!-- Akhir Modal Tambah  -->

            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {

        // app mgr
        var app_mgr = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr']))); ?>";
        var content_mgr = '<b>Manager</b><small>sudah memverifikasi dan menyetujui</small>';
        if (app_mgr == "01 Jan 1970 07:00") {
            app_mgr = "";
            var content_mgr = "<b>Manager </b><small>Waiting....</small>";
        }

        // purchasing
        var app_purchasing = "<?php print(date("d M Y H:i", strtotime($data2['app_purchasing']))); ?>";
        var content_purchasing = '<b>purchasing</b><small> sudah melakukan bidding</small>'
        if (app_purchasing == "01 Jan 1970 07:00") {
            app_purchasing = " ";
            content_purchasing = "<b>purchasing </b><small>Waiting....</small>";
        }

        var app_pajak = "<?php print(date("d M Y H:i", strtotime($data2['app_pajak']))); ?>";
        var content_pajak = '<b>Pajak</b><small> sudah melakukan bidding</small>'
        if (app_pajak == "01 Jan 1970 07:00") {
            app_pajak = " ";
            content_pajak = "<b>Pajak </b><small>Waiting....</small>";
        }

        var status = "<?php print($data2['status_biayaops']); ?>";

        if (status == '1') {
            var content_mgr = "<b>Manager </b><font color= blue ><small>Approval Proccess </small></font>";
        } else if (status == '2') {
            var content_purchasing = "<b>Purchasing </b><font color= blue ><small>Bidding Proccess </small></font>";
        } else if (status == '3') {
            var content_pajak = "<b>Pajak </b><font color= blue ><small>Verifikasi Pajak </small></font>";
        }

        var events = [{
                date: '<?= date("d M Y H:i", strtotime($data2['created_on'])); ?>',
                content: '<b>User</b><small>membuat pengajuan</small>'
            },
            {
                date: app_mgr,
                content: content_mgr
            },
            {
                date: app_purchasing,
                content: content_purchasing
            }
        ];

        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 4,
            slide: 1,
            prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
            nextArrow: '<i class="material-icons">keyboard_arrow_right</i>'
        });
    });
</script>