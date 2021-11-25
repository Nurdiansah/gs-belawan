<?php ob_start(); 
    session_start();
    include "../fungsi/fungsi.php";
    include "../fungsi/koneksi.php";
    if(isset($_GET['id'])) {

      $id = $_GET['id'];
  }
?>

<!-- tambahan baru include-->
<!-- Setting CSS bagian header/ kop -->
<style type="text/css">
  table.page_header {width: 1020px; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
  table.page_footer {width: 1020px; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}
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
  }
  .tabel2 th, .tabel2 td {
      padding: 5px 5px;
      border: 1px solid #000;
  }

    div.kanan {
     width:300px;
	 float:right;
     margin-left:210px;
     margin-top:-140px;
  }

  div.kiri {
	  width:300px;
	  float:left;
	  margin-left:30px;
	  display:inline;
  }

  .right {
    text-align:right;
  }
  
  </style>
  <!-- <table> -->
  <?php 
      
      $queryBkk = mysqli_query($koneksi, "SELECT * 
                                          FROM bkk_final b   
                                          JOIN supplier s
                                          ON b.id_supplier = s.id_supplier
                                          JOIN anggaran a
                                          ON b.id_anggaran = a.id_anggaran
                                          WHERE b.id = '$id' ");
                                          
      $i   = 1;    

      while($row=mysqli_fetch_array($queryBkk))
      {        

   ?>
    <br><br><br>
  <p align="center" style="font-weight: bold; font-size: 18px;">BUKTI KAS KELUAR</p>
  <br><br>

    <table >
    <tr>
      <td >Dibayarkan Kepada </td>
      <td>: <?= $row['nm_supplier']; ?></td>
      <td style="width=70px;"></td>
      <td style="width=60px;">No BKK </td>
      <td>: <?= $row['no_bkk']; ?></td>
    </tr>
    <tr><td style="text-align: left; "  colspan="2" height=5px;></td></tr>
    <tr>
      <td>Sejumlah </td> 
      <td>: <?= formatRupiah($row['nominal']); ?></td>
      <td ></td>
      <td >Tanggal BKK </td>
      <td>: <?= $row['created_on_bkk']; ?></td>
    </tr>
    <tr><td style="text-align: left; "  colspan="2" height=5px;></td></tr>
    <tr>
      <td>Terbilang </td>
      <td>: <?= Terbilang($row['nominal']); ?>  </td>
      <td ></td>
      <td >NO.Cek/Giro </td>
      <td>: <?= $row['no_cekbkk']; ?></td>
    </tr> 
    </table>
    <br>
    <hr>
    
    <table class="tabel2 text-center">
        <tr>
          <th style="text-align: center; " > Keterangan</th>
          <th style="text-align: center; " colspan="2"> Nilai</th>
        </tr>        

        <tr>

        <td style="text-align: left; vertical-align: top;" height="400px;"> 

        <?php                 
          echo $row['keterangan'];                                                         
                  ?>
            
            
            <?php if ($row['nilai_jasa'] > 0) {
              echo "<br> - Dpp Jasa ";
            } else {
              echo "";
            }
            ?>
            <?php if ($row['nilai_ppn'] > 0) {
              echo "<br> - PPN Masukan ( 10% * ".formatRupiah($row['nilai_barang']+$row['nilai_jasa']).")";
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
        <td style="text-align: top; vertical-align: top; " >          
          
           <?= $row['kd_anggaran']; ?>
          <br><br><br><br><br><br>          
        </td>             
        <td style="text-align: center; vertical-align: top; ">
          <?php              
                $nilai_barang = number_format($row['nilai_barang'],0,",",".");
                $nilai_jasa = number_format($row['nilai_jasa'],0,",",".");
                $ppn_nilai = number_format($row['nilai_ppn'],0,",",".");
                $pph_nilai = number_format($row['nilai_pph'],0,",","."); 
                $pengembalian = number_format($row['pengembalian'],0,",",".");?>
            
           <?= formatRupiah($row['nilai_barang']); ?>            
            <?php if ($nilai_jasa > 0) {
              echo "<br> Rp.$nilai_jasa ";
            } else {
              echo "";
            }
            ?>
            <?php if ($ppn_nilai > 0) {
              echo "<br> ". formatRupiah($row['nilai_ppn']);
            } else {
              echo "";
            }
            ?>           
            <?php if ($pph_nilai > 0) {
              echo "<br> ( Rp.".$pph_nilai." )";
            } else {
              echo "";
            }
            ?>                    
            <?php if ($pengembalian > 0) {
              echo "<br> ( ".$pengembalian." )";
            } else {
              echo "";
            }
            ?>                                            
            
        </td>            
        </tr>                

        <tr>
          <td style="text-align: center; "  width=500px;"><b></b></td>          
          <td style="text-align: center; " width=80 px; ><b>Jumlah</b></td>
          <td style="text-align: center; " width=80 px; ><br><?= formatRupiah($row['nominal']); ?></td>
        </tr>        
    </table>

    <table class="tabel2 text-center">
        <tr>
          <td style="text-align: center; " colspan="2"><b>PERMINTAAN PENGELUARAN KAS</b></td>
          <td width="23px;"> </td>
          <td style="text-align: center; " colspan="2"><b>PEMBAYARAN</b></td>
        </tr>
        <tr>
          <td style="text-align: center; "  width="150px;" height="70px;">Disiapkan/Tgl <br>  <br>  <img src="../gambar/ttd_dhika.jpeg" width="50px;"> <br> ( Rosita ) <br>Pemohon</td> 
          <td style="text-align: center; "  width="150px;">Verifikasi/Pembukuan <br>  <br>  <img src="../gambar/ttd_andri.jpeg" width="50px;"> <br> <br> ( ..... ) <br>Manager</td>
          <td style="text-align: center; "  width="5px;"></td>
          <td style="text-align: center; "  width="145px;">Disiapkan/Tgl <br> <br><br><br>  <br> ( ................... ) <br>Kasir</td>
          <td style="text-align: center; "  width="145px;">Disetujui/Tgl <br> <br><br><br> <br> <br> (Roy Royadi) <br>Direktur</td>
        </tr>
        <tr>
          <td style="text-align: center; "  width="150px;" height="70px;">Diperiksa/tgl <br> <br><br><br> <br> <br> (Andi K.Nasution) <br>GM Fin.&Acc</td> 
          <td style="text-align: center; "  width="150px;">Disetujui/Tgl <br>  <br><img src="../gambar/ttd_bosia.jpeg" width="50px;">  <br> <br> (Roy Royadi) <br>Direktur</td>
          <td style="text-align: center; "  width="5px;"></td>
          <td style="text-align: center; "  width="145px;">Disetujui/Tgl <br> <br><br><br> <br> <br> ( ................... ) <br>Direktur</td>
          <td style="text-align: center; "  width="145px;">Diterima/Tgl <br> <br><br><br> <br> <br> ( ................... ) <br>Penerima</td>
        </tr>
    </table>

    <?php
    $i++;
    } ?>
<!-- Memanggil fungsi bawaan HTML2PDF -->
<?php
$content = ob_get_clean();
 include '../assets/html2pdf/html2pdf.class.php';
 try
{
    $html2pdf = new HTML2PDF('P', 'A4', 'en', false, 'UTF-8', array(10, 10, 4, 10));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output('bkkno.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>
