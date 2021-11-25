<?php
$queryKu = mysqli_query($koneksi, "SELECT * 
                                    FROM kasbon k
                                    JOIN detail_biayaops dbo
                                    ON k.id_dbo = dbo.id
                                    JOIN divisi d
                                    ON d.id_divisi = dbo.id_divisi                                            
                                    WHERE k.status_kasbon = '2'
                                    AND from_user = '1'
                                    ORDER BY k.id_kasbon DESC   ");

?>

<!-- Kasbon dari User -->
<br>
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
                <th>Aksi</th>
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
                        <td> <button class="btn btn-success"><?= formatRupiah($row['harga_akhir']) ?> </button></td>
                        <td>
                            <a href="?p=verifikasi_dkasbon_user&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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
                                            <td colspan='7'> Tidak Ada Data</td>
                                        </tr>
                                        ";
                }
    ?>

        </tbody>
    </table>
</div>