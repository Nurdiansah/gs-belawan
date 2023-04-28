<?php
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

date_default_timezone_set('Asia/Jakarta');

if (isset($_GET['tahun']) && isset($_GET['project'])) {
    $tahun = dekripRambo($_GET['tahun']);
    $project = dekripRambo($_GET['project']);
}

$link = "url=index.php?p=transaksi_bkk&lvl=anggaran";

$query = mysqli_query($koneksi, "SELECT DISTINCT agg.id_anggaran, nm_divisi, nm_header, nm_subheader, no_coa, nm_coa, nm_item, 
                                        januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal AS jml_nominal,
                                        januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi AS jml_realisasi,
                                        IFNULL(SUM(nota.nominal), 0) AS nota, IFNULL(SUM(pra_nota.nominal), 0) AS pra_nota
                                    FROM program_kerja pk
                                    JOIN anggaran agg
                                        ON programkerja_id = id_programkerja
                                    JOIN cost_center cc
                                        ON costcenter_id = id_costcenter
                                    JOIN pt_copy pt
                                        ON pt_id = id_pt
                                    JOIN divisi_copy dvs
                                        ON divisi_id = dvs.id_divisi
                                    JOIN parent_divisi_copy pd
                                        ON parent_id = id_parent
                                    LEFT JOIN realisasi_sementara nota
                                        ON agg.id_anggaran = nota.id_anggaran
                                        AND nota.pengajuan = 'BUM'
                                        AND nota.is_deleted = '0'
                                    LEFT JOIN realisasi_sementara pra_nota
                                        ON agg.id_anggaran = pra_nota.id_anggaran
                                        AND pra_nota.pengajuan = 'PO'
                                        AND pra_nota.is_deleted = '0'
                                    JOIN sub_header sh
                                        ON subheader_id = id_subheader
                                    JOIN header h
                                        ON sh.id_header = h.id_header
                                    WHERE agg.tahun = '$tahun'
                                    AND id_pt = '$project'
                                    GROUP BY agg.id_anggaran
                                    ORDER BY nm_header, nm_subheader, nm_coa ASC");

$total = mysqli_num_rows($query);

// ngambil data PT
$dataPT = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pt WHERE id_pt = '$project'"));

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "anggaran") {
    echo "<script>window.alert('Engga bisa cetak, ente belom login!');
						location='../index.php'
					</script>";
} else if ($total > 0) {
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file eksport
    header("Content-Disposition: attachment; filename=LR02-" . $tahun . ".xls");

    // header("Content-Disposition: attachment; filename=RK01\"$tahun\".xlsx");
    // header("Content-Type: application/vnd.ms-excel");

?>

    <table>
        <tr>
            <td colspan="4"><b>LAPORAN REALISASI ANGGARAN TAHUN <?= $tahun; ?></b></td>
        </tr>
        <tr>
            <td colspan="4"><b>PT Graha Segara Belawan</b></td>
        </tr>
        <tr>
            <td colspan="4"><b>Project - <?= $dataPT['nm_pt']; ?></b></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>

    <table border="1">
        <tr style="background-color: #87CEFA;">
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th>Divisi</th>
            <th>Anggaran</th>
            <th>Realisasi Kas</th>
            <th>Realisasi Nota</th>
            <th>Realisasi Pra Nota</th>
            <th>Total Realisasi</th>
            <th>Sisa Anggaran</th>
            <th>% Realisasi</th>
            <!-- <th>Link</th> -->
        </tr>

        <?php

        // variabel per sub header, dideklarasiin 0 dulu
        $sub_header = "";
        $sub_header_nominal = 0;
        $sub_header_realisasi = 0;
        $sub_header_nota = 0;
        $sub_header_pranota = 0;
        $sub_header_jumlah_realisasi = 0;
        $sub_header_sisa_anggaran = 0;
        $sub_header_realisasi_persen = 0;

        // variabel per header, dideklarasiin 0 dulu
        $header = "";
        $header_nominal = 0;
        $header_realisasi = 0;
        $header_nota = 0;
        $header_pranota = 0;
        $header_jumlah_realisasi = 0;
        $header_sisa_anggaran = 0;
        $header_realisasi_persen = 0;

        $no = 1;

        while ($data = mysqli_fetch_assoc($query)) {

            // Sub total per sub header ditengah
            if ($no > 1 && $sub_header != $data['nm_subheader']) {  ?>
                <tr style="background-color: yellow;">
                    <th></th>
                    <th><?= $sub_header; ?></th>
                    <th></th>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_sisa_anggaran); ?></b></td>
                    <th><?= $sub_header_realisasi_persen; ?>%</th>
                    <!-- <th></th> -->
                </tr>

            <?php
                // setelah ditampilin, dideklarasiin lagi ke 0
                $sub_header_nominal = 0;
                $sub_header_realisasi = 0;
                $sub_header_nota = 0;
                $sub_header_pranota = 0;
                $sub_header_jumlah_realisasi = 0;
                $sub_header_sisa_anggaran = 0;
                $sub_header_realisasi_persen = 0;
            }
            $sub_header_nominal += $data['jml_nominal'];
            $sub_header_realisasi += $data['jml_realisasi'];
            $sub_header_nota += $data['nota'];
            $sub_header_pranota += $data['pra_nota'];
            $sub_header_jumlah_realisasi = $sub_header_realisasi + $sub_header_nota + $sub_header_pranota;
            $sub_header_sisa_anggaran = $sub_header_nominal - $sub_header_jumlah_realisasi;
            $sub_header_realisasi_persen = cekPersenNew($sub_header_jumlah_realisasi, $sub_header_nominal);
            // END sub total per sub header ditengah

            // Sub total per header ditengah
            if ($no > 1 && $header != $data['nm_header']) {  ?>
                <tr style="background-color: red;">
                    <th></th>
                    <th><?= $header; ?></th>
                    <th></th>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_sisa_anggaran); ?></b></td>
                    <th><?= $header_realisasi_persen; ?>%</th>
                    <!-- <th></th> -->
                </tr>

            <?php
                // setelah ditampilin, dideklarasiin lagi ke 0
                $header_nominal = 0;
                $header_realisasi = 0;
                $header_nota = 0;
                $header_pranota = 0;
                $header_jumlah_realisasi = 0;
                $header_sisa_anggaran = 0;
                $header_realisasi_persen = 0;
            }
            $header_nominal += $data['jml_nominal'];
            $header_realisasi += $data['jml_realisasi'];
            $header_nota += $data['nota'];
            $header_pranota += $data['pra_nota'];
            $header_jumlah_realisasi = $header_realisasi + $header_nota + $header_pranota;
            $header_sisa_anggaran = $header_nominal - $header_jumlah_realisasi;
            $header_realisasi_persen = cekPersenNew($header_jumlah_realisasi, $header_nominal);
            // END total per header ditengah

            // isi
            $jumlah_realisasi =   $data['jml_realisasi'] + $data['nota'] + $data['pra_nota'];
            $sisa_anggaran = $data['jml_nominal'] - $jumlah_realisasi;
            $realisasi_persen = cekPersenNew($jumlah_realisasi, $data['jml_nominal']);
            ?>
            <tr>
                <td><?= $data['no_coa']; ?></td>
                <td><?= $data['nm_coa']; ?></td>
                <td><?= $data['nm_divisi']; ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['jml_nominal']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['jml_realisasi']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['pra_nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($sisa_anggaran); ?></td>
                <td style="text-align: center;"><?= $realisasi_persen; ?>%</td>
                <!-- <td>-</td> -->
            </tr>
            <!-- end isi -->

        <?php
            $sub_header = $data['nm_subheader'];
            $header = $data['nm_header'];
            $no++;
        }
        ?>

        <!-- sub total sub header paling akhir -->
        <tr style="background-color: yellow;">
            <th></th>
            <th><?= $sub_header; ?></th>
            <th></th>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_sisa_anggaran); ?></b></td>
            <th><?= $sub_header_realisasi_persen; ?>%</th>
            <!-- <th></th> -->
        </tr>

        <!-- total header paling akhir -->
        <tr style="background-color: red;">
            <th></th>
            <th><?= $header; ?></th>
            <th></th>
            <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_sisa_anggaran); ?></b></td>
            <th><?= $header_realisasi_persen; ?>%</th>
            <!-- <th></th> -->
        </tr>
    </table>
<?php } else {
    echo "<script>window.alert('Data laporan Project (LR02) tidak ada/kosong!');
    				location='index.php?p=laporan_lr&sp=lr_02'
    			</script>";
} ?>