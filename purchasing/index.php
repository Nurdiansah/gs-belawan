<?php
ob_start();
session_start();
//include "cekuser.php";
include "../fungsi/koneksi.php";

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != 'purchasing') {
  header("location: ../index.php");
}

// biar langsung masuk ke URL pas login klik dari email
if (isset($_COOKIE['url']) && $_SESSION['level_blw'] == $_COOKIE['lvl']) {
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
$queryNama =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];
$idUser = $rowNama['id_user'];
$idDivisi = $rowNama['id_divisi'];

$queryTolak = mysqli_query($koneksi, "SELECT * FROM kasbon WHERE status_kasbon IN ('101', '202')");
$dataTolak = mysqli_fetch_assoc($queryTolak);
// 
$querySelesai = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_Selesai FROM bkk WHERE status_bkk='9' AND id_divisi='$idDivisi' ");
$dataSelesai = mysqli_fetch_assoc($querySelesai);


$queryProses = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_proses FROM bkk WHERE status_bkk<='8' AND status_bkk>='1' AND id_divisi='$idDivisi' ");
$dataProses = mysqli_fetch_assoc($queryProses);

// query data Verifikasi Pajak
$queryBno = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='3' ");
$dataBno = mysqli_fetch_assoc($queryBno);

// query MR
$queryPM = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah_proses FROM biaya_ops WHERE status_biayaops < '8' AND status_biayaops >= '1' AND id_divisi='$idDivisi'");
$dataPM = mysqli_fetch_assoc($queryPM);

// query MT
$queryTM = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah_proses FROM biaya_ops WHERE status_biayaops = '0' AND id_divisi='$idDivisi'");
$dataTM = mysqli_fetch_assoc($queryTM);

// query list order  

$queryLl = mysqli_query($koneksi, "SELECT COUNT(bo.kd_transaksi) AS jumlah FROM biaya_ops bo
                                            JOIN detail_biayaops dbo
                                            ON bo.kd_transaksi = dbo.kd_transaksi
                                            WHERE (dbo.status = '2' AND bo.status_biayaops = '2')
                                            ");
$dataLl = mysqli_fetch_assoc($queryLl);

// count ditolak
$queryTolak = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) as jumlah
                                      FROM detail_biayaops
                                      WHERE status = '2'
                                      AND alasan_penolakan IS NOT NULL");

$dataTolak = mysqli_fetch_assoc($queryTolak);

// query kasbon proses 
$queryKp = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon k
                                    JOIN biaya_ops bo
                                      ON k.kd_transaksi = bo.kd_transaksi
                                    JOIN detail_biayaops dbo
                                      ON k.id_dbo = dbo.id                                  
                                    WHERE status_kasbon IN (1, 2, 3, 4, 5, 6, 7, 101, 202, 303, 404, 808) ");
$dataKp = mysqli_fetch_assoc($queryKp);

$queryKs = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon k
                                    JOIN sr sr
                                        ON id_sr = sr_id
                                    JOIN divisi d
                                        ON divisi_id = d.id_divisi
                                    WHERE status_kasbon IN (1, 2, 3, 4, 5, 101, 303, 404)");
$dataKs = mysqli_fetch_assoc($queryKs);

$totalKpKs = $dataKp['jumlah'] + $dataKs['jumlah'];

// Lpj kasbon
// query MT
$queryKl = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon WHERE status_kasbon IN ('8', '707') AND from_user = '0' AND sr_id IS NULL ");
$dataKl = mysqli_fetch_assoc($queryKl);

$queryKl2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah  FROM kasbon  WHERE status_kasbon = '8' AND sr_id IS NOT NULL ");
$dataKl2 = mysqli_fetch_assoc($queryKl2);

$jumlahKl = $dataKl['jumlah'] + $dataKl2['jumlah'];


// submit po
$queryQtt = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah  FROM po WHERE status_po = '1' ");
$dataQtt = mysqli_fetch_assoc($queryQtt);

// Po proses
$queryPOP = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah FROM po WHERE status_po NOT BETWEEN 6 AND 10 AND status_po NOT IN (0, 1, 88)");
$dataPOP = mysqli_fetch_assoc($queryPOP);

// Po proses
$queryPOR = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah FROM po WHERE status_po BETWEEN 6 AND 8 ");
$dataPOR = mysqli_fetch_assoc($queryPOR);


// po outstanding
$queryOpo = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah  FROM po WHERE status_po = '9' ");
$dataOpo = mysqli_fetch_assoc($queryOpo);

$query = mysqli_query($koneksi, "SELECT * 
                                            FROM kasbon k
                                            JOIN biaya_ops bo
                                            ON k.kd_transaksi = bo.kd_transaksi
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id
                                            JOIN divisi d
                                            ON d.id_divisi = bo.id_divisi                                            
                                            WHERE k.status_kasbon = '6'
                                            ORDER BY k.id_kasbon DESC   ");


// query kasbon ditolak
$queryKasbonTolak = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon IN ('202', '606') AND from_user = '0'");
$dataKasbonTolak = mysqli_fetch_assoc($queryKasbonTolak);

$queryTolakPO = mysqli_query($koneksi, "SELECT COUNT(id_po) as jumlah FROM po WHERE status_po = '101'");
$dataTolakPO = mysqli_fetch_assoc($queryTolakPO);

$totalTolakMR = $dataKasbonTolak['jumlah'] + $dataTolakPO['jumlah'];

$querySK = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah FROM kasbon WHERE status_kasbon IS NULL AND from_user = 0");
$dataSK = mysqli_fetch_assoc($querySK);

$querySPO = mysqli_query($koneksi, "SELECT COUNT(id_po) as jumlah FROM po WHERE status_po IS NULL");
$dataSPO = mysqli_fetch_assoc($querySPO);

$queryVS =  mysqli_query($koneksi, "SELECT COUNT(id_sr) as jumlah FROM sr WHERE status = '2' ");
$dataVS = mysqli_fetch_assoc($queryVS);

$queryTolakSO  = mysqli_query($koneksi, "SELECT COUNT(id_so) as jumlah FROM so WHERE status IN ('303', '404')");
$dataTolakSO = mysqli_fetch_assoc($queryTolakSO);

$queryTolakKSR = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah
                                          FROM kasbon k
                                          INNER JOIN sr sr
                                              ON id_sr = sr_id
                                          WHERE status_kasbon IN ('303', '404', '0')
                                          AND from_user = '0'");

$dataTolakKSR = mysqli_fetch_assoc($queryTolakKSR);

$totalSR = $dataTolakKSR['jumlah'] + $dataTolakSO['jumlah'];

$querySubmitSO = mysqli_query($koneksi, "SELECT COUNT(id_so) as jumlah FROM so WHERE status IS NULL");
$dataSubmitSO = mysqli_fetch_assoc($querySubmitSO);

$queryPetty = mysqli_query($koneksi, "SELECT COUNT(id_pettycash) as jumlah FROM transaksi_pettycash 
                                            WHERE status_pettycash IN (1, 2, 10, 202)
                                            AND `from` IN ('mr', 'sr')");
$dataPetty = mysqli_fetch_assoc($queryPetty);

$queryLPJPetty = mysqli_query($koneksi, "SELECT COUNT(id_pettycash) as jumlah FROM transaksi_pettycash 
                                          WHERE status_pettycash IN (3, 101)
                                          AND `from` IN ('mr', 'sr')");
$dataLPJPetty = mysqli_fetch_assoc($queryLPJPetty);

$queryProsesSO = mysqli_query($koneksi, "SELECT COUNT(id_so) as jumlah FROM so WHERE status IN ('1', '2', '3', '4', '5', '202', '303', '404', '505', '606')");
$dataProsesSO = mysqli_fetch_assoc($queryProsesSO);


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GS | SYSTEM</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/icon" href="../gambar/fav-gs.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link href="../assets/bootstrap/css/custom.css" rel="stylesheet">
  <!-- Link Chosen -->

  <!-- baru -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css" />
  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css" rel="stylesheet" type="text/css" /> -->
  <!-- <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> -->

  <!-- Include jQuery Timeline CSS -->
  <link href="../assets/dist/js/jquery.roadmap.min.css" rel="stylesheet" type="text/css" />

  <!--  -->

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
  <!-- Select2 -->
  <!-- <link rel="stylesheet" href="../assets/plugins/select2/select2.min.css"> -->
  <!-- Include jQuery Timeline CSS -->
  <link href="../assets/dist/js/jquery.roadmap.min.css" rel="stylesheet" type="text/css" />

  <script src="../assets/plugins/jQuery/jquery.min.js"></script>
  <!-- <script src="../assets/plugins/jQuery/jquery.js"></script> -->
  <script type="text/javascript" src="my.js"></script>

  <!-- chart -->

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

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

  <style>
    /* untk kontak dibawah */
    .kontak-email {
      background-color: cadetblue;
      color: white;
      padding: 10px;
      text-decoration: none;
      font-size: 16px;
      border-radius: 5px;
    }

    .kontak-email {
      position: fixed;
      right: 50px;
      bottom: 10px;
    }

    .kontak-wa {
      background-color: #25D366;
      color: white;
      padding: 10px;
      text-decoration: none;
      font-size: 16px;
      border-radius: 5px;
    }

    .kontak-wa {
      position: fixed;
      right: 10px;
      bottom: 10px;
    }
  </style>

</head>

<body class="hold-transition skin-red sidebar-collapse sidebar-mini">
  <div class="wrapper">

    <header class="main-header col">
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
                    <small>- ADMIN DIVISI -</small>
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
              <i class="fa fa-home"></i> <span>Home</span>
            </a>
          </li>
          <!--  -->
          <li class="header">Master Data</li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-database"></i>
              <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <!-- <li><a href="index.php?p=lihat_anggaran&act=view"><i class="fa fa-chevron-right"></i>Anggaran</a></li>
              <li><a href="index.php?p=tambah_anggaran"><i class="fa fa-chevron-right"></i>Tambah Anggaran</a></li> -->
              <li><a href="index.php?p=supplier"><i class="fa fa-chevron-right"></i>Supplier</a></li>
          </li>
        </ul>
        </li>

        <li class="header">Transaksi</li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-folder-open-o"></i>
            <span>Material Request</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <?php if ($dataLl['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataLl['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=list_mr"><i class="fa fa-send"></i> Bidding Process</a></li>
            <?php if ($totalTolakMR > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-danger pull-right"><?= $totalTolakMR; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=ditolak_mr&sp=ditolak_kasbon"><i class="fa fa-close"></i> Ditolak</a></li>
            <li><a href="index.php?p=proses_mr"><i class="fa fa-spinner"></i> Proses</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-file-o"></i>
            <span>Service Request</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <?php if ($dataVS['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-warning pull-right"><?= $dataVS['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="#index.php?p=verifikasi_sr"><i class="fa fa-check-square"></i> Verifikasi</a></li>
            <?php if ($dataSubmitSO['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataSubmitSO['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="#index.php?p=submit_kembali_so"><i class="fa fa-refresh"></i> Submit Kembali SO</a></li>
            <?php if ($totalSR > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-danger pull-right"><?= $totalSR; ?></span>
              </span>
            <?php } ?>
            <li><a href="#index.php?p=ditolak_sr&sp=ditolak_kasbon_sr"><i class="fa fa-close"></i> Ditolak</a></li>
            <?php if ($dataProsesSO['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataProsesSO['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="#index.php?p=proses_sr"><i class="fa fa-spinner"></i> Proses</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="">
            <i class="fa fa-dollar"></i>
            <span>Petty Cash</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <?php if ($dataPetty['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataPetty['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=proses_petty"><i class="fa fa-spinner"></i> Petty Cash</a></li>
            <?php if ($dataLPJPetty['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-danger pull-right"><?= $dataLPJPetty['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=lpj_petty"><i class="fa fa-tags"></i> LPJ</a></li>
            <li><a href="index.php?p=transaksi_pettycash"><i class="fa fa-bar-chart-o"></i> Riwayat</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="">
            <i class="fa fa-money"></i>
            <span>Kasbon</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <?php if ($dataSK['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataSK['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=submit_kasbon"><i class="fa fa-refresh"></i> Submit Kembali Kasbon</a></li>
            <?php if ($dataKp['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataKp['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=kasbon_process&sp=proses_kasbon_mr"><i class="fa fa-spinner"></i> Proses</a></li>
            <?php if ($jumlahKl > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-warning pull-right"><?= $jumlahKl; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=lpj_kasbon&sp=lpj_kmr"><i class="fa fa-tags"></i> LPJ Kasbon</a></li>
            <li><a href="index.php?p=transaksi_kasbon"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="">
            <i class="fa fa-list"></i>
            <span>PO</span> <i class="fa fa-angle-left pull-right"></i>
          </a>

          <ul class="treeview-menu">
            <?php if ($dataQtt['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataQtt['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=submit_po"><i class="fa fa-share"></i> Submit Quotation</a></li>
            <?php if ($dataSPO['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-info pull-right"><?= $dataSPO['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=submit_kembali_po"><i class="fa fa-share"></i> Submit PO</a></li>
            <li><a href="index.php?p=po_proses"><i class="fa fa-spinner"></i> Proses</a></li>
            <?php if ($dataOpo['jumlah'] > 0) { ?>
              <span class="pull-right-container">
                <span class="label label-danger pull-right"><?= $dataOpo['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=po_outstanding"><i class="fa fa-hourglass-1"></i> Outstanding</a></li>
            <li><a href="index.php?p=transaksi_po"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
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

    <!-- Timeline-->
    <!-- Include jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script> -->

    <!-- Include jQuery Timeline Plugin -->
    <script src="../assets/dist/js/jquery.roadmap.js" type="text/javascript"></script>

</body>

</html>