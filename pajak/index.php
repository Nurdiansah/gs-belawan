<?php
ob_start();
session_start();
//include "cekuser.php";
include "../fungsi/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['level'] != 'kordinator_pajak') {
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
$queryBno = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='4' ");
$dataBno = mysqli_fetch_assoc($queryBno);

// query data proses
$queryProsesbno = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_proses FROM bkk WHERE id_manager='$idUser' AND status_bkk >'1' ORDER BY tgl_bkk DESC  ");
$dataProsesbno = mysqli_fetch_assoc($queryProsesbno);

$query = mysqli_query($koneksi, "SELECT * FROM kasbon k WHERE k.status_kasbon = '1' ORDER BY k.id_kasbon DESC   ");

// query verifikasi kasbon 1
$queryVK1 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon WHERE from_user = '0' AND  status_kasbon = '2' AND sr_id IS NULL ");
$dataVK1 = mysqli_fetch_assoc($queryVK1);

// query verifikasi kasbon 2
$queryVK2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon WHERE from_user = '1' AND  status_kasbon = '2' ");
$dataVK2 = mysqli_fetch_assoc($queryVK2);

// query verifikasi kasbon 3
$queryVK3 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon WHERE from_user = '0' AND status_kasbon = '2' AND sr_id IS NOT NULL ");
$dataVK3 = mysqli_fetch_assoc($queryVK3);

$jvk = $dataVK1['jumlah'] + $dataVK2['jumlah'] + $dataVK3['jumlah'];

// po verifikasi
$queryPV = mysqli_query($koneksi, "SELECT COUNT(id) AS jumlah  FROM bkk_ke_pusat WHERE status_bkk = '0' AND pengajuan = 'PO' ");
$dataPV = mysqli_fetch_assoc($queryPV);

// query kasbon ditolak
$queryKasbonTolak = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon IN ('505') -- AND from_user = '0'
                                                  AND sr_id IS NULL");
$dataKasbonTolak = mysqli_fetch_assoc($queryKasbonTolak);

$queryTolakPO = mysqli_query($koneksi, "SELECT COUNT(id) as jumlah FROM bkk_final WHERE status_bkk = '202' AND pengajuan = 'PO'");
$dataTolakPO = mysqli_fetch_assoc($queryTolakPO);

$querySO = mysqli_query($koneksi, "SELECT COUNT(id_so) AS jumlah FROM so WHERE status = '2'");
$dataSO = mysqli_fetch_assoc($querySO);

$queryLPJ = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon = '707' AND sr_id IS NULL");
$dataLPJ = mysqli_fetch_assoc($queryLPJ);

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

  <script type="text/javascript" src="my.js"></script>

  <script src="../assets/plugins/jQuery/jquery.min.js"></script>
  <script type="text/javascript" src="../assets/bootstrap/js/my.js"></script>

  <style>
    .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 15px;
      margin-top: 10px;
      margin-left: 20px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: red;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 15px;
      width: 15px;
      left: 0px;
      bottom: 0px;
      background-color: black;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked+.slider {
      background-color: #2196F3;
    }

    input:focus+.slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
  </style>

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
                    <?php echo " $Nama " ?> Belawan
                    <small>- Pajak -</small>
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
              <?php if ($dataBno['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataBno['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_bno"><i class="fa fa-calendar-check-o"></i> Biaya Umum</a></li>
              <?php if ($jvk > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $jvk; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_kasbon&sp=vk_purchasing"><i class="fa fa-money"></i> Kasbon</a></li>
              <?php if ($dataPV['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataPV['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_po"><i class="fa fa-list"></i> Invoice PO</a></li>
              <?php if ($dataSO['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataSO['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_sr"><i class="fa fa-gear"></i> Service Order</a></li>
              <?php if ($dataLPJ['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $dataLPJ['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=verifikasi_sr"><i class="fa fa-tags"></i> LPJ</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-bar-chart-o"></i>
              <span>Transaksi</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-calendar-check-o"></i> Biaya Umum</a></li>
              <li><a href="#"><i class="fa fa-money"></i> Kasbon</a></li>
              <li><a href="#"><i class="fa fa-list"></i> PO</a></li>
              <li><a href="#"><i class="fa fa-gear"></i> Service Order</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-close"></i>
              <span>Ditolak</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-calendar-check-o"></i> Biaya Umum</a></li>
              <?php if ($dataKasbonTolak['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataKasbonTolak['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing"><i class="fa fa-money"></i> Kasbon</a></li>
              <?php if ($dataTolakPO['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataTolakPO['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_po"><i class="fa fa-list"></i> Invoice PO</a></li>
              <li><a href="#"><i class="fa fa-gear"></i> Service Order</a></li>
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