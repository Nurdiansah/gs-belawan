<?php 
   mysql_connect("localhost","root","");
   mysql_select_db("gs");
   // include "../fungsi/koneksi.php";

//  nama file
//    $filename="data produk-".date('Ymd').".xls";
//    $filename="BKK.xlsx";

   if(isset($_POST['cetak'])){
      $tgl_awal = $_POST['tgl_awal'];
      $tgl_akhir = $_POST['tgl_akhir'];

      if($tgl_awal > $tgl_akhir){
         echo "<script>window.alert('Tanggal periode akhir harus lebih besar')</script>";
         //<a href="index.php?p=laporan-xls"> -->
      }else{
         header("Content-Type: application/vnd-ms-excel"); 
         header("Content-Disposition: attachment; filename=BKK.xlsx;");

         $out=array();
         $sql=mysql_query("SELECT no_bkk as `No BKK`, created_on_bkk as `Tanggal BKK`,pengajuan as `Nama Pengajuan`,
                              no_cekbkk as `No Cek BKK`,
                              Nominal, nm_bank as Bank, no_rekening as Rekening,
                              Keterangan
                           FROM bkk_final a 
                           JOIN bank b
                           ON a.dari_bank = b.id_bank
                           JOIN rekening r
                           ON a.dari_rekening = r.id_rekening                          
                           Where created_on_bkk between '$tgl_awal' and '$tgl_akhir'");
         while($data=mysql_fetch_assoc($sql)) $out[]=$data;

         $show_coloumn = false;
         foreach($out as $record) {
            if(!$show_coloumn){
               //menampilkan nama kolom di baris pertama
               echo implode("\t", array_keys($record)) . "\n";
               $show_coloumn = true;
            }
            //looping data dari database
            echo implode("\t", array_values($record)) . "\n";
         }
         exit;
      }
   }
?>
