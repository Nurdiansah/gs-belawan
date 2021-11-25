<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=lpj_dkasbon&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k
                                            JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi                                            
                                            WHERE k.status_kasbon = '6'
                                            ORDER BY k.id_kasbon DESC   ");


?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">LPJKasbon</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id=" ">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th style="vertical-align: middle;" rowspan="2">No</th>
                                    <th style="vertical-align: middle;" rowspan="2">Kode </th>
                                    <th style="vertical-align: middle;" colspan="2">Tanggal</th>
                                    <th style="vertical-align: middle;" rowspan="2">Divisi</th>
                                    <th style="vertical-align: middle;" rowspan="2">Deskripsi</th>
                                    <th style="vertical-align: middle;" rowspan="2">Total</th>
                                    <th style="vertical-align: middle;" rowspan='2'>Pending</th>
                                    <th style="vertical-align: middle;" rowspan="2">Detail</th>
                                </tr>
                                <tr style="background-color :#B0C4DE;">
                                    <th>Buat</th>
                                    <th>Penyerahan Dana</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :

                                            // Memnghitung waktu tempuh dari penyerahan dana
                                            $payment  = strtotime($row['waktu_penerima_dana']);
                                            $today  = strtotime(date("Y-m-d H:i:s"));
                                            $dif = $today - $payment;
                                            $pendingLpj = detikToString($dif);

                                            if ($dif > 172800) {
                                                $notifPending = 'danger';
                                            } else {
                                                $notifPending = 'warning';
                                            }
                                    ?>
                                            <td> <?= $no; ?> </td>
                                            <td> <?= $row['id_kasbon']; ?> </td>
                                            <td> <?= formatTanggal($row['tgl_pengajuan']); ?> </td>
                                            <td> <?= formatTanggal($row['waktu_penerima_dana']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
                                            <td>
                                                <span class="label label-<?= $notifPending; ?>">
                                                    <?= $pendingLpj; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?p=lpj_kasbon&aksi=lihat&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
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
<script>
    $(function() {
        $("#material").DataTable({
            "language": {
                "url": "http://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json",
                "sEmptyTable": "Tidak ada data di database"
            }
        });
    });

    $(function() {

        // add multiple select / deselect functionality
        $("#selectall").click(function() {
            $('.case').attr('checked', this.checked);
        });

        // if all checkbox are selected, check the selectall checkbox
        // and viceversa
        $(".case").click(function() {

            if ($(".case").length == $(".case:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }

        });
    });
</script>