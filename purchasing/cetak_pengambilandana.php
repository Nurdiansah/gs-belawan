<?php


ob_start();
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsiuser.php";
if (isset($_GET['id'])) {

    $id = dekripRambo($_GET['id']);
}

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'purchasing') {
    header("location: ../index.php");
}

$query =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                    JOIN detail_biayaops dbo
                                        ON k.id_dbo = dbo.id
                                    JOIN biaya_ops bo
                                        ON dbo.kd_transaksi = bo.kd_transaksi
                                    JOIN anggaran a
                                        ON a.id_anggaran = dbo.id_anggaran
                                    JOIN divisi d
                                        ON d.id_divisi = bo.id_divisi
                                    WHERE k.id_kasbon ='$id'
            ");
$data = mysqli_fetch_assoc($query);
$nm_barang = $data['nm_barang'];

$bln_tgl = array(
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
);

$tgl_sekarang = date("Y-m-d");

?>

<!-- tambahan baru include-->
<!-- Setting CSS bagian header/ kop -->
<style type="text/css">
    table.page_header {
        width: 1020px;
        border: none;
        background-color: #DDDDFF;
        border-bottom: solid 1mm #AAAADD;
        padding: 2mm
    }

    table.page_footer {
        width: 1020px;
        border: none;
        background-color: #DDDDFF;
        border-top: solid 1mm #AAAADD;
        padding: 2mm
    }

    h1 {
        color: #000033
    }

    h2 {
        color: #000055
    }

    h3 {
        color: #000077
    }
</style>
<!-- Setting Margin header/ kop -->
<!-- Setting CSS Tabel data yang akan ditampilkan -->
<style type="text/css">
    .tabel2 {
        border-collapse: collapse;
        margin-left: 0px;
        text-align: center;
        font-size: 10px;
    }

    .tabel2 th,
    .tabel2 td {
        padding: 5px 5px;
        border: 1px solid #000;
    }

    .table {
        border-collapse: collapse;
    }

    .table table,
    .table tr,
    .table td {
        border: 1px solid black;
    }

    div.tengah {
        width: 300px;
        float: none;
        margin-left: 125px;
        margin-top: -140px;
    }

    div.kanan {
        width: 300px;
        float: right;
        margin-left: 10px;
        margin-top: 0px;
    }

    div.kiri {
        width: 100px;
        float: left;
        margin-left: 30px;
        display: inline;
    }

    div.tablekiri {
        float: left;
        margin-left: 30px;
        display: inline;
    }

    div.tablekanan {
        float: right;
        margin-left: 10px;
        margin-top: 0px;
    }

    .right {
        width: 300px;
        float: right;
        margin-left: 480px;
        margin-top: 0px;
        text-align: left;
    }

    .left {
        width: 300px;
        float: left;
        margin-left: 150px;
        display: inline;
        text-align: left;

    }
</style>
<div class="kiri">
    <img src="../gambar/gs.png" style="width:80px;height:50px" />
</div>
<br>
<h2><b>PT.GRAHA SEGARA</b></h2>
<hr>

<?php

?>
<h3 align="center"><u>LAPORAN PENGAMBILAN DANA</u></h3>
<h4 align="" style="font-size: 12px;"><u>KODE ID : [ <?= $data['id_kasbon'] ?> ]</u></h4>
<!-- <table border="1px">
    <tr>
        <td> -->
<table border="0px" style="font-size: 11px;">
    <tr>
        <td style="text-align: left; width=120px; "><b>Keperluan</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="width=400px;">
            Untuk Pembelian <?= $data['nm_barang']; ?>
        </td>
        <td align="right" rowspan="9">
            <qrcode value="[ E-Finance GS ] | Kode Kasbon : <?= $data['id_kasbon']; ?> | Sebesar :  <?= formatRupiah($data['harga_akhir']); ?> " ec="H" style="width: 40mm; background-color: white; color: black;"></qrcode>
        </td>
    </tr>
    <tr>
        <td><b>Kode Anggaran</b></td>
        <td>:</td>
        <td><?= $data['kd_anggaran'] . " [" . $data['nm_item'] . "]"; ?></td>
    </tr>
    <tr>
        <td><b>Divisi</b></td>
        <td>:</td>
        <td><?= $data['nm_divisi']; ?></td>
    </tr>
    <tr>
        <td><b>Nominal</b></td>
        <td>:</td>
        <td><?= formatRupiah($data['harga_akhir']); ?></td>
    </tr>
    <tr>
        <td><b>Terbilang</b></td>
        <td>:</td>
        <td><?= Terbilang($data['harga_akhir']); ?> Rupiah </td>
    </tr>
    <tr>
        <td><b>Dibuat</b></td>
        <td>:</td>
        <td><?= formatTanggalWaktu($data['tgl_kasbon']); ?></td>
    </tr>
    <tr>
        <td><b>Cost Control</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggalWaktu($data['app_costcontrol']); ?>)</td>
    </tr>
    <tr>
        <td><b>Manager</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggalWaktu($data['app_mgr_ga']); ?>)</td>
    </tr>
    <tr>
        <td><b>Manager Finance</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggalWaktu($data['app_mgr_finance']); ?>)</td>
    </tr>
    <!-- <tr>
        <td><b>Direktur 1 </b></td>
        <td>:</td>
        <td>APPROVED by <?= strtoupper(cekUsername($data['id_direktur'])) ?> (<?= formatTanggalWaktu($data['app_direktur']); ?>)</td>
    </tr> -->
    <tr>
        <td><b>Direktur</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggalWaktu($data['app_direktur2']); ?>)</td>
        <td>Medan, <?= date('d', strtotime($tgl_sekarang)) . ' ' . ($bln_tgl[date('m', strtotime($tgl_sekarang))]) . ' ' . date('Y', strtotime($tgl_sekarang)); ?></td>
    </tr>
</table>
<!-- </td> -->

<!-- </tr>
</table> -->
<br>
<table border="0" style="font-size: 10px;">
    <tr>
        <th style="text-align: center; width=350px;">Mengeluarkan<br><br><br><br><br>(..............................)</th>
        <th style="text-align: center; width=350px;">Penerima<br><br><br><br><br>(..............................)</th>
    </tr>
</table>

<!-- Memanggil fungsi bawaan HTML2PDF -->
<?php
$content = ob_get_clean();
include '../assets/html2pdf/html2pdf.class.php';
try {
    $html2pdf = new HTML2PDF('l', 'A5', 'en', false, 'UTF-8', array(10, 10, 10, 10));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output('Laporan-Pengambilan-Dana-' . $id . '.pdf');
    $html2pdf->setDefaultFont("roboto");
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>