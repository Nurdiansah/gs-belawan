<?php
include "../fungsi/koneksi.php";
include "../fungsi/fungsi.php";

if (isset($_POST['cetak'])) {
   $tahun = $_POST['tahun'];
   $divisi = $_POST['divisi'];
}

$queryCetak = mysqli_query($koneksi, "SELECT id_anggaran, tahun, nm_divisi as Divisi,
                                             no_coa as 'No COA', kd_anggaran as 'Kode Anggaran', nm_golongan as Golongan,
                                             nm_subgolongan as 'Sub Golongan', nm_item as 'Nama Item', id_satuan as Satuan, Harga,
                                             januari_kuantitas as 'Januari Kuantitas', januari_nominal as 'Januari Nominal', januari_realisasi as 'Januari Realisasi',
                                             februari_kuantitas as 'Februari Kuantitas', februari_nominal as 'Februari Nominal', februari_realisasi as 'Februari Realisasi',
                                             maret_kuantitas as 'Maret Kuantitas', maret_nominal as 'Maret Nominal', maret_realisasi as 'Maret Realisasi',
                                             april_kuantitas as 'April Kuantitas', april_nominal as 'April Nominal', april_realisasi as 'April Realisasi',
                                             mei_kuantitas as 'Mei Kuantitas', mei_nominal as 'Mei Nominal', mei_realisasi as 'Mei Realisasi',
                                             juni_kuantitas as 'Juni Kuantitas', juni_nominal as 'Juni Nominal', juni_realisasi as 'Juni Realisasi',
                                             juli_kuantitas as 'Juli Kuantitas', juli_nominal as 'Juli Nominal', juli_realisasi as 'Juli Realisasi',
                                             agustus_kuantitas as 'Agustus Kuantitas', agustus_nominal as 'Agustus Nominal', agustus_realisasi as 'Agustus Realisasi',
                                             september_kuantitas as 'September Kuantitas', september_nominal as 'September Nominal', september_realisasi as 'September Realisasi',
                                             oktober_kuantitas as 'Oktober Kuantitas', oktober_nominal as 'Oktober Nominal', oktober_realisasi as 'Oktober Realisasi',
                                             november_kuantitas as 'November Kuantitas', november_nominal as 'November Nominal', november_realisasi as 'November Realisasi',
                                             desember_kuantitas as 'Desember Kuantitas', desember_nominal as 'Desember Nominal', desember_realisasi as 'Desember Realisasi',
                                             jumlah_kuantitas as 'Jumlah Kuantitas', jumlah_nominal as 'Jumlah Nominal', created_by as 'Dibuat Oleh', created_on as 'Waktu Dibuat',
                                             last_modified_by as 'Dirubah Oleh', last_modified_on 'Waktu Dirubah', row_version as 'Versi Row'
                                       From anggaran a
                                       inner join divisi c
                                       on a.id_divisi = c.id_divisi
                                       inner join golongan d
                                       on a.id_golongan = d.id_golongan
                                       inner join sub_golongan e
                                       on a.id_subgolongan = e.id_subgolongan
                                       WHERE tahun = '$tahun'
                                       AND a.id_divisi = '$divisi'
                                       ORDER BY no_coa ASC");

$totalData = mysqli_num_rows($queryCetak);

$queryDivisi = mysqli_query($koneksi, "SELECT * FROM divisi WHERE id_divisi = '$divisi'");
$dataDivisi = mysqli_fetch_assoc($queryDivisi);

$nm_divisi = $dataDivisi['nm_divisi'];

if ($totalData > 1) {
   // fungsi header dengan mengirimkan raw data excel
   header("Content-type: application/vnd-ms-excel");

   // membuat nama file ekspor "export-to-excel.xls"
   header("Content-Disposition: attachment; filename=Anggaran-$nm_divisi-$tahun.xls");

   // NGINDARIN Undefined variable
   $no = 1;
   $sub_no_coa = 0;
   $total_harga = 0;
   $sub_harga = 0;
   // januari
   $total_januari_kuantitas = 0;
   $total_januari_nominal = 0;
   $total_januari_realisasi = 0;
   $sub_januari_kuantitas = 0;
   $sub_januari_nominal = 0;
   $sub_januari_realisasi = 0;
   // februari
   $total_februari_kuantitas = 0;
   $total_februari_nominal = 0;
   $total_februari_realisasi = 0;
   $sub_februari_kuantitas = 0;
   $sub_februari_nominal = 0;
   $sub_februari_realisasi = 0;
   // maret
   $total_maret_kuantitas = 0;
   $total_maret_nominal = 0;
   $total_maret_realisasi = 0;
   $sub_maret_kuantitas = 0;
   $sub_maret_nominal = 0;
   $sub_maret_realisasi = 0;
   // april
   $total_april_kuantitas = 0;
   $total_april_nominal = 0;
   $total_april_realisasi = 0;
   $sub_april_kuantitas = 0;
   $sub_april_nominal = 0;
   $sub_april_realisasi = 0;
   // mei
   $total_mei_kuantitas = 0;
   $total_mei_nominal = 0;
   $total_mei_realisasi = 0;
   $sub_mei_kuantitas = 0;
   $sub_mei_nominal = 0;
   $sub_mei_realisasi = 0;
   // juni
   $total_juni_kuantitas = 0;
   $total_juni_nominal = 0;
   $total_juni_realisasi = 0;
   $sub_juni_kuantitas = 0;
   $sub_juni_nominal = 0;
   $sub_juni_realisasi = 0;
   // juli
   $total_juli_kuantitas = 0;
   $total_juli_nominal = 0;
   $total_juli_realisasi = 0;
   $sub_juli_kuantitas = 0;
   $sub_juli_nominal = 0;
   $sub_juli_realisasi = 0;
   // agustus
   $total_agustus_kuantitas = 0;
   $total_agustus_nominal = 0;
   $total_agustus_realisasi = 0;
   $sub_agustus_kuantitas = 0;
   $sub_agustus_nominal = 0;
   $sub_agustus_realisasi = 0;
   // september
   $total_september_kuantitas = 0;
   $total_september_nominal = 0;
   $total_september_realisasi = 0;
   $sub_september_kuantitas = 0;
   $sub_september_nominal = 0;
   $sub_september_realisasi = 0;
   // oktober
   $total_oktober_kuantitas = 0;
   $total_oktober_nominal = 0;
   $total_oktober_realisasi = 0;
   $sub_oktober_kuantitas = 0;
   $sub_oktober_nominal = 0;
   $sub_oktober_realisasi = 0;
   // november
   $total_november_kuantitas = 0;
   $total_november_nominal = 0;
   $total_november_realisasi = 0;
   $sub_november_kuantitas = 0;
   $sub_november_nominal = 0;
   $sub_november_realisasi = 0;
   // desember
   $total_desember_kuantitas = 0;
   $total_desember_nominal = 0;
   $total_desember_realisasi = 0;
   $sub_desember_kuantitas = 0;
   $sub_desember_nominal = 0;
   $sub_desember_realisasi = 0;

   $nomer_coa = "";
?>
   <table style="text-align: left;">
      <tr>
         <td><b>GRAHA SEGARA</b></td>
      </tr>
      <tr>
         <td><b>JAKARTA</b></td>
      </tr>
      <tr>
         <td><b>ANGGARAN <?= $tahun; ?></b></td>
      </tr>
      <tr>
         <td><b>BELANJA OPERASIONAL <?= $nm_divisi; ?></b></td>
      </tr>
      <tr>
         <td></td>
      </tr>
   </table>
   <table border="1">
      <tr>
         <th rowspan="2">ID Anggaran</th>
         <th rowspan="2">Tahun</th>
         <th rowspan="2">Divisi</th>
         <th rowspan="2">No COA</th>
         <th rowspan="2">Kode Transaksi</th>
         <th rowspan="2">Golongan</th>
         <th rowspan="2">Sub Golongan</th>
         <th rowspan="2">Nama Item</th>
         <th rowspan="2">Satuan</th>
         <th rowspan="2">Harga</th>
         <th colspan="3">Januari</th>
         <th colspan="3">Februari</th>
         <th colspan="3">Maret</th>
         <th colspan="3">April</th>
         <th colspan="3">Mei</th>
         <th colspan="3">Juni</th>
         <th colspan="3">Juli</th>
         <th colspan="3">Agustus</th>
         <th colspan="3">September</th>
         <th colspan="3">Oktober</th>
         <th colspan="3">November</th>
         <th colspan="3">Desember</th>
      </tr>
      <tr>
         <th>Januari Kuantitas</th>
         <th>Januari Nominal</th>
         <th>Januari Realisasi</th>
         <th>Februari Kuantitas</th>
         <th>Februari Nominal</th>
         <th>Februari Realisasi</th>
         <th>Maret Kuantitas</th>
         <th>Maret Nominal</th>
         <th>Maret Realisasi</th>
         <th>April Kuantitas</th>
         <th>April Nominal</th>
         <th>April Realisasi</th>
         <th>Mei Kuantitas</th>
         <th>Mei Nominal</th>
         <th>Mei Realisasi</th>
         <th>Juni Kuantitas</th>
         <th>Juni Nominal</th>
         <th>Juni Realisasi</th>
         <th>Juli Kuantitas</th>
         <th>Juli Nominal</th>
         <th>Juli Realisasi</th>
         <th>Agustus Kuantitas</th>
         <th>Agustus Nominal</th>
         <th>Agustus Realisasi</th>
         <th>September Kuantitas</th>
         <th>September Nominal</th>
         <th>September Realisasi</th>
         <th>Oktober Kuantitas</th>
         <th>Oktober Nominal</th>
         <th>Oktober Realisasi</th>
         <th>November Kuantitas</th>
         <th>November Nominal</th>
         <th>November Realisasi</th>
         <th>Desember Kuantitas</th>
         <th>Desember Nominal</th>
         <th>Desember Realisasi</th>
      </tr>
      <?php while ($dataCetak = mysqli_fetch_assoc($queryCetak)) {
         // CETAK SUB TOTAL DITENGAH2
         if ($no > 1 && $sub_no_coa != $dataCetak['No COA']) {
      ?>
            <tr style="background-color: grey;">
               <th colspan="9">Sub Total <?= $nomer_coa; ?></th>
               <th><?= formatRupiah2($sub_harga); ?></th>
               <th><?= formatRupiah2($sub_januari_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_januari_nominal); ?></th>
               <th><?= formatRupiah2($sub_januari_realisasi); ?></th>
               <th><?= formatRupiah2($sub_februari_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_februari_nominal); ?></th>
               <th><?= formatRupiah2($sub_februari_realisasi); ?></th>
               <th><?= formatRupiah2($sub_maret_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_maret_nominal); ?></th>
               <th><?= formatRupiah2($sub_maret_realisasi); ?></th>
               <th><?= formatRupiah2($sub_april_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_april_nominal); ?></th>
               <th><?= formatRupiah2($sub_april_realisasi); ?></th>
               <th><?= formatRupiah2($sub_mei_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_mei_nominal); ?></th>
               <th><?= formatRupiah2($sub_mei_realisasi); ?></th>
               <th><?= formatRupiah2($sub_juni_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_juni_nominal); ?></th>
               <th><?= formatRupiah2($sub_juni_realisasi); ?></th>
               <th><?= formatRupiah2($sub_juli_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_juli_nominal); ?></th>
               <th><?= formatRupiah2($sub_juli_realisasi); ?></th>
               <th><?= formatRupiah2($sub_agustus_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_agustus_nominal); ?></th>
               <th><?= formatRupiah2($sub_agustus_realisasi); ?></th>
               <th><?= formatRupiah2($sub_september_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_september_nominal); ?></th>
               <th><?= formatRupiah2($sub_september_realisasi); ?></th>
               <th><?= formatRupiah2($sub_oktober_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_oktober_nominal); ?></th>
               <th><?= formatRupiah2($sub_oktober_realisasi); ?></th>
               <th><?= formatRupiah2($sub_november_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_november_nominal); ?></th>
               <th><?= formatRupiah2($sub_november_realisasi); ?></th>
               <th><?= formatRupiah2($sub_desember_kuantitas); ?></th>
               <th><?= formatRupiah2($sub_desember_nominal); ?></th>
               <th><?= formatRupiah2($sub_desember_realisasi); ?></th>
            </tr>
         <?php
            $sub_harga = 0;
            // januari
            $sub_januari_kuantitas = 0;
            $sub_januari_nominal = 0;
            $sub_januari_realisasi = 0;
            // februari
            $sub_februari_kuantitas = 0;
            $sub_februari_nominal = 0;
            $sub_februari_realisasi = 0;
            // maret
            $sub_maret_kuantitas = 0;
            $sub_maret_nominal = 0;
            $sub_maret_realisasi = 0;
            // april
            $sub_april_kuantitas = 0;
            $sub_april_nominal = 0;
            $sub_april_realisasi = 0;
            // mei
            $sub_mei_kuantitas = 0;
            $sub_mei_nominal = 0;
            $sub_mei_realisasi = 0;
            // juni
            $sub_juni_kuantitas = 0;
            $sub_juni_nominal = 0;
            $sub_juni_realisasi = 0;
            // juli
            $sub_juli_kuantitas = 0;
            $sub_juli_nominal = 0;
            $sub_juli_realisasi = 0;
            // agustus
            $sub_agustus_kuantitas = 0;
            $sub_agustus_nominal = 0;
            $sub_agustus_realisasi = 0;
            // september
            $sub_september_kuantitas = 0;
            $sub_september_nominal = 0;
            $sub_september_realisasi = 0;
            // oktober
            $sub_oktober_kuantitas = 0;
            $sub_oktober_nominal = 0;
            $sub_oktober_realisasi = 0;
            // november
            $sub_november_kuantitas = 0;
            $sub_november_nominal = 0;
            $sub_november_realisasi = 0;
            // desember
            $sub_desember_kuantitas = 0;
            $sub_desember_nominal = 0;
            $sub_desember_realisasi = 0;
         }
         $sub_harga += $dataCetak['Harga'];
         $sub_januari_kuantitas += $dataCetak['Januari Kuantitas'];
         $sub_januari_nominal += $dataCetak['Januari Nominal'];
         $sub_januari_realisasi += $dataCetak['Januari Realisasi'];
         $sub_februari_kuantitas += $dataCetak['Februari Kuantitas'];
         $sub_februari_nominal += $dataCetak['Februari Nominal'];
         $sub_februari_realisasi += $dataCetak['Februari Realisasi'];
         $sub_maret_kuantitas += $dataCetak['Maret Kuantitas'];
         $sub_maret_nominal += $dataCetak['Maret Nominal'];
         $sub_maret_realisasi += $dataCetak['Maret Realisasi'];
         $sub_april_kuantitas += $dataCetak['April Kuantitas'];
         $sub_april_nominal += $dataCetak['April Nominal'];
         $sub_april_realisasi += $dataCetak['April Realisasi'];
         $sub_mei_kuantitas += $dataCetak['Mei Kuantitas'];
         $sub_mei_nominal += $dataCetak['Mei Nominal'];
         $sub_mei_realisasi += $dataCetak['Mei Realisasi'];
         $sub_juni_kuantitas += $dataCetak['Juni Kuantitas'];
         $sub_juni_nominal += $dataCetak['Juni Nominal'];
         $sub_juni_realisasi += $dataCetak['Juni Realisasi'];
         $sub_juli_kuantitas += $dataCetak['Juli Kuantitas'];
         $sub_juli_nominal += $dataCetak['Juli Nominal'];
         $sub_juli_realisasi += $dataCetak['Juli Realisasi'];
         $sub_agustus_kuantitas += $dataCetak['Agustus Kuantitas'];
         $sub_agustus_nominal += $dataCetak['Agustus Nominal'];
         $sub_agustus_realisasi += $dataCetak['Agustus Realisasi'];
         $sub_september_kuantitas += $dataCetak['September Kuantitas'];
         $sub_september_nominal += $dataCetak['September Nominal'];
         $sub_september_realisasi += $dataCetak['September Realisasi'];
         $sub_oktober_kuantitas += $dataCetak['Oktober Kuantitas'];
         $sub_oktober_nominal += $dataCetak['Oktober Nominal'];
         $sub_oktober_realisasi += $dataCetak['Oktober Realisasi'];
         $sub_november_kuantitas += $dataCetak['November Kuantitas'];
         $sub_november_nominal += $dataCetak['November Nominal'];
         $sub_november_realisasi += $dataCetak['November Realisasi'];
         $sub_desember_kuantitas += $dataCetak['Desember Kuantitas'];
         $sub_desember_nominal += $dataCetak['Desember Nominal'];
         $sub_desember_realisasi += $dataCetak['Desember Realisasi'];

         $nomer_coa = $dataCetak['No COA'];
         $sub_no_coa = $dataCetak['No COA'];
         $no++;

         // END SUB TOTAL DITENGAH2
         ?>
         <tr>
            <td><?= $dataCetak['id_anggaran']; ?></td>
            <td><?= $dataCetak['tahun']; ?></td>
            <td><?= $dataCetak['Divisi']; ?></td>
            <td>'<?= $dataCetak['No COA']; ?></td>
            <td><?= $dataCetak['Kode Anggaran']; ?></td>
            <td><?= $dataCetak['Golongan']; ?></td>
            <td><?= $dataCetak['Sub Golongan']; ?></td>
            <td><?= $dataCetak['Nama Item']; ?></td>
            <td><?= $dataCetak['Satuan']; ?></td>
            <td><?= formatRupiah2($dataCetak['Harga']); ?></td>
            <td><?= formatRupiah2($dataCetak['Januari Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Januari Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Januari Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Februari Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Februari Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Februari Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Maret Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Maret Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Maret Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['April Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['April Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['April Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Mei Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Mei Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Mei Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Juni Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Juni Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Juni Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Juli Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Juli Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Juli Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Agustus Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Agustus Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Agustus Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['September Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['September Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['September Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Oktober Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Oktober Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Oktober Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['November Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['November Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['November Realisasi']); ?></td>
            <td><?= formatRupiah2($dataCetak['Desember Kuantitas']); ?></td>
            <td><?= formatRupiah2($dataCetak['Desember Nominal']); ?></td>
            <td><?= formatRupiah2($dataCetak['Desember Realisasi']); ?></td>
         </tr>
      <?php

         // ITUNGAN TOTAL
         $total_harga += $dataCetak['Harga'];
         $total_januari_kuantitas += $dataCetak['Januari Kuantitas'];
         $total_januari_nominal += $dataCetak['Januari Nominal'];
         $total_januari_realisasi += $dataCetak['Januari Realisasi'];
         $total_februari_kuantitas += $dataCetak['Februari Kuantitas'];
         $total_februari_nominal += $dataCetak['Februari Nominal'];
         $total_februari_realisasi += $dataCetak['Februari Realisasi'];
         $total_maret_kuantitas += $dataCetak['Maret Kuantitas'];
         $total_maret_nominal += $dataCetak['Maret Nominal'];
         $total_maret_realisasi += $dataCetak['Maret Realisasi'];
         $total_april_kuantitas += $dataCetak['April Kuantitas'];
         $total_april_nominal += $dataCetak['April Nominal'];
         $total_april_realisasi += $dataCetak['April Realisasi'];
         $total_mei_kuantitas += $dataCetak['Mei Kuantitas'];
         $total_mei_nominal += $dataCetak['Mei Nominal'];
         $total_mei_realisasi += $dataCetak['Mei Realisasi'];
         $total_juni_kuantitas += $dataCetak['Juni Kuantitas'];
         $total_juni_nominal += $dataCetak['Juni Nominal'];
         $total_juni_realisasi += $dataCetak['Juni Realisasi'];
         $total_juli_kuantitas += $dataCetak['Juli Kuantitas'];
         $total_juli_nominal += $dataCetak['Juli Nominal'];
         $total_juli_realisasi += $dataCetak['Juli Realisasi'];
         $total_agustus_kuantitas += $dataCetak['Agustus Kuantitas'];
         $total_agustus_nominal += $dataCetak['Agustus Nominal'];
         $total_agustus_realisasi += $dataCetak['Agustus Realisasi'];
         $total_september_kuantitas += $dataCetak['September Kuantitas'];
         $total_september_nominal += $dataCetak['September Nominal'];
         $total_september_realisasi += $dataCetak['September Realisasi'];
         $total_oktober_kuantitas += $dataCetak['Oktober Kuantitas'];
         $total_oktober_nominal += $dataCetak['Oktober Nominal'];
         $total_oktober_realisasi += $dataCetak['Oktober Realisasi'];
         $total_november_kuantitas += $dataCetak['November Kuantitas'];
         $total_november_nominal += $dataCetak['November Nominal'];
         $total_november_realisasi += $dataCetak['November Realisasi'];
         $total_desember_kuantitas += $dataCetak['Desember Kuantitas'];
         $total_desember_nominal += $dataCetak['Desember Nominal'];
         $total_desember_realisasi += $dataCetak['Desember Realisasi'];
         // END ITUNGAN TOTAL
      }
      // CETAK SUB TOTAL DIAKHIR
      $totalData = $totalData + 1;
      if ($no > 1 && $totalData == $no) {
      ?>
         <tr style="background-color: grey;">
            <th colspan="9">Sub Total <?= $nomer_coa; ?></th>
            <th><?= formatRupiah2($sub_harga); ?></th>
            <th><?= formatRupiah2($sub_januari_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_januari_nominal); ?></th>
            <th><?= formatRupiah2($sub_januari_realisasi); ?></th>
            <th><?= formatRupiah2($sub_februari_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_februari_nominal); ?></th>
            <th><?= formatRupiah2($sub_februari_realisasi); ?></th>
            <th><?= formatRupiah2($sub_maret_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_maret_nominal); ?></th>
            <th><?= formatRupiah2($sub_maret_realisasi); ?></th>
            <th><?= formatRupiah2($sub_april_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_april_nominal); ?></th>
            <th><?= formatRupiah2($sub_april_realisasi); ?></th>
            <th><?= formatRupiah2($sub_mei_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_mei_nominal); ?></th>
            <th><?= formatRupiah2($sub_mei_realisasi); ?></th>
            <th><?= formatRupiah2($sub_juni_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_juni_nominal); ?></th>
            <th><?= formatRupiah2($sub_juni_realisasi); ?></th>
            <th><?= formatRupiah2($sub_juli_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_juli_nominal); ?></th>
            <th><?= formatRupiah2($sub_juli_realisasi); ?></th>
            <th><?= formatRupiah2($sub_agustus_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_agustus_nominal); ?></th>
            <th><?= formatRupiah2($sub_agustus_realisasi); ?></th>
            <th><?= formatRupiah2($sub_september_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_september_nominal); ?></th>
            <th><?= formatRupiah2($sub_september_realisasi); ?></th>
            <th><?= formatRupiah2($sub_oktober_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_oktober_nominal); ?></th>
            <th><?= formatRupiah2($sub_oktober_realisasi); ?></th>
            <th><?= formatRupiah2($sub_november_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_november_nominal); ?></th>
            <th><?= formatRupiah2($sub_november_realisasi); ?></th>
            <th><?= formatRupiah2($sub_desember_kuantitas); ?></th>
            <th><?= formatRupiah2($sub_desember_nominal); ?></th>
            <th><?= formatRupiah2($sub_desember_realisasi); ?></th>
         </tr>
      <?php
         $sub_harga = 0;
         $sub_januari_kuantitas = 0;
         $sub_januari_nominal = 0;
         $sub_januari_realisasi = 0;
      }
      // $sub_harga += $dataCetak['Harga'];
      // $sub_januari_kuantitas += $dataCetak['Januari Kuantitas'];
      // $sub_januari_nominal += $dataCetak['Januari Nominal'];
      // $sub_januari_realisasi += $dataCetak['Januari Realisasi'];

      // $nomer_coa = $dataCetak['No COA'];
      // $sub_no_coa = $dataCetak['No COA'];
      // $no++;
      // END SUB TOTAL DIAKHIR
      ?>
      <!-- TAMPIL TOTAL -->

      <tr>
         <th colspan="9">Total</th>
         <th><?= formatRupiah2($total_harga); ?></th>
         <th><?= formatRupiah2($total_januari_kuantitas); ?></th>
         <th><?= formatRupiah2($total_januari_nominal); ?></th>
         <th><?= formatRupiah2($total_januari_realisasi); ?></th>
         <th><?= formatRupiah2($total_februari_kuantitas); ?></th>
         <th><?= formatRupiah2($total_februari_nominal); ?></th>
         <th><?= formatRupiah2($total_februari_realisasi); ?></th>
         <th><?= formatRupiah2($total_maret_kuantitas); ?></th>
         <th><?= formatRupiah2($total_maret_nominal); ?></th>
         <th><?= formatRupiah2($total_maret_realisasi); ?></th>
         <th><?= formatRupiah2($total_april_kuantitas); ?></th>
         <th><?= formatRupiah2($total_april_nominal); ?></th>
         <th><?= formatRupiah2($total_april_realisasi); ?></th>
         <th><?= formatRupiah2($total_mei_kuantitas); ?></th>
         <th><?= formatRupiah2($total_mei_nominal); ?></th>
         <th><?= formatRupiah2($total_mei_realisasi); ?></th>
         <th><?= formatRupiah2($total_juni_kuantitas); ?></th>
         <th><?= formatRupiah2($total_juni_nominal); ?></th>
         <th><?= formatRupiah2($total_juni_realisasi); ?></th>
         <th><?= formatRupiah2($total_juli_kuantitas); ?></th>
         <th><?= formatRupiah2($total_juli_nominal); ?></th>
         <th><?= formatRupiah2($total_juli_realisasi); ?></th>
         <th><?= formatRupiah2($total_agustus_kuantitas); ?></th>
         <th><?= formatRupiah2($total_agustus_nominal); ?></th>
         <th><?= formatRupiah2($total_agustus_realisasi); ?></th>
         <th><?= formatRupiah2($total_september_kuantitas); ?></th>
         <th><?= formatRupiah2($total_september_nominal); ?></th>
         <th><?= formatRupiah2($total_september_realisasi); ?></th>
         <th><?= formatRupiah2($total_oktober_kuantitas); ?></th>
         <th><?= formatRupiah2($total_oktober_nominal); ?></th>
         <th><?= formatRupiah2($total_oktober_realisasi); ?></th>
         <th><?= formatRupiah2($total_november_kuantitas); ?></th>
         <th><?= formatRupiah2($total_november_nominal); ?></th>
         <th><?= formatRupiah2($total_november_realisasi); ?></th>
         <th><?= formatRupiah2($total_desember_kuantitas); ?></th>
         <th><?= formatRupiah2($total_desember_nominal); ?></th>
         <th><?= formatRupiah2($total_desember_realisasi); ?></th>
      </tr>
      <!-- END TAMPIL TOTAL -->
   </table>
<?php } else {
   echo "<script>window.alert('Data laporan divisi $nm_divisi tahun $tahun tidak ada (kosong)!');
						location='index.php?p=laporan-xls'
					</script>";
}
?>