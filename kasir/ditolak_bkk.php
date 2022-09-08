<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

$query = mysqli_query($koneksi, "SELECT *, b.id as id_bkk_final, 'bkk_final' as tabel
                                    FROM bkk_final b
                                    LEFT JOIN anggaran a
                                        ON b.id_anggaran = a.id_anggaran
                                    LEFT JOIN tolak_bkk_final
                                        ON b.id = id_bkk_final
                                    WHERE b.status_bkk = '101'
                                    -- AND pengajuan != 'PO'
                                UNION ALL
                                SELECT *, b.id as id_bkk_final, 'bkk_ke_pusat' as tabel
                                    FROM bkk_ke_pusat b
                                    LEFT JOIN anggaran a
                                        ON b.id_anggaran = a.id_anggaran
                                    LEFT JOIN tolak_bkk_final
                                        ON b.id = id_bkk_final
                                    WHERE b.status_bkk = '101'
                        ");
?>
<!-- onclick="window.open('bkk_new.php?id=<?= $row['id']; ?>','newwindow','width=700,height=700'); return false;" -->
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <br>
                <div class="box-header with-border">
                    <h3 class="text-center">BKK Ditolak</h3>
                </div>
                <div class="box-body">
                    <form method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                        <div class="table-responsive">
                            <table class="table text-center table table-striped table-hover" id=" ">
                                <thead>
                                    <tr style="background-color :#B0C4DE;">
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jenis BKK</th>
                                        <th>Keterangan</th>
                                        <th>Kode Anggaran</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query)) {
                                        while ($row = mysqli_fetch_assoc($query)) :
                                    ?>
                                            <tr>
                                                <td> <?= $no; ?> </td>
                                                <td> <?= formatTanggal($row['created_on_bkk']); ?> </td>
                                                <td> <?= $row['pengajuan']; ?> </td>
                                                <td> <?= $row['keterangan']; ?> </td>
                                                <td> <?= $row['kd_anggaran']; ?> </td>
                                                <td><span class="btn btn-success"> <?= formatRupiah($row['nominal']); ?></span> </td>
                                                <td><a href="index.php?p=dtl_bkkditolak&id=<?= $row['id_bkk_final']; ?>&tabel=<?= $row['tabel']; ?>" class="btn btn-warning"><i class="fa fa-search-plus"></i></a></td>
                                            </tr>
                                    <?php
                                            $no++;
                                        endwhile;
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>