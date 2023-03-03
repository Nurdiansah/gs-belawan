<?php
session_start();

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
$tahunSekarang = date('Y');

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != 'direktur') {
    header("location: ../index.php");
}

if (isset($_GET['divisi']) && isset($_GET['tahun'])) {
    $divisi = dekripRambo($_GET['divisi']);
    $tahun = dekripRambo($_GET['tahun']);

    // jika milih semua divisi jalanin query yg atas else bawahnya
    if ($divisi == "all") {
        $queryCetak = mysqli_query($koneksi, "SELECT no_coa, kd_anggaran, nm_item, id_satuan, harga, januari_kuantitas, januari_nominal, januari_realisasi, (januari_nominal - januari_realisasi) as januari_surplus, ((januari_nominal - januari_realisasi) / januari_nominal) * 100 as januari_persen,
                                                        februari_kuantitas, februari_nominal, februari_realisasi, (februari_nominal - februari_realisasi) as februari_surplus, ((februari_nominal - februari_realisasi) / februari_nominal) * 100 as februari_persen,
                                                        maret_kuantitas, maret_nominal, maret_realisasi, (maret_nominal - maret_realisasi) as maret_surplus, ((maret_nominal - maret_realisasi) / maret_nominal) * 100 as maret_persen,
                                                        april_kuantitas, april_nominal, april_realisasi, (april_nominal - april_realisasi) as april_surplus, ((april_nominal - april_realisasi) / april_nominal) * 100 as april_persen,
                                                        mei_kuantitas, mei_nominal, mei_realisasi, (mei_nominal - mei_realisasi) as mei_surplus, ((mei_nominal - mei_realisasi) / mei_nominal) * 100 as mei_persen,
                                                        juni_kuantitas, juni_nominal, juni_realisasi, (juni_nominal - juni_realisasi) as juni_surplus, ((juni_nominal - juni_realisasi) / juni_nominal) * 100 as juni_persen,
                                                        juli_kuantitas, juli_nominal, juli_realisasi, (juli_nominal - juli_realisasi) as juli_surplus, ((juli_nominal - juli_realisasi) / juli_nominal) * 100 as juli_persen,
                                                        agustus_kuantitas, agustus_nominal, agustus_realisasi, (agustus_nominal - agustus_realisasi) as agustus_surplus, ((agustus_nominal - agustus_realisasi) / agustus_nominal) * 100 as agustus_persen,
                                                        september_kuantitas, september_nominal, september_realisasi, (september_nominal - september_realisasi) as september_surplus, ((september_nominal - september_realisasi) / september_nominal) * 100 as september_persen,
                                                        oktober_kuantitas, oktober_nominal, oktober_realisasi, (oktober_nominal - oktober_realisasi) as oktober_surplus, ((oktober_nominal - oktober_realisasi) / oktober_nominal) * 100 as oktober_persen,
                                                        november_kuantitas, november_nominal, november_realisasi, (november_nominal - november_realisasi) as november_surplus, ((november_nominal - november_realisasi) / november_nominal) * 100 as november_persen,
                                                        desember_kuantitas, desember_nominal, desember_realisasi, (desember_nominal - desember_realisasi) as desember_surplus, ((desember_nominal - desember_realisasi) / desember_nominal) * 100 as desember_persen,
                                                        (januari_kuantitas + februari_kuantitas + maret_kuantitas + april_kuantitas + mei_kuantitas + juni_kuantitas + juli_kuantitas + agustus_kuantitas + september_kuantitas + oktober_kuantitas + november_kuantitas + desember_kuantitas) as jumlah_kuantitas,
                                                        (januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal) as jumlah_nominal,
                                                        (januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi) as jumlah_realisasi,
                                                        ((januari_nominal - januari_realisasi) + (februari_nominal - februari_realisasi) + (maret_nominal - maret_realisasi) + (april_nominal - april_realisasi) + (mei_nominal - mei_realisasi) + (juni_nominal - juni_realisasi) + (juli_nominal - juli_realisasi) + (agustus_nominal - agustus_realisasi) + (september_nominal - september_realisasi) + (oktober_nominal - oktober_realisasi) + (november_nominal - november_realisasi) + (desember_nominal - desember_realisasi)) as jumlah_surplus,
                                                        ((jumlah_nominal - jumlah_realisasi) / jumlah_nominal) * 100 as jumlah_persen
                                                FROM anggaran
                                                WHERE tahun = '$tahun'
                                                ORDER BY no_coa ASC");
    } else {
        $queryCetak = mysqli_query($koneksi, "SELECT no_coa, kd_anggaran, nm_item, id_satuan, harga, januari_kuantitas, januari_nominal, januari_realisasi, (januari_nominal - januari_realisasi) as januari_surplus, ((januari_nominal - januari_realisasi) / januari_nominal) * 100 as januari_persen,
                                                    februari_kuantitas, februari_nominal, februari_realisasi, (februari_nominal - februari_realisasi) as februari_surplus, ((februari_nominal - februari_realisasi) / februari_nominal) * 100 as februari_persen,
                                                    maret_kuantitas, maret_nominal, maret_realisasi, (maret_nominal - maret_realisasi) as maret_surplus, ((maret_nominal - maret_realisasi) / maret_nominal) * 100 as maret_persen,
                                                    april_kuantitas, april_nominal, april_realisasi, (april_nominal - april_realisasi) as april_surplus, ((april_nominal - april_realisasi) / april_nominal) * 100 as april_persen,
                                                    mei_kuantitas, mei_nominal, mei_realisasi, (mei_nominal - mei_realisasi) as mei_surplus, ((mei_nominal - mei_realisasi) / mei_nominal) * 100 as mei_persen,
                                                    juni_kuantitas, juni_nominal, juni_realisasi, (juni_nominal - juni_realisasi) as juni_surplus, ((juni_nominal - juni_realisasi) / juni_nominal) * 100 as juni_persen,
                                                    juli_kuantitas, juli_nominal, juli_realisasi, (juli_nominal - juli_realisasi) as juli_surplus, ((juli_nominal - juli_realisasi) / juli_nominal) * 100 as juli_persen,
                                                    agustus_kuantitas, agustus_nominal, agustus_realisasi, (agustus_nominal - agustus_realisasi) as agustus_surplus, ((agustus_nominal - agustus_realisasi) / agustus_nominal) * 100 as agustus_persen,
                                                    september_kuantitas, september_nominal, september_realisasi, (september_nominal - september_realisasi) as september_surplus, ((september_nominal - september_realisasi) / september_nominal) * 100 as september_persen,
                                                    oktober_kuantitas, oktober_nominal, oktober_realisasi, (oktober_nominal - oktober_realisasi) as oktober_surplus, ((oktober_nominal - oktober_realisasi) / oktober_nominal) * 100 as oktober_persen,
                                                    november_kuantitas, november_nominal, november_realisasi, (november_nominal - november_realisasi) as november_surplus, ((november_nominal - november_realisasi) / november_nominal) * 100 as november_persen,
                                                    desember_kuantitas, desember_nominal, desember_realisasi, (desember_nominal - desember_realisasi) as desember_surplus, ((desember_nominal - desember_realisasi) / desember_nominal) * 100 as desember_persen,
                                                    (januari_kuantitas + februari_kuantitas + maret_kuantitas + april_kuantitas + mei_kuantitas + juni_kuantitas + juli_kuantitas + agustus_kuantitas + september_kuantitas + oktober_kuantitas + november_kuantitas + desember_kuantitas) as jumlah_kuantitas,
                                                    (januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal) as jumlah_nominal,
                                                    (januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi) as jumlah_realisasi,
                                                    ((januari_nominal - januari_realisasi) + (februari_nominal - februari_realisasi) + (maret_nominal - maret_realisasi) + (april_nominal - april_realisasi) + (mei_nominal - mei_realisasi) + (juni_nominal - juni_realisasi) + (juli_nominal - juli_realisasi) + (agustus_nominal - agustus_realisasi) + (september_nominal - september_realisasi) + (oktober_nominal - oktober_realisasi) + (november_nominal - november_realisasi) + (desember_nominal - desember_realisasi)) as jumlah_surplus,
                                                    ((jumlah_nominal - jumlah_realisasi) / jumlah_nominal) * 100 as jumlah_persen
                                            FROM anggaran
                                            WHERE tahun = '$tahun'
                                            AND id_divisi = '$divisi'
                                            ORDER BY no_coa ASC");
    }

    // query buat ngambil nama divisi, dan namain filenya
    $queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi = '$divisi'");
    $dataDivisi = mysqli_fetch_assoc($queryDivisi);
    $totalDivisi = mysqli_num_rows($queryDivisi);

    if ($totalDivisi > 0) {
        $nm_divisi = $dataDivisi['nm_divisi'];
    } else {
        $nm_divisi = "Semua Divisi";
    }
    // end query nampilin
} else {
    $queryCetak = mysqli_query($koneksi, "SELECT no_coa, kd_anggaran, nm_item, id_satuan, harga, januari_kuantitas, januari_nominal, januari_realisasi, (januari_nominal - januari_realisasi) as januari_surplus, ((januari_nominal - januari_realisasi) / januari_nominal) * 100 as januari_persen,
                                                    februari_kuantitas, februari_nominal, februari_realisasi, (februari_nominal - februari_realisasi) as februari_surplus, ((februari_nominal - februari_realisasi) / februari_nominal) * 100 as februari_persen,
                                                    maret_kuantitas, maret_nominal, maret_realisasi, (maret_nominal - maret_realisasi) as maret_surplus, ((maret_nominal - maret_realisasi) / maret_nominal) * 100 as maret_persen,
                                                    april_kuantitas, april_nominal, april_realisasi, (april_nominal - april_realisasi) as april_surplus, ((april_nominal - april_realisasi) / april_nominal) * 100 as april_persen,
                                                    mei_kuantitas, mei_nominal, mei_realisasi, (mei_nominal - mei_realisasi) as mei_surplus, ((mei_nominal - mei_realisasi) / mei_nominal) * 100 as mei_persen,
                                                    juni_kuantitas, juni_nominal, juni_realisasi, (juni_nominal - juni_realisasi) as juni_surplus, ((juni_nominal - juni_realisasi) / juni_nominal) * 100 as juni_persen,
                                                    juli_kuantitas, juli_nominal, juli_realisasi, (juli_nominal - juli_realisasi) as juli_surplus, ((juli_nominal - juli_realisasi) / juli_nominal) * 100 as juli_persen,
                                                    agustus_kuantitas, agustus_nominal, agustus_realisasi, (agustus_nominal - agustus_realisasi) as agustus_surplus, ((agustus_nominal - agustus_realisasi) / agustus_nominal) * 100 as agustus_persen,
                                                    september_kuantitas, september_nominal, september_realisasi, (september_nominal - september_realisasi) as september_surplus, ((september_nominal - september_realisasi) / september_nominal) * 100 as september_persen,
                                                    oktober_kuantitas, oktober_nominal, oktober_realisasi, (oktober_nominal - oktober_realisasi) as oktober_surplus, ((oktober_nominal - oktober_realisasi) / oktober_nominal) * 100 as oktober_persen,
                                                    november_kuantitas, november_nominal, november_realisasi, (november_nominal - november_realisasi) as november_surplus, ((november_nominal - november_realisasi) / november_nominal) * 100 as november_persen,
                                                    desember_kuantitas, desember_nominal, desember_realisasi, (desember_nominal - desember_realisasi) as desember_surplus, ((desember_nominal - desember_realisasi) / desember_nominal) * 100 as desember_persen,
                                                    (januari_kuantitas + februari_kuantitas + maret_kuantitas + april_kuantitas + mei_kuantitas + juni_kuantitas + juli_kuantitas + agustus_kuantitas + september_kuantitas + oktober_kuantitas + november_kuantitas + desember_kuantitas) as jumlah_kuantitas,
                                                    (januari_nominal + februari_nominal + maret_nominal + april_nominal + mei_nominal + juni_nominal + juli_nominal + agustus_nominal + september_nominal + oktober_nominal + november_nominal + desember_nominal) as jumlah_nominal,
                                                    (januari_realisasi + februari_realisasi + maret_realisasi + april_realisasi + mei_realisasi + juni_realisasi + juli_realisasi + agustus_realisasi + september_realisasi + oktober_realisasi + november_realisasi + desember_realisasi) as jumlah_realisasi,
                                                    ((januari_nominal - januari_realisasi) + (februari_nominal - februari_realisasi) + (maret_nominal - maret_realisasi) + (april_nominal - april_realisasi) + (mei_nominal - mei_realisasi) + (juni_nominal - juni_realisasi) + (juli_nominal - juli_realisasi) + (agustus_nominal - agustus_realisasi) + (september_nominal - september_realisasi) + (oktober_nominal - oktober_realisasi) + (november_nominal - november_realisasi) + (desember_nominal - desember_realisasi)) as jumlah_surplus,
                                                    ((jumlah_nominal - jumlah_realisasi) / jumlah_nominal) * 100 as jumlah_persen
                                            FROM anggaran
                                            WHERE tahun = '$tahunSekarang'
                                            ORDER BY no_coa ASC");

    // fungsinya buat namain filenya
    $nm_divisi = "Semua Divisi";
    $tahun = $tahunSekarang;
}
$totalCetak = mysqli_num_rows($queryCetak);

// fungsi buat export ke excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan Anggaran Detail (" . $nm_divisi . " " . $tahun . ").xls");
?>

<!-- <style>
    * {
        font-family: arial;
        font-size: 10;
    }
</style> -->

<font face="arial" , size="10">
    <table style="text-align: left;">
        <tr>
            <td colspan="3"><b>GRAHA SEGARA</b></td>
        </tr>
        <tr>
            <td colspan="3"><b>JAKARTA</b></td>
        </tr>
        <tr>
            <td colspan="3"><b>ANGGARAN <?= $tahun; ?></b></td>
        </tr>
        <tr>
            <td colspan="3"><b>BELANJA OPERASIONAL <?= strtoupper($nm_divisi); ?></b></td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </table>

    <table border="1">
        <tr>
            <th rowspan="2" style="background-color: #009e00;">Account<br>Number</th>
            <th rowspan="2" style="background-color: #C0C0C0;">Kode<br>Transaksi</th>
            <th rowspan="2" style="background-color: #C0C0C0;">Account Description</th>
            <th rowspan="2" style="background-color: #C0C0C0;">Satuan</th>
            <th rowspan="2" style="background-color: #C0C0C0;">Harga Persatuan</th>
            <th colspan="5" style="background-color: #1e90ff;">Januari</th>
            <th colspan="5" style="background-color: #1e90ff;">Februari</th>
            <th colspan="5" style="background-color: #1e90ff;">Maret</th>
            <th colspan="5" style="background-color: #1e90ff;">April</th>
            <th colspan="5" style="background-color: #1e90ff;">Mei</th>
            <th colspan="5" style="background-color: #1e90ff;">Juni</th>
            <th colspan="5" style="background-color: #1e90ff;">Juli</th>
            <th colspan="5" style="background-color: #1e90ff;">Agustus</th>
            <th colspan="5" style="background-color: #1e90ff;">September</th>
            <th colspan="5" style="background-color: #1e90ff;">Oktober</th>
            <th colspan="5" style="background-color: #1e90ff;">November</th>
            <th colspan="5" style="background-color: #1e90ff;">Desember</th>
            <th colspan="5" style="background-color: #1e90ff;">Jumlah</th>
        </tr>
        <tr style="background-color: #1e90ff;">
            <!-- januari -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- februari -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- maret -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- april -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- mei -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- juni -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- juli -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- agustus -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- september -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- oktober -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- november -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- desember -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
            <!-- jumlah -->
            <th>Qty</th>
            <th>Budget</th>
            <th>Relisasi</th>
            <th>Surplus (Defisit)</th>
            <th>%</th>
        </tr>
        <?php while ($dataCetak = mysqli_fetch_assoc($queryCetak)) { ?>
            <tr>
                <td>'<?= $dataCetak['no_coa']; ?></td>
                <td>'<?= $dataCetak['kd_anggaran']; ?></td>
                <td><?= $dataCetak['nm_item']; ?></td>
                <td><?= $dataCetak['id_satuan']; ?></td>
                <td><?= formatRupiah2($dataCetak['harga']); ?></td>
                <td><?= $dataCetak['januari_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['januari_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['januari_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['januari_surplus']); ?></td>
                <td><?= round($dataCetak['januari_persen']); ?>%</td>
                <td><?= $dataCetak['februari_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['februari_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['februari_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['februari_surplus']); ?></td>
                <td><?= round($dataCetak['februari_persen']); ?>%</td>
                <td><?= $dataCetak['maret_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['maret_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['maret_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['maret_surplus']); ?></td>
                <td><?= round($dataCetak['maret_persen']); ?>%</td>
                <td><?= $dataCetak['april_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['april_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['april_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['april_surplus']); ?></td>
                <td><?= round($dataCetak['april_persen']); ?>%</td>
                <td><?= $dataCetak['mei_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['mei_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['mei_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['mei_surplus']); ?></td>
                <td><?= round($dataCetak['mei_persen']); ?>%</td>
                <td><?= $dataCetak['juni_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['juni_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['juni_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['juni_surplus']); ?></td>
                <td><?= round($dataCetak['juni_persen']); ?>%</td>
                <td><?= $dataCetak['juli_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['juli_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['juli_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['juli_surplus']); ?></td>
                <td><?= round($dataCetak['juli_persen']); ?>%</td>
                <td><?= $dataCetak['agustus_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['agustus_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['agustus_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['agustus_surplus']); ?></td>
                <td><?= round($dataCetak['agustus_persen']); ?>%</td>
                <td><?= $dataCetak['september_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['september_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['september_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['september_surplus']); ?></td>
                <td><?= round($dataCetak['september_persen']); ?>%</td>
                <td><?= $dataCetak['oktober_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['oktober_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['oktober_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['oktober_surplus']); ?></td>
                <td><?= round($dataCetak['oktober_persen']); ?>%</td>
                <td><?= $dataCetak['november_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['november_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['november_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['november_surplus']); ?></td>
                <td><?= round($dataCetak['november_persen']); ?>%</td>
                <td><?= $dataCetak['desember_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['desember_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['desember_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['desember_surplus']); ?></td>
                <td><?= round($dataCetak['desember_persen']); ?>%</td>
                <td><?= $dataCetak['jumlah_kuantitas']; ?></td>
                <td><?= formatRupiah2($dataCetak['jumlah_nominal']); ?></td>
                <td><?= formatRupiah2($dataCetak['jumlah_realisasi']); ?></td>
                <td><?= formatRupiah2($dataCetak['jumlah_surplus']); ?></td>
                <td><?= round($dataCetak['jumlah_persen']); ?>%</td>
            </tr>
        <?php } ?>
    </table>
</font>