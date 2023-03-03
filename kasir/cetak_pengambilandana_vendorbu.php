<?php

ob_start();
session_start();
include "../fungsi/fungsi.php";
include "../fungsi/fungsiuser.php";
include "../fungsi/koneksi.php";

if (isset($_GET['id'])) {

    $id = dekripRambo($_GET['id']);
}

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != 'kasir') {
    header("location: ../index.php");
}

$query =  mysqli_query($koneksi, "SELECT * FROM bkk_final bf
                                    INNER JOIN bkk bk
                                        ON kd_transaksi = id_kdtransaksi
                                    WHERE id = '$id'  ");
$data = mysqli_fetch_assoc($query);

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

    /* * {
        font-size: 10px;
    } */
</style>

<div class="kiri">
    <img src="../gambar/gs.png" style="width:80px;height:50px" />
</div>
<h2><b>PT.GRAHA SEGARA</b></h2>
<hr>

<h3 align="center"><u>LAPORAN PENGAMBILAN DANA</u></h3>
<h4 align="" style="font-size: 12px;"><u>KODE ID : [ <?= $data['id_kdtransaksi']; ?> ]</u></h4>
<!-- <table border="1px">
    <tr>
        <td> -->
<table border="0" style="font-size: 11px;">
    <tr>
        <td style="text-align: left; width=120px; "><b>Keperluan</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="width=420px;">
            <?= $data['keterangan']; ?>
        </td>
        <td align="right" rowspan="7    ">
            <qrcode value="[ E-Finance GS ] | KODE ID : <?= $data['no_bkk']; ?> | Sebesar :  <?= formatRupiah($data['nominal']); ?> " ec="H" style="width: 40mm; background-color: white; color: black;"></qrcode>
        </td>
    </tr>
    <tr>
        <td><b>Nominal</b></td>
        <td>:</td>
        <td><?= formatRupiah($data['nominal']) ?></td>
    </tr>
    <tr>
        <td><b>Terbilang</b></td>
        <td>:</td>
        <td style="width=420px;"><?= Terbilang($data['nominal']) ?> Rupiah</td>
    </tr>
    <tr>
        <td><b>Dibuat</b></td>
        <td>:</td>
        <td><?= formatTanggalWaktu($data['created_on_bkk']); ?></td>
    </tr>
    <tr>
        <td><b>Manager Finance</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggalWaktu($data['v_mgr_finance']); ?>)</td>
    </tr>
    <tr>
        <td><b>Direktur</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggalWaktu($data['v_direktur']); ?>)</td>
    </tr>
</table>
<!-- </td> -->

<!-- </tr>
</table> -->
<br>
<table border="0" style="font-size: 10px;">
    <tr>
        <th style="text-align: center; width=350px;">Mengeluarkan<br><br><br><br><br>(..............................)</th>
        <th style="text-align: center; width=350px;">Penerima<br><br><br><br><br>(<?= $data['nm_vendor']; ?>)</th>
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