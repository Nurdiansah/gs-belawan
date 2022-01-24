<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";


if (isset($_GET['aksi']) && isset($_GET['id'])) {
    //die($id = $_GET['id']);
    $id = $_GET['id'];
    echo $id;

    if ($_GET['aksi'] == 'lihat') {
        header("location:?p=verifikasi_dbkk&id=$id");
    } else if ($_GET['aksi'] == 'hapus') {
        header("location:?p=verifikasi_dmr&id=$id");
    }
}

$queryUser =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowUser = mysqli_fetch_assoc($queryUser);
$idUser = $rowUser['id_user'];

$query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                                LEFT JOIN anggaran a
                                                ON b.id_anggaran = a.id_anggaran
                                                WHERE b.status_bkk = '1' AND b.pengajuan != 'BIAYA KHUSUS'
                                                ORDER BY b.id ASC   ");

$jumlahData  = mysqli_num_rows($query);
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (isset($_COOKIE['pesan'])) {
                echo "<div class='alert alert-success' role='alert'><b>" . $_COOKIE['pesan'] . "</b></div>";
            }
            ?>
        </div>
    </div>
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="text-center">Verifikasi BKK</h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
                            <thead>
                                <tr style="background-color :#B0C4DE;">
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th>Kode Anggaran</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Detail</th>
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
                                            <td> <?= formatTanggalWaktu($row['created_on_bkk']); ?> </td>
                                            <td> <?= $row['pengajuan']; ?> </td>
                                            <td> <?= batasiKata($row['keterangan']); ?> </td>
                                            <td> <?= $row['kd_anggaran']; ?> </td>
                                            <td> <button class="btn btn-primary"><?= formatRupiah(round($row['nominal'])) ?> </button></td>
                                            <td>
                                                <?php
                                                if ($row['pengajuan'] == 'KASBON') {
                                                    echo "<button class='btn btn-success'> Dibayar</button>";
                                                } else {
                                                    // Jika pengajuan Biaya Umum
                                                    if ($row['pengajuan'] == 'BIAYA UMUM') {
                                                        $bkkId = $row['id'];
                                                        $queryBU = mysqli_query($koneksi, "SELECT jenis
                                                                                            FROM bkk_final bf
                                                                                            JOIN bkk b
                                                                                            ON b.kd_transaksi = bf.id_kdtransaksi
                                                                                            WHERE bf.id = '$bkkId'
                                                                                            ");

                                                        $dataBU = mysqli_fetch_assoc($queryBU);
                                                        if ($dataBU['jenis'] == 'umum') {
                                                            echo "<button class='btn btn-success'> Dibayar</button>";
                                                        } else {
                                                            echo "<button class='btn btn-warning'> Belum dibayar</button>";
                                                        }
                                                    } else {
                                                        echo "<button class='btn btn-warning'> Belum dibayar</button>";
                                                    }
                                                }

                                                ?>
                                            </td>
                                            <td>
                                                <a href="?p=verifikasi_bkk&aksi=lihat&id=<?= $row['id']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button type="button" class="btn btn-warning"><i class="fa fa-search-plus"></i></button></span></a>
                                            </td>
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