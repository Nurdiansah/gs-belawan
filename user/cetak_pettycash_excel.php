<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_GET['bulan']) && isset($_GET['tahun'])) {
    $bulan = dekripRambo($_GET['bulan']);
    $tahun = dekripRambo($_GET['tahun']);
}

$query = mysqli_query($koneksi, "SELECT * -- id_pettycash, nm_divisi, no_coa, nm_coa, kd_project, nm_project, kd_programkerja, nm_programkerja, kd_anggaran, nm_item, pengajuan, id_kdtransaksi, kd_pettycash, keterangan_pettycash, nilai_barang, nilai_jasa, nilai_ppn, CASE WHEN id_pph = '1' THEN nilai_pph END AS pph_21,
                                            -- CASE WHEN id_pph = '2' THEN nilai_pph END AS pph_23,
                                            -- CASE WHEN id_pph = '3' THEN nilai_pph END AS pph_4,
                                            -- total_pettycash
                                        FROM transaksi_pettycash tp  
                                        LEFT JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran
                                        LEFT JOIN divisi dvs
                                            ON dvs.id_divisi = a.id_divisi
                                        LEFT JOIN program_kerja pk
                                            ON id_programkerja = programkerja_id
                                        LEFT JOIN cost_center cc
                                            ON id_costcenter = costcenter_id
                                        LEFT JOIN pt p
                                            ON id_pt = pt_id
                                        WHERE tp.status_pettycash = '5'
                                        AND MONTH(pym_ksr) = '$bulan'
                                        AND YEAR(pym_ksr) = '$tahun'
                                        ORDER BY kd_pettycash DESC");

$totalData = mysqli_num_rows($query);
$no = 1;


if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "admin_divisi") {
    echo "<script>window.alert('Engga bisa cetak, login dulu!');
						location='../index.php'
					</script>";
} elseif ($totalData > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=Pettycash-" . $nm_bln[$bulan] . "-" . $tahun . ".xls");

?>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Nama Divisi</th>
            <th>COA</th>
            <th>Project</th>
            <th>Program Kerja</th>
            <th>Deskripsi Anggaran</th>
            <th>Kode Pettycash</th>
            <th>Keterangan</th>
            <th>Nominal</th>
            <!-- <th>#</th> -->
        </tr>
        <?php while ($data = mysqli_fetch_assoc($query)) { ?>
            <tr>
                <td><?= $no; ?></td>
                <td><?= $data['nm_divisi']; ?></td>
                <td><?= $data['no_coa'] . " [" . $data['nm_coa'] . "]"; ?></td>
                <td><?= $data['nm_project']; ?></td>
                <td><?= $data['kd_programkerja'] . " [" . $data['nm_programkerja'] . "]"; ?></td>
                <td><?= $data['kd_anggaran'] . " [" . $data['nm_item'] . "]"; ?></td>
                <td><?= $data['kd_pettycash']; ?></td>
                <td><?= $data['keterangan_pettycash']; ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['total_pettycash']); ?></td>
                <!-- <td><a href="<?= host(); ?>anggaran/cetak_petty.php?id=<?= enkripRambo($data['id_pettycash']); ?>" download">Lihat Pettycash</a></td> -->
            </tr>
        <?php
            $no++;
        } ?>
    </table>
<?php } else {
    echo "<script>alert('Data yang ingin dicetak tidak ada!');
        location='index.php?p=laporan_pettycash'
    </script>";
} ?>