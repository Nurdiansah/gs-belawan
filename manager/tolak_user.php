<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id=" ">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>Kode </th>
                <th>Tanggal</th>
                <th>Deskripsi</th>
                <th>Total</th>
                <th>Alasan Penolakan</th>
                <th>Waktu Penolakan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                $no = 1;
                if (mysqli_num_rows($queryUser)) {
                    while ($row = mysqli_fetch_assoc($queryUser)) :

                ?>
                        <td> <?= $no; ?> </td>
                        <td> <?= $row['id_kasbon']; ?> </td>
                        <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                        <td> <?= $row['keterangan']; ?> </td>
                        <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
                        <td><?= $row['alasan_tolak_mgrfin']; ?></td>
                        <td><?= $row['waktu_tolak_mgrfin']; ?></td>
                        <td>
                            <button type="button" class="btn btn-warning modalLihat" data-toggle="modal" data-target="#lihatKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-binoculars"></i> Show</button>
                            <button type="button" class="btn btn-primary modalSetuju " data-toggle="modal" data-target="#setujuKasbon" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-check-square"></i> Approve </button></span></a>
                            <button type="button" class="btn btn-danger modalTolak " data-toggle="modal" data-target="#tolakKasbon_<?= $row['id_kasbon']; ?>" data-id="<?= $row['id_kasbon']; ?>"><i class="fa fa-reply"></i> Reject </button></span></a>
                        </td>
            </tr>

            <!-- MODAL REMBO -->
            <!-- Modal Reject -->
            <div id="tolakKasbon_<?= $row['id_kasbon']; ?>" class="modal fade" role="dialog">
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
                            <form method="post" enctype="multipart/form-data" action="tolak_kasbon.php" class="form-horizontal">
                                <div class="box-body">
                                    <input type="hidden" name="id_kasbon" id="id_kasbon" value="<?= $row['id_kasbon']; ?>">
                                    <input type="hidden" name="url" id="url" value="ditolak_kasbon&sp=tolak_user">
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
            <!--  -->

    <?php
                        $no++;
                    endwhile;
                }

                $jumlahData  = mysqli_num_rows($queryUser);

                if ($jumlahData == 0) {
                    echo
                    "<tr>
                                                <td colspan='6'> Tidak Ada Data</td>
                                            </tr>
                                            ";
                }
    ?>
        </tbody>
    </table>
</div>
<!-- Modal Lihat  -->
<div id="lihatKasbon" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Lihat Kasbon</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="edit_kasbon.php" class="form-horizontal">
                    <div class="box-body">
                        <input type="hidden" name="id" id="ml_id">
                        <input type="hidden" name="id_dbo" id="ml_id_dbo">
                        <div class="form-group ">
                            <label for="id_anggaran" class="col-sm-offset-1 col-sm-3 control-label">Kode Anggaran</label>
                            <div class="col-sm-5">
                                <select class="form-control select2" name="id_anggaran" id="ml_id_anggaran" readonly>
                                    <option value="">--Kode Anggaran--</option>
                                    <?php
                                    $queryUserAnggaran = mysqli_query($koneksi, "SELECT * FROM anggaran WHERE  tahun = '$tahun' ORDER BY nm_item ASC");
                                    if (mysqli_num_rows($queryUserAnggaran)) {
                                        while ($rowAnggaran = mysqli_fetch_assoc($queryUserAnggaran)) :
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
                                    <input type="text" class="form-control" id="ml_nominal" name="nominal" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label id="tes" for="keterangan" class="control-label">Keterangan: </label>
                            <!-- <div class="col-sm-8"> -->
                            <textarea rows="7" type="textarea" readonly class="form-control" name="keterangan" id="ml_keterangan" placeholder="Keterangan Kebutuhan"></textarea>
                            <!-- </div> -->
                        </div>
                        <div class="form-group ">
                            <div class="box-header with-border">
                                <h3 class="text-center">Document Pendukung </h3>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="" id="ml_doc"></iframe>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Tutup">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Akhir Modal Lihat Kasbon  -->

<!-- Modal setuju -->
<div id="setujuKasbon" class="modal fade" role="dialog">
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
                    <form method="post" name="form" enctype="multipart/form-data" action="setuju_kembali_kasbon.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id" value="" id="ma_id_kasbon">
                            <input type="hidden" name="vrf_pajak" value="" id="ma_vrf_pajak">

                            <h4>Apakah anda yakin ingin menyetujui Kasbon <b><span id="ma_keterangan"></b></span></h4>
                            <br>
                            <div class="mb-3">
                                <label for="validationTextarea">Alasan Setuju Kembali</label>
                                <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required>@<?php echo $Nama ?> : </textarea>
                                <div class="invalid-feedback">
                                    Please enter a message in the textarea.
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button class="btn btn-success" type="submit" name="setuju">Approve</button></span></a>
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
<!-- End setuju -->


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

    // Modal Lihat
    $(function() {
        $('.modalLihat').on('click', function() {

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
                    $('#ml_id').val(data.id_kasbon);
                    $('#ml_id_dbo').val(data.id_dbo);
                    $('#ml_id_anggaran').val(data.id_anggaran);
                    $('#ml_nominal').val(formatRibuan(data.harga_akhir));
                    $('#ml_keterangan').val(data.keterangan);
                    $('#ml_doc').attr('src', '../file/doc_pendukung/' + data.doc_pendukung);
                }
            });
        });
    });

    // Modal Tolak
    $(function() {
        $('.modalTolak').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'tolak_kasbon.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    // console.log(data);
                    $('#mt_id_kasbon').val(data.id_kasbon);
                }
            });
        });
    });

    // Modal Setuju
    $(function() {
        $('.modalSetuju').on('click', function() {

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
                    $('#ma_id_kasbon').val(data.id_kasbon);
                    $('#ma_vrf_pajak').val(data.vrf_pajak);
                    $('#ma_keterangan').text(data.keterangan);
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