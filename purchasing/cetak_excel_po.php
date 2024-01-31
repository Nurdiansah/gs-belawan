<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
}

$query = mysqli_query($koneksi, "SELECT po_number, tgl_po, dbo.kd_transaksi as dbokd_transaksi, nm_divisi, CONCAT(kd_anggaran, ' [', nm_item, ']') as kd_anggaran, nm_barang, sub_deskripsi, concat(sub_qty, ' (',sub_unit,')') as sub_qtyunit, sub_unitprice, total_price, merk, type, spesifikasi, keterangan, nilai_barang, nilai_jasa, nilai_ppn, nm_pph, nilai_pph, diskon_po, biaya_lain, grand_totalpo
                                    FROM po p
                                    JOIN detail_biayaops dbo
                                        ON p.id_dbo = dbo.id
                                    JOIN biaya_ops bo
                                        ON dbo.kd_transaksi = bo.kd_transaksi
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi
                                    LEFT JOIN anggaran ag
                                        ON ag.id_anggaran = dbo.id_anggaran
                                    JOIN sub_dbo sdbo
                                        ON sdbo.id_dbo = id
                                    LEFT JOIN pph ph
                                        ON p.id_pph = ph.id_pph
                                    WHERE status_po = '10'
                                    AND MONTH(tgl_po) = '$bulan'
                                    AND YEAR(tgl_po) = '$tahun'
                                    ORDER BY po_number, tgl_po DESC");

$total = mysqli_num_rows($query);

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "purchasing") {
    echo "<script>window.alert('Engga bisa cetak, login dulu!');
						location='../index.php'
					</script>";
} elseif ($total > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=PO-" . bulanArray($bulan) . "-" . $tahun . ".xls");

    $no = 1;
?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Kode PO</th>
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
            <th>Nilai Barang</th>
            <th>Nilai Jasa</th>
            <th>Nilai PPN</th>
            <th>PPh</th>
            <th>Nilai PPh</th>
            <th>Diskon</th>
            <th>Biaya Lain</th>
            <th>Total</th>
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['po_number']; ?></td>
                <td><?= $data['tgl_po']; ?></td>
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
                <td><?= formatRupiah2($data['nilai_barang']); ?></td>
                <td><?= formatRupiah2($data['nilai_jasa']); ?></td>
                <td><?= formatRupiah2($data['nilai_ppn']); ?></td>
                <td><?= $data['nm_pph']; ?></td>
                <td><?= formatRupiah2($data['nilai_pph']); ?></td>
                <td><?= formatRupiah2($data['diskon_po']); ?></td>
                <td><?= formatRupiah2($data['biaya_lain']); ?></td>
                <td><?= formatRupiah2($data['grand_totalpo']); ?></td>
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
