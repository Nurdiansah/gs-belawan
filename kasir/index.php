<?php
ob_start();
session_start();
//include "cekuser.php";
include "../fungsi/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'kasir') {
  header("location: ../index.php");
}

// biar langsung masuk ke URL pas login klik dari email
if (isset($_COOKIE['url']) && $_SESSION['level'] == $_COOKIE['lvl']) {
  if (isset($_COOKIE['sp'])) {
    header('Location: ' . $_COOKIE['url'] . '&sp=' . $_COOKIE['sp']);
  } else {
    header('Location: ' . $_COOKIE['url'] . '');
  }
  unset($_COOKIE['url']);
  unset($_COOKIE['lvl']);
  setcookie('url', $_GET['url'], time() - 1);
  setcookie('lvl', $_GET['lvl'], time() - 1);
}

$tahun = date('Y');

$tanggal = date("Y-m-d");

// $query = mysqli_query($koneksi, "SELECT COUNT(id_jenis) AS jumlah FROM jenis_barang ");
// $data = mysqli_fetch_assoc($query);
$query = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='6' ");
$data = mysqli_fetch_assoc($query);

$jmlV = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='8' ");
$dataV = mysqli_fetch_assoc($jmlV);

$jmlAll = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='9'  ");
$dataAll = mysqli_fetch_assoc($jmlAll);

