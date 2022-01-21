<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'edit') {
        header("location:?p=detail_paymentkaskeluar&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=hapus_joborder&id=$id");
    }
}

$tanggal = date("Y-m-d");

$query = mysqli_query($koneksi,  "SELECT * 
                                FROM bkk b
                                JOIN divisi d
                                ON d.id_divisi = b.id_divisi
                                WHERE b.status_bkk='9' AND b.metode_pembayaran = 'tunai' ORDER BY b.kd_transaksi DESC  ");


$jumlahData  = mysqli_num_rows($query);


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
                    <h3 class="text-center">Payment Biaya Umum Non Tempo</h3>
                </div>
                <div class="box-body">

                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead class="bg-primary">
                                <tr>
                                    <th style="vertical-align: middle;">No</th>
                                    <th style="vertical-align: middle;">Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th style="vertical-align: middle;">Divisi</th>
                                    <th style="vertical-align: middle;">Jenis</th>
                                    <th style="vertical-align: middle;">Vendor</th>
                                    <th style="vertical-align: middle;">Keterangan</th>
                                    <th style="vertical-align: middle;">Jumlah</th>
                                    <th style="vertical-align: middle;">Aksi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :
                                            $angka_format = number_format($row['jml_bkk'], 2, ",", ".");

                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['kd_transaksi']; ?> </td>
                                            <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['jenis']; ?> </td>
                                            <td> <?= $row['nm_vendor']; ?> </td>
                                            <td> <?= batasiKata($row['keterangan']); ?> </td>
                                            <td> <?= "Rp." . $angka_format; ?> </td>
                                            <!-- <td>                
                                            <?php echo '0', ' %';
                                            ?>                                        
                                         </td> -->
                                            <td>
                                                <a href="?p=payment_kaskeluar&aksi=edit&id=<?= $row['id_bkk']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info"> <i class="fa fa-search"></i> </button></span></a>
                                            </td>
                                            <td>
                                                <button title="Pindahkan Ke" class="btn btn-success modalEdit" data-toggle="modal" data-target="#editBu" data-id="<?= enkripRambo($row['id_bkk']); ?>"> <i class="fa fa-exchange"></i> </button>
                                            </td>
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    }
                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                            <td colspan='7'> Tidak Ada Data</td>
                                        </tr>";
                                    }
                        ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Edit -->
<div id="editBu" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- konten modal-->
        <div class="modal-content">
            <!-- heading modal -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pindahkan Biaya Umum Ke Tempo</h4>
            </div>
            <!-- body modal -->
            <div class="modal-body">
                <div class="perhitungan">
                    <form method="post" name="form" enctype="multipart/form-data" action="pindah_bu.php" class="form-horizontal">
                        <div class="box-body">
                            <input type="hidden" name="id_bkk" value="" id="me_id_bkk">
                            <div class="form-group">
                                <label for="tgl_tempo" class="col-sm-offset-1 col-sm-4 control-label">Tanggal Tempo</label>
                                <div class="col-sm-4">
                                    <input type="date" required class="form-control tanggal" name="tgl_tempo" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tgl_payment" class="col-sm-offset-1 col-sm-4 control-label">Tanggal Pembayaran</label>
                                <div class="col-sm-4">
                                    <input type="date" required class="form-control tanggal" name="tgl_payment" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <h5 class="col-sm-offset-1">Apakah anda yakin ingin memindahkan biaya <b id="me_keterangan"> </b> ke Tempo ? </5>
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

            console.log(id);

            $.ajax({
                url: host + 'api/biayaumum/getdatabu.php',
                data: {
                    id: id
                },
                method: 'post',
                dataType: 'json',
                success: function(data) {
                    $('#me_id_bkk').val(data.id_bkk);
                    $('#me_keterangan').html(data.keterangan);
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