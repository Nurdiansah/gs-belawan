<?php

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
    $jmlKarakter = strlen($bulan) + 1;
}

$query = mysqli_query($koneksi, "SELECT * FROM bkk_final b    
                                    JOIN anggaran a
                                        ON b.id_anggaran = a.id_anggaran
                                    WHERE b.status_bkk = '4'
                                    AND SUBSTRING(no_bkk, 11, $jmlKarakter) = '$bulan/'     -- ngambil bulan romawi ditambah /
                                    AND RIGHT(no_bkk, 4) = '$tahun'     -- ngambil tahun paling kanan dari field no_bkk, (minggir2 kanan kaya belek)
                                    ORDER BY no_bkk ASC
                            ");
$totalData = mysqli_num_rows($query);
$no = 1;

if ($totalData > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=BKK-" . getNotRomawi($bulan) . "-" . $tahun . ".xls");

?>
    <table>
        <tr>
            <th align="left" colspan="2">BUKTI KAS KELUAR</th>
        </tr>
        <tr>
            <th align="left" colspan="2">PT. GRAHA SEGARA</th>
        </tr>
        <tr>
            <th align="left" colspan="2"><?= strtoupper(getNotRomawi($bulan)) . " " . $tahun ?></th>
        </tr>
    </table>
    <br>
    <table border="1">
        <tr>
            <th>No BKK</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Kode Anggaran</th>
            <th>Pengajuan</th>
            <th>Nominal</th>
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $data['no_bkk']; ?></td>
                <td><?= formatTanggal($data['created_on_bkk']); ?></td>
                <td><?= $data['keterangan']; ?></td>
                <td><?= $data['kd_anggaran']; ?> [<?= $data['nm_item']; ?>]</td>
                <td><?= $data['pengajuan']; ?></td>
                <td><?= formatRupiah2($data['nominal']); ?></td>
            </tr>
        <?php $no++;
        } ?>
    </table>
<?php } else {
    echo "<script>alert('Data yang ingin dicetak tidak ada!');
        location='index.php?p=transaksi_bkk'
    </script>";;
} ?>