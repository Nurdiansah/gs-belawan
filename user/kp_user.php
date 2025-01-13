<?php
if ($idDivisi == "6") {
    $queryKu = mysqli_query($koneksi, "SELECT * 
                                        FROM kasbon k
                                        LEFT JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                        JOIN divisi d
                                            ON d.id_divisi = dbo.id_divisi                                            
                                        WHERE status_kasbon BETWEEN 1 AND 606 AND status_kasbon != '10'
                                        AND k.from_user = '1' AND (dbo.id_divisi = '$idDivisi' OR dbo.id_anggaran IN (SELECT id_anggaran FROM anggaran WHERE spj = '1'))
                                        ORDER BY k.id_kasbon DESC   ");
} else {
    $queryKu = mysqli_query($koneksi, "SELECT * 
                                    FROM kasbon k
                                    LEFT JOIN detail_biayaops dbo
                                        ON k.id_dbo = dbo.id
                                    JOIN divisi d
                                        ON d.id_divisi = dbo.id_divisi
                                    WHERE status_kasbon BETWEEN 1 AND 606 AND status_kasbon != '10'
                                    AND k.from_user = '1' AND dbo.id_divisi = '$idDivisi'
                                    ORDER BY k.id_kasbon DESC   ");
}
?>
<!-- <form method="post" enctype="multipart/form-data" action="setuju_kasbon2.php" class="form-horizontal"> -->
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
                <th style="vertical-align: middle;" rowspan='2'>Status</th>
                <th style="vertical-align: middle;" rowspan='2'>Pending</th>
                <th style="vertical-align: middle;" rowspan="2">Detail</th>
            </tr>
            <tr style="background-color :#B0C4DE;">
                <th>Buat</th>
                <th>Penyerahan Dana</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($queryKu)) {
                while ($row = mysqli_fetch_assoc($queryKu)) :

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
                    <tr>
                        <td> <?= $no; ?> </td>
                        <td> <?= $row['id_kasbon']; ?> </td>
                        <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                        <td>
                            <?php
                            if (empty($row['waktu_penerima_dana'])) {
                                echo "-";
                            } else {
                                echo formatTanggal($row['waktu_penerima_dana']);
                            }

                            ?>
                        </td>
                        <td> <?= $row['nm_divisi']; ?> </td>
                        <td> <?= $row['keterangan']; ?> </td>
                        <td>
                            <h4><span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?></h4> </span>
                        </td>
                        <!-- <td>
                            <?php if ($row['status_kasbon'] == 2) { ?>
                                <span class="label label-primary">Verifikasi Pajak</span>
                            <?php  } else if ($row['status_kasbon'] == 3) { ?>
                                <span class="label label-primary">Verifikasi Cost Control</span>
                            <?php  } else if ($row['status_kasbon'] == 4) { ?>
                                <span class="label label-warning">Approval Manager</span>
                            <?php  } else if ($row['status_kasbon'] == 5) { ?>
                                <span class="label label-warning">Approval GM Finance</span>
                            <?php  } else if ($row['status_kasbon'] == 6) { ?>
                                <span class="label label-warning">Approval Direksi</span>
                            <?php  } else if ($row['status_kasbon'] == 7) { ?>
                                <span class="label label-info">Dana sudah bisa di ambil </span>
                            <?php  } else if ($row['status_kasbon'] == 8) { ?>
                                <span class="label label-info">Proses pembelian purchasing</span>
                            <?php  } else if ($row['status_kasbon'] == 9) { ?>
                                <span class="label label-info">Verifikasi LPJ kasir</span>
                            <?php  } elseif (is_null($row['status_kasbon'])) { ?>
                                <span class="label label-info">Submit Kembali Purchasing</span>
                            <?php }  ?>
                        </td> -->
                        <td>
                            <?php if ($row['status_kasbon'] == 1) {
                                if ($row['id_manager'] == '17' || $row['id_manager'] == '20' || $row['id_manager'] == '33') {
                                    echo "<span class='label label-primary'>Verifikasi Manager</span>";
                                } elseif ($row['id_manager'] == '19') {
                                    echo "<h4><span class='label label-primary'> Approval Assistant Manager </span></h4>";
                                } else {
                                    echo "<span class='label label-primary'>Verifikasi Supervisor</span>";
                                }

                            ?>
                            <?php  } else if ($row['status_kasbon'] == 2) { ?>
                                <span class="label label-primary">Verifikasi Pajak </span>
                            <?php  } else if ($row['status_kasbon'] == 3) { ?>
                                <span class="label label-success">Approval Cost Control </span>
                            <?php  } else if ($row['status_kasbon'] == 4) { ?>
                                <span class="label label-success">Approval Manager </span>
                            <?php  } else if ($row['status_kasbon'] == 5) { ?>
                                <span class="label label-success">Approval GM Finance </span>
                            <?php  } else if ($row['status_kasbon'] == 6) { ?>
                                <span class="label label-success">Approval Direksi </span>
                            <?php  } else if ($row['status_kasbon'] == 7) { ?>
                                <span class="label label-success">Dana sudah bisa diambil </span>
                            <?php  } else if ($row['status_kasbon'] == 8) { ?>
                                <span class="label label-success">Silahkan LPJ</span>
                            <?php  } else if ($row['status_kasbon'] == 9) { ?>
                                <span class="label label-primary">Verifikasi LPJ</span>
                            <?php  } elseif ($row['status_kasbon'] == 101) {  ?>
                                <span class="label label-danger">Ditolak Manager</span>
                            <?php  } elseif ($row['status_kasbon'] == 202) {  ?>
                                <span class="label label-danger">Ditolak Pajak</span>
                            <?php  } elseif ($row['status_kasbon'] == 303) {  ?>
                                <span class="label label-danger">Ditolak Manager Finance</span>
                            <?php  } elseif ($row['status_kasbon'] == 404) {  ?>
                                <span class="label label-danger">Ditolak Pajak - Berada di Manager</span>
                            <?php  } elseif ($row['status_kasbon'] == 505) {  ?>
                                <span class="label label-danger">Ditolak Kasir (LPJ Ulang)</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            if (empty($row['waktu_penerima_dana'])) {
                                echo "-";
                            } else {
                                echo "<span class='label label-" . $notifPending . "'>
                                          " . $pendingLpj . "
                                      </span>";
                            }

                            ?>
                        </td>
                        <td>
                            <a href="?p=kasbon_dproses_user&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
                        </td>
                    </tr>
            <?php
                    $no++;
                endwhile;
            }

            $jumlahData  = mysqli_num_rows($queryKu);

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
<!-- <button class="btn btn-primary col-sm-offset-11" type="submit" name="submit" onclick="javascript: return confirm('Apakah anda yakin ingin menyetujui  ?')">Approve</button></span></a>
</form> -->

<script>
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