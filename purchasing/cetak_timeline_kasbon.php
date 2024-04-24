<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
}

$query = mysqli_query($koneksi, "SELECT id_kasbon, nm_barang, nm_divisi, created_on, app_purchasing, tgl_kasbon, app_costcontrol, app_mgr_ga, app_pajak, app_mgr_finance, app_direktur2
                                    FROM kasbon k
                                    JOIN detail_biayaops db 
                                        on k.id_dbo = db.id 
                                    JOIN biaya_ops bo 
                                        ON db.kd_transaksi = bo.kd_transaksi 
                                    LEFT JOIN divisi dv
                                        ON db.id_divisi = dv.id_divisi 
                                    WHERE status_kasbon = '10'
                                    AND month(tgl_kasbon) = '$bulan'
                                    AND YEAR(tgl_kasbon) = '$tahun'
                                    ORDER BY id_kasbon, tgl_kasbon ASC");

$total = mysqli_num_rows($query);

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "purchasing") {
    echo "<script>window.alert('Engga bisa cetak, login dulu!');
						location='../index.php'
					</script>";
} elseif ($total > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=Timeline-Kasbon-" . $nm_bln[$bulan] . "-" . $tahun . ".xls");

    $no = 1;
?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Kode Kasbon</th>
            <th>Nama Barang</th>
            <th>Divisi</th>
            <th>Request User</th>
            <th>Input Sistem</th>
            <th>Bidding</th>
            <th>Cost Control</th>
            <th>Manager GA</th>
            <th>Pajak</th>
            <th>Manager Finance</th>
            <th>Direksi</th>
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['id_kasbon']; ?></td>
                <td><?= $data['nm_barang']; ?></td>
                <td><?= $data['nm_divisi']; ?></td>
                <td><?= $data['created_on']; ?></td>
                <td><?= $data['app_purchasing']; ?></td>
                <td><?= $data['tgl_kasbon']; ?></td>
                <td><?= $data['app_costcontrol']; ?></td>
                <td><?= $data['app_mgr_ga']; ?></td>
                <td><?= $data['app_pajak']; ?></td>
                <td><?= $data['app_mgr_finance']; ?></td>
                <td><?= $data['app_direktur2']; ?></td>
            </tr>
        <?php $no++;
        } ?>
    </table>

<?php
} else {
    echo "<script>alert('Data yang ingin dicetak tidak ada!');
        location='index.php?p=transaksi_kasbon'
    </script>";;
}
