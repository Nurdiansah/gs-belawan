<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=transaksi_dkasbon&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k
                                            LEFT JOIN biaya_ops bo
                                                ON k.kd_transaksi = bo.kd_transaksi
                                            LEFT JOIN detail_biayaops dbo
                                                ON k.id_dbo = dbo.id
                                            LEFT JOIN divisi d
                                                ON d.id_divisi = dbo.id_divisi  
                                            LEFT JOIN anggaran a
                                                ON dbo.id_anggaran = a.id_anggaran                                                                                      
                                            ORDER BY k.id_kasbon DESC   ");


?>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Transaksi Kasbon</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="material">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Kode </th>
                                    <th>Tanggal</th>
                                    <th>Divisi</th>
                                    <th>Kode Anggaran</th>
                                    <th>Deskripsi</th>
                                    <th>Total</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($query)) {
                                    while ($row = mysqli_fetch_assoc($query)) :

                                        if ($row['tahun'] == '2025') {
                                            # code...
                                            echo "<tr style='background-color :#ff751a;'>";
                                        } else {
                                            # code...
                                            echo "<tr>";
                                        }
                                ?>
                                        <td> <?= $no; ?> </td>
                                        <td> <?= $row['id_kasbon']; ?> </td>
                                        <td>
                                            <?php
                                            if ($row['from_user'] == '0') {
                                                echo  formatTanggal($row['tgl_pengajuan']);
                                            } else {
                                                echo  formatTanggal($row['tgl_kasbon']);
                                            }

                                            ?>
                                        </td>

                                        <td> <?= $row['nm_divisi']; ?> </td>
                                        <td> <?= $row['kd_anggaran'] . ' [' . $row['nm_item'] . ']'; ?> </td>

                                        <td>
                                            <?php
                                            if ($row['from_user'] == '0') {
                                                echo   $row['nm_barang'];
                                            } else {
                                                echo   $row['keterangan'];
                                            }

                                            ?>
                                        </td>
                                        <td> <button class="btn btn-success"><?= formatRupiah($row['harga_akhir']) ?> </button></td>
                                        <td>
                                            <a href="?p=transaksi_kasbon&aksi=lihat&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
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