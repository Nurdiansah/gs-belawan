<?php
$query = mysqli_query($koneksi, "SELECT * 
                                        FROM kasbon k
                                        JOIN biaya_ops bo
                                        ON k.kd_transaksi = bo.kd_transaksi
                                        JOIN detail_biayaops dbo
                                        ON k.id_dbo = dbo.id
                                        JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi                                            
                                        WHERE k.status_kasbon !=0 AND k.status_kasbon !=3 AND k.status_kasbon !=10  AND from_user= '0' 
                                        ORDER BY k.id_kasbon DESC   ");

$jumlahData  = mysqli_num_rows($query);
?>
<div class="row">
    <br><br>
</div>
<div class="table-responsive">
    <table class="table text-center table table-striped table-hover" id="<?php echo $jumlahData > 0 ? 'material' : ''; ?>">
        <thead>
            <tr style="background-color :#B0C4DE;">
                <th>No</th>
                <th>Kode </th>
                <th>Tanggal</th>
                <th>Divisi</th>
                <th>Deskripsi</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
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
                        <td> <?= $row['id_kasbon']; ?> </td>
                        <td> <?= tanggal_indo($row['tgl_pengajuan']); ?> </td>
                        <td> <?= $row['nm_divisi']; ?> </td>
                        <td> <?= $row['nm_barang']; ?> </td>
                        <td> <?= formatRupiah($row['harga_akhir']) ?> </td>
                        <td>
                            <?php if ($row['status_kasbon'] == 1) {
                                echo "<span class='label label-primary'>'Verifikasi Manager</span>";
                            } else if ($row['status_kasbon'] == 2) {
                                echo "<span class='label label-primary'>'Verifikasi Pajak </span>";
                            } else if ($row['status_kasbon'] == 3) {
                                echo "<span class='label label-primary'>'Approval Costcontrol </span>";
                            } else if ($row['status_kasbon'] == 4) {
                                echo "<span class='label label-primary'>'Approval Manager </span>";
                            } else if ($row['status_kasbon'] == 5) {
                                echo "<span class='label label-primary'>'Approval GM Finance </span>";
                            } else if ($row['status_kasbon'] == 6) {
                                echo "<span class='label label-primary'>'Approval Direktur </span>";
                            } else if ($row['status_kasbon'] == 7) {
                                echo "<span class='label label-warning'>Payment Kasir </span>";
                            } else if ($row['status_kasbon'] == 8) {
                                echo "<span class='label label-info'>'Pengajuan sedang di belikan </span>";
                            } else if ($row['status_kasbon'] == 9) {
                                echo "<span class='label label-info'>'Verifikasi LPJ </span>";
                            } else if ($row['status_kasbon'] == 101) { ?>
                                <span class="label label-danger">Rejected Manager Ga </span>
                            <?php  } else if ($row['status_kasbon'] == 202) { ?>
                                <span class="label label-danger">Rejected Pajak </span>
                            <?php  } else if ($row['status_kasbon'] == 303) { ?>
                                <span class="label label-danger">Rejected Manager Finance </span>
                            <?php  } else if ($row['status_kasbon'] == 404) { ?>
                                <span class="label label-danger">Rejected Direksi </span>
                            <?php  }   ?>
                        </td>
                        <td>
                            <a href="?p=proses_kasbon&aksi=lihat&id=<?= $row['id_kasbon']; ?>"><span data-placement='top' data-toggle='tooltip' title='Lihat'><button class="btn btn-info">Lihat</button></span></a>
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