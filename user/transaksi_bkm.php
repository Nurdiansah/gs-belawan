<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$queryBKM = mysqli_query($koneksi, "SELECT * FROM bkm b
                                    JOIN anggaran a
                                        ON a.id_anggaran = b.id_anggaran
                                    WHERE status_bkm IN ('5')
                                    AND b.id_divisi = '$idDivisi'
                                    ORDER BY id_bkm DESC");


$no = 1;

$totalBKM = mysqli_num_rows($queryBKM);

?>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="col-sm-offset-11">
                    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buat"><i class="fa fa-edit"></i> Buat </button></span></a> -->
                </div>
                <h3 class="text-center">Transaksi Bukti Kas Masuk</h3>
                <div class="box-body">
                    <form action="" method="POST" enctype="multipart/form-data" class="form-horizontal" id="">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id="<?= $totalBKM > 0 ? 'material' : ''; ?>">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nomor BKK</th>
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
                                            <td><?= $dataBKM['no_bkm']; ?></td>
                                            <td><?= batasiKata($dataBKM['keterangan']); ?></td>
                                            <td><?= kodeAnggaran($dataBKM['id_anggaran']); ?>]</td>
                                            <td><?= formatRupiah($dataBKM['grand_total']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info modalLihat" data-toggle="modal" data-target="#modalLihat" data-id="<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-search" title="Lihat" data-toggle="tooltip"></i></button>
                                                <!-- <button type="button" class="btn btn-info " data-toggle="modal" data-target="#lihat_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-search" title="Lihat" data-toggle="tooltip"></i></button> -->
                                                <!-- <button type="button" class="btn btn-success " data-toggle="modal" data-target="#verifikasi_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-check-square" title="Verifikasi" data-toggle="tooltip"></i></button>
                                                <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#reject_<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-close" title="Reject" data-toggle="tooltip"></i></button> -->
                                            </td>
                                        </tr>

                                        <!-- Modal Lihat -->
                                        <div id="lihat_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog modal-lg">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Detail Bukti Kas Masuk</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <form class="form-horizontal">
                                                        <div class="modal-body">
                                                            <div class="perhitungan">
                                                                <div class="box-body">
                                                                    <!-- <div class="form-group">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label"></label>
                                                                        <div class="col-sm-9">
                                                                            <fieldset class="form-control">
                                                                                <div class="col-sm-4">
                                                                                    <input type="checkbox" id="accounting" disabled checked> <label for="accounting"> Verifikasi Accounting<br>2021-11-22 17:17</label>
                                                                                </div>ml_pengajuan"
                                                                    </div> -->
                                                                    <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Tanggal</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control" value="<?= formatTanggal($dataBKM['tgl_bkm']); ?>" readonly>
                                                                        </div>
                                                                        <label for="id_anggaran" class="col-sm-1 control-label">Kode Anggaran</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control" value="<?= kodeAnggaran($dataBKM['id_anggaran']); ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Nominal</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['nominal']); ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">PPN</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['nilai_ppn']); ?>" readonly>
                                                                        </div>
                                                                        <label for="id_anggaran" class="col-sm-1 control-label">PPh</label>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control text-right" value="<?= formatRupiah($dataBKM['nilai_pph']); ?>" readonly>
                                                                        </div>
                                                                    </div> -->
                                                                    <!-- <div class="form-group ">
                                                                        <label for="id_anggaran" class="col-sm-2 control-label">Divisi</label>
                                                                        <div class="col-sm-9">
                                                                            <input type="text" class="form-control text-right" value="<?= $dataBKM['nm_divisi']; ?>" readonly>
                                                                        </div>
                                                                    </div> -->
                                                                    <div class="mb-3">
                                                                        <div class="form-group">
                                                                            <label for="validationTextarea" class="col-sm-2 control-label">Keterangan</label>
                                                                            <div class="col-sm-9">
                                                                                <textarea rows="8" class="form-control is-invalid" placeholder="Deskripsi" readonly><?= $dataBKM['keterangan']; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="doc">
                                                                        <div class="form-group">
                                                                            <h3 class="text-center">Document BKM</h3>
                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                <iframe class="embed-responsive-item" src="../file/bkm/<?= $dataBKM['doc_bkm']; ?>"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <!-- <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal"> -->
                                                                    <button type="button" class="btn btn-info modalLihat" data-toggle="modal" data-target="#modalLihat" data-id="<?= $dataBKM['id_bkm']; ?>"><i class="fa fa-search" title="Lihat" data-toggle="tooltip"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Akhir modal lihat -->

                                        <!-- Modal approve -->
                                        <div id="verifikasi_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog ">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Verifikasi Bukti Kas Masuk</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                                                        <div class="modal-body">
                                                            <div class="perhitungan">
                                                                <div class="box-body">
                                                                    <div class="form-group">
                                                                        <h4 class="text-center">Apakah anda yakin ingin memverifikasi pengajuan <b><?= $dataBKM['keterangan']; ?>?</b></h4>
                                                                        <input type="hidden" value="<?= $dataBKM['id_bkm']; ?>" class="form-control" name="id_bkm">
                                                                    </div>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-success" type="submit" name="verifikasi">Verifikasi</button></span>
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end modal approve -->

                                        <!-- Modal reject -->
                                        <div id="reject_<?= $dataBKM['id_bkm']; ?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- konten modal-->
                                                <div class="modal-content">
                                                    <!-- heading modal -->
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Alasan Penolakan</h4>
                                                    </div>
                                                    <!-- body modal -->
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                                                            <div class="box-body">
                                                                <div class="form-group ">
                                                                    <div class="col-sm-4">
                                                                        <input type="hidden" value="<?= $dataBKM['id_bkm']; ?>" class="form-control" name="id_bkm">
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="validationTextarea">Komentar</label>
                                                                    <textarea rows="8" class="form-control is-invalid" name="komentar" id="validationTextarea" required></textarea>
                                                                    <div class="invalid-feedback">
                                                                        Please enter a message in the textarea.
                                                                    </div>
                                                                </div>
                                                                <div class=" modal-footer">
                                                                    <button class="btn btn-success" type="submit" name="tolak">Kirim</button></span></a>
                                                                    <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end modal reject -->
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

    <!-- Modal Lihat -->
    <div id="modalLihat" class="modal fade" role="dialog">
        <div class="modal-dialog " style="width: 90%;">
            <!-- konten modal-->
            <div class="modal-content">
                <!-- heading modal -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Lihat Bukti Kas Masuk</h4>
                </div>
                <!-- body modal -->
                <form class="form-horizontal" enctype="multipart/form-data" action="" method="POST">
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="col-sm-5">
                                <input type="hidden" name="id_bkm" value="" id="me_id_bkm">
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Tanggal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="" id="me_tgl_bkm" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Kode Anggaran</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" value="" id="me_nm_item" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Nominal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " value="" id="me_nominal" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Divisi</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " value="" id="me_nm_divisi" readonly>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label for="id_anggaran" class="col-sm-offset- col-sm-3 control-label">Rekening Koran</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control " value="" id="me_remarks" readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="validationTextarea" class="col-sm-offset- col-sm-3 control-label">Keterangan</label>
                                        <div class="col-sm-8">
                                            <textarea rows="5" class="form-control is-invalid" placeholder="Deskripsi" id="me_keterangan" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <!-- <h3 class="text-center">Document BKM</h3> -->
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="" id="me_doc"></iframe>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-5">
                            </div>
                            <div class="col-sm-7 ">
                                <div class="form-group ">
                                    <div class="table-responsive">
                                        <table class="table text-left table table-striped table-hover" border="2px" id="">
                                            <tr>
                                                <th>Nominal</th>
                                                <th id="dpp"></th>
                                            </tr>
                                            <tr>
                                                <th id="nm_ppn">PPN</th>
                                                <th id="ppn_nilai"></th>
                                            </tr>
                                            <tr>
                                                <th>PPh</th>
                                                <th id="pph_nilai"></th>
                                            </tr>
                                            <tr>
                                                <th>Potongan</th>
                                                <th id="potongan"></th>
                                            </tr>
                                            <tr>
                                                <th>Biaya Lain</th>
                                                <th id="biaya_lain"></th>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <th id="total"></th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="slip_setoran">
                                <div class="form-group">
                                    <h3 class="text-center">Slip Setoran</h3>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="" id="me_slip"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" modal-footer">
                            <!-- <button class="btn btn-success" type="submit" name="verifikasi">Simpan</button></span></a> -->
                            <input type="reset" class="btn btn-danger" data-dismiss="modal" value="Batal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    let host = "<?= host() ?>"

    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    $(function() {
        $('.modalLihat').on('click', function() {

            const id = $(this).data('id');

            $.ajax({
                url: host + 'api/bkm/getvrfbkm.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_bkm').val(data.id_bkm);
                    $('#me_tgl_bkm').val(data.tgl_bkm);
                    $('#me_nm_item').val(data.nm_item);
                    $('#me_nominal').val(formatRibuan(Math.round(data.nominal)));
                    $('#me_nm_divisi').val(data.nm_divisi);
                    $('#me_remarks').val(data.remarks);
                    $('#me_keterangan').val(data.keterangan);
                    $('#dpp').text(formatRibuan(Math.round(data.nominal)));
                    $('#ppn_nilai').text(formatRibuan(Math.round(data.nilai_ppn)));
                    $('#id_pph').text(data.id_pph);
                    $('#pph_nilai').text('(' + formatRibuan(Math.round(data.nilai_pph)) + ')');
                    $('#potongan').text(formatRibuan(Math.round(data.potongan)));
                    $('#biaya_lain').text(formatRibuan(Math.round(data.biaya_lain)));
                    $('#total').text(formatRibuan(Math.round(data.grand_total)));

                    // var persentase = Math.round(data.nilai_ppn / data.nominal * 100)
                    // if (persentase > 0) {
                    //     $('#nm_ppn').text('PPN (' + persentase + '%)')
                    // } else {
                    //     $('#nm_ppn').text('PPN')
                    // }

                    let doc_bkm = '../file/bkm/' + data.doc_bkm;
                    $("#me_doc").attr("src", doc_bkm);
                    let bukti_pembayaran = '../file/bkm/' + data.bukti_pembayaran;
                    $("#me_slip").attr("src", bukti_pembayaran);

                    // if (!file_exists('../file/bkm/' + data.bukti_pembayaran)) {
                    //     $("#slip_setoran").hide();
                    // } else {
                    //     $("#slip_setoran").show();
                    // }

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

    function hilangkanTitik(data) {
        var angka = eval(bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah(document.getElementById(data).value))))); //input ke dalam angka tanpa titik

        return angka;
    }
</script>