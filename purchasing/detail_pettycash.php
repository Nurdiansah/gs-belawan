<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$id = $_GET['id'];

if (!isset($_GET['id'])) {
    header("location:index.php");
}
$query =  mysqli_query($koneksi, "SELECT * FROM transaksi_pettycash tp   
                                    JOIN anggaran a
                                        ON tp.id_anggaran = a.id_anggaran     
                                    JOIN divisi d
                                        ON tp.id_divisi = d.id_divisi
                                    LEFT JOIN detail_biayaops
                                        ON id_dbo = id                                                                    
                                    WHERE tp.id_pettycash ='$id' ");
$data2 = mysqli_fetch_assoc($query);
$totalPengajuan = $data2['total_pettycash'];
$id_anggaran = $data2['id_anggaran'];
$id_dbo = $data2['id'];

$querySbo =  mysqli_query($koneksi, "SELECT * 
                                                        FROM sub_dbo                                                         
                                                        WHERE id_dbo=$id_dbo ");
?>
<section class="content">

    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="row">
                    <div class="col-md-2">
                        <a href="index.php?p=proses_petty" class="btn btn-primary"><i class="fa fa-backward"></i> Kembali</a>
                    </div>
                    <br><br>
                </div>

                <?php if ($data2['status_pettycash'] == '10') { ?>
                    <div class="box-body">
                        <div class="form-group">
                            <div class="mb-2">
                                <label for="validationTextarea">Alasan Ditolak</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" disabled><?= $data2['komentar_pettycash']; ?>&#13;&#10;<?= $data2['alasan_tolak_mgrga']; ?>&#13;&#10;<?= $data2['alasan_tolak_mgrfin']; ?>&#13;&#10;<?= $data2['alasan_tolak_direktur']; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <br>
                    <div id="my-timeline"></div>
                    <br>
                <?php } ?>
                <!-- Detail Job Order -->

                <div class="box-header with-border">
                    <h3 class="text-center">Detail Petty Cash</h3>
                </div>
                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="box-body">
                        <div class="form-group">
                            <label id="tes" for="divisi" class="col-sm-offset col-sm-1 control-label">Divisi</label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="divisi" value="<?= $data2['nm_divisi'];  ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Tanggal </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatTanggal($data2['created_pettycash_on']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tgl_pengajuan" class="col-sm-offset- col-sm-1 control-label">Kode Anggaran </label>
                            <div class="col-sm-3">
                                <input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= $data2['kd_anggaran'] . ' [' . $data2['nm_item'] . ']'; ?>">
                            </div>
                            <label for="tgl_pengajuan" class="col-sm-offset-2 col-sm-3 control-label">Total </label>
                            <div class="col-sm-3">
                                <b><input type="text" disabled class="form-control is-valid" name="tgl_pengajuan" value="<?= formatRupiah($data2['total_pettycash']); ?>"> </b>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan" class="col-sm-offset- col-sm-1 control-label">Keterangan</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="keterangan" disabled class="form-control "> <?= $data2['keterangan_pettycash']; ?></textarea>
                            </div>
                            <label for="nm_barang" class="col-sm-offset-2 col-sm-3 control-label">Nama Barang</label>
                            <div class="col-sm-3">
                                <textarea rows="5" type="text" name="nm_barang" disabled class="form-control "> <?= $data2['nm_barang']; ?></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="box-header with-border">
                            <h3 class="text-center">Rincian Barang</h3>
                        </div>
                        <div class="table-responsive datatab">
                            <table class="table text-center table table-striped table-dark table-hover ">
                                <thead style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Deskripsi</th>
                                    <th>Unit</th>
                                    <th>QTY</th>
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
                                                    <td> <?= $row['sub_unit']; ?> </td>
                                                    <td> <?= $row['sub_qty']; ?> </td>
                                                    <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
                                                    <td><?= formatRupiah($row['total_price']); ?></td>
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
                                    </tbody>
                            </table>
                        </div>
                        <?php if (!is_null($data2['foto_item']) || !file_exists("../file/foto/" . $data2['foto_item'] . "")) { ?>
                            <h3 class="text-center">Foto Barang</h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="../file/foto/<?= $data2['foto_item']; ?> "></iframe>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (!is_null($data2['doc_penawaran']) || !file_exists("../file/doc_penawaran/" . $data2['doc_penawaran'] . "")) { ?>
                        <div class="box-header with-border">
                            <h3 class="text-center">Document Penawaran</h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="../file/doc_penawaran/<?= $data2['doc_penawaran']; ?> "></iframe>
                            </div>
                        </div>
                    <?php } ?>
                    <br>
                </form>
                <br>


            </div>
        </div>
    </div>

    <br>
</section>

<script>
    // $(document).ready(function() {
    //     $('.tanggal').datepicker({
    //         format: "yyyy-mm-dd",
    //         autoclose: true
    //     });
    // });

    $(document).ready(function() {
        $('.tanggal').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });


        // manager ga
        var app_mgrga = "<?php print(date("d M Y H:i", strtotime($data2['app_mgr']))); ?>";
        var content_mgrga = '<b>Manager Finance</b><small>sudah memverifikasi</small>'
        if (app_mgrga == "01 Jan 1970 01:00") {
            app_mgrga = " ";
            content_mgrga = "<b>Manager Finance</b><small>Waiting....</small>";
        }

        // kasir
        var app_kasir = "<?php print(date("d M Y H:i", strtotime($data2['pym_ksr']))); ?>";
        var content_kasir = '<b>Direktur</b><small> sudah memverifikasi</small>'
        if (app_kasir == "01 Jan 1970 01:00") {
            app_kasir = " ";
            content_kasir = "<b>Kasir </b><small>Waiting....</small>";
        }

        var status = "<?php print($data2['status_pettycash']); ?>";

        if (status == '1') {
            var content_mgrga = "<b>Manager GA </b><font color= blue ><small>Approval Manager GA</small></font>";
        } else if (status == '2') {
            var content_kasir = "<b>Kasir</b><font color= blue ><small>Dana Sudah Bisa Diambil</small></font>";
        }

        var events = [{
                date: '<?= date("d M Y H:i", strtotime($data2['created_pettycash_on'])); ?>',
                content: '<b>Purchasing</b><small>Bidding MR Pettycash</small>'
            },
            {
                date: app_mgrga,
                content: content_mgrga
            },
            {
                date: app_kasir,
                content: content_kasir
            }
        ];

        $('#my-timeline').roadmap(events, {
            eventsPerSlide: 3,
            slide: 1,
            prevArrow: '<i class="material-icons">keyboard_arrow_left</i>',
            nextArrow: '<i class="material-icons">keyboard_arrow_right</i>'
        });
    });
</script>