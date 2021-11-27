<?php
ob_start();
session_start();
//include "cekuser.php";
include "../fungsi/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'manager_ga') {
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

// $query = mysqli_query($koneksi, "SELECT COUNT(id_jenis) AS jumlah FROM jenis_barang ");
// $data = mysqli_fetch_assoc($query);
$queryNama =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];
$idUser = $rowNama['id_user'];

// query data approval 
$query = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='1' AND id_manager='$idUser' ");
$data = mysqli_fetch_assoc($query);

// query data proses
$queryProsesbno = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_proses FROM bkk WHERE id_manager='$idUser' AND status_bkk >='2' AND status_bkk <='8' ORDER BY tgl_bkk DESC  ");
$dataProsesbno = mysqli_fetch_assoc($queryProsesbno);

// query data biaya umum approve all
$queryVBU = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='6' ");
$dataVBU = mysqli_fetch_assoc($queryVBU);

// query data lihat
$querySelesai = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_Selesai FROM bkk WHERE status_bkk='9' AND id_manager='$idUser' ");
$dataSelesai = mysqli_fetch_assoc($querySelesai);

// query data Verifikasi Pajak
$queryBno = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='5' ");
$dataBno = mysqli_fetch_assoc($queryBno);

// query data approval mr
$queryAM = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah FROM biaya_ops WHERE status_biayaops='1' AND id_manager='$idUser' ");
$dataAM = mysqli_fetch_assoc($queryAM);

// query proses mr
$queryPMR = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah FROM biaya_ops WHERE id_manager='$idUser' AND status_biayaops = '2' ORDER BY kd_transaksi DESC");
$dataPMR = mysqli_fetch_assoc($queryPMR);

// query AP
$queryAP = mysqli_query($koneksi, "SELECT COUNT(id_pettycash) AS jumlah FROM transaksi_pettycash WHERE id_manager = '$idUser' AND status_pettycash = '1'   ");
$dataAP = mysqli_fetch_assoc($queryAP);

// query proses Kasbon 1
$queryKV1 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah
                                    FROM kasbon k
                                    JOIN biaya_ops bo
                                    ON k.kd_transaksi = bo.kd_transaksi
                                    JOIN detail_biayaops dbo
                                    ON k.id_dbo = dbo.id
                                    JOIN divisi d
                                    ON d.id_divisi = bo.id_divisi
                                    WHERE k.status_kasbon = '2'
                                    AND sr_id IS NULL
                                    AND from_user='0' ");
$dataKV1 = mysqli_fetch_assoc($queryKV1);

// query proses Kasbon 2
$queryKV2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah
                                    FROM kasbon k
                                    JOIN detail_biayaops dbo
                                    ON k.id_dbo = dbo.id
                                    JOIN divisi d
                                    ON d.id_divisi = dbo.id_divisi
                                    WHERE k.status_kasbon = '4'
                                    AND k.from_user = '1'
                                    -- AND k.id_manager = '$idUser'
                                    ORDER BY k.id_kasbon DESC");
$dataKV2 = mysqli_fetch_assoc($queryKV2);

$queryKV3 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah
                                    FROM kasbon k
                                    JOIN sr s
                                    ON k.sr_id = s.id_sr
                                    JOIN divisi d
                                    ON d.id_divisi = k.divisi_id
                                    WHERE k.status_kasbon = '1'");

$dataKV3 = mysqli_fetch_assoc($queryKV3);


$jkv = $dataKV1['jumlah'] + $dataKV2['jumlah'] + $dataKV3['jumlah'];

// po verifikasi
$queryPV = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah  FROM po WHERE status_po = '2'  ");
$dataPV = mysqli_fetch_assoc($queryPV);

// query kasbon APPROVE
$queryKasbonDivisi = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon = '1' AND id_manager = '$idUser'  AND from_user = '1'");
$dataKasbonDivisi = mysqli_fetch_assoc($queryKasbonDivisi);

// query kasbon ditolak
$queryKasbonTolak = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon = '303' AND id_manager = '$idUser'  AND from_user = '1'");
$dataKasbonTolak = mysqli_fetch_assoc($queryKasbonTolak);

$queryTolakKSR = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon = '505' AND sr_id IS NOT NULL");
$dataTolakKSR = mysqli_fetch_assoc($queryTolakKSR);

$queryTolakMR = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon = '303' AND from_user = '0'");
$dataTolakMR = mysqli_fetch_assoc($queryTolakMR);

