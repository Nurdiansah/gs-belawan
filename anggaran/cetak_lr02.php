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

$queryOpex = mysqli_query($koneksi, "SELECT DISTINCT nm_header, nm_subheader, no_coa, nm_coa, no_coa AS nocoa,
                                        SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS jml_nominal,
                                        SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS jml_realisasi,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'BUM'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS nota,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'PO'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS pra_nota
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
                                        JOIN sub_header sh
                                            ON subheader_id = id_subheader
                                        JOIN header h
                                            ON sh.id_header = h.id_header
                                        WHERE agg.tahun = '$tahun'
                                        AND id_pt = '$project'
                                        AND tipe_anggaran = 'OPEX'
                                        GROUP BY no_coa, nm_coa, nm_header, nm_subheader
                                        ORDER BY jenis_anggaran DESC, nm_header, nm_subheader, nm_coa ASC");

$queryCapex = mysqli_query($koneksi, "SELECT DISTINCT nm_header, nm_subheader, no_coa, nm_coa, no_coa AS nocoa,
                                        SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) AS jml_nominal,
                                        SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) AS jml_realisasi,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'BUM'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS nota,
                                        IFNULL((SELECT SUM(nominal)
                                                    FROM realisasi_sementara rs
                                                    JOIN anggaran ag
                                                        ON ag.id_anggaran = rs.id_anggaran
                                                    WHERE no_coa = nocoa
                                                    AND pengajuan = 'PO'
                                                    AND is_deleted = '0'
                                                    GROUP BY no_coa), '0') AS pra_nota
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
                                        JOIN sub_header sh
                                            ON subheader_id = id_subheader
                                        JOIN header h
                                            ON sh.id_header = h.id_header
                                        WHERE agg.tahun = '$tahun'
                                        AND id_pt = '$project'
                                        AND tipe_anggaran = 'CAPEX'
                                        GROUP BY no_coa, nm_coa, nm_header, nm_subheader
                                        ORDER BY jenis_anggaran DESC, nm_header, nm_subheader, nm_coa ASC");


