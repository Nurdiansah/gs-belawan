<?php
ob_start();
session_start();
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";
include "../fungsi/fungsiuser.php";
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $id = dekripRambo($id);
}

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != 'anggaran') {
    header("location: ../index.php");
}

$queryBkk = mysqli_query($koneksi, "SELECT *, b.keterangan as bketerangan
                                        FROM bkk_final b   
                                        LEFT JOIN supplier s
                                            ON b.id_supplier = s.id_supplier
                                        LEFT JOIN anggaran a
                                            ON b.id_anggaran = a.id_anggaran
                                        LEFT JOIN divisi d
                                            ON a.id_divisi = d.id_divisi
                                        LEFT JOIN bkk bk
                                            ON kd_transaksi = id_kdtransaksi
                                        WHERE b.id = '$id' ");

$data = mysqli_fetch_assoc($queryBkk);
$id_kdtransaksi = $data['id_kdtransaksi'];
$id_bkk = $data['id'];

if (!file_exists("../file/bkk_temp/BKK-" . $data['id'] . ".pdf")) {

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
        .body {
            font-family: Courier;
        }

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
            margin-left: 1px;
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
            margin-left: 120px;
            display: inline;
            text-align: left;

        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.5;
            z-index: -1;
            font-size: 7em;
            color: #ccc;
        }
    </style>

    <div class="body" style="font-size: 11px;">
        <table border="0">
            <tr>
                <td style="text-align: center; width=100px; ">
                    <img src="../gambar/logoenc.jpg" style="width:120px;height:50px" />
                    <h3><b>PT.EKANURI</b></h3>
                </td>
                <td style="text-align: right; width=480px; ">
                </td>
                <td style="vertical-align:middle; width=120px; ">
                    <?php if (is_null($data['v_mgr_finance']) || is_null($data['v_direktur'])) { ?>
                        <div class="watermark">BKK Not Completed</div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <hr>

        <h3 align="center"><u>BUKTI KAS KELUAR</u></h3>
        <h4 align=""><u>No BKK : [ <?= $data['no_bkk'] ?> ]</u></h4>
        <!-- <table border="1px">
    <tr>
        <td> -->
        <table border="0px">
            <tr>
                <td style="text-align: left; width=130px; "><b>Di Bayarkan Kepada</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <td style="width=380px;">
                    <?php if ($data['pengajuan'] == "PO") {
                        echo $data['nm_supplier'];
                    } elseif ($data['pengajuan'] == "BIAYA UMUM") {
                        echo $data['nm_vendor'];
                    } else {
                        echo "-";
                    } ?>
                </td>
                <td align="right" rowspan="7" style="width=170px;">
                    <qrcode value="[ E-Finance Ekanuri ] | Kode BKK : <?= $data['no_bkk']; ?> | Sebesar :  <?= formatRupiah($data['nominal']); ?> " ec="H" style="width: 35mm; background-color: white; color: black;"></qrcode>
                    <br>
                    Jakarta, <?= is_null($data['release_on_bkk']) ? formatTanggal(dateNow()) : formatTanggal($data['release_on_bkk']); ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; width=130px; "><b>Kode Anggaran</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <td style="width=380px;">
                    <?php if ($data['pengajuan'] == "REFILL FUND") {
                        echo "-";
                    } else {
                        echo $data['kd_anggaran'] . " [" . $data['nm_item'] . "]";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; width=130px; "><b>Divisi</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <td style="width=380px;">
                    <?php if ($data['pengajuan'] == "REFILL FUND") {
                        echo "Kasir";
                    } else {
                        echo $data['nm_divisi'];
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left; width=130px; "><b>Untuk</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <td style="width=380px;"><?= $data['bketerangan']; ?></td>
            </tr>
            <tr>
                <td style="text-align: left; width=130px; "><b>Jumlah</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <td style="text-align: left; width=180px; "><?= formatRupiah($data['nominal']) ?></td>
            </tr>
            <tr>
                <td style="text-align: left; width=130px; "><b>Terbilang</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <td style="width=380px;"><?= Terbilang($data['nominal']); ?> Rupiah </td>
            </tr>
            <tr>
                <td><b>Manager Finance</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <?php if ($data['v_mgr_finance'] == "") { ?>
                    <td>-</td>
                <?php } else { ?>
                    <td>APPROVED (<?= formatTanggalWaktu($data['v_mgr_finance']); ?>)</td>
                <?php } ?>
            </tr>
            <tr>
                <td><b>Direktur</b></td>
                <td style="text-align: ; width=5%;">:</td>
                <?php if ($data['v_direktur'] == "") { ?>
                    <td>-</td>
                <?php } else { ?>
                    <td>APPROVED (<?= formatTanggalWaktu($data['v_direktur']); ?>)</td>
                <?php } ?>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="text-align: center;">Yang Mengeluarkan,</td>
            </tr>
            <tr>
                <td style="text-align: right; height=20px; " colspan="4"></td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td style="text-align: center;">System</td>
            </tr>
        </table>
        <!-- </td> -->

        <!-- </tr>
</table> -->
    </div>

    <!-- Memanggil fungsi bawaan HTML2PDF -->
<?php

    $content = ob_get_clean();
    include '../assets/html2pdf/html2pdf.class.php';
    try {
        $html2pdf = new HTML2PDF('l', 'A5', 'en', false, 'UTF-8', array(8, 8, 5, 5));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        // $html2pdf->Output('BKK-' .  $data['no_bkk'] . '.pdf');
        $html2pdf->Output('../file/bkk_temp/BKK-' . $data['id'] . '.pdf', 'F');
        $html2pdf->setDefaultFont("roboto");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
    header("Location: index.php?p=transaksi_bkk&sp=" . $_GET['sp'] . "");
}


// BUAT MERGE PDF
include '../assets/PDFMerger/PDFMerger.php';

use PDFMerger\PDFMerger;

$gabung = new PDFMerger;
$gabung->addPDF('../file/bkk_temp/BKK-' . $data['id'] . '.pdf');

// jika pengajuan BIAYA UMUM
if ($data['pengajuan'] == "BIAYA UMUM") {
    $dataBU = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM bkk WHERE kd_transaksi = '$id_kdtransaksi'"));

    $gabung->addPDF('../file/' . $dataBU['invoice']);
}

// klo pengajuan PO
if ($data['pengajuan'] == "PO") {
    $dataPO = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tagihan_po WHERE bkk_id = '$id_bkk'"));

    $gabung->addPDF('../file/invoice/' . $dataPO['doc_faktur']);
}

// klo pengajuannya kasbon
if ($data['pengajuan'] == "KASBON") {
    $dataKasbon = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM kasbon WHERE id_kasbon = '$id_kdtransaksi'"));

    $gabung->addPDF('../file/doc_pendukung/' . $dataKasbon['doc_pendukung']);
}

// $gabung->merge('download', 'merged.pdf');
$gabung->merge('output', 'Merge-' . $data['no_bkk'] . '.pdf');

// unlink('../file/bkk_temp/BKK-' . $data['id'] . '.pdf');