$totalTolakKasbon = $dataKasbonTolak['jumlah'] + $dataTolakKSR['jumlah'] + $dataTolakMR['jumlah'];

$queryTolakPO = mysqli_query($koneksi, "SELECT COUNT(id_po) as jumlah FROM po WHERE status_po = '303'");
$dataTolakPO = mysqli_fetch_assoc($queryTolakPO);

$querySO = mysqli_query($koneksi, "SELECT COUNT(id_so) AS jumlah FROM so WHERE status = '1'");
$dataSO = mysqli_fetch_assoc($querySO);

$queryTolakSO = mysqli_query($koneksi, "SELECT COUNT(id_so) AS jumlah FROM so WHERE status = '505'");
$dataTolakSO = mysqli_fetch_assoc($queryTolakSO);

// approval SR
$querySR = mysqli_query($koneksi, "SELECT COUNT(id_sr) AS jumlah FROM sr WHERE status = '1' AND id_manager = '$idUser'");
$dataSR = mysqli_fetch_assoc($querySR);

// query bkk ditolak
$queryTolakBNO = mysqli_query($koneksi, "SELECT COUNT(id_bkk) as jumlah FROM bkk WHERE status_bkk = '303' AND id_manager='$idUser' ");
$dataTolakBNO = mysqli_fetch_assoc($queryTolakBNO);

$queryBKK = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM bkk_final WHERE status_bkk = '2'  ");
$dataBKK = mysqli_fetch_assoc($queryBKK);


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GS|SYSTEM</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/icon" href="../gambar/fav-gs.png">
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
  <script type="text/javascript" src="my.js"></script>

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
        <span class="logo-mini"><b>GS</b></span>
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
                    <small>- Manager-</small>
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
              <i class="fa fa-check-square"></i>
              <span>Verifikasi</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($jkv >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $jkv; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_kasbon"><i class="fa fa-money"></i> Kasbon</a></li>
              <?php if ($dataPV['jumlah'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataPV['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="#"><i class="fa fa-list"></i> PO</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-calendar-check-o"></i>
              <span>Biaya Umum</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($data['jumlah'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $data['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=approval_biayanonops"><i class="fa fa-check-square-o"></i> Approval</a></li>
              <?php if ($dataProsesbno['jumlah_proses'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataProsesbno['jumlah_proses']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=proses_biayanonops"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($dataSelesai['jumlah_Selesai'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataSelesai['jumlah_Selesai']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=lihat_bno"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-folder-open-o"></i>
              <span>Material Request</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataAM['jumlah'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataAM['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=approval_mr"><i class="fa fa-check-square-o"></i> Approval</a></li>
              <?php if ($dataPMR['jumlah'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataPMR['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=proses_mr"><i class="fa fa-spinner"></i> Proses </a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-file-o"></i>
              <span>Service Request</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataSR['jumlah'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataSR['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=approval_srga"><i class="fa fa-check-square-o"></i> Approval</a></li>
              <li><a href="#"><i class="fa fa-spinner"></i> Proses </a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-dollar"></i>
              <span>Petty Cash</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataAP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $dataAP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=approval_pettycash"><i class="fa fa-check-square-o"></i> Approval</a></li>
              <li><a href="#"><i class="fa fa-spinner"></i> Proses</a></li>
              <li><a href="#"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
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
              <li><a href="index.php?p=approval_sr"><i class="fa fa-check-square-o"></i> Approval</a></li>
              <li><a href="index.php?p=proses_sr"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($dataTolakSO['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataTolakSO['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_so"><i class="fa fa-close"></i> Ditolak</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-money"></i>
              <span>Kasbon</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($totalTolakKasbon > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $totalTolakKasbon; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing"><i class="fa fa-close"></i> Ditolak</a></li>
              <li><a href="#"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-list"></i>
              <span>PO</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($dataTolakPO['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataTolakPO['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_po"><i class="fa fa-close"></i> Ditolak</a></li>
              <li><a href="#"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>


          <li class="header">Laporan</li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-files-o"></i>
              <span>Laporan</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <!-- <ul class="treeview-menu">
                <li><a href="?pg=laprekap&act=view"><i class="fa fa-check-square-o"></i> Laporan Rekapitulasi</a></li>
              </ul> -->
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

    <!-- jQuery UI 1.11.4 -->
    <script src="../assets/plugins/jQueryUI/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
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