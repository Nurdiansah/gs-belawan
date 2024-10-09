<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
}

$query = mysqli_query($koneksi, "SELECT id, nm_divisi, no_coa, nm_coa, kd_pt, nm_pt, kd_programkerja, nm_programkerja, kd_anggaran, nm_item, pengajuan, id_kdtransaksi, no_bkk, keterangan, nilai_barang, nilai_jasa, nilai_ppn, CASE WHEN id_pph = '1' THEN nilai_pph END AS pph_21,
                                        CASE WHEN id_pph = '2' THEN nilai_pph END AS pph_23,
                                        CASE WHEN id_pph = '3' THEN nilai_pph END AS pph_4,
                                        nominal
                                    FROM bkk_final b    
                                    LEFT JOIN anggaran a
                                        ON b.id_anggaran = a.id_anggaran
                                    LEFT JOIN divisi dvs
                                        ON dvs.id_divisi = a.id_divisi
                                    LEFT JOIN program_kerja pk
                                        ON id_programkerja = programkerja_id
                                    LEFT JOIN cost_center cc
					                    ON id_costcenter = costcenter_id
                                    LEFT JOIN pt p
                                        ON id_pt = pt_id
                                    WHERE no_bkk LIKE '%/$bulan/$tahun'
                                    ORDER BY no_bkk DESC
                            ");

$totalData = mysqli_num_rows($query);
$no = 1;


if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "kordinator_pajak") {
    echo "<script>window.alert('Engga bisa cetak, login dulu!');
						location='../index.php'
					</script>";
} elseif ($totalData > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=BKK-" . getNotRomawi($bulan) . "-" . $tahun . ".xls");

?>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Nama Divisi</th>
            <th>COA</th>
            <th>Project</th>
            <th>Program Kerja</th>
            <th>Deskripsi Anggaran</th>
            <th>Pengajuan</th>
            <th>Kode Transkasi</th>
            <th>No BKK</th>
            <th>Keterangan</th>
            <th>Nilai Barang</th>
            <th>Nilai Jasa</th>
            <th>Nilai PPN</th>
            <th>PPh 21</th>
            <th>PPh 23</th>
            <th>PPh 4</th>
            <th>Nominal</th>
            <!-- <th>#</th> -->
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['nm_divisi']; ?></td>
                <td><?= $data['no_coa'] . " [" . $data['nm_coa'] . "]"; ?></td>
                <td><?= $data['nm_pt']; ?></td>
                <td><?= $data['kd_programkerja'] . " [" . $data['nm_programkerja'] . "]"; ?></td>
                <td><?= $data['kd_anggaran'] . " [" . $data['nm_item'] . "]"; ?></td>
                <td><?= $data['pengajuan']; ?></td>
                <td><?= $data['id_kdtransaksi']; ?></td>
                <td><?= $data['no_bkk']; ?></td>
                <td><?= $data['keterangan']; ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['nilai_barang']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['nilai_jasa']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['nilai_ppn']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['pph_21']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['pph_23']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['pph_4']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['nominal']); ?></td>
                <!-- <td><a href="<?= host(); ?>anggaran/bkk_new.php?id=<?= enkripRambo($data['id']); ?>" download">Lihat BKK</a></td> -->
            </tr>
        <?php
            // $totalDpp += $data['nilai_barang'] + $data['nilai_jasa'];
            // $totalPpn += $data['nilai_ppn'];
            // $totalPph23 += $data['pph_23'];
            // $totalPph4 += $data['pph_4'];
            // $totalPph21 += $data['pph_21'];
            // $totalNominal += $data['nominal'];
            $no++;
        } ?>
    </table>
<?php } else {
    echo "<script>alert('Data yang ingin dicetak tidak ada!');
        location='index.php?p=transaksi_bkk'
    </script>";;
} ?>