$queryNama =  mysqli_query($koneksi, "SELECT nama from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];

// biaya umum tempo
$queryBUT = mysqli_query($koneksi,  "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='9' AND metode_pembayaran='transfer'  ");
$dataBUT = mysqli_fetch_assoc($queryBUT);

// biaya umum payment umum
$queryUmum = mysqli_query($koneksi,  "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='9' AND metode_pembayaran = 'tunai'  ");
$dataBUPU = mysqli_fetch_assoc($queryUmum);

// biaya umum tempo
$queryBPP = mysqli_query($koneksi,  "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='17' AND jenis='kontrak'  ");
$dataBPP = mysqli_fetch_assoc($queryBPP);

$dataBUP = $dataBUPU['jumlah'];

// biaya umum transaksi
// $jmlBT = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='9' ");
// $dataBT = mysqli_fetch_assoc($jmlBT);

// Payment Pettycash
$queryPTP = mysqli_query($koneksi, "SELECT COUNT(id_pettycash) AS jumlah FROM transaksi_pettycash WHERE status_pettycash = '2'  ");
$dataPTP = mysqli_fetch_assoc($queryPTP);

// Pending LPJ Pettycash
$queryPTPN = mysqli_query($koneksi, "SELECT COUNT(id_pettycash) AS jumlah FROM transaksi_pettycash WHERE status_pettycash = '3'  ");
$dataPTPN = mysqli_fetch_assoc($queryPTPN);

// Verfikasi LPJ Kasbon
$queryPTV = mysqli_query($koneksi, "SELECT COUNT(id_pettycash) AS jumlah FROM transaksi_pettycash WHERE status_pettycash = '4'  ");
$dataPTV = mysqli_fetch_assoc($queryPTV);

// kasbon payment
$queryKP = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '5'  ");
$dataKP = mysqli_fetch_assoc($queryKP);

// kasbon payment 1
$queryKP1 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '7' AND from_user ='0' AND sr_id IS NULL ");
$dataKP1 = mysqli_fetch_assoc($queryKP1);

// kasbon payment 2
$queryKP2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '7' AND from_user ='1' ");
$dataKP2 = mysqli_fetch_assoc($queryKP2);

// kasbon payment 3 INI KASBON SR YA
$queryKP3 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '5' AND from_user ='0' AND sr_id IS NOT NULL ");
$dataKP3 = mysqli_fetch_assoc($queryKP3);

$jumlahKP = $dataKP1['jumlah'] + $dataKP2['jumlah'] + $dataKP3['jumlah'];

// kasbon pending lpj
$queryKPL = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon IN ('8')");
$dataKPL = mysqli_fetch_assoc($queryKPL);

// kasbon pending lpj 1
$queryKPL1 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '8' AND from_user = '0' AND sr_id IS NULL");
$dataKPL1 = mysqli_fetch_assoc($queryKPL1);

// kasbon pending lpj 2
$queryKPL2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '8' AND from_user = '1' ");
$dataKPL2 = mysqli_fetch_assoc($queryKPL2);

// kasbon pending lpj 3
$queryKPL3 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon IN ('6', '0') AND sr_id IS NOT NULL AND from_user = '0'");
$dataKPL3 = mysqli_fetch_assoc($queryKPL3);

// kasbon ver lpj
$queryKL = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '7'  ");
$dataKL = mysqli_fetch_assoc($queryKL);

// kasbon ver lpj 1
$queryKL1 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '9' AND from_user='0' AND sr_id IS NULL ");
$dataKL1 = mysqli_fetch_assoc($queryKL1);

// kasbon ver lpj 2
$queryKL2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '9' AND from_user='1'  ");
$dataKL2 = mysqli_fetch_assoc($queryKL2);

// kasbon vNOT er lpj 3 Untuk lpj kasbon sr
$queryKL3 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon = '9' AND from_user='0' AND sr_id IS NOT NULL ");
$dataKL3 = mysqli_fetch_assoc($queryKL3);

$jumlahKL = $dataKL1['jumlah'] + $dataKL2['jumlah'] + $dataKL3['jumlah'];

// kasbon transaksi
$queryKT = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah  FROM biaya_ops WHERE status_biayaops = '11'  ");
$dataKT = mysqli_fetch_assoc($queryKT);

// po verifikasi
$queryVP = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah  FROM po WHERE status_po = '6'  ");
$dataVP = mysqli_fetch_assoc($queryVP);

// po tempo
$queryTP = mysqli_query($koneksi, "SELECT COUNT(id_tagihan) AS jumlah  FROM tagihan_po WHERE status_tagihan = '1' ");
$dataTP = mysqli_fetch_assoc($queryTP);

// po payment
$queryPP = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM bkk_final WHERE status_bkk = '3' AND pengajuan ='PO'  ");
$dataPP = mysqli_fetch_assoc($queryPP);

// po proses payment
$queryPRP = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah  FROM po WHERE status_po ='8'  ");
$dataPRP = mysqli_fetch_assoc($queryPRP);

// print_r($dataPRP);
// die;

// po outstanding
$queryOP = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah  FROM po WHERE status_po= '11'  ");
$dataOP = mysqli_fetch_assoc($queryOP);

// bkk proses
$queryBP = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM bkk_final WHERE status_bkk <= '3' AND status_bkk NOT IN ('101', '202', '17', '18')");
$dataBP = mysqli_fetch_assoc($queryBP);

// biaya khusus proses
$queryBKP = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM bkk_final WHERE status_bkk <= '3' AND status_bkk <> '101' AND pengajuan = 'BIAYA KHUSUS'");
$dataBKP = mysqli_fetch_assoc($queryBKP);

// pengajuan khusus ditolak
$queryTolakPK = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM bkk_final WHERE status_bkk = '101' AND pengajuan = 'BIAYA KHUSUS'");
$dataTolakPK = mysqli_fetch_assoc($queryTolakPK);

// BKK ditolak
$queryTolakBKK = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM bkk_final WHERE status_bkk = '101' -- AND pengajuan != 'PO'");
$dataTolakBKK = mysqli_fetch_assoc($queryTolakBKK);

$queryTolakBKKBelawan = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM gs_belawan.bkk_ke_pusat WHERE status_bkk = '101' -- AND pengajuan != 'PO'");
$dataTolakBKKBelawan = mysqli_fetch_assoc($queryTolakBKKBelawan);

$totalTolakBKK = $dataTolakBKK['jumlah'] + $dataTolakBKKBelawan['jumlah'];

$querySO = mysqli_query($koneksi, "SELECT COUNT(id_so) as jumlah FROM so WHERE status = '5' -- AND tgl_tempo <= '$tanggal'");
$dataSO = mysqli_fetch_assoc($querySO);

$queryOutstanding = mysqli_query($koneksi, "SELECT COUNT(id) as jumlah FROM bkk_final WHERE status_bkk = '17'");
$dataOutstanding = mysqli_fetch_assoc($queryOutstanding);

// REFILL FUND PROSES
$queryRP = mysqli_query($koneksi, "SELECT COUNT(id_refill) AS jumlah FROM refill_funds WHERE status BETWEEN '0' AND '5'");
$dataRP = mysqli_fetch_assoc($queryRP);

//  QUERY BKM
$queryBKM = mysqli_query($koneksi, "SELECT COUNT(id_bkm) AS jumlah FROM bkm WHERE status_bkm = '2'");
$dataBKM = mysqli_fetch_assoc($queryBKM);

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GS|System</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/icon" href="../gambar/fav-gs.png">
  <!-- allert -->
  <link rel="stylesheet" href="../assets/plugins/alertify/themes/alertify.core.css" />
  <link rel="stylesheet" href="../assets/plugins/alertify/themes/alertify.default.css" id="toggleCSS" />
  <!-- <link rel="shortcut icon" type="image/icon" href="../pv.png"> -->
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link href="../assets/bootstrap/css/custom.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/fa/css/font-awesome.min.css">
  <!-- Ionicons -->
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../assets/dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../assets/plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../assets/plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../assets/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../assets/plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables/dataTables.bootstrap.css">

  <script src="../assets/plugins/jQuery/jquery.min.js"></script>

  <!-- chart -->
  <style>
    #chartdiv {
      width: 100%;
      height: 380px;
    }

    #pie1 {
      width: 100%;
      height: 500px;
    }
  </style>

  <!-- Resources -->
  <script src="https://www.amcharts.com/lib/4/core.js"></script>
  <script src="https://www.amcharts.com/lib/4/charts.js"></script>
  <script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

