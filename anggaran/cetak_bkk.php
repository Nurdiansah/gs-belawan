<?php ob_start();
session_start();
include "../fungsi/fungsi.php";
if (isset($_POST['simpan'])) {

  $id = $_POST['id_bkk'];
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
include "../fungsi/koneksi.php";
$queryBkk = mysqli_query($koneksi, "SELECT * FROM bkk WHERE id_bkk = '$id' ");

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

$i   = 1;
while ($row = mysqli_fetch_array($queryBkk)) {
  $noBkk = $row['no_bkk'];
  $jml_bkk = number_format($row['jml_bkk'], 2, ",", ".");
?>
  <br><br><br>
  < <p align="center" style="font-weight: bold; font-size: 18px;">BUKTI KAS KELUAR</p>
    <br><br>

    <table>
      <tr>
        <td>Dibayarkan Kepada </td>
        <td>: <?= $row['nm_vendor']; ?></td>
        <td style="width=70px;"></td>
        <td style="width=60px;">No BKK </td>
        <td>: <?= $row['no_bkk']; ?></td>
      </tr>
      <tr>
        <td style="text-align: left; " colspan="2" height=5px;></td>
      </tr>
      <tr>
        <td>Sejumlah </td>
        <td>: <?= "Rp." . $jml_bkk; ?></td>
        <td></td>
        <td>Tanggal BKK </td>
        <td>: <?= $row['tgl_bkk']; ?></td>
      </tr>
      <tr>
        <td style="text-align: left; " colspan="2" height=5px;></td>
      </tr>
      <tr>
        <td>Terbilang </td>
        <td>: <?= $row['terbilang_bkk']; ?> Rupiah </td>
        <td></td>
        <td>NO.Cek/Giro </td>
        <td>: <?= $row['nocek_bkk']; ?></td>
      </tr>
    </table>
    <br>
    <hr>

    <table class="tabel2 text-center">
      <tr>
        <th style="text-align: center; " colspan="2"> Keterangan</th>
        <th style="text-align: center; " colspan="2"> Nilai</th>
      </tr>
      <tr>
        <td style="text-align: left; " colspan="2" height=400px;> <?= $row['keterangan']; ?></td>
        <td></td>
        <td> <?= "Rp." . $jml_bkk; ?></td>
      </tr>
      <tr>
        <td style="text-align: center; " width=225px;"><b>Bank</b></td>
        <td style="text-align: center; " width=225px;"><b>No. Rekening</b></td>
        <td style="text-align: center; " width=80 px; rowspan="2"><b>Jumlah</b></td>
        <td style="text-align: center; " width=80 px; rowspan="2"><?= "Rp." . $jml_bkk; ?></td>
      </tr>
      <tr>
        <td style="text-align: center; "> <?= $row['dari_bank']; ?></td>
        <td style="text-align: center; "> <?= $row['dari_rekening']; ?></td>
      </tr>
    </table>

    <table class="tabel2 text-center">
      <tr>
        <td style="text-align: center; " colspan="2"><b>PERMINTAAN PENGELUARAN KAS</b></td>
        <td></td>
        <td style="text-align: center; " colspan="2"><b>PEMBAYARAN</b></td>
      </tr>
      <tr>
        <td style="text-align: center; " width="150px;" height="70px;">Disiapkan/Tgl <br> <?= $row['tgl_pengajuankasir']; ?> <br> <img src="../gambar/ttd_dhika.jpeg" width="50px;"> <br> ( Dhika ) <br>Pemohon</td>
        <td style="text-align: center; " width="150px;">Verifikasi/Pembukuan <br> <?= $row['tgl_verifikasimanager']; ?> <br> <img src="../gambar/ttd_andri.jpeg" width="50px;"> <br> <br> ( Andri ) <br>Manager</td>
        <td style="text-align: center; " width="5px;"></td>
        <td style="text-align: center; " width="145px;">Disiapkan/Tgl <br> <br><br><br> <br> ( ................... ) <br>Kasir</td>
        <td style="text-align: center; " width="145px;">Disetujui/Tgl <br> <br><br><br> <br> <br> (M.I. Anwar) <br>President</td>
      </tr>
      <tr>
        <td style="text-align: center; " width="150px;" height="70px;">Diperiksa/tgl <br> <br><br><br> <br> <br> (Andi K.Nasution) <br>GM Fin.&Acc</td>
        <td style="text-align: center; " width="150px;">Disetujui/Tgl <br> <?= $row['tgl_verifikasidireksi']; ?> <br><img src="../gambar/ttd_bosia.jpeg" width="50px;"> <br> <br> (Indra Anwar) <br>Direktur</td>
        <td style="text-align: center; " width="5px;"></td>
        <td style="text-align: center; " width="145px;">Disetujui/Tgl <br> <br><br><br> <br> <br> ( ................... ) <br>Direktur</td>
        <td style="text-align: center; " width="145px;">Diterima/Tgl <br> <br><br><br> <br> <br> ( ................... ) <br>Penerima</td>
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
    $html2pdf = new HTML2PDF('P', 'A4', 'en', false, 'UTF-8', array(10, 10, 4, 10));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output('bkkno.pdf');
  } catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
  }
  ?>