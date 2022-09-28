<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$query =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                            -- JOIN biaya_ops bo
                                            -- ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = dbo.id_divisi 
                                            WHERE k.id_kasbon ='$id' ");
$data2 = mysqli_fetch_assoc($query);

// $status = $data2['status_biayaops'];


$queryBo =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                            JOIN detail_biayaops dbo
                                            ON  dbo.id = k.id_dbo
                                            JOIN biaya_ops bo                                            
                                            ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN anggaran a
                                            ON a.id_anggaran = dbo.id_anggaran
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            JOIN supplier s
                                            ON s.id_supplier = dbo.id_supplier
                                            WHERE k.id_kasbon='$id'");

$queryTotal = mysqli_query($koneksi, "SELECT sum(harga_estimasi) as total FROM detail_biayaops WHERE kd_transaksi='$id' ");
$rowTotal = mysqli_fetch_assoc($queryTotal);

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=bidding_itemmr&id=$id");
    } else if ($_GET['aksi'] == 'lihat') {
        header("location:?p=detail_item&id=$id");
    }
}

?>
<section class="content">
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <!-- <div class="col-md-2">
                            <a href="index.php?p=list_order" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a> 
                        </div> -->
                    <br><br>
                </div>

                <!-- <br>
                    <div id="my-timeline"></div>
                <br>                   -->

                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Kasbon</h3>
                </div>
                <form method="post" name="form" action="#" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">Kode </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['id_kasbon']; ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal Pengajuan</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['tgl_kasbon']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="penerima_dana" class="col-sm-offset-2 col-sm-3 control-label">Penerima Dana</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="penerima_dana" value="<?= ucwords($data2['penerima_dana']); ?>">
                            </div>
                            <label for="tgl_penerima" class="col-sm-offset-6 col-sm-3 control-label">Tanggal Penerima Dana</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_penerima" value="<?= $data2['waktu_penerima_dana']; ?>">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="validationTextarea">Keterangan :</label>
                            <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" disabled><?= $data2['keterangan']; ?></textarea>
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
                            <th>Spesifikasi</th>
                            <th>Supplier/Vendor</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
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
                                            <td> <?= $row['kd_anggaran'] . ' ' . $row['nm_item']; ?> </td>
                                            <td> <?= $row['merk']; ?> </td>
                                            <td> <?= $row['spesifikasi']; ?> </td>
                                            <td> <?= $row['nm_supplier']; ?> </td>
                                            <td> <?= $row['satuan']; ?> </td>
                                            <td> <?= $row['jumlah']; ?> </td>
                                            <td>Rp. <?= number_format($row['harga_akhir'], 0, ",", "."); ?> </td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                            </tbody>
                        </tr>
                    </table>
                </div>
                <!--  -->

                <?php
                $foto = $data2['foto_item'];
                if ($foto === '0') { ?>
                    <h3 class="text-center">Foto Barang</h3>
                    <br>
                    <div class="row ">
                        <div class="col-sm-offset-">
                            <h5 class="text-center">Tidak Ada Foto</h5>
                        </div>
                    </div>
                <?php } else { ?>
                    <h3 class="text-center">Foto Barang</h3>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="../file/foto/<?= $data2['foto_item']; ?> "></iframe>
                    </div>
                    <!-- <div class="row ">
                        <div class="col-sm-offset-2">
                            <img src="../file/foto/<?= $data2['foto_item']; ?>" width="80%" alt="...">
                        </div>
                    </div> -->
                <?php } ?>

                <!-- <h3 class="text-center">Foto Barang</h3>
                <br>
                <div class="row ">
                    <div class="col-sm-offset-">
                        <h5 class="text-center">Tidak Ada Foto</h5>
                    </div>
                </div> -->

                <!--  -->
            </div>
        </div>
    </div>
    </div>

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
                    <form method="post" enctype="multipart/form-data" action="#" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group ">
                                <div class="col-sm-4">
                                    <input type="hidden" value="<?= $data2['kd_transaksi']; ?>" class="form-control" name="kd_transaksi" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="validationTextarea">Komentar</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
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
</section>

<script>
    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        // app mgr
        var app_mgr = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr']))); ?>";
        var content_mgr = '<b>Manager</b><small>sudah memverifikasi</small>';
        if (app_mgr == "01 Jan 1970 01:00") {
            app_mgr = "";
            var content_mgr = "<b>Manager </b><small>Waiting....</small>";
        }

        // purchasing
        var app_purchasing = "<?php print(date("d M Y H:i", strtotime($data2['tgl_kasbon']))); ?>";
        var content_purchasing = '<b>purchasing</b><small> sudah melakukan bidding</small>'
        if (app_purchasing == "01 Jan 1970 01:00") {
            app_purchasing = " ";
            content_purchasing = "<b>purchasing </b><small>Waiting....</small>";
        }

        // pajak
        var app_pajak = "<?php print(date("d M Y H:i", strtotime($data2['app_pajak']))); ?>";
        var content_pajak = '<b>Pajak</b><small> sudah memverifikasi</small>'
        if (app_pajak == "01 Jan 1970 01:00") {
            app_pajak = " ";
            content_pajak = "<b>Pajak </b><small>Waiting....</small>";
        }

        // manager ga
        var app_manager_ga = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr_ga']))); ?>";
        var content_manager_ga = '<b>Manager GA</b><small> sudah memverifikasi</small>'
        if (app_manager_ga == "01 Jan 1970 01:00") {
            app_manager_ga = " ";
            content_manager_ga = "<b>Manager GA</b><small>Waiting....</small>";
        }

        // manager finance
        var app_manager_finance = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr_finance']))); ?>";
        var content_manager_finance = '<b>Manager GA</b><small>sudah memverifikasi</small>'
        if (app_manager_finance == "01 Jan 1970 01:00") {
            app_manager_finance = " ";
            content_manager_finance = "<b>Manager Finance</b><small>Waiting....</small>";
        }

        // direktur
        var app_direktur = "<?php print(date("d M Y H:i", strtotime($data2['app_direktur']))); ?>";
        var content_direktur = '<b>Direktur</b><small> sudah memverifikasi</small>'
        if (app_direktur == "01 Jan 1970 01:00") {
            app_direktur = " ";
            content_direktur = "<b>Direktur </b><small>Waiting....</small>";
        }

        var status = "<?php print($data2['status_kasbon']); ?>";

        if (status == '1') {
            var content_pajak = "<b>Pajak </b><font color= blue ><small>Verifikasi Pajak </small></font>";
        } else if (status == '2') {
            var content_manager_ga = "<b>Manager GA </b><font color= blue ><small>Verifikasi Manager GA </small></font>";
        } else if (status == '3') {
            var content_manager_finance = "<b>Manager Finance</b><font color= blue ><small>Verifikasi Manager Finance </small></font>";
        } else if (status == '4') {
            var content_direktur = "<b>Direktur</b><font color= blue ><small>Verifikasi Direktur </small></font>";
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
            },
            {
                date: app_pajak,
                content: content_pajak
            },
            {
                date: app_manager_ga,
                content: content_manager_ga
            },
            {
                date: app_manager_finance,
                content: content_manager_finance
            },
            {
                date: app_direktur,
                content: content_direktur
            }
        ];

        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 7,
            slide: 1,
            prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
            nextArrow: '<i class="material-icons">keyboard_arrow_right</i>'
        });
    });
</script>