</head>

<body class="hold-transition skin-red sidebar-collapse sidebar-mini">
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>G</b>S</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">PT. Graha Segara</span>
      </a>

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="../gambar/avatar1.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs"> <?php echo strtoupper($Nama); ?> </span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="../gambar/avatar1.jpg" class="img-circle" alt="User Image">
                  <p>
                    <?php echo " $Nama " ?>
                    <small>- KASIR -</small>
                  </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="index.php?p=rubah_password" class="btn btn-default btn-flat">Change Password</a>
                  </div>
                  <div class="pull-right">
                    <a href="../logout.php" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
          </ul>
        </div>
      </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">Menu Utama</li>
          <li class="active treeview">
            <a href="?pg=dashboard">
              <i class="fa fa-dashboard"></i> <span>Home</span>
            </a>
          </li>

          <li class="header">Transaksi</li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-calendar-check-o"></i>
              <span>Biaya Umum</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataBUT['jumlah'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataBUT['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=biayaumum_tempo"><i class="fa fa-clock-o"></i> Tempo</a></li>
              <?php if ($dataBUP >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $dataBUP; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=lihat_bno"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-print"></i>
              <span>BKK</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataBP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataBP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=proses_bkk"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($dataTolakBKK['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataTolakBKK['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_bkk"><i class="fa fa-close"></i> Ditolak</a></li>
              <li><a href="index.php?p=transaksi_bkk"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-gear"></i>
              <span>Service Order</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataSO['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataSO['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=payment_sr"><i class="fa fa-money"></i> Payment</a></li>
              <li><a href="index.php?p=transaksi_sr"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-dollar"></i>
              <span>Petty Cash</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataPTP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $dataPTP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=payment_pettycash"><i class="fa fa-money"></i> Payment</a></li>
              <?php if ($dataPTPN['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataPTPN['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=pending_pettycash"><i class="fa fa-hourglass-2"></i> Pending LPJ</a></li>
              <?php if ($dataPTV['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataPTV['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_pettylpj"><i class="fa fa-tags"></i> Verifikasi LPJ</a></li>
              <li><a href="index.php?p=transaksi_pettycash"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-money"></i>
              <span>Kasbon</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($jumlahKP > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $jumlahKP; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=payment_kasbon&sp=pk_purchasing"><i class="fa fa-money"></i> Payment</a></li>
              <?php if ($dataKPL['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataKPL['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=pending_kasbon&sp=pnk_purchasing"><i class="fa fa-hourglass-2"></i> Pending LPJ</a></li>
              <?php if ($dataKL['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataKL['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_kasbonlpj&sp=vlk_purchasing"><i class="fa fa-tags"></i> Verifikasi LPJ</a></li>
              <?php if ($dataKT['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataKT['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=transaksi_kasbon"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-list"></i>
              <span>PO</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataVP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataVP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_po"><i class="fa fa-check-square"></i> Verifikasi</a></li>
              <?php if ($dataOP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataOP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=outstanding_po"><i class="fa fa-hourglass-1"></i> Outstanding</a></li>
              <?php if ($dataTP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $dataTP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=list_po"><i class="fa fa-clock-o"></i> Tempo</a></li>
              <?php if ($dataPP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $dataPP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=payment_po"><i class="fa fa-money"></i> Payment</a></li>
              <li><a href="index.php?p=transaksi_po"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>


          <li class="header">Laporan</li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-files-o"></i>
              <span>Laporan</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?p=laporan_bkk"><i class="fa fa-print"></i> BKK</a></li>
            </ul>
          </li>

        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <?php include "page.php"; ?>
    </div>

    <footer class="main-footer">
      <marquee hspace="40" width="full-width"></marquee>
      <strong>Copyright &copy; ENC SYSTEM v1.0 </strong>
    </footer>

    <!-- Script Custom -->
    <script>
      $('body').on('keydown', 'input, select', function(e) {
        if (e.key === "Enter") {
          var self = $(this),
            form = self.parents('form:eq(0)'),
            focusable, next;
          focusable = form.find('input,a,select,button,textarea').filter(':visible');
          next = focusable.eq(focusable.index(this) + 1);
          if (next.length) {
            next.focus();
          } else {
            form.submit();
          }
          return false;
        }
      });
    </script>

    <!-- jQuery UI 1.11.4 -->
    <script src="../assets/plugins/jQueryUI/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/bootstrap/js/my.js"></script>
    <!-- Morris.js charts -->

    <script src="../assets/plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="../assets/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="../assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="../assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="../assets/plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <!-- datepicker -->
    <script src="../assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="../assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="../assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../assets/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/app.min.js"></script>

    <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

</body>

</html>