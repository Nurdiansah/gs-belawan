<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
}

$query = mysqli_query($koneksi, "SELECT kd_pettycash, created_pettycash_on, dbo.kd_transaksi as dbokd_transaksi, nm_divisi, CONCAT(kd_anggaran, ' [', nm_item, ']') AS kd_anggaran, nm_barang, sub_deskripsi, CONCAT(sub_qty, ' (',sub_unit,')') AS sub_qtyunit, sub_unitprice, total_price, merk, TYPE, spesifikasi, keterangan, nominal_pengajuan, pengembalian, penambahan, total_pettycash, tp.penerima_dana, pym_ksr
                                    FROM transaksi_pettycash tp
                                    JOIN detail_biayaops dbo
                                        ON tp.id_dbo = dbo.id
                                    JOIN biaya_ops bo
                                        ON dbo.kd_transaksi = bo.kd_transaksi
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi
                                    LEFT JOIN anggaran ag
                                        ON ag.id_anggaran = dbo.id_anggaran
                                    JOIN sub_dbo sdbo
                                        ON sdbo.id_dbo = id
                                    WHERE status_pettycash = '5'
                                    AND MONTH(created_pettycash_on) = '$bulan'
                                    AND YEAR(created_pettycash_on) = '$tahun'
                                    ORDER BY kd_pettycash, created_pettycash_on DESC");

$total = mysqli_num_rows($query);

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "purchasing") {
    echo "<script>window.alert('Engga bisa cetak, login dulu!');
						location='../index.php'
					</script>";
} elseif ($total > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=Pettycash-" . GetBulanIndo($bulan) . "-" . $tahun . ".xls");

    $no = 1;
?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Kode Pettycash</th>
            <th>Tanggal</th>
            <th>Nomor MR</th>
            <th>Divisi</th>
            <th>Kode Anggaran</th>
            <th>Nama Barang</th>
            <th>Rincian Barang</th>
            <th>QTY</th>
            <th>Nominal Rician</th>
            <th>Total Nominal Rician</th>
            <th>Merk</th>
            <th>Tipe</th>
            <th>Spesifikasi</th>
            <th>Keterangan</th>
            <th>Nominal Pengajuan</th>
            <th>Pengembalian</th>
            <th>Penambahan</th>
            <th>Total</th>
            <th>Penerima Dana</th>
            <th>Waktu Penerima Dana</th>
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['kd_pettycash']; ?></td>
                <td><?= $data['created_pettycash_on']; ?></td>
                <td><?= $data['dbokd_transaksi']; ?></td>
                <td><?= $data['nm_divisi']; ?></td>
                <td><?= $data['kd_anggaran']; ?></td>
                <td><?= $data['nm_barang']; ?></td>
                <td><?= $data['sub_deskripsi']; ?></td>
                <td><?= $data['sub_qtyunit']; ?></td>
                <td><?= formatRupiah2($data['sub_unitprice']); ?></td>
                <td><?= formatRupiah2($data['total_price']); ?></td>
                <td><?= $data['merk']; ?></td>
                <td><?= $data['tipe']; ?></td>
                <td><?= $data['spesifikasi']; ?></td>
                <td><?= $data['keterangan']; ?></td>
                <td><?= formatRupiah2($data['nominal_pengajuan']); ?></td>
                <td><?= formatRupiah2($data['pengembalian']); ?></td>
                <td><?= formatRupiah2($data['penambahan']); ?></td>
                <td><?= formatRupiah2($data['total_pettycash']); ?></td>
                <td><?= $data['penerima_dana']; ?></td>
                <td><?= $data['pym_ksr']; ?></td>
            </tr>
        <?php $no++;
        } ?>
    </table>

<?php
} else {
    echo "<script>alert('Data yang ingin dicetak tidak ada!');
        location='index.php?p=transaksi_pettycash'
    </script>";;
}
