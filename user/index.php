<?php
ob_start();
session_start();
//include "cekuser.php";
include "../fungsi/koneksi.php";

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != 'admin_divisi') {
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

$tahun = date('Y');
// $query = mysqli_query($koneksi, "SELECT COUNT(id_jenis) AS jumlah FROM jenis_barang ");
// $data = mysqli_fetch_assoc($query);
$queryNama =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$dataUser = mysqli_fetch_assoc($queryNama);
$Nama = $dataUser['nama'];
$idUser = $dataUser['id_user'];
$idManager = $dataUser['id_manager'];
$idDivisi = $dataUser['id_divisi'];

$queryTolak = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_ditolak FROM bkk WHERE status_bkk IN ('101', '202', '303') AND id_divisi='$idDivisi' ");
$dataTolak = mysqli_fetch_assoc($queryTolak);
// 
$querySelesai = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_Selesai FROM bkk WHERE status_bkk='9' AND id_divisi='$idDivisi' ");
$dataSelesai = mysqli_fetch_assoc($querySelesai);


$queryProses = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah_proses FROM bkk WHERE status_bkk IN (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 202, 303, 404, 17) AND id_divisi='$idDivisi' ");
$dataProses = mysqli_fetch_assoc($queryProses);

// query data Verifikasi Pajak
$queryBno = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='3' ");
$dataBno = mysqli_fetch_assoc($queryBno);

# PAKE YG INI HASILNYA JADI DOUBLE / NGIKUTIN detail_biayaops, tapi klo statusnya udh sampe kasir bener
// query MR
$queryPM = mysqli_query($koneksi, "SELECT COUNT(b.kd_transaksi) AS jumlah_proses FROM biaya_ops b     
                                                                                JOIN detail_biayaops dbo                                                                       
                                                                                ON b.kd_transaksi = dbo.kd_transaksi
                                                                                WHERE b.status_biayaops <= '2' AND dbo.status <= '2' AND b.status_biayaops != '0'  AND b.id_divisi = '$idDivisi'  ");

// $queryPM = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) as jumlah_proses FROM biaya_ops
//                                     WHERE status_biayaops <= '2' AND status_biayaops != '0' AND id_divisi = '$idDivisi'");

$dataPM = mysqli_fetch_assoc($queryPM);

// query MT
$queryTM = mysqli_query($koneksi, "SELECT COUNT(kd_transaksi) AS jumlah_proses FROM biaya_ops WHERE status_biayaops = '0' AND id_divisi='$idDivisi'");
$dataTM = mysqli_fetch_assoc($queryTM);

$queryKC = mysqli_query($koneksi, "SELECT COUNT(k.id_kasbon) AS jumlah 
                                            FROM kasbon k                                            
                                            JOIN detail_biayaops dbo
                                            ON k.id_dbo = dbo.id                                            
                                            WHERE k.status_kasbon = 0
                                            AND from_user = 1
                                            AND dbo.id_divisi = '$idDivisi'
                                            ");
$dataKC = mysqli_fetch_assoc($queryKC);

// Pettycash proses
$queryPTP = mysqli_query($koneksi, "SELECT COUNT(id_pettycash) AS jumlah FROM transaksi_pettycash tp   
                                            JOIN anggaran a
                                            ON tp.id_anggaran = a.id_anggaran   
                                            WHERE tp.id_divisi = '$idDivisi'
                                            AND status_pettycash IN (1, 2, 3, 4, 10, 202)
                                            -- AND `from` = 'user'
                                            ORDER BY tp.created_pettycash_on DESC
                                    ");
$dataPTP = mysqli_fetch_assoc($queryPTP);

// kasbon proses
// $queryKP = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon k
//                                                                       JOIN detail_biayaops dbo 
//                                                                       ON k.id_dbo = dbo.id  
//                                                                       WHERE dbo.id_divisi = '$idDivisi' AND k.status_kasbon BETWEEN 1 AND 7 ");
// $dataKP = mysqli_fetch_assoc($queryKP);

// kasbon proses 1
$queryKP1 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon k
                                                                      JOIN detail_biayaops dbo 
                                                                      ON k.id_dbo = dbo.id  
                                                                      WHERE dbo.id_divisi = '$idDivisi'
                                                                      AND (k.status_kasbon IN (2, 3, 4, 5, 6, 7, 202, 606) OR k.status_kasbon IS NULL)
                                                                      AND k.from_user = '0'
                                                                      AND sr_id IS NULL");
$dataKP1 = mysqli_fetch_assoc($queryKP1);

// kasbon proses 2
$queryKP2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon k
                                                                      JOIN detail_biayaops dbo 
                                                                      ON k.id_dbo = dbo.id  
                                                                      WHERE dbo.id_divisi = '$idDivisi' AND k.status_kasbon  BETWEEN 1 AND 9 AND k.from_user = '1'");
$dataKP2 = mysqli_fetch_assoc($queryKP2);

$dataKP = $dataKP1['jumlah'] + $dataKP2['jumlah'];

// kasbon transaksi
// $queryKT = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon k
//                                                                        JOIN biaya_ops bo
//                                                                        ON k.kd_transaksi = bo.kd_transaksi
//                                                                        WHERE id_divisi = '$idDivisi' AND status_kasbon = '8'");
$queryKT = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah
                                    FROM kasbon
                                    JOIN detail_biayaops
                                        ON id = id_dbo
                                    WHERE id_divisi = '$idDivisi'
                                    AND status_kasbon = '10'");

$dataKT = mysqli_fetch_assoc($queryKT);

// po proses
$queryPP = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah FROM po p 
                                                                        JOIN biaya_ops bo
                                                                        ON p.kd_transaksi = bo.kd_transaksi
                                                                        JOIN detail_biayaops dbo
                                                                        ON p.id_dbo = dbo.id
                                                                        JOIN divisi d
                                                                        ON d.id_divisi = bo.id_divisi
                                                                        WHERE (status_po BETWEEN 1 AND 7
                                                                        OR status_po IN ('', '0', '101', '202', '303', '404') OR status_po IS NULL)
                                                                        AND bo.id_divisi = '$idDivisi' ");
$dataPP = mysqli_fetch_assoc($queryPP);

// KASBON

//----------kasbon user
$queryTKU = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon k                                            
                                                                      JOIN detail_biayaops dbo
                                                                      ON k.id_dbo = dbo.id
                                                                      JOIN divisi d
                                                                      ON d.id_divisi = dbo.id_divisi                                            
                                                                      WHERE k.status_kasbon IN ('101', '202', '303', '707', '606')
                                                                      AND from_user = '1'
                                                                      AND dbo.id_divisi = '$idDivisi'");

$dataTKU = mysqli_fetch_assoc($queryTKU);

//----------kasbon purchasing
$queryTKP = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon k
                                    JOIN detail_biayaops db
                                      ON id = id_dbo
                                    WHERE status_kasbon = '0'
                                    AND from_user = '0'
                                    AND id_divisi = '$idDivisi'");
$dataTKP = mysqli_fetch_assoc($queryTKP);

//----------kasbon SR
$queryTolakTKS = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) as jumlah  FROM kasbon k
                                          INNER JOIN sr sr
                                              ON id_sr = sr_id
                                          WHERE status_kasbon IN ('202')
                                          AND from_user = '0'
                                          AND id_divisi = '$idDivisi'
                                          ORDER BY id_kasbon DESC");
$dataTolakTKS = mysqli_fetch_assoc($queryTolakTKS);

$totalTolakKasbon = $dataTKU['jumlah'] + $dataTKP['jumlah'] + $dataTolakTKS['jumlah'];
// END KASBON

$queryTolakPO = mysqli_query($koneksi, "SELECT COUNT(id_po) as jumlah
                                        FROM po po
                                        JOIN detail_biayaops db
                                          ON db.id = po.id_dbo
                                        WHERE status_po = '0'
                                        AND id_divisi = '$idDivisi'");
$dataTolakPO = mysqli_fetch_assoc($queryTolakPO);

$queryTolakSR = mysqli_query($koneksi, "SELECT COUNT(id_sr) as jumlah FROM sr WHERE status IN ('101', '202') AND id_divisi = '$idDivisi'");
$dataTolakSR = mysqli_fetch_assoc($queryTolakSR);

$queryTolakSO = mysqli_query($koneksi, "SELECT COUNT(id_so) as jumlah FROM so WHERE status IN ('202') AND id_divisi = '$idDivisi'");
$dataTolakSO = mysqli_fetch_assoc($queryTolakSO);

$totalTolakSRO = $dataTolakSR['jumlah'] + $dataTolakSO['jumlah'];

$queryProsesSR = mysqli_query($koneksi, "SELECT COUNT(id_sr) as jumlah
                                          FROM sr s
                                          JOIN anggaran a
                                              ON a.id_anggaran = s.id_anggaran
                                          JOIN divisi d
                                              ON d.id_divisi = s.id_divisi
                                          WHERE status IN ('1', '2', '101', '202')
                                          AND s.id_divisi = '$idDivisi'");
$dataProsesSR = mysqli_fetch_assoc($queryProsesSR);

$queryProsesSO = mysqli_query($koneksi, "SELECT COUNT(id_so) as jumlah
                                          FROM so s
                                          JOIN anggaran a
                                              ON a.id_anggaran = s.id_anggaran
                                          JOIN divisi d
                                              ON d.id_divisi = s.id_divisi
                                          WHERE status IN ('1', '2', '3', '4', '5', '202', '303', '404', '505', '606')
                                          AND s.id_divisi = '$idDivisi'");
$dataProsesSO = mysqli_fetch_assoc($queryProsesSO);

$totalSRSO = $dataProsesSR['jumlah'] + $dataProsesSO['jumlah'];

$queryTolakBKM = mysqli_query($koneksi, "SELECT COUNT(id_bkm) as jumlah FROM bkm WHERE status_bkm IN ('101', '202', '303') AND id_divisi = '$idDivisi'");
$dataTolakBKM = mysqli_fetch_assoc($queryTolakBKM);

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
  <link rel="stylesheet" href="../assets/plugins/select2/select2.min.css">

  <script src="../assets/plugins/jQuery/jquery.min.js"></script>
  <!-- <script src="../assets/plugins/jQuery/jquery.js"></script> -->
  <script type="text/javascript" src="my.js"></script>

  <!-- Resources -->
  <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
  <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
  <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

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
          <li class="treeview">
            <a href="?pg=dashboard">
              <i class="fa fa-home"></i> <span>Home</span>
            </a>
          </li>

          <!-- KONDISI MENU BKM, HANYA DIVISI (ADMIN BILLING, KALIBARU, PAJAK, DIGUL, DAN MEDAN BILLING & KASIR ) -->
          <?php if ($idDivisi == "3" || $idDivisi == "9" || $idDivisi == "18") { ?>
            <li class="header">Pendapatan</li>
            <li class="treeview">
              <a href="">
                <i class="fa fa-print"></i>
                <span>BKM</span> <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="index.php?p=buat_bkm"><i class="fa fa-edit"></i> Create</a></li>
                <!-- <?php if ($prosesBKM['jumlah'] > 0) { ?> -->
                <span class="pull-right-container">
                  <span class="label label-info pull-right"></span>
                </span>
                <!-- <?php } ?> -->
                <li><a href="index.php?p=proses_bkm"><i class="fa fa-spinner"></i> Proses</a></li>
                <?php if ($dataTolakBKM['jumlah'] > 0) { ?>
                  <span class="pull-right-container">
                    <span class="label label-danger pull-right"><?= $dataTolakBKM['jumlah']; ?></span>
                  </span>
                <?php } ?>
                <li><a href="index.php?p=ditolak_bkm"><i class="fa fa-close"></i> Ditolak</a></li>
                <!-- <?php if ($dataTolakBKM['jumlah'] > 0) { ?> -->
                <span class="pull-right-container">
                  <span class="label label-success pull-right"></span>
                </span>
                <!-- <?php } ?> -->
                <li><a href="index.php?p=transaksi_bkm"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
              </ul>
            </li>
          <?php } ?>
          <!-- END -->

          <li class="header">Transaksi</li>
          <li class="treeview">
            <a href="">
              <i class="fa fa-calendar-check-o"></i>
              <span>Biaya Umum</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?p=buat_biayanonops"><i class="fa fa-edit"></i> Create</a></li>
              <!-- <li><a href="#" onClick="alert('Untuk Biaya Umum saat ini bisa langsung melalui Kasir\n\nDengan memberikan Invoice & Kode Anggarannya.')"><i class="fa fa-edit"></i> Create</a></li> -->
              <?php if ($dataProses['jumlah_proses'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataProses['jumlah_proses']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=proses_biayanonops"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($dataTolak['jumlah_ditolak'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataTolak['jumlah_ditolak']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_biayanonops"><i class="fa fa-close"></i> Ditolak</a></li>
              <?php if ($dataSelesai['jumlah_Selesai'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataSelesai['jumlah_Selesai']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=lihat_bno"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-folder-open-o"></i>
              <span>Material Request</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?p=buat_mr"><i class="fa fa-edit"></i> Create</a></li>
              <?php if ($dataPM['jumlah_proses'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataPM['jumlah_proses']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=proses_mr"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($dataTM['jumlah_proses'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataTM['jumlah_proses']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=tolak_mr"><i class="fa fa-close"></i> Ditolak</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-gear"></i>
              <span>Service Request</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="#" onClick="alert('Untuk Service Request bisa melalui Material Request\n\nAtau lebih lanjut bisa hubungi ke Pak Amos.')"><i class="fa fa-edit"></i> Create</a></li>
              <li><a href="index.php?p=proses_sr"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($totalTolakSRO > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $totalTolakSRO; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_sr"><i class="fa fa-close"></i> Ditolak</a></li>
              <li><a href="index.php?p=transaksi_sr"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-dollar"></i>
              <span>Petty Cash</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?p=buat_petty"><i class="fa fa-edit"></i> Create</a></li>
              <?php if ($dataPTP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-warning pull-right"><?= $dataPTP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=proses_petty"><i class="fa fa-spinner"></i> Proses</a></li>
              <li><a href="index.php?p=transaksi_pettycash"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-money"></i>
              <span>Kasbon</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataKC['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataKC['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=buat_kasbon"><i class="fa fa-edit"></i> Create</a></li>
              <?php if ($dataKP['jumlah'] >= 1) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataKP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=kasbon_proses&sp=kp_purchasing"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($totalTolakKasbon  > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $totalTolakKasbon; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_kasbon&sp=tolak_purchasing"><i class="fa fa-close"></i> Ditolak</a></li>
              <?php if ($dataKT['jumlah']  > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-success pull-right"><?= $dataKT['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=kasbon_transaksi"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="">
              <i class="fa fa-list"></i>
              <span>PO</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php if ($dataPP['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-info pull-right"><?= $dataPP['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=po_proses"><i class="fa fa-spinner"></i> Proses</a></li>
              <?php if ($dataTolakPO['jumlah'] > 0) { ?>
                <span class="pull-right-container">
                  <span class="label label-danger pull-right"><?= $dataTolakPO['jumlah']; ?></span>
                </span>
              <?php } ?>
              <li><a href="index.php?p=ditolak_po"><i class="fa fa-close"></i> Ditolak</a></li>
              <li><a href="index.php?p=transaksi_po"><i class="fa fa-bar-chart-o"></i> Transaksi</a></li>
            </ul>
          </li>

          <li class="header">Laporan</li>

          <li class="treeview">
            <a href="index.php?p=laporan_anggaran">
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

    <!-- script custom -->
    <script>
      $('.kotakAnggaran').hide();

      $('.programkerja_id').on('change', function() {
        let programKerjaId = this.value;

        if (programKerjaId == '') {

          $('.kotakAnggaran').hide();

        } else {

          $('.kotakAnggaran').show();

          $.ajax({
            url: host + 'api/anggaran/getanggaranprogramkerja.php',
            data: {
              id: programKerjaId
            },
            method: 'post',
            dataType: 'json',
            success: function(data) {
              // console.log(data);

              $('#id_anggaran').empty();
              $.each(data, function(i, value) {
                // $('#id_anggaran').append($('<option>').text(value.nm_item).attr('value', value.id_anggaran));
                $('#id_anggaran').append($('<option>').text(value.kd_anggaran + " [" + value.nm_item + "]").attr('value', value.id_anggaran));
              });

            }
          });

        }

      });

      $('.id_anggaran').on('change', function() {
        let anggaranId = this.value;
      });
    </script>

    <!-- option anggaran edit -->
    <script>
      // $('.kotakAnggaran').hide();
      $('.programkerja_id_edit').on('change', function() {
        let programKerjaId = this.value;
        // if (programKerjaId == '') {
        //   $('.kotakAnggaran').hide();
        // } else {
        $('.kotakAnggaran_edit').show();
        $.ajax({
          url: host + 'api/anggaran/getanggaranprogramkerja_edit.php',
          data: {
            id: programKerjaId
          },
          method: 'post',
          dataType: 'json',
          success: function(data) {

            $('#id_anggaran_edit').empty();
            $.each(data, function(i, value) {
              $('.id_anggaran_edit').append($('<option>').text(value.nm_item + " - [" + value.program_kerja + "]").attr('value', value.id_anggaran));
            });
          }
        });
        // }
      });

      $('.id_anggaran_edit').on('change', function() {
        let anggaranId = this.value;
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