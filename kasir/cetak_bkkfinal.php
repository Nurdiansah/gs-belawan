<?php ob_start();
session_start();
include "../fungsi/fungsi.php";
include "../fungsi/koneksi.php";
if (isset($_GET['id'])) {

  $id = $_GET['id'];
}
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
  }

  .tabel2 th,
  .tabel2 td {
    padding: 5px 5px;
    border: 1px solid #000;
  }

  .tabelhead {
    border-collapse: collapse;
    margin-left: 0px;
  }

  .tabelhead th,
  .tabelhead td {
    padding: 1px 1px;
    border: 0px solid #000;
  }

  div.kanan {
    width: 300px;
    float: right;
    margin-left: 210px;
    margin-top: -140px;
  }

  div.kiri {
    width: 300px;
    float: left;
    margin-left: 30px;
    display: inline;
  }

  .right {
    text-align: right;
  }
</style>
<!-- <table> -->
<?php

$queryBkk = mysqli_query($koneksi, "SELECT * 
                                          FROM bkk_final b   
                                          LEFT JOIN supplier s
                                          ON b.id_supplier = s.id_supplier
                                          JOIN anggaran a
                                          ON b.id_anggaran = a.id_anggaran
                                          WHERE b.id = '$id' ");

$i   = 1;

while ($row = mysqli_fetch_array($queryBkk)) {

?>
  <br><br><br>
  <p align="center" style="font-weight: bold; font-size: 18px;">BUKTI KAS KELUAR</p>
  <br><br>

  <table class="tabelhead text-center">
    <tr>
      <td style="width=20%;">Dibayarkan Kepada </td>
      <td style="width=45%;">: <?= $row['nm_supplier']; ?></td>
      <td style="width=1%;"></td>
      <td style="width=14%;">No BKK </td>
      <td style="width=20%;">: <?= $row['no_bkk']; ?></td>
    </tr>
    <tr>
      <td style="text-align: left; " colspan="5" height=5px;></td>
    </tr>
    <tr>
      <td>Sejumlah </td>
      <td>: <?= formatRupiah($row['nominal']); ?></td>
      <td></td>
      <td>Tanggal BKK </td>
      <td>: <?= formatTanggal($row['created_on_bkk']); ?></td>
    </tr>
    <tr>
      <td style="text-align: left; " colspan="5" height=5px;></td>
    </tr>
    <tr>
      <td>Terbilang </td>
      <td style="text-align: left; ">: <?= Terbilang($row['nominal']); ?> </td>
      <td></td>
      <td>NO.Cek/Giro </td>
      <td>: <?= $row['no_cekbkk']; ?></td>
    </tr>
  </table>
  <br>
  <hr>

  <table class="tabel2 text-center">
    <tr>
      <th style="text-align: center; "> Keterangan</th>
      <th style="text-align: center; " colspan="2"> Nilai</th>
    </tr>

    <tr>

      <td style="text-align: left; vertical-align: top;" height="400px;">

        <?php
        if ($row['pengajuan'] == 'BIAYA KHUSUS') {
          # Biaya Khusus
          echo "Biaya Operasional";
        } else {
          # Non Biaya Khusus
          echo $row['keterangan'];
        }

        ?>


        <?php if ($row['nilai_jasa'] > 0) {
          echo "<br> - Dpp Jasa ";
        } else {
          echo "";
        }
        ?>
        <?php if ($row['nilai_ppn'] > 0) {
          echo "<br> - PPN Masukan ( 10% * " . formatRupiah($row['nilai_barang'] + $row['nilai_jasa']) . ")";
        } else {
          echo "";
        }
        ?>
        <?php if ($row['nilai_pph'] > 0) {
          echo "<br> - PPh ";
        } else {
          echo "";
        }
        ?>
        <?php if ($row['pengembalian'] > 0) {
          echo "<br> - Pengembalian ";
        } else {
          echo "";
        }
        ?>
        <br><br>
      </td>
      <td style="text-align: top; vertical-align: top; ">

        <?= $row['kd_anggaran']; ?>
        <br><br><br><br><br><br>
      </td>
      <td style="text-align: center; vertical-align: top; ">
        <?php
        $nilai_barang = number_format($row['nilai_barang'], 0, ",", ".");
        $nilai_jasa = number_format($row['nilai_jasa'], 0, ",", ".");
        $ppn_nilai = number_format($row['nilai_ppn'], 0, ",", ".");
        $pph_nilai = number_format($row['nilai_pph'], 0, ",", ".");
        $pengembalian = number_format($row['pengembalian'], 0, ",", "."); ?>

        <?= formatRupiah($row['nilai_barang']); ?>
        <?php if ($nilai_jasa > 0) {
          echo "<br> " . formatRupiah($row['nilai_jasa']);
        } else {
          echo "";
        }
        ?>
        <?php if ($ppn_nilai > 0) {
          echo "<br> " . formatRupiah($row['nilai_ppn']);
        } else {
          echo "";
        }
        ?>
        <?php if ($pph_nilai > 0) {
          echo "<br> ( " . formatRupiah($row['nilai_pph']) . " )";
        } else {
          echo "";
        }
        ?>
        <?php if ($pengembalian > 0) {
          echo "<br> ( " . $pengembalian . " )";
        } else {
          echo "";
        }
        ?>

      </td>
    </tr>

    <tr>
      <td style="text-align: center; " width=500px;"><b></b></td>
      <td style="text-align: center; " width=80 px;><b>Jumlah</b></td>
      <td style="text-align: center; " width=80 px;><br><?= formatRupiah($row['nominal']); ?></td>
    </tr>
  </table>

  <br>
  <table class="tabel2 text-center">
    <tr>
      <!-- <td style="text-align: center; " width="168px;">Disiapkan/Tgl</td> -->
      <td rowspan="4" width="155px;">
        <qrcode value="[ E-Finance GS ] | Nomor BKK : 'Nomor Bkk' | Sebesar :  <?= formatRupiah($row['nominal']); ?> " ec="H" style="width: 45mm; background-color: white; color: black;"></qrcode>
      </td>
      <td style="text-align: center; " width="168px;">Disiapkan/Tgl</td>
      <td style="text-align: center; " width="155px;">Diperiksa/Tgl</td>
      <td style="text-align: center; " width="155px;">Disetujui/Tgl </td>
    </tr>
    <tr>
      <!-- <td></td> -->
      <td style="text-align: center;" height=" 80px;"><?= formatTanggalWaktu($row['created_on_bkk']); ?></td>
      <?php
      if ($row['status_bkk'] == 2) {
        # code...
        echo "<td style='text-align: center; '> Approved </td>
              <td style='text-align: center; '></td>";
      } else if ($row['status_bkk'] == 3 || $row['status_bkk'] == 4) {
        echo "<td style='text-align: center; '> Approved </td>
              <td style='text-align: center; '> Approved </td>";
      } else {
        echo "<td> </td>
              <td></td>";
      }

      ?>

    </tr>
    <tr>
      <td style="text-align: center; ">( Neneng ) </td>
      <td style="text-align: center; "> ( Andi K.Nasution ) </td>
      <!-- <td style="text-align: center; ">( ................... ) </td> -->
      <td style="text-align: center; "> </td>
    </tr>
    <tr>
      <!-- <td></td> -->
      <td style="text-align: center; ">Kasir</td>
      <td style="text-align: center; ">GM Fin.&Acc</td>
      <td style="text-align: center; " rowspan="">Direktur</td>
    </tr>
  </table>

<?php
  $i++;
} ?>
<!-- Memanggil fungsi bawaan HTML2PDF -->
<?php
$content = ob_get_clean();
include '../assets/html2pdf/html2pdf.class.php';
try {
  $html2pdf = new HTML2PDF('P', 'A4', 'en', false, 'UTF-8', array(10, 10, 10, 10));
  $html2pdf->pdf->SetDisplayMode('fullpage');
  $html2pdf->writeHTML($content);
  $html2pdf->Output('bkkno.pdf');
} catch (HTML2PDF_exception $e) {
  echo $e;
  exit;
}
?>