<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=verifikasi_dpo&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                                FROM po p
                                                JOIN biaya_ops bo
                                                ON p.kd_transaksi = bo.kd_transaksi
                                                JOIN detail_biayaops dbo
                                                ON p.id_dbo = dbo.id
                                                JOIN divisi d
                                                ON d.id_divisi = bo.id_divisi                                            
                                                WHERE status_po = '2'
                                                ORDER BY p.kd_transaksi DESC   ");

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
                    <h3 class="text-center">Verifikasi PO</h3>
                </div>
                <div class="box-body">
                    <!-- <form method="post" enctype="multipart/form-data" action="setuju_po2.php" class="form-horizontal"> -->
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Po Number</th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                    <th>Detail</th>
                                    <!-- <th><input type="checkbox" id="selectall" /></th> -->
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
                                            <td> <?= $row['po_number']; ?> </td>
                                            <td> <?= formatTanggalWaktu($row['tgl_po']); ?> </td>
                                            <td> <?= $row['nm_divisi']; ?> </td>
                                            <td> <?= $row['nm_barang']; ?> </td>
                                            <td> <button class="btn btn-success"><?= formatRupiah(round($row['grand_totalpo'])) ?> </button></td>
                                            <td>
                                                <a href="?p=verifikasi_po&aksi=lihat&id=<?= $row['id_po']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                                            </td>
                                            <!-- <td>
                                                    <input type="checkbox" class="case" name="id_item[]" value="<?= $row['id_po']; ?>" />
                                                </td> -->
                                </tr>
                        <?php
                                            $no++;
                                        endwhile;
                                    }

                                    if ($jumlahData == 0) {
                                        echo
                                        "<tr>
                                                <td colspan='8'> Tidak Ada Data</td>
                                            </tr>
                                            ";
                                    }
                        ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- <button class="btn btn-primary col-sm-offset-11" type="submit" name="submit" onclick="javascript: return confirm('Apakah anda yakin ingin menyetujui  ?')">Approve</button></span></a> -->
                    <!-- </form> -->
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