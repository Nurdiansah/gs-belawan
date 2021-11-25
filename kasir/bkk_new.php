<?php ob_start();
session_start();
include "../fungsi/fungsi.php";
include "../fungsi/fungsiuser.php";
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $id = dekripRambo($id);
}

$queryBkk = mysqli_query($koneksi, "SELECT * 
                                          FROM bkk_final b   
                                          LEFT JOIN supplier s
                                          ON b.id_supplier = s.id_supplier
                                          LEFT JOIN anggaran a
                                          ON b.id_anggaran = a.id_anggaran
                                          WHERE b.id = '$id' ");

$data = mysqli_fetch_assoc($queryBkk);

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
<h3><b>PT.GRAHA SEGARA</b></h3>
<hr>

<?php

?>
<h3 align="center"><u>BUKTI KAS KELUAR</u></h3>
<h4 align="" style="font-size: 12px;"><u>No BKK : [ <?= $data['no_bkk'] ?> ]</u></h4>
<!-- <table border="1px">
    <tr>
        <td> -->
<table border="0px" style="font-size: 11px;">
    <tr>
        <td style="text-align: left; width=150px; "><b>Di Bayarkan Kepada</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="width=380px;">-</td>
        <td align="right" rowspan="6">
            <qrcode value="[ E-Finance GS ] | Kode BKK : <?= $data['nomor']; ?> | Sebesar :  <?= formatRupiah($data['nominal']); ?> " ec="H" style="width: 35mm; background-color: white; color: black;"></qrcode>
        </td>
    </tr>
    <tr>
        <td style="text-align: left; width=150px; "><b>Kode Anggaran</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="width=380px;"><?= $data['kd_anggaran']; ?> [<?= $data['nm_item']; ?>]</td>
    </tr>
    <tr>
        <td style="text-align: left; width=150px; "><b>Untuk</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="width=380px;"><?= $data['keterangan']; ?></td>
    </tr>
    <tr>
        <td style="text-align: left; width=150px; "><b>Jumlah</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="text-align: left; width=180px; "><?= formatRupiah($data['nominal']) ?></td>
    </tr>
    <tr>
        <td style="text-align: left; width=150px; "><b>Terbilang</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td style="width=380px;"><?= Terbilang($data['nominal']); ?> Rupiah </td>
    </tr>
    <tr>
        <td><b>Manager Finance</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <td>APPROVED (<?= formatTanggalWaktu($data['v_mgr_finance']); ?>)</td>
    </tr>
    <tr>
        <td><b>Direktur</b></td>
        <td style="text-align: ; width=5%;">:</td>
        <?php if ($data['v_direktur'] == "") { ?>
            <td>-</td>
        <?php } else { ?>
            <td>APPROVED (<?= formatTanggalWaktu($data['v_direktur']); ?>)</td>
        <?php } ?>
        <td style="text-align: right; width=150px; ">Jakarta, <?= formatTanggal($data['release_on_bkk']) ?></td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td style="text-align: right;">Yang Mengeluarkan,</td>
    </tr>
    <tr>
        <td style="text-align: right; height=40px; " colspan="4"></td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td style="text-align: center;">System</td>
    </tr>
</table>
<!-- </td> -->

<!-- </tr>
</table> -->

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