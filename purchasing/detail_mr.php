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

$queryBo =  mysqli_query($koneksi, "SELECT * FROM biaya_ops bo
                                                     RIGHT JOIN detail_biayaops dbo
                                                     ON dbo.kd_transaksi = bo.kd_transaksi
                                                     JOIN anggaran a
                                                     ON a.id_anggaran = dbo.id_anggaran
                                                     WHERE bo.kd_transaksi='$id' ");


$query =  mysqli_query($koneksi, "SELECT * FROM biaya_ops 
                                            WHERE kd_transaksi='$id' ");
$data2 = mysqli_fetch_assoc($query);

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

        var app_mgr = "<?php print($data2['app_mgr']); ?>";
        var content_mgr = '<b>Manager</b><small>Manager sudah mengecek dan menyetujui pengajuan mr</small>';
        if (app_mgr == "0000-00-00 00:00:00") {
            app_mgr = "";
            var content_mgr = "<b>Manager </b><small>Waiting....</small>";
        }

        var app_anggaran = "<?php print($data2['app_anggaran']); ?>";
        var content_anggaran = '<b>Anggaran</b><small>Anggaran sudah memverifikasi kode anggaran pada pengajuan mr</small>'
        if (app_anggaran == "0000-00-00 00:00:00") {
            app_anggaran = "Waiting....";
            content_anggaran = "<b>Anggaran </b><small>Waiting....</small>";
        }

        var events = [{
                date: '<?= $data2['created_on']; ?>',
                content: '<b>User</b><small>User membuat pengajuan mr</small>'
            },
            {
                date: app_mgr,
                content: content_mgr
            },
            {
                date: app_anggaran,
                content: content_anggaran
            },
            {
                date: '<?= $data2['app_purchasing']; ?>',
                content: '<b>Purchasing</b><small>Waiting.....</small>'
            },
            {
                date: '<?= $data2['app_purchasing']; ?>',
                content: '<b>Purchasing</b><small>Waiting.....</small>'
            },
            {
                date: '',
                content: '<b>Manager GA</b><small>Waiting.....</small>'
            },
            {
                date: 'Q3 - 2019',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small><small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q4 - 2019',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q1 - 2020',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q2 - 2020',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q3 - 2020',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q4 - 2020',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q1 - 2021',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q2 - 2021',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q3 - 2021',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            },
            {
                date: 'Q4 - 2021',
                content: 'Lorem ipsum dolor sit amet<small>Consectetur adipisicing elit</small>'
            }
        ];

        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 6,
            slide: 1,
            prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
            nextArrow: '<i class="material-icons">keyboard_arrow_right</i>'
        });
    });
</script>