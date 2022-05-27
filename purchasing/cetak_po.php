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
    margin-top: 5px;
  }

  .tabel2 th,
  .tabel2 td {
    padding: 5px 5px;
    border: 1px solid #000;
  }

  .tablebawah td {
    font-size: 12;
    text-align: center;
    font-weight: bold;
  }

  div.kanan {
    width: 300px;
    float: right;
    margin-left: 150px;
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

  .kotak-ceklis {
    background-color: white;
    border: 1px solid #17202A;
    height: 50px;
    width: 50px;
    margin: 10px 0px;
    padding: 5px;
    text-align: center;

  }

  .garis {
    border: 2px;
  }

  .kotak {
    width: 250px;
    height: 50px;
    border: 1px;
    margin-top: 150px;
  }

  .kotak-to {
    border: 1px solid #17202A;
    height: 80px;
    margin: 10px 0px;
    padding: 5px;
    width: 300px;
    margin-top: 150px;
  }

  .table-atas {
    border-collapse: collapse;
    margin-left: 0px;
    margin-top: 5px;
  }

  div.kirittd {
    width: 150px;
    float: left;
    margin-left: 30px;
    display: inline;
    margin-top: 50px;

  }

  div.tengahkiri {
    width: 200px;
    float: none;
    margin-left: 30px;
    margin-top: -140px;
  }

  div.tengahttd {
    width: 200px;
    float: none;
    margin-left: 60px;
    margin-top: -140px;

  }

  div.tengahkirittd {
    width: 100px;
    float: left;
    margin-left: 10px;
    /* display:inline; */
    margin-top: -140px;

  }

  div.tengahkananttd {
    width: 200px;
    float: right;
    margin-left: 370px;
    margin-top: -30px;

  }

  div.kananttd {
    width: 300px;
    float: right;
    margin-left: 600px;
    margin-top: -110px;

  }

  div.posisibawah {
    margin-top: 430px
  }
</style>
<!-- <table> -->
<?php

$queryBkk = mysqli_query($koneksi, "SELECT * 
                                          FROM po p
                                          JOIN biaya_ops b
                                          ON p.kd_transaksi = b.kd_transaksi
                                          JOIN detail_biayaops dbo                                          
                                          ON p.id_dbo = dbo.id
                                          LEFT JOIN supplier s
                                          ON dbo.id_supplier = s.id_supplier
                                        --   JOIN anggaran a
                                        --   ON dbo.id_anggaran = a.id_anggaran
                                        --   JOIN bkk_final bf
                                        --   ON bf.id_kdtransaksi = k.id_kasbon
                                          WHERE p.id_po = '$id' ");

$i   = 1;

$row = mysqli_fetch_array($queryBkk);
$total = number_format($row['total'], 0, ",", ".");
$terbilang_bkk = Terbilang($row['total']);
$id_dbo = $row['id_dbo'];

$diskon_po  = $row['diskon_po'];
$total_po  = $row['total_po'];
$nilai_ppn  = $row['nilai_ppn'];
$grand_total  = $row['grand_totalpo'];

$app_create  = $row['tgl_po'];
$app_spv = $row['app_mgr_ga'];
$app_cc = $row['app_cc'];
$app_mgr = $row['app_mgr_ga'];
$app_mgr_finance = $row['app_mgr_finance'];
$app_direksi = $row['app_direksi'];

$noPo = $row['po_number'];


$querySbo =  mysqli_query($koneksi, "SELECT * FROM sub_dbo                                                         
                                                    WHERE id_dbo=$id_dbo");

?>
<!-- Header -->
<div class="kiri">
  <img src="../gambar/gs.png" style="width:100px;height:80px" />
</div>
<div class="kanan">
  <div class="kotak">
    FM :
  </div>
  <input type="checkbox" checked="checked"> PURCHASE ORDER <br>
  <input type="checkbox"> SERVICE ORDER
</div>

<!--  -->
<div class="kiri">
  <p style="font-size : 8;"><b>PT.GRAHA SEGARA Cabang Belawan - Medan</b><br>
    Jl. Raya Pelabuhan Gabion Lingkungan XII, Kelurahan Bagian Deli, <br>
    Kecamatan Medan Belawan, Medan, Sumatera Utara 20414<br>
    Phone +62-61 888 10100 - 04, Fax: +62-61 888 10106 - 07<br>
    Website: http://www.grahasegara.co.id</p>
</div>
<div class="kanan">
</div>


<div class="kotak-to">
  To : <?= $row['nm_supplier']; ?>
</div>
<div class="kiri"></div>
<div class="kanan">
  <br><br>
  <table class="tabel2 text-center">
    <tr>
      <td>PO/SO Number</td>
      <td width=120 px;><?= $row['po_number']; ?></td>
    </tr>
    <tr>
      <td>DATE</td>
      <td><?= formatTanggal($row['tgl_po']); ?></td>
    </tr>
    <tr>
      <td>PAGE</td>
      <td>1</td>
    </tr>
  </table>
</div>

<br><br>

<table class="tabel2 text-center">
  <tr>
    <th>ITEM</th>
    <th style="text-align: center; width: 300px;"> DESCRIPTION</th>
    <th style="text-align: center; max-width: 20px; ">QTY</th>
    <th style="text-align: center;  max-width: 30px;">UNIT</th>
    <th style="text-align: center;  width: 110px;">UNIT PRICE</th>
    <th style="text-align: center;  width: 110px;"> TOTAL PRICE</th>
  </tr>

  <?php
  $no = 1;
  $total = 0;
  if (mysqli_num_rows($querySbo)) {
    while ($row = mysqli_fetch_assoc($querySbo)) :

  ?>

      <tr>


        <td> <?= $no; ?> </td>
        <td style="width: 200px;"> <?= $row['sub_deskripsi']; ?> </td>
        <td style="width: 20px;"> <?= $row['sub_qty']; ?> </td>
        <td style="width: 30px;"> <?= $row['sub_unit']; ?> </td>
        <td style="text-align: right;"> <?= formatRupiah($row['sub_unitprice']); ?> </td>
        <td style="text-align: right;"><?= formatRupiah(round($row['total_price'])); ?></td>

        <!-- <td></td>
          <td style="text-align: left;" height="450px;"> 
              <?= $row['nm_barang']; ?>                                
          </td>
          <td style="text-align: left;" > 
              <?= $row['jumlah']; ?>                                
          </td>
          <td style="text-align: left;" > 
              <?= $row['satuan']; ?>                                
          </td>
          <td style="text-align: left;" > 
              <?= formatRupiah($row['total_po'] / $row['jumlah']); ?>                                
          </td>
          <td style="text-align: left;" > 
              <?= formatRupiah($row['total_po']); ?>                                
          </td>                                 -->
      </tr>
  <?php
      $total += $row['total_price'];
      $no++;
    endwhile;
  } ?>
  <tr>
    <td colspan="5" style="text-align: right;"><b> Sub Total </b></td>
    <td style="text-align: right;"><b><?= formatRupiah($total); ?> </b></td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: right;"><b> Disc </b></td>
    <td style="text-align: right;"><?= formatRupiah(round($diskon_po)); ?> </td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: right;"><b> Total </b></td>
    <td style="text-align: right;"><?= formatRupiah(round($total_po)); ?> </td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: right;"><b> PPN 11 % </b></td>
    <td style="text-align: right;"><?= formatRupiah(round($nilai_ppn)); ?> </td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: right;"><b> Grand Total</b></td>
    <td style="text-align: right;"><b><?= formatRupiah(round($grand_total)); ?> </b></td>
  </tr>
</table>
<br><br>

<table border="0px" class="tablebawah">
  <tr>
    <!-- <td style="width: 200px;"> <?= $row['sub_deskripsi']; ?> </td> -->
    <td style="width: 150px;">ORIGINATED BY</td>
    <td style="width: 150px;">ACCEPTED & ASSIGNED BY HEAD OF DIVISION</td>
    <td style="width: 160px;">ACCEPTED & ASSIGNED BY FINANCE & ACCOUNTING DIVISION</td>
    <td colspan="2">APPROVED BY</td>
  </tr>
  <tr>
    <td style="height: 50px;">
      <qrcode value="E-FIN GS BELAWAN | Nomor PO <?= $noPo; ?> di buat <?= $app_create ?>" ec="H" style="width: 25mm; background-color: white; color: black;"></qrcode>
    </td>
    <td style="height: 50px;">
      <qrcode value="E-FIN GS BELAWAN | Nomor PO <?= $noPo; ?>, Approved Supervisor <?= $app_spv ?>" ec="H" style="width: 25mm; background-color: white; color: black;"></qrcode>
    </td>
    <td style="height: 50px;">
      <qrcode value="E-FIN GS BELAWAN | Nomor PO <?= $noPo; ?>, Approved Costcontrol <?= $app_cc ?>" ec="H" style="width: 25mm; background-color: white; color: black;"></qrcode>
    </td>
    <td style="height: 50px;">
      <qrcode value="E-FIN GS BELAWAN | Nomor PO <?= $noPo; ?>, Approved Manager <?= $app_mgr ?>" ec="H" style="width: 25mm; background-color: white; color: black;"></qrcode>
    </td>
    <td style="height: 50px;">
      <qrcode value="E-FIN GS BELAWAN | Nomor PO <?= $noPo; ?>, Approved Direktur <?= $app_direksi ?>" ec="H" style="width: 25mm; background-color: white; color: black;"></qrcode>
    </td>
  </tr>
  <tr>
    <td>Purchasing</td>
    <td>Supervisor</td>
    <td>Supervisor</td>
    <td style="width: 120px;">Manager</td>
    <td style="width: 120px;">Director</td>
  </tr>
</table>
<!-- </div> -->
<!-- Memanggil fungsi bawaan HTML2PDF -->
<?php
$content = ob_get_clean();
include '../assets/html2pdf/html2pdf.class.php';
try {
  $html2pdf = new HTML2PDF('P', 'A4', 'en', false, 'UTF-8', array(10, 10, 4, 10));
  $html2pdf->pdf->SetDisplayMode('fullpage');
  $html2pdf->writeHTML($content);
  $html2pdf->Output('po.pdf');
} catch (HTML2PDF_exception $e) {
  echo $e;
  exit;
}
?>