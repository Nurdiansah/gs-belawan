<?php ob_start();
session_start();
include "../fungsi/fungsi.php";
include "../fungsi/fungsiuser.php";
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $id = dekripRambo($id);
}

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != 'anggaran') {
    header("location: ../index.php");
}

$queryBkk = mysqli_query($koneksi, "SELECT * 
                                        FROM bkk_final b   
                                        LEFT JOIN supplier s
                                            ON b.id_supplier = s.id_supplier
                                        LEFT JOIN anggaran a
                                            ON b.id_anggaran = a.id_anggaran
                                        LEFT JOIN divisi d
                                            ON a.id_divisi = d.id_divisi
                                        WHERE b.id = '$id' ");

$data = mysqli_fetch_assoc($queryBkk);
$id_kdtransaksi = $data['id_kdtransaksi'];
$id_bkk = $data['id'];

if (!file_exists("../file/lampiran_temp/BKK-" . $data['id'] . ".pdf")) {

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
            margin-left: 430px;
            margin-top: -140px;
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

        .kotak {
            width: 150px;
            height: 40px;
            border: 1px;
            margin-top: 140px;
        }
    </style>
    <?php
    include "../fungsi/koneksi.php";

    ?>

    <div class="kiri">
        <img src="../gambar/gs.png" style="width:80px;height:50px" />
    </div>

    <div class="kanan">
        <div class="kotak">
            FM.08/02/14
        </div>
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
            <td align="right" rowspan="8">
                <qrcode value="[ E-Finance GS ] | Kode BKK : <?= $data['nomor']; ?> | Sebesar :  <?= formatRupiah($data['nominal']); ?> " ec="H" style="width: 35mm; background-color: white; color: black;"></qrcode>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; width=150px; "><b>Kode Anggaran</b></td>
            <td style="text-align: ; width=5%;">:</td>
            <td style="width=380px;"><?php

                                        if (is_null($data['id_anggaran'])) {
                                            echo '-';
                                        } else {
                                            echo $data['kd_anggaran'] . " [" . $data['nm_item'] . "]";
                                        }
                                        ?></td>
        </tr>
        <tr>
            <td style="text-align: left; width=150px; "><b>Divisi</b></td>
            <td style="text-align: ; width=5%;">:</td>
            <td style="width=380px;"><?= $data['nm_divisi']; ?></td>
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
            <td><b>Tanggal BKK</b></td>
            <td style="text-align: ; width=5%;">:</td>
            <td><?= formatTanggalWaktu($data['release_on_bkk']); ?></td>
        </tr>
        <tr>
            <td><b>Cost Control</b></td>
            <td style="text-align: ; width=5%;">:</td>
            <td>APPROVED (<?= formatTanggalWaktu($data['v_mgr_finance']); ?>)</td>
        </tr>
        <tr>
            <td><b>Manager</b></td>
            <td style="text-align: ; width=5%;">:</td>
            <?php if ($data['v_direktur'] == "") { ?>
                <td>-</td>
            <?php } else { ?>
                <td>APPROVED (<?= formatTanggalWaktu($data['v_direktur']); ?>)</td>
            <?php } ?>
            <td style="text-align: right; width=150px; ">Medan, <?= formatTanggal($data['release_on_bkk']) ?></td>
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
        // $html2pdf->Output('Laporan-Pengambilan-Dana-' . $id . '.pdf');
        $html2pdf->Output('../file/lampiran_temp/BKK-' . $data['id'] . '.pdf', 'F');
        $html2pdf->setDefaultFont("roboto");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

    // header("Location: index.php?p=laporan_bkk&project=" . $_GET['project'] . "&tgl_1=" . $_GET['tgl_1'] . "&tgl_2=" . $_GET['tgl_2']);
    echo "<script type='text/javascript'>
                window.open('bkk_new.php?id=" . enkripRambo($id) . "','name','width=800,height=600');
            </script>";
}


// BUAT MERGE PDF
include '../assets/PDFMerger/PDFMerger.php';

use PDFMerger\PDFMerger;

$gabung = new PDFMerger;
$gabung->addPDF('../file/lampiran_temp/BKK-' . $data['id'] . '.pdf');

// jika pengajuan BIAYA UMUM
if ($data['pengajuan'] == "BIAYA UMUM") {
    $dataBU = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM bkk WHERE kd_transaksi = '$id_kdtransaksi'"));

    if (!is_null($dataBU['invoice']) && file_exists("../file/" . $dataBU['invoice'])) {
        $gabung->addPDF('../file/' . $dataBU['invoice']);
    }
}

// klo pengajuan PO
if ($data['pengajuan'] == "PO") {
    $dataMR = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM po po
                                                            JOIN detail_biayaops db
                                                                ON id_dbo = id
                                                            WHERE id_po = '$id_kdtransaksi'
                                                "));

    if (!is_null($dataMR['foto_item']) && file_exists("../file/foto/" . $dataMR['foto_item'])) {
        $gabung->addPDF("../file/foto/" . $dataMR['foto_item']);
    }

    if (!is_null($dataMR['doc_penawaran']) && file_exists("../file/doc_penawaran/" . $dataMR['doc_penawaran'])) {
        $gabung->addPDF("../file/doc_penawaran/" . $dataMR['doc_penawaran']);
    }

    if (!is_null($dataMR['doc_quotation']) && file_exists("../file/doc_quotation/" . $dataMR['doc_quotation'])) {
        $gabung->addPDF("../file/doc_quotation/" . $dataMR['doc_quotation']);
    }

    $dataPO = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tagihan_po WHERE bkk_id = '$id_bkk'"));

    if (!is_null($dataPO['doc_faktur']) && file_exists("../file/invoice/" . $dataPO['doc_faktur'])) {
        $gabung->addPDF("../file/invoice/" . $dataPO['doc_faktur']);
    }
}

// klo pengajuannya kasbon
if ($data['pengajuan'] == "KASBON") {
    $dataKasbon = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kasbon WHERE id_kasbon = '$id_kdtransaksi'"));
    $id_dbo = $dataKasbon['id_dbo'];

    if ($dataKasbon['from_user'] == "1") {
        if (!is_null($dataKasbon['doc_pendukung']) && file_exists("../file/doc_pendukung/" . $dataKasbon['doc_pendukung'])) {
            $gabung->addPDF('../file/doc_pendukung/' . $dataKasbon['doc_pendukung']);
        }
    } else {
        $dataDBO = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM detail_biayaops WHERE id = '$id_dbo'"));

        if (!is_null($dataDBO['foto_item']) && file_exists("../file/foto/" . $dataDBO['foto_item'])) {
            $gabung->addPDF('../file/foto/' . $dataDBO['foto_item']);
        }
    }

    if (!is_null($dataKasbon['doc_lpj']) && file_exists("../file/doc_lpj/" . $dataKasbon['doc_lpj'])) {
        $gabung->addPDF("../file/doc_lpj/" . $dataKasbon['doc_lpj']);
    }
}

if (!is_null($data['bukti_pembayaran']) && file_exists("../file/bukti_pembayaran/" . $data['bukti_pembayaran'])) {
    $gabung->addPDF("../file/bukti_pembayaran/" . $data['bukti_pembayaran']);
}

// $gabung->merge('download', 'merged.pdf');
$gabung->merge('output', 'Merge-' . $data['no_bkk'] . '.pdf');

unlink('../file/lampiran_temp/BKK-' . $data['id'] . '.pdf');
