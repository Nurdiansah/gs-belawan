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
    width: 200px;
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

  div.kananttd {
    width: 300px;
    float: right;
    margin-left: 600px;
    margin-top: 0px;
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
                                          JOIN supplier s
                                          ON dbo.id_supplier = s.id_supplier
                                        --   JOIN supplier s
                                        --   ON dbo.id_supplier = s.id_supplier
                                        --   JOIN anggaran a
                                        --   ON dbo.id_anggaran = a.id_anggaran
                                        --   JOIN bkk_final bf
                                        --   ON bf.id_kdtransaksi = k.id_kasbon
                                          WHERE p.id_po = '$id' ");

$i   = 1;

$row2 = mysqli_fetch_array($queryBkk);
$total = number_format($row2['total'], 0, ",", ".");
$terbilang_bkk = Terbilang($row2['total']);
$id_dbo = $row2['id_dbo'];

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
  <input type="checkbox"> PURCHASE ORDER <br>
  <input type="checkbox"> SERVICE ORDER
</div>

<!--  -->
<div class="kiri">
  <p style="font-size : 8;"><b>PT.GRAHA SEGARA</b><br>
    Jl. Timor Raya No. 1 Koja, Tanjung Priok, Jakarta 14310 <br>
    Telp.(62.21) 4390 4902-03-04 (HUNTING). Fax. (62-21) 4390 4906 <br>
    E-mail : finance@grahasegara.com Website: www.grahasegara.com</p>
</div>
<div class="kanan">
</div>


<div class="kotak-to">
  To : <?= $row2['nm_supplier']; ?>
</div>
<div class="kiri"></div>
<div class="kanan">
  <br><br>
  <table class="tabel2 text-center">
    <tr>
      <td>PO/SO Number</td>
      <td width=120 px;><?= $row2['po_number']; ?></td>
    </tr>
    <tr>
      <td>DATE</td>
      <td><?= formatTanggal($row2['tgl_po']); ?></td>
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
    <th style="text-align: center; " width="370px;"> DESCRIPTION</th>
    <th style="text-align: center; ">QTY</th>
    <th style="text-align: center; ">UNIT</th>
    <th style="text-align: center; ">UNIT PRICE</th>
    <th style="text-align: center; "> TOTAL PRICE</th>
  </tr>

  <?php
  $no = 1;
  $total = 0;
  if (mysqli_num_rows($querySbo)) {
    while ($row = mysqli_fetch_assoc($querySbo)) :

  ?>

      <tr>


        <td> <?= $no; ?> </td>
        <td> <?= $row['sub_deskripsi']; ?> </td>
        <td> <?= $row['sub_qty']; ?> </td>
        <td> <?= $row['sub_unit']; ?> </td>
        <td> <?= formatRupiah($row['sub_unitprice']); ?> </td>
        <td><?= formatRupiah($row['total_price']); ?></td>

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
    <td colspan="5" style="text-align: left;"><b> Sub Total</b></td>
    <td style="text-align: center;"><b><?= formatRupiah($total); ?> </b></td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: left;"> Disc</td>
    <td style="text-align: center;"><?= formatRupiah($row2['diskon_po']); ?> </td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: left;"> Total</td>
    <td style="text-align: center;"><?= formatRupiah($row2['total_po']); ?> </td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: left;"> PPN 11 %</td>
    <td style="text-align: center;"><?= formatRupiah($row2['nilai_ppn']); ?> </td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: left;"><b> Grand Total</b></td>
    <td style="text-align: center;"><b><?= formatRupiah($row2['grand_totalpo']); ?> </b></td>
  </tr>
  <tr>
    <td colspan="5" style="text-align: left; height: 50px; width:600px;"> <?= $row2['note_po']; ?></td>
    <td style="text-align: center;"> </td>
  </tr>
  <tr>
    <td colspan="4" style="text-align: right;"><b># <?= Terbilang($total); ?></b></td>
    <td style="text-align: right;"><b> TOTAL</b></td>
    <td style="text-align: center;"><b><?= formatRupiah($total); ?> </b></td>
  </tr>
</table>
<br><br>
<div class="kirittd">
  <p>ORIGINATED BY,<br> </p>
  <br>
  <br>
  <br>
  <!-- <img src="../file/ttd/ttd1.png" style="width:120px"> -->
  <p><b>Purchasing <br></b></p>
</div>

<div class="tengahttd">
  <p>ACCEPTED & ASSIGNED<br>BY FINANCE &<br>ACCOUNTING DIVISION </p>
  <br>
  <br>
  <br>
  <!-- <img src="../file/ttd/ttd1.png" style="width:120px"> -->
  <p><b>Finance Manager <br></b></p>
</div>

<div class="kananttd">
  <p>APPROVED BY,<br></p>
  <br>
  <br>
  <br>
  <!-- <img src="../file/ttd/ttd2.png" style="width:120px"> -->
  <p><b>Director <br></b></p>
</div>

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