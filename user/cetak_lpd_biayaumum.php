<?php ob_start();
session_start();
include "../fungsi/fungsi.php";
include "../fungsi/fungsiuser.php";
if (isset($_GET['id'])) {

    $id = dekripRambo($_GET['id']);
}

$query =  mysqli_query($koneksi, "SELECT * FROM bkk
                                            WHERE id_bkk = '$id'  ");
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
</style>
<?php
include "../fungsi/koneksi.php";

?>

<div class="kiri">
    <img src="../gambar/gs.png" style="width:80px;height:50px" />
</div>
<h2><b>PT.GRAHA SEGARA</b></h2>
<hr>

<?php

?>
<h3 align="center"><u>LAPORAN PENGAMBILAN DANA</u></h3>
<h4 align="" style="font-size: 12px;"><u>KODE ID : [ <?= $data['kd_transaksi'] ?> ]</u></h4>
<!-- <table border="1px">
    <tr>
        <td> -->
<table border="0px" style="font-size: 11px;">
    <tr>
        <td style="text-align: left; width=120px; "><b>Keperluan</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="width=400px;">
            <?= $data['keterangan']; ?>
        </td>
        <td align="right" rowspan="7    ">
            <qrcode value="[ E-Finance GS ] | Kode Biaya Umum : <?= $data['kd_transaksi']; ?> | Sebesar :  <?= formatRupiah($data['jml_bkk']); ?> " ec="H" style="width: 40mm; background-color: white; color: black;"></qrcode>
        </td>
    </tr>
    <tr>
        <td><b>Nominal</b></td>
        <td>:</td>
        <td><?= formatRupiah($data['jml_bkk']) ?></td>
    </tr>
    <tr>
        <td style="vertical-align: top;"><b>Terbilang</b></td>
        <td style="vertical-align: top;">:</td>
        <td style="width: 400px;"><?= Terbilang($data['jml_bkk']); ?> Rupiah </td>
    </tr>
    <tr>
        <td><b>Dibuat</b></td>
        <td>:</td>
        <td><?= formatTanggal($data['tgl_bkk']); ?></td>
    </tr>
    <tr>
        <td><b>Manager </b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggal($data['tgl_verifikasimanager']); ?>)</td>
    </tr>
    <tr>
        <td><b>Manager Finance</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggal($data['tgl_verifikasimanagerkeuangan']); ?>)</td>
    </tr>
    <tr>
        <td><b>Direktur 1 </b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggal($data['tgl_verifikasidireksi']); ?>)</td>
    </tr>
    <tr>
        <td><b>Direktur 2</b></td>
        <td>:</td>
        <td>APPROVED (<?= formatTanggal($data['tgl_verifikasidireksi2']); ?>)</td>
        <td>Jakarta, <?= date('d', strtotime($tgl_sekarang)) . ' ' . ($bln_tgl[date('m', strtotime($tgl_sekarang))]) . ' ' . date('Y', strtotime($tgl_sekarang)); ?></td>
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