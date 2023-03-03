<?php
session_start();

include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
$tahunSekarang = date('Y');

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != 'admin_divisi') {
    header("location: ../index.php");
}

// ngambil id divisi dari user terkait
$user = $_SESSION['username_blw'];
$queryUser = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$user'");
$dataUser = mysqli_fetch_assoc($queryUser);

$divisi = $dataUser['id_divisi'];
if (isset($_GET['tahun'])) {
    $tahun = dekripRambo($_GET['tahun']);
} else {
    $tahun = $tahunSekarang;
}

$queryCetak = mysqli_query($koneksi, "SELECT SUM(januari_nominal) as januari_nominal, SUM(januari_realisasi) as januari_realisasi,
                                                    SUM(februari_nominal) as februari_nominal, SUM(februari_realisasi) as februari_realisasi, 
                                                    SUM(maret_nominal) as maret_nominal, SUM(maret_realisasi) as maret_realisasi, 
                                                    SUM(april_nominal) as april_nominal, SUM(april_realisasi) as april_realisasi, 
                                                    SUM(mei_nominal) as mei_nominal, SUM(mei_realisasi) as mei_realisasi, 
                                                    SUM(juni_nominal) as juni_nominal, SUM(juni_realisasi) as juni_realisasi, 
                                                    SUM(juli_nominal) as juli_nominal, SUM(juli_realisasi) as juli_realisasi, 
                                                    SUM(agustus_nominal) as agustus_nominal, SUM(agustus_realisasi) as agustus_realisasi, 
                                                    SUM(september_nominal) as september_nominal, SUM(september_realisasi) as september_realisasi, 
                                                    SUM(oktober_nominal) as oktober_nominal, SUM(oktober_realisasi) as oktober_realisasi, 
                                                    SUM(november_nominal) as november_nominal, SUM(november_realisasi) as november_realisasi, 
                                                    SUM(desember_nominal) as desember_nominal, SUM(desember_realisasi) as desember_realisasi,
                                                    SUM(januari_nominal) + SUM(februari_nominal) + SUM(maret_nominal) + SUM(april_nominal) + SUM(mei_nominal) + SUM(juni_nominal) + SUM(juli_nominal) + SUM(agustus_nominal) + SUM(september_nominal) + SUM(oktober_nominal) + SUM(november_nominal) + SUM(desember_nominal) as total_nominal,
                                                    SUM(januari_realisasi) + SUM(februari_realisasi) + SUM(maret_realisasi) + SUM(april_realisasi) + SUM(mei_realisasi) + SUM(juni_realisasi) + SUM(juli_realisasi) + SUM(agustus_realisasi) + SUM(september_realisasi) + SUM(oktober_realisasi) + SUM(november_realisasi) + SUM(desember_realisasi) as total_realisasi
                                            FROM anggaran
                                            WHERE tahun = '$tahun'
                                            AND id_divisi = '$divisi'");
$dataCetak = mysqli_fetch_assoc($queryCetak);

$queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi = '$divisi'");
$dataDivisi = mysqli_fetch_assoc($queryDivisi);


// jika datanya ada dia nyetak ke excel, klo ngga ada message box
if ($dataCetak['total_nominal'] > 0) {
    // fungsi buat export ke excel
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Laporan Anggaran Summary (" . $dataDivisi['nm_divisi'] . " " . $tahun . ").xls");

?>
    <table>
        <tr>
            <th colspan="4">Summary Laporan Anggaran</th>
        </tr>
        <tr>
            <th colspan="4">(Divisi <?= $dataDivisi['nm_divisi'] . " " . $tahun ?>)</th>
        </tr>
    </table>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Bulan</th>
            <th>Nominal</th>
            <th>Realisasi</th>
        </tr>
        <tr>
            <td>1</td>
            <td>Januari</td>
            <td><?= formatRupiah2($dataCetak['januari_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['januari_realisasi']); ?></td>
        </tr>
        <tr>
            <td>2</td>
            <td>Februari</td>
            <td><?= formatRupiah2($dataCetak['februari_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['februari_realisasi']); ?></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Maret</td>
            <td><?= formatRupiah2($dataCetak['maret_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['maret_realisasi']); ?></td>
        </tr>
        <tr>
            <td>4</td>
            <td>April</td>
            <td><?= formatRupiah2($dataCetak['april_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['april_realisasi']); ?></td>
        </tr>
        <tr>
            <td>5</td>
            <td>Mei</td>
            <td><?= formatRupiah2($dataCetak['mei_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['mei_realisasi']); ?></td>
        </tr>
        <tr>
            <td>6</td>
            <td>Juni</td>
            <td><?= formatRupiah2($dataCetak['juni_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['juni_realisasi']); ?></td>
        </tr>
        <tr>
            <td>7</td>
            <td>Juli</td>
            <td><?= formatRupiah2($dataCetak['juli_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['juli_realisasi']); ?></td>
        </tr>
        <tr>
            <td>8</td>
            <td>Agustus</td>
            <td><?= formatRupiah2($dataCetak['agustus_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['agustus_realisasi']); ?></td>
        </tr>
        <tr>
            <td>9</td>
            <td>September</td>
            <td><?= formatRupiah2($dataCetak['september_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['september_realisasi']); ?></td>
        </tr>
        <tr>
            <td>10</td>
            <td>Oktober</td>
            <td><?= formatRupiah2($dataCetak['oktober_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['oktober_realisasi']); ?></td>
        </tr>
        <tr>
            <td>11</td>
            <td>November</td>
            <td><?= formatRupiah2($dataCetak['november_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['november_realisasi']); ?></td>
        </tr>
        <tr>
            <td>12</td>
            <td>Desember</td>
            <td><?= formatRupiah2($dataCetak['desember_nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['desember_realisasi']); ?></td>
        </tr>
        <tr>
            <td>
                <h3>#</h3>
            </td>
            <td>
                <h3>Total</h3>
            </td>
            <td>
                <h3><?= formatRupiah2($dataCetak['total_nominal']); ?></h3>
            </td>
            <td>
                <h3><?= formatRupiah2($dataCetak['total_realisasi']); ?></h3>
            </td>
        </tr>
    </table>
<?php } else {
    echo "<script>window.alert('Data laporan yang dicetak kosong!');
                location='index.php?p=laporan_anggaran'
            </script>";
} ?>