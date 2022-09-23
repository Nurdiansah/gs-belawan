<?php

$queryKu = mysqli_query($koneksi, "SELECT * FROM kasbon k
                                    LEFT JOIN detail_biayaops dbo
                                        ON id_dbo = id
                                    JOIN divisi d
                                        ON d.id_divisi = dbo.id_divisi
                                    WHERE id_manager = '$idUser'
                                    AND status_kasbon IN ('2', '3', '4', '5', '6', '7', '202', '303', '404', '505')
                                    AND from_user = '1' ORDER BY k.id_kasbon DESC
                            ");

?>
<!-- <form method="post" enctype="multipart/form-data" action="setuju_kasbon2.php" class="form-horizontal"> -->
<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id=" ">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th style="vertical-align: middle;">No</th>
                <th style="vertical-align: middle;">Kode </th>
                <th style="vertical-align: middle;">Tanggal</th>
                <th style="vertical-align: middle;">Divisi</th>
                <th style="vertical-align: middle;">Deskripsi</th>
                <th style="vertical-align: middle;">Total</th>
                <th style="vertical-align: middle;">Status</th>
                <th style="vertical-align: middle;">Detail</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                $no = 1;
                if (mysqli_num_rows($queryKu)) {
                    while ($row = mysqli_fetch_assoc($queryKu)) :

                        // Memnghitung waktu tempuh dari penyerahan dana
                        $payment  = $row['waktu_penerima_dana'];
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
                        <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                        <td> <?= $row['nm_divisi']; ?> </td>
                        <td> <?= $row['keterangan']; ?> </td>
                        <td>
                            <h4><span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?></h4> </span>
                        </td>
                        <td>
                            <?php if ($row['status_kasbon'] == 2) { ?>
                                <span class="label label-primary">Verifikasi Pajak </span>
                            <?php  } else if ($row['status_kasbon'] == 3) { ?>
                                <span class="label label-success">Approval Cost Control </span>
                            <?php  } else if ($row['status_kasbon'] == 4) { ?>
                                <span class="label label-warning">Approval Manager </span>
                            <?php  } else if ($row['status_kasbon'] == 5) { ?>
                                <span class="label label-info">Approval GM Finance </span>
                            <?php  } else if ($row['status_kasbon'] == 6) { ?>
                                <span class="label label-primary">Approval Direksi </span>
                            <?php  } else if ($row['status_kasbon'] == 7) { ?>
                                <span class="label label-success">Dana sudah bisa diambil </span>
                            <?php  } elseif ($row['status_kasbon'] == 202) { ?>
                                <span class="label label-danger">Ditolak Cost Control</span>
                            <?php  } elseif ($row['status_kasbon'] == 303) { ?>
                                <span class="label label-danger">Ditolak Manager</span>
                            <?php  } elseif ($row['status_kasbon'] == 404) { ?>
                                <span class="label label-danger">Ditolak Pajak</span>
                            <?php  } elseif ($row['status_kasbon'] == 505) { ?>
                                <span class="label label-danger">Ditolak GM Finance</span>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="?p=proses_dkasbon_user&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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