$total = mysqli_num_rows($queryOpex) + mysqli_num_rows($queryCapex);

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
    </table>

    <h4><b><u>OPEX</u></b></h4>
    <table border="1">
        <tr style="background-color: #87CEFA;">
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <!-- <th>Divisi</th> -->
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

        // buat rumus perhitungan pendapatan usaha, laba usaha, laba rugi dll
        // pendapatan bersih
        $pendapatan_bersih_nominal = 0;
        $pendapatan_bersih_realisasi = 0;
        $pendapatan_bersih_nota = 0;
        $pendapatan_bersih_pranota = 0;
        $pendapatan_bersih_jumlah = 0;
        $pendapatan_bersih_sisa = 0;
        $pendapatan_bersih_persen = 0;

        // pendapatan usaha
        $temp_header_nominal_pendapatan_usaha = 0;
        $temp_header_realisasi_pendapatan_usaha = 0;
        $temp_header_nota_pendapatan_usaha = 0;
        $temp_header_pranota_pendapatan_usaha = 0;
        $temp_header_jumlah_pendapatan_usaha = 0;
        $temp_header_sisa_pendapatan_usaha = 0;
        $temp_header_persen_pendapatan_usaha = 0;

        // beban nota
        $temp_header_nominal_beban_nota = 0;
        $temp_header_realisasi_beban_nota = 0;
        $temp_header_nota_beban_nota = 0;
        $temp_header_pranota_beban_nota = 0;
        $temp_header_jumlah_beban_nota = 0;
        $temp_header_sisa_beban_nota = 0;
        $temp_header_persen_beban_nota = 0;

        // laba kotor
        $laba_kotor_nominal = 0;
        $laba_kotor_realisasi = 0;
        $laba_kotor_nota = 0;
        $laba_kotor_pranota = 0;
        $laba_kotor_jumlah = 0;
        $laba_kotor_sisa = 0;
        $laba_kotor_persen = 0;

        // biaya umum dan administrasi
        $temp_header_nominal_beban_umum = 0;
        $temp_header_realisasi_beban_umum = 0;
        $temp_header_nota_beban_umum = 0;
        $temp_header_pranota_beban_umum = 0;
        $temp_header_jumlah_beban_umum = 0;
        $temp_header_sisa_beban_umum = 0;
        $temp_header_persen_beban_umum = 0;

        // beban pokok
        $temp_header_nominal_beban_pokok = 0;
        $temp_header_realisasi_beban_pokok = 0;
        $temp_header_nota_beban_pokok = 0;
        $temp_header_pranota_beban_pokok = 0;
        $temp_header_jumlah_beban_pokok = 0;
        $temp_header_sisa_beban_pokok = 0;
        $temp_header_persen_beban_pokok = 0;

        // laba operasional
        $laba_operasional_nominal = 0;
        $laba_operasional_realisasi = 0;
        $laba_operasional_nota = 0;
        $laba_operasional_pranota = 0;
        $laba_operasional_jumlah = 0;
        $laba_operasional_sisa = 0;
        $laba_operasional_persen = 0;

        // pendapatan lain
        $temp_header_nominal_pendapatan_lain = 0;
        $temp_header_realisasi_pendapatan_lain = 0;
        $temp_header_nota_pendapatan_lain = 0;
        $temp_header_pranota_pendapatan_lain = 0;
        $temp_header_jumlah_pendapatan_lain = 0;
        $temp_header_sisa_pendapatan_lain = 0;
        $temp_header_persen_pendapatan_lain = 0;

        // biaya lain
        $temp_header_nominal_biaya_lain = 0;
        $temp_header_realisasi_biaya_lain = 0;
        $temp_header_nota_biaya_lain = 0;
        $temp_header_pranota_biaya_lain = 0;
        $temp_header_jumlah_biaya_lain = 0;
        $temp_header_sisa_biaya_lain = 0;
        $temp_header_persen_biaya_lain = 0;

        // pendapatan lain dan biaya lain
        $pendapatanlain_biayalain_nominal =  0;
        $pendapatanlain_biayalain_realisasi = 0;
        $pendapatanlain_biayalain_nota = 0;
        $pendapatanlain_biayalain_pranota = 0;
        $pendapatanlain_biayalain_jumlah = 0;
        $pendapatanlain_biayalain_sisa = 0;
        $pendapatanlain_biayalain_persen = 0;

        // laba sebelum pajak
        $labasebelum_pajak_nominal =  0;
        $labasebelum_pajak_realisasi = 0;
        $labasebelum_pajak_nota = 0;
        $labasebelum_pajak_pranota = 0;
        $labasebelum_pajak_jumlah = 0;
        $labasebelum_pajak_sisa = 0;
        $labasebelum_pajak_persen = 0;

        // pajak penghasilan
        $temp_header_nominal_pajak_penghasilan = 0;
        $temp_header_realisasi_pajak_penghasilan = 0;
        $temp_header_nota_pajak_penghasilan = 0;
        $temp_header_pranota_pajak_penghasilan = 0;
        $temp_header_jumlah_pajak_penghasilan = 0;
        $temp_header_sisa_pajak_penghasilan = 0;
        $temp_header_persen_pajak_penghasilan = 0;

        // laba setelah pajak
        $labasetelah_pajak_nominal =  0;
        $labasetelah_pajak_realisasi = 0;
        $labasetelah_pajak_nota = 0;
        $labasetelah_pajak_pranota = 0;
        $labasetelah_pajak_jumlah = 0;
        $labasetelah_pajak_sisa = 0;
        $labasetelah_pajak_persen = 0;

        $no = 1;

        while ($dataOpex = mysqli_fetch_assoc($queryOpex)) {

            // Sub total per sub header ditengah
            if ($no > 1 && $sub_header != $dataOpex['nm_subheader']) {  ?>
                <tr style="background-color: yellow;">
                    <th></th>
                    <th style="text-align: left;"><?= $sub_header; ?></th>
                    <!-- <th></th> -->
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
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
            $sub_header_nominal += $dataOpex['jml_nominal'];
            $sub_header_realisasi += $dataOpex['jml_realisasi'];
            $sub_header_nota += $dataOpex['nota'];
            $sub_header_pranota += $dataOpex['pra_nota'];
            $sub_header_jumlah_realisasi = $sub_header_realisasi + $sub_header_nota + $sub_header_pranota;
            $sub_header_sisa_anggaran = $sub_header_nominal - $sub_header_jumlah_realisasi;
            $sub_header_realisasi_persen = cekPersenNew($sub_header_jumlah_realisasi, $sub_header_nominal);
            // END sub total per sub header ditengah

            // Sub total per header ditengah
            if ($no > 1 && $header != $dataOpex['nm_header']) {  ?>
                <tr style="background-color: red;">
                    <th></th>
                    <th style="text-align: left;"><?= $header; ?></th>
                    <!-- <th></th> -->
                    <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
                    <th><?= $header_realisasi_persen; ?>%</th>
                    <!-- <th></th> -->
                </tr>

                <?php
                // PENDAPATAN BERSIH
                if ($header == "Beban Nota") { ?>
                    <tr style="background-color: orange;">
                        <th></th>
                        <th style="text-align: left;">Pendapatan Bersih</th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatan_bersih_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatan_bersih_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatan_bersih_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatan_bersih_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatan_bersih_jumlah); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatan_bersih_sisa); ?></b></td>
                        <th><?= $pendapatan_bersih_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>
                <?php }

                // LABA KOTOR
                if ($header == "Beban Pokok") { ?>
                    <tr style="background-color: orange;">
                        <th></th>
                        <th style="text-align: left;">Laba Kotor</th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= kurungMinus($laba_kotor_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_kotor_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_kotor_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_kotor_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_kotor_jumlah); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_kotor_sisa); ?></b></td>
                        <th><?= $laba_kotor_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>
                <?php }

                // LABA OPERASIONAL
                if ($header == "Beban Umum") { ?>
                    <tr style="background-color: orange;">
                        <th></th>
                        <th style="text-align: left;">Laba Operasional</th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= kurungMinus($laba_operasional_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_operasional_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_operasional_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_operasional_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_operasional_jumlah); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($laba_operasional_sisa); ?></b></td>
                        <th><?= $laba_operasional_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>
                <?php }

                // PENDAPATAN LAIN DAN BIAYA LAIN
                if ($header == "Biaya Lain-Lain") { ?>
                    <tr style="background-color: orange;">
                        <th></th>
                        <th style="text-align: left;">Pendapatan dan Biaya Lain-Lain</th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatanlain_biayalain_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatanlain_biayalain_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatanlain_biayalain_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatanlain_biayalain_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatanlain_biayalain_jumlah); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($pendapatanlain_biayalain_sisa); ?></b></td>
                        <th><?= $pendapatanlain_biayalain_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>

                    <!-- LABA SEBELUM PAJAK -->
                    <tr style="background-color: orange;">
                        <th></th>
                        <th style="text-align: left;">Laba Sebelum Pajak</th>
                        <!-- <th></th> -->
                        <td style="text-align: right;"><b><?= kurungMinus($labasebelum_pajak_nominal); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($labasebelum_pajak_realisasi); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($labasebelum_pajak_nota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($labasebelum_pajak_pranota); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($labasebelum_pajak_jumlah); ?></b></td>
                        <td style="text-align: right;"><b><?= kurungMinus($labasebelum_pajak_sisa); ?></b></td>
                        <th><?= $labasebelum_pajak_persen; ?>%</th>
                        <!-- <th></th> -->
                    </tr>
            <?php }
                // setelah ditampilin, dideklarasiin lagi ke 0
                $header_nominal = 0;
                $header_realisasi = 0;
                $header_nota = 0;
                $header_pranota = 0;
                $header_jumlah_realisasi = 0;
                $header_sisa_anggaran = 0;
                $header_realisasi_persen = 0;
            }
            $header_nominal += $dataOpex['jml_nominal'];
            $header_realisasi += $dataOpex['jml_realisasi'];
            $header_nota += $dataOpex['nota'];
            $header_pranota += $dataOpex['pra_nota'];
            $header_jumlah_realisasi = $header_realisasi + $header_nota + $header_pranota;
            $header_sisa_anggaran = $header_nominal - $header_jumlah_realisasi;
            $header_realisasi_persen = cekPersenNew($header_jumlah_realisasi, $header_nominal);
            // END total per header ditengah

            // isi
            $jumlah_realisasi =   $dataOpex['jml_realisasi'] + $dataOpex['nota'] + $dataOpex['pra_nota'];
            $sisa_anggaran = $dataOpex['jml_nominal'] - $jumlah_realisasi;
            $realisasi_persen = cekPersenNew($jumlah_realisasi, $dataOpex['jml_nominal']);
            ?>
            <tr>
                <td><?= $dataOpex['no_coa']; ?></td>
                <td style="text-align: left;"><?= $dataOpex['nm_coa']; ?></td>
                <!-- <td><?= $dataOpex['nm_divisi']; ?></td> -->
                <td style="text-align: right;"><?= formatRupiah2($dataOpex['jml_nominal']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($dataOpex['jml_realisasi']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($dataOpex['nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($dataOpex['pra_nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                <td style="text-align: right;"><?= kurungSurplus2($dataOpex['jml_nominal'], $jumlah_realisasi); ?></td>
                <td style="text-align: center;"><?= $realisasi_persen; ?>%</td>
                <!-- <td>-</td> -->
            </tr>
            <!-- end isi -->

        <?php
            // PENDAPATAN BERSIH
            // tampung nominal header pendapatan usaha
            if ($dataOpex['nm_header'] == "Pendapatan Usaha") {
                $temp_header_nominal_pendapatan_usaha = $header_nominal;
                $temp_header_realisasi_pendapatan_usaha = $header_realisasi;
                $temp_header_nota_pendapatan_usaha = $header_nota;
                $temp_header_pranota_pendapatan_usaha = $header_pranota;
                $temp_header_jumlah_pendapatan_usaha = $header_jumlah_realisasi;
                $temp_header_sisa_pendapatan_usaha = $header_sisa_anggaran;
                $temp_header_persen_pendapatan_usaha = $header_realisasi_persen;
            }

            // tampung nominal header beban nota
            if ($dataOpex['nm_header'] == "Beban Nota") {
                $temp_header_nominal_beban_nota = $header_nominal;
                $temp_header_realisasi_beban_nota = $header_realisasi;
                $temp_header_nota_beban_nota = $header_nota;
                $temp_header_pranota_beban_nota = $header_pranota;
                $temp_header_jumlah_beban_nota = $header_jumlah_realisasi;
                $temp_header_sisa_beban_nota = $header_sisa_anggaran;
                $temp_header_persen_beban_nota = $header_realisasi_persen;
            }

            $pendapatan_bersih_nominal =  $temp_header_nominal_pendapatan_usaha - $temp_header_nominal_beban_nota;
            $pendapatan_bersih_realisasi = $temp_header_realisasi_pendapatan_usaha - $temp_header_realisasi_beban_nota;
            $pendapatan_bersih_nota = $temp_header_nota_pendapatan_usaha - $temp_header_nota_beban_nota;
            $pendapatan_bersih_pranota = $temp_header_pranota_pendapatan_usaha - $temp_header_pranota_beban_nota;
            $pendapatan_bersih_jumlah = $temp_header_jumlah_pendapatan_usaha - $temp_header_jumlah_beban_nota;
            $pendapatan_bersih_sisa = $temp_header_sisa_pendapatan_usaha - $temp_header_sisa_beban_nota;
            $pendapatan_bersih_persen = cekPersenNew($pendapatan_bersih_jumlah, $pendapatan_bersih_nominal);
            // END PERDAPATAN BERSIH

            // LABA KOTOR
            if ($dataOpex['nm_header'] == "Beban Pokok") {
                $temp_header_nominal_beban_pokok = $header_nominal;
                $temp_header_realisasi_beban_pokok = $header_realisasi;
                $temp_header_nota_beban_pokok = $header_nota;
                $temp_header_pranota_beban_pokok = $header_pranota;
                $temp_header_jumlah_beban_pokok = $header_jumlah_realisasi;
                $temp_header_sisa_beban_pokok = $header_sisa_anggaran;
                $temp_header_persen_beban_pokok = $header_realisasi_persen;
            }

            $laba_kotor_nominal =  $pendapatan_bersih_nominal - $temp_header_nominal_beban_pokok;
            $laba_kotor_realisasi = $pendapatan_bersih_realisasi - $temp_header_realisasi_beban_pokok;
            $laba_kotor_nota = $pendapatan_bersih_nota - $temp_header_nota_beban_pokok;
            $laba_kotor_pranota = $pendapatan_bersih_pranota - $temp_header_pranota_beban_pokok;
            $laba_kotor_jumlah = $pendapatan_bersih_jumlah - $temp_header_jumlah_beban_pokok;
            $laba_kotor_sisa = $pendapatan_bersih_sisa - $temp_header_sisa_beban_pokok;
            $laba_kotor_persen = cekPersenNew($laba_kotor_jumlah, $laba_kotor_nominal);
            // END LABA KOTOR

            // LABA OPERASIONAL
            if ($dataOpex['nm_header'] == "Beban Umum") {
                $temp_header_nominal_beban_umum = $header_nominal;
                $temp_header_realisasi_beban_umum = $header_realisasi;
                $temp_header_nota_beban_umum = $header_nota;
                $temp_header_pranota_beban_umum = $header_pranota;
                $temp_header_jumlah_beban_umum = $header_jumlah_realisasi;
                $temp_header_sisa_beban_umum = $header_sisa_anggaran;
                $temp_header_persen_beban_umum = $header_realisasi_persen;
            }

            $laba_operasional_nominal =  $laba_kotor_nominal - $temp_header_nominal_beban_umum;
            $laba_operasional_realisasi = $laba_kotor_realisasi - $temp_header_realisasi_beban_umum;
            $laba_operasional_nota = $laba_kotor_nota - $temp_header_nota_beban_umum;
            $laba_operasional_pranota = $laba_kotor_pranota - $temp_header_pranota_beban_umum;
            $laba_operasional_jumlah = $laba_kotor_jumlah - $temp_header_jumlah_beban_umum;
            $laba_operasional_sisa = $laba_kotor_sisa - $temp_header_sisa_beban_umum;
            $laba_operasional_persen = cekPersenNew($laba_operasional_jumlah, $laba_operasional_nominal);
            // END LABA OPERASIONAL

            // PENDAPATAN LAIN DAN BIAYA LAIN
            if ($dataOpex['nm_header'] == "Pendapatan Lain-Lain") {
                $temp_header_nominal_pendapatan_lain = $header_nominal;
                $temp_header_realisasi_pendapatan_lain = $header_realisasi;
                $temp_header_nota_pendapatan_lain = $header_nota;
                $temp_header_pranota_pendapatan_lain = $header_pranota;
                $temp_header_jumlah_pendapatan_lain = $header_jumlah_realisasi;
                $temp_header_sisa_pendapatan_lain = $header_sisa_anggaran;
                $temp_header_persen_pendapatan_lain = $header_realisasi_persen;
            }

            // tampung nominal header biaya lain
            if ($dataOpex['nm_header'] == "Biaya Lain-Lain") {
                $temp_header_nominal_biaya_lain = $header_nominal;
                $temp_header_realisasi_biaya_lain = $header_realisasi;
                $temp_header_nota_biaya_lain = $header_nota;
                $temp_header_pranota_biaya_lain = $header_pranota;
                $temp_header_jumlah_biaya_lain = $header_jumlah_realisasi;
                $temp_header_sisa_biaya_lain = $header_sisa_anggaran;
                $temp_header_persen_biaya_lain = $header_realisasi_persen;
            }

            $pendapatanlain_biayalain_nominal =  $temp_header_nominal_pendapatan_lain - $temp_header_nominal_biaya_lain;
            $pendapatanlain_biayalain_realisasi = $temp_header_realisasi_pendapatan_lain - $temp_header_realisasi_biaya_lain;
            $pendapatanlain_biayalain_nota = $temp_header_nota_pendapatan_lain - $temp_header_nota_biaya_lain;
            $pendapatanlain_biayalain_pranota = $temp_header_pranota_pendapatan_lain - $temp_header_pranota_biaya_lain;
            $pendapatanlain_biayalain_jumlah = $temp_header_jumlah_pendapatan_lain - $temp_header_jumlah_biaya_lain;
            $pendapatanlain_biayalain_sisa = $temp_header_sisa_pendapatan_lain - $temp_header_sisa_biaya_lain;
            $pendapatanlain_biayalain_persen = cekPersenNew($pendapatanlain_biayalain_jumlah, $pendapatanlain_biayalain_nominal);
            // END PENDAPATAN LAIN DAN BIAYA LAIN

            // LABA SEBELUM PAJAK
            $labasebelum_pajak_nominal =  $laba_operasional_nominal - $pendapatanlain_biayalain_nominal;
            $labasebelum_pajak_realisasi = $laba_operasional_realisasi - $pendapatanlain_biayalain_realisasi;
            $labasebelum_pajak_nota = $laba_operasional_nota - $pendapatanlain_biayalain__nota;
            $labasebelum_pajak_pranota = $laba_operasional_pranota - $pendapatanlain_biayalain_pranota;
            $labasebelum_pajak_jumlah = $laba_operasional_jumlah - $pendapatanlain_biayalain_jumlah;
            $labasebelum_pajak_sisa = $laba_operasional_sisa - $pendapatanlain_biayalain_sisa;
            $labasebelum_pajak_persen = cekPersenNew($labasebelum_pajak_jumlah, $labasebelum_pajak_nominal);
            // END LABA SEBELUM PAJAK

            // LABA SETELAH PAJAK
            // tampung nominal header biaya lain
            if ($dataOpex['nm_header'] == "Pajak Penghasilan") {
                $temp_header_nominal_pajak_penghasilan = $header_nominal;
                $temp_header_realisasi_pajak_penghasilan = $header_realisasi;
                $temp_header_nota_pajak_penghasilan = $header_nota;
                $temp_header_pranota_pajak_penghasilan = $header_pranota;
                $temp_header_jumlah_pajak_penghasilan = $header_jumlah_realisasi;
                $temp_header_sisa_pajak_penghasilan = $header_sisa_anggaran;
                $temp_header_persen_pajak_penghasilan = $header_realisasi_persen;
            }

            $labasetelah_pajak_nominal = $labasebelum_pajak_nominal - $temp_header_nominal_pajak_penghasilan;
            $labasetelah_pajak_realisasi = $labasebelum_pajak_realisasi - $temp_header_realisasi_pajak_penghasilan;
            $labasetelah_pajak_nota = $labasebelum_pajak_nota - $temp_header_nota_pajak_penghasilan;
            $labasetelah_pajak_pranota = $labasebelum_pajak_pranota - $temp_header_pranota_pajak_penghasilan;
            $labasetelah_pajak_jumlah = $labasebelum_pajak_jumlah - $temp_header_jumlah_pajak_penghasilan;
            $labasetelah_pajak_sisa = $labasebelum_pajak_sisa - $temp_header_sisa_pajak_penghasilan;
            $labasetelah_pajak_persen = cekPersenNew($labasetelah_pajak_jumlah, $labasetelah_pajak_nominal);
            // END LABA SETELAH PAJAK

            $sub_header = $dataOpex['nm_subheader'];
            $header = $dataOpex['nm_header'];
            $no++;
        }
        ?>

        <!-- sub total sub header paling akhir -->
        <tr style="background-color: yellow;">
            <th></th>
            <th style="text-align: left;"><?= $sub_header; ?></th>
            <!-- <th></th> -->
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
            <th><?= $sub_header_realisasi_persen; ?>%</th>
            <!-- <th></th> -->
        </tr>

        <!-- total header paling akhir -->
        <tr style="background-color: red;">
            <th></th>
            <th style="text-align: left;"><?= $header; ?></th>
            <!-- <th></th> -->
            <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
            <th><?= $header_realisasi_persen; ?>%</th>
            <!-- <th></th> -->
        </tr>

        <!-- LABA STELAH PAJAK -->
        <?php if ($header == "Pajak Penghasilan") { ?>
            <tr style="background-color: orange;">
                <th></th>
                <th style="text-align: left;">Laba Setelah Pajak</th>
                <!-- <th></th> -->
                <td style="text-align: right;"><b><?= kurungMinus($labasetelah_pajak_nominal); ?></b></td>
                <td style="text-align: right;"><b><?= kurungMinus($labasetelah_pajak_realisasi); ?></b></td>
                <td style="text-align: right;"><b><?= kurungMinus($labasetelah_pajak_nota); ?></b></td>
                <td style="text-align: right;"><b><?= kurungMinus($labasetelah_pajak_pranota); ?></b></td>
                <td style="text-align: right;"><b><?= kurungMinus($labasetelah_pajak_jumlah); ?></b></td>
                <td style="text-align: right;"><b><?= kurungMinus($labasetelah_pajak_sisa); ?></b></td>
                <th><?= $labasetelah_pajak_persen; ?>%</th>
                <!-- <th></th> -->
            </tr>
        <?php } ?>
    </table>


    <!-- ------------ CAPEX ------------ -->
    <h4><b><u>CAPEX</u></b></h4>
    <table border="1">
        <tr style="background-color: #87CEFA;">
            <th>Kode Akun</th>
            <th>Nama Akun</th>
            <!-- <th>Divisi</th> -->
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

        while ($dataCapex = mysqli_fetch_assoc($queryCapex)) {

            // Sub total per sub header ditengah
            if ($no > 1 && $sub_header != $dataCapex['nm_subheader']) {  ?>
                <tr style="background-color: yellow;">
                    <th></th>
                    <th style="text-align: left;"><?= $sub_header; ?></th>
                    <!-- <th></th> -->
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
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
            $sub_header_nominal += $dataCapex['jml_nominal'];
            $sub_header_realisasi += $dataCapex['jml_realisasi'];
            $sub_header_nota += $dataCapex['nota'];
            $sub_header_pranota += $dataCapex['pra_nota'];
            $sub_header_jumlah_realisasi = $sub_header_realisasi + $sub_header_nota + $sub_header_pranota;
            $sub_header_sisa_anggaran = $sub_header_nominal - $sub_header_jumlah_realisasi;
            $sub_header_realisasi_persen = cekPersenNew($sub_header_jumlah_realisasi, $sub_header_nominal);
            // END sub total per sub header ditengah

            // Sub total per header ditengah
            if ($no > 1 && $header != $dataCapex['nm_header']) {  ?>
                <tr style="background-color: red;">
                    <th></th>
                    <th style="text-align: left;"><?= $header; ?></th>
                    <!-- <th></th> -->
                    <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
                    <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
                    <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
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
            $header_nominal += $dataCapex['jml_nominal'];
            $header_realisasi += $dataCapex['jml_realisasi'];
            $header_nota += $dataCapex['nota'];
            $header_pranota += $dataCapex['pra_nota'];
            $header_jumlah_realisasi = $header_realisasi + $header_nota + $header_pranota;
            $header_sisa_anggaran = $header_nominal - $header_jumlah_realisasi;
            $header_realisasi_persen = cekPersenNew($header_jumlah_realisasi, $header_nominal);
            // END total per header ditengah

            // isi
            $jumlah_realisasi =   $dataCapex['jml_realisasi'] + $dataCapex['nota'] + $dataCapex['pra_nota'];
            $sisa_anggaran = $dataCapex['jml_nominal'] - $jumlah_realisasi;
            $realisasi_persen = cekPersenNew($jumlah_realisasi, $dataCapex['jml_nominal']);
            ?>
            <tr>
                <td><?= $dataCapex['no_coa']; ?></td>
                <td style="text-align: left;"><?= $dataCapex['nm_coa']; ?></td>
                <!-- <td><?= $dataCapex['nm_divisi']; ?></td> -->
                <td style="text-align: right;"><?= formatRupiah2($dataCapex['jml_nominal']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($dataCapex['jml_realisasi']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($dataCapex['nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($dataCapex['pra_nota']); ?></td>
                <td style="text-align: right;"><?= formatRupiah2($jumlah_realisasi); ?></td>
                <td style="text-align: right;"><?= kurungSurplus2($dataCapex['jml_nominal'], $jumlah_realisasi); ?></td>
                <td style="text-align: center;"><?= $realisasi_persen; ?>%</td>
                <!-- <td>-</td> -->
            </tr>
            <!-- end isi -->

        <?php
            $sub_header = $dataCapex['nm_subheader'];
            $header = $dataCapex['nm_header'];
            $no++;
        }
        ?>

        <!-- sub total sub header paling akhir -->
        <tr style="background-color: yellow;">
            <th></th>
            <th style="text-align: left;"><?= $sub_header; ?></th>
            <!-- <th></th> -->
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($sub_header_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= kurungSurplus2($sub_header_nominal, $sub_header_jumlah_realisasi); ?></b></td>
            <th><?= $sub_header_realisasi_persen; ?>%</th>
            <!-- <th></th> -->
        </tr>

        <!-- total header paling akhir -->
        <tr style="background-color: red;">
            <th></th>
            <th style="text-align: left;"><?= $header; ?></th>
            <!-- <th></th> -->
            <td style="text-align: right;"><b><?= formatRupiah2($header_nominal); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_nota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_pranota); ?></b></td>
            <td style="text-align: right;"><b><?= formatRupiah2($header_jumlah_realisasi); ?></b></td>
            <td style="text-align: right;"><b><?= kurungSurplus2($header_nominal, $header_jumlah_realisasi); ?></b></td>
            <th><?= $header_realisasi_persen; ?>%</th>
            <!-- <th></th> -->
        </tr>
    </table>
<?php } else {
    echo "<script>window.alert('Data laporan Project (LR02) tidak ada/kosong!');
    				location='index.php?p=laporan_lr&sp=lr_02'
    			</script>";
} ?>