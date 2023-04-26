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

$query = mysqli_query($koneksi, "SELECT DISTINCT agg.id_anggaran, kd_pt, nm_pt, nm_divisi, kd_programkerja, nm_programkerja, no_coa, nm_coa, nm_item, 
                                        januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal AS jml_nominal,
                                        januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi AS jml_realisasi,
                                        IFNULL(SUM(nota.nominal), 0) AS nota, IFNULL(SUM(pra_nota.nominal), 0) AS pra_nota
                                        -- (januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi) + IFNULL(SUM(nota.nominal), 0) + IFNULL(SUM(pra_nota.nominal), 0) AS total_realisasi
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
                                    WHERE agg.tahun = '$tahun'
                                    AND id_pt = '$project'
                                    -- AND tipe_anggaran = 'OPEX'
                                    GROUP BY agg.id_anggaran
                                    ORDER BY nm_pt, nm_divisi, nm_programkerja ASC
                        ");

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
    // header("Content-Disposition: attachment; filename=RK02-" . $tahun . "-" . $dataPT['nm_pt'] . ".xls");
    header("Content-Disposition: attachment; filename=RK02-" . $tahun . ".xls");

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
        <tr style="background-color: #98FB98;">
            <th>Kode Proyek</th>
            <th>Deskripsi Proyek</th>
            <th>Kode Departemen</th>
            <th>Kode Sub Departemen</th>
            <th>Kode Rencana Kerja</th>
            <th>Deskripsi Rencana Kerja</th>
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <th>Deskripsi Biaya</th>
            <th>Anggaran</th>
            <th>Realisasi Kas</th>
            <th>Realisasi Nota</th>
            <th>Realisasi Pra Nota</th>
            <th>Total Realisasi</th>
            <th>Sisa Anggaran</th>
            <th>% Realisasi</th>
            <th>Link</th>
        </tr>
        <?php

        $program_kerja = 0;
        $divisi = 0;
        $pt = 0;
        $no = 1;

        // variabel per program kerja, dideklarasiin 0 dulu
        $pk_kd_pt = "";
        $pk_nm_pt = "";
        $pk_kd_dept = "";
        $pk_kd_subdept = "";
        $pk_kd_subrenja = "";
        $sub_pk_nominal = 0;
        $sub_pk_realisasi = 0;
        $sub_pk_nota = 0;
        $sub_pk_pranota = 0;
        $sub_pk_jumlah_realisasi = 0;
        $sub_pk_sisa_anggaran = 0;
        $sub_pk_realisasi_persen = 0;

        // variabel nominal per divisi, dideklarasiin 0 dulu
        $sub_divisi_nominal = 0;
        $sub_divisi_realisasi = 0;
        $sub_divisi_nota = 0;
        $sub_divisi_pranota = 0;
        $sub_divisi_jumlah_realisasi = 0;
        $sub_divisi_sisa_anggaran = 0;
        $sub_divisi_realisasi_persen = 0;

        // variabel nominal per pt, dideklarasiin 0 dulu
        $sub_pt_nominal = 0;
        $sub_pt_realisasi = 0;
        $sub_pt_nota = 0;
        $sub_pt_pranota = 0;
        $sub_pt_jumlah_realisasi = 0;
        $sub_pt_sisa_anggaran = 0;
        $sub_pt_realisasi_persen = 0;

        while ($data = mysqli_fetch_assoc($query)) {

            // sub total per program kerja ditengah
            if ($no > 1 && $program_kerja != $data['nm_programkerja']) {  ?>
                <!-- tampilin sub total per PK nya -->
                <tr>
                    <th><?= $pk_kd_pt; ?></th>
                    <th><?= $pk_nm_pt; ?></th>
                    <th><?= $pk_kd_dept; ?></th>
                    <th><?= $pk_kd_subdept; ?></th>
                    <th><?= $pk_kd_subrenja; ?></th>
                    <th><?= $program_kerja; ?></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_sisa_anggaran); ?></b></td>
                    <th><?= $sub_pk_realisasi_persen; ?>%</th>
                    <th></th>
                </tr>

            <?php
                // setelah ditampilin, dideklarasiin lagi ke 0
                $pk_kd_pt = "";
                $pk_nm_pt = "";
                $pk_kd_dept = "";
                $pk_kd_subdept = "";
                $pk_kd_subrenja = "";
                $sub_pk_nominal = 0;
                $sub_pk_realisasi = 0;
                $sub_pk_nota = 0;
                $sub_pk_pranota = 0;
                $sub_pk_jumlah_realisasi = 0;
                $sub_pk_sisa_anggaran = 0;
                $sub_pk_realisasi_persen = 0;
            }
            $pk_kd_pt = $data['kd_pt'];
            $pk_nm_pt = $data['nm_pt'];
            $pk_kd_dept = substr($data['kd_programkerja'], 5, 2);
            $pk_kd_subdept = substr($data['kd_programkerja'], 5, 4);
            $pk_kd_subrenja = $data['kd_programkerja'];
            $sub_pk_nominal += $data['jml_nominal'];
            $sub_pk_realisasi += $data['jml_realisasi'];
            $sub_pk_nota += $data['nota'];
            $sub_pk_pranota += $data['pra_nota'];
            $sub_pk_jumlah_realisasi = $sub_pk_realisasi + $sub_pk_nota + $sub_pk_pranota;
            $sub_pk_sisa_anggaran = $sub_pk_nominal - $sub_pk_jumlah_realisasi;
            $sub_pk_realisasi_persen = cekPersenNew($sub_pk_jumlah_realisasi, $sub_pk_nominal);
            // END sub total per program kerja ditengah


            // sub total per divisi ditengah
            if ($no > 1 && $divisi != $data['nm_divisi']) { ?>
                <tr style="background-color: #F0E68C">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total Divisi <?= $divisi; ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_sisa_anggaran); ?></b></td>
                    <th><?= $sub_divisi_realisasi_persen; ?>%</th>
                    <th></th>
                </tr>
            <?php

                $sub_divisi_nominal = 0;
                $sub_divisi_realisasi = 0;
                $sub_divisi_nota = 0;
                $sub_divisi_pranota = 0;
                $sub_divisi_jumlah_realisasi = 0;
                $sub_divisi_sisa_anggaran = 0;
                $sub_divisi_realisasi_persen = 0;
            }
            $sub_divisi_nominal += $data['jml_nominal'];
            $sub_divisi_realisasi += $data['jml_realisasi'];
            $sub_divisi_nota += $data['nota'];
            $sub_divisi_pranota += $data['pra_nota'];
            $sub_divisi_jumlah_realisasi = $sub_divisi_realisasi + $sub_divisi_nota + $sub_divisi_pranota;
            $sub_divisi_sisa_anggaran = $sub_divisi_nominal - $sub_divisi_jumlah_realisasi;
            $sub_divisi_realisasi_persen = cekPersenNew($sub_divisi_jumlah_realisasi, $sub_divisi_nominal);
            // END sub total per divisi ditengah


            // sub total per pt ditengah
            if ($no > 1 && $pt != $data['nm_pt']) { ?>
                <tr style="background-color: #87CEFA">
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total - <?= $pt; ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_sisa_anggaran); ?></b></td>
                    <th><?= $sub_pt_realisasi_persen; ?>%</th>
                    <th></th>
                </tr>
            <?php

                $sub_pt_nominal = 0;
                $sub_pt_realisasi = 0;
                $sub_pt_nota = 0;
                $sub_pt_pranota = 0;
                $sub_pt_jumlah_realisasi = 0;
                $sub_pt_sisa_anggaran = 0;
                $sub_pt_realisasi_persen = 0;
            }
            $sub_pt_nominal += $data['jml_nominal'];
            $sub_pt_realisasi += $data['jml_realisasi'];
            $sub_pt_nota += $data['nota'];
            $sub_pt_pranota += $data['pra_nota'];
            $sub_pt_jumlah_realisasi = $sub_pt_realisasi + $sub_pt_nota + $sub_pt_pranota;
            $sub_pt_sisa_anggaran = $sub_pt_nominal - $sub_pt_jumlah_realisasi;
            $sub_pt_realisasi_persen = cekPersenNew($sub_pt_jumlah_realisasi, $sub_pt_nominal);
            // END sub total per divisi ditengah

            ?>


            <!-- isi  -->
            <?php
            $jumlah_realisasi =   $data['jml_realisasi'] + $data['nota'] + $data['pra_nota'];
            $sisa_anggaran = $data['jml_nominal'] - $jumlah_realisasi;
            $realisasi_persen = cekPersenNew($jumlah_realisasi, $data['jml_nominal']);
            ?>
            <tr>
                <td><?= $data['kd_pt']; ?></td>
                <td><?= $data['nm_pt']; ?></td>
                <td><?= substr($data['kd_programkerja'], 5, 2); ?></td>
                <td><?= substr($data['kd_programkerja'], 5, 4); ?></td>
                <td><?= $data['kd_programkerja']; ?></td>
                <td><?= $data['nm_programkerja']; ?></td>
                <td><?= $data['no_coa']; ?></td>
                <td><?= $data['nm_coa']; ?></td>
                <td><?= $data['nm_item']; ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['jml_nominal']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['jml_realisasi']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($data['pra_nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($sisa_anggaran); ?></td>
                <td style="text-align: center;"><?= $realisasi_persen; ?>%</td>
                <td><a target="_blank" href="<?= host(); ?>index.php?<?= $link; ?>&sp=<?= enkripRambo($data['id_anggaran']); ?>">Lihat BKK</a></td>
            </tr>
            <!-- end isi -->

        <?php
            $program_kerja = $data['nm_programkerja'];
            $divisi = $data['nm_divisi'];
            $pt = $data['nm_pt'];
            $no++;
        }


        // sub total program kerja & divisi paling akhir
        // if ($no > 1 &&  $no == $total + 1) { 
        ?>
        <!-- program kerja -->
        <tr>
            <th><?= $pk_kd_pt; ?></th>
            <th><?= $pk_nm_pt; ?></th>
            <th><?= $pk_kd_dept; ?></th>
            <th><?= $pk_kd_subdept; ?></th>
            <th><?= $pk_kd_subrenja; ?></th>
            <th><?= $program_kerja; ?></th>
            <th></th>
            <th></th>
            <th>Total</th>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pk_sisa_anggaran); ?></b></td>
            <th><?= $sub_pk_realisasi_persen; ?>%</th>
            <th></th>
        </tr>

        <!-- divisi -->
        <tr style="background-color: #F0E68C">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total Divisi <?= $divisi; ?></th>
            <th></th>
            <th></th>
            <th></th>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_divisi_sisa_anggaran); ?></b></td>
            <th><?= $sub_divisi_realisasi_persen; ?>%</th>
            <th></th>
        </tr>

        <!-- pt -->
        <tr style="background-color: #87CEFA">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total - <?= $pt; ?></th>
            <th></th>
            <th></th>
            <th></th>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_pt_sisa_anggaran); ?></b></td>
            <th><?= $sub_pt_realisasi_persen; ?>%</th>
            <th></th>
        </tr>
        <?php // }
        // END sub total program kerja paling akhir
        ?>

    </table>
<?php } else {
    echo "<script>window.alert('Data laporan Project (RK02) tidak ada/kosong!');
						location='index.php?p=laporan_rk&sp=rk_02'
					</script>";
} ?>