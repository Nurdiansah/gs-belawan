<?php ob_start(); 
    session_start();
    include "../fungsi/fungsi.php";
    if(isset($_GET['id'])) {

        $id = $_GET['id'];
    }

    $query =  mysqli_query($koneksi, "SELECT * FROM kasbon k
                                            JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON dbo.kd_transaksi = bo.kd_transaksi                                            
                                            JOIN anggaran a
                                            ON a.id_anggaran = dbo.id_anggaran
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi
                                            WHERE k.id_kasbon ='$id'  ");
    $data=mysqli_fetch_assoc($query);          
    $nm_barang = $data['nm_barang'];            


?>

<!-- tambahan baru include-->
<!-- Setting CSS bagian header/ kop -->
<style type="text/css">
  table.page_header {width: 1020px; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
  table.page_footer {width: 1020px; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm }
  h1 {color: #000033}
  h2 {color: #000055}
  h3 {color: #000077}
</style>
<!-- Setting Margin header/ kop -->
  <!-- Setting CSS Tabel data yang akan ditampilkan -->
  <style type="text/css">
  .tabel2 {
    border-collapse: collapse;
    margin-left: 0px;
    text-align : center;
    font-size : 10px;
  }
  .tabel2 th, .tabel2 td {
      padding: 5px 5px;
      border: 1px solid #000;
  }
  
  .table {
    border-collapse: collapse;    
  }

  .table table, .table tr, .table td{
    border: 1px solid black;
  }

  div.tengah{
     width:300px;
	   float:none;
     margin-left:125px;
     margin-top:-140px;
  }

  div.kanan {
     width:300px;
	   float:right;
     margin-left:10px;
     margin-top:0px;
  }

  div.kiri {
	  width:100px;
	  float:left;
	  margin-left:30px;
	  display:inline;
  }

  div.tablekiri{
    float:left;
	  margin-left:30px;
	  display:inline;
  }

  div.tablekanan{
    float:right;
    margin-left:10px;
    margin-top:0px;
  }

  .right {    
    width:300px;
	  float:right;
    margin-left:480px;
    margin-top:0px;
    text-align:left;
  }

  .left {
    width:300px;
	  float:left;
	  margin-left:150px;
	  display:inline;
    text-align:left;
    
  }
  
  </style>
  <!-- <table>
  <?php 
      include "../fungsi/koneksi.php";

   ?>
    <br><br><br><br><br>
    <tr>
      <th rowspan="8"><img src="../gambar/gs.png" style="width:100px;height:80px" /></th>
    </tr>
  </table> -->
  <div class="kiri">
    <img src="../gambar/gs.png" style="width:100px;height:80px" />
  </div>
  <!-- <div class="kanan">
    
    <p>FM : 08/06/11</p>
  </div> -->
  <br>
  <p><b>PT.GRAHA SEGARA</b><br>
  Jl. Timor Raya No. 1 Koja, Tanjung Priok, Jakarta 14310 <br>
  Telp.(62.21) 4390  4902-03-04 (HUNTING). Fax. (62-21) 4390 4906 <br>
  E-mail : finance@grahasegara.com  Website: www.grahasegara.com</p>
  <hr>  
  
  <?php
 
  ?>

  <h1 align="center"><u>KAS BON</u></h1>
  <table border="0px">
    <tr >
      <td style="text-align: left; width=100px;"></td>
      <td style="text-align: left; width=100px; height=100px;"><b>Keperluan</b></td>
      <td style="text-align: ; width=10px;">:</td>
      <td style="width=800px;"> 
        Untuk Pembelian <?= $data['nm_barang'];?>        
      </td>
    </tr>
    <tr>
      <td></td>
      <td style=" height=20px;"><b>Sebesar</b></td>
      <td>:</td>
      <td><?= formatRupiah($data['harga_akhir']) ?></td>
    </tr>
    <tr>
      <td></td>
      <td style=" height=100px;"><b>Terbilang</b></td>
      <td>:</td>
      <td><?= Terbilang($data['harga_akhir']); ?> Rupiah</td>
    </tr>
  </table>     
  <p style="text-align : right;"><b>Jakarta, <?= date("d F Y", strtotime($data['tgl_kasbon'])) ;?> </b></p>
  <br>
  <div class="tablekiri">
  <table border="1px">
    <tr>
      <td colspan="2" style="text-align: center;"><b>Permintaan</b></td>
      <td colspan="2" style="text-align: center;"><b>Mengetahui</b></td>
      <td colspan="3" style="text-align: center;"><b>Pembayaran</b></td>  
    </tr>
    <tr>
      <td style="text-align: center; width=100px; height=100px;"><br><br> <img src="../gambar/admin_divisi.png" alt="" width="100"></td>
      <td style="text-align: center; width=100px;" > <br><?php if ($data['status_kasbon']>=5) { ?><img src="../gambar/approved.jpg" alt="" width="100"> <?php  }  ?> </td>
      <td style="text-align: center; width=100px;" > <br><?php if ($data['status_kasbon']>=5) { ?> <img src="../gambar/approved.jpg" alt="" width="100">  <?php  }  ?> </td>
      <td style="text-align: center; width=100px;" > <br><?php if ($data['status_kasbon']>=5) { ?> <img src="../gambar/approved.jpg" alt="" width="100">  <?php  }  ?> </td>
      <td style="text-align: center; width=120px; height=100px;"> <br><?php if ($data['status_kasbon']>=5) { ?> <img src="../gambar/approved.jpg" alt="" width="100">  <?php  }  ?> </td>
      <td style="text-align: center; width=120px;" ><br><br> <?php if ($data['status_kasbon']>=5) { ?> <img src="../gambar/kasir.png" alt="" width="100">  <?php  }  ?> </td>
      <td style="text-align: center; width=120px;" ><br><br> <?php if ($data['status_kasbon']>=5) { ?> <img src="../gambar/kasir.png" alt="" width="100">  <?php  }  ?> </td> 
    </tr>
    <tr>
      <td style="text-align: center;"><b>PEMOHON</b></td>
      <td style="text-align: center;"><b>MANAGER</b></td>
      <td style="text-align: center;"><b> OPERATION DIRECTOR </b></td>
      <td style="text-align: center;"><b> EXECUTIVE DIRECTOR </b></td>
      <td style="text-align: center;"><b> FINANCE MANAGER </b></td>
      <td style="text-align: center;"><b> CASHIER </b></td>
      <td style="text-align: center;"><b> PENERIMA </b></td> 
    </tr>
  </table>
  </div>
<!-- Memanggil fungsi bawaan HTML2PDF -->
<?php
$content = ob_get_clean();
 include '../assets/html2pdf/html2pdf.class.php';
 try
{
    $html2pdf = new HTML2PDF('l', 'A4', 'en', false, 'UTF-8', array(10, 10, 4, 10));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output('Tallysheet-'.$id.'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>