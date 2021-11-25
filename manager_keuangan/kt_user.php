<?php

$queryKu = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k                                            
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = dbo.id_divisi                                            
                                            WHERE k.status_kasbon = 8 AND from_user = '1' AND id_manager='$idUser'
                                            ");
?>
<!-- <form method="post" enctype="multipart/form-data" action="setuju_kasbon2.php" class="form-horizontal"> -->
<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id=" ">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>Kode </th>
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
                if (mysqli_num_rows($queryKu)) {
                    while ($row = mysqli_fetch_assoc($queryKu)) :

                ?>
                        <td> <?= $no; ?> </td>
                        <td> <?= $row['id_kasbon']; ?> </td>
                        <td> <?= formatTanggal($row['tgl_kasbon']); ?> </td>
                        <td> <?= $row['nm_divisi']; ?> </td>
                        <td> <?= $row['keterangan']; ?> </td>
                        <td> <span class="label label-success"><?= formatRupiah($row['harga_akhir']) ?> </span></td>
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