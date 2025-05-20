<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
}

$query = mysqli_query($koneksi, "SELECT po_number, nm_barang, nm_divisi, created_on, app_purchasing, tgl_po, app_pajak, app_mgr_ga, app_mgr_finance, app_direksi, app_direksi2
                                    FROM po p
                                    JOIN detail_biayaops db 
                                        on p.id_dbo = db.id
                                    JOIN biaya_ops bo 
                                        ON db.kd_transaksi = bo.kd_transaksi 
                                    LEFT JOIN divisi dv
                                        ON db.id_divisi = dv.id_divisi 
                                    WHERE status_po = '10'
                                    AND month(tgl_po) = '$bulan'
                                    AND YEAR(tgl_po) = '$tahun'
                                    ORDER BY po_number, tgl_po ASC");

$total = mysqli_num_rows($query);

if (!isset($_SESSION['username_gs']) || $_SESSION['level_gs'] != "purchasing") {
    echo "<script>window.alert('Engga bisa cetak, login dulu!');
						location='../index.php'
					</script>";
} elseif ($total > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=Timeline-PO-" . $nm_bln[$bulan] . "-" . $tahun . ".xls");

    $no = 1;
?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>No PO</th>
            <th>Nama Barang</th>
            <th>Divisi</th>
            <th>Request User</th>
            <th>Input Sistem</th>
            <th>Bidding</th>
            <th>Manager GA</th>
            <th>Manager Finance</th>
            <th>Direksi 1</th>
            <th>Direksi 2</th>
            <th>Pajak</th>
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['po_number']; ?></td>
                <td><?= $data['nm_barang']; ?></td>
                <td><?= $data['nm_divisi']; ?></td>
                <td><?= $data['created_on']; ?></td>
                <td><?= $data['app_purchasing']; ?></td>
                <td><?= $data['tgl_po']; ?></td>
                <td><?= $data['app_mgr_ga']; ?></td>
                <td><?= $data['app_mgr_finance']; ?></td>
                <td><?= $data['app_direksi']; ?></td>
                <td><?= $data['app_direksi2']; ?></td>
                <td><?= $data['app_pajak']; ?></td>
            </tr>
        <?php $no++;
        } ?>
    </table>

<?php
} else {
    echo "<script>alert('Data yang ingin dicetak tidak ada!');
        location='index.php?p=transaksi_po'
    </script>";;
}
