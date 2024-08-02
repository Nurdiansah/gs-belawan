<?php
ob_start();
session_start();
//include "cekuser.php";
include "../fungsi/koneksi.php";

if (!isset($_SESSION['username_blw']) || $_SESSION['level_blw'] != "anggaran") {
  header("location: ../index.php");
}

// biar langsung masuk ke URL pas login
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

$bulan = date("m");
$tahun = date("Y");

$jmlV = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='3' ");
$dataV = mysqli_fetch_assoc($jmlV);

$jmlAll = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE status_bkk='7'  ");
$dataAll = mysqli_fetch_assoc($jmlAll);

$queryNama =  mysqli_query($koneksi, "SELECT * from user WHERE username  = '$_SESSION[username_blw]'");
$rowNama = mysqli_fetch_assoc($queryNama);
$Nama = $rowNama['nama'];
$idUser = $rowNama['id_user'];
$pwUser = $rowNama['password'];

// query jumlah data verifikasi biaya non ops 
$query = mysqli_query($koneksi, "SELECT COUNT(id_bkk) AS jumlah FROM bkk WHERE MONTH(tgl_pengajuan) = '$bulan' AND YEAR(tgl_pengajuan) = '$tahun'");
$data = mysqli_fetch_assoc($query);

$queryKs = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon WHERE MONTH(tgl_kasbon) = '$bulan' AND YEAR(tgl_kasbon) = '$tahun'");
$dataKs = mysqli_fetch_assoc($queryKs);


$queryPo = mysqli_query($koneksi, "SELECT COUNT(id_po) AS jumlah FROM po WHERE MONTH(tgl_po) = '$bulan' AND YEAR(tgl_po) = '$tahun'");
$dataPo = mysqli_fetch_assoc($queryPo);

// print_r($dataPo);
// die;
// kasbon verifikasi 1 
$queryKV1 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon WHERE status_kasbon != '0' AND from_user = '0' ");
$dataKV1 = mysqli_fetch_assoc($queryKV1);

// kasbon verifikasi 2
$queryKV2 = mysqli_query($koneksi, "SELECT COUNT(id_kasbon) AS jumlah FROM kasbon WHERE status_kasbon != '0' AND from_user = '1' ");
$dataKV2 = mysqli_fetch_assoc($queryKV2);

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>E-FIN | GS</title>
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
                    <small>- Anggaran -</small>
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


          <li class="header">Master Data</li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-database"></i>
              <span>Master Data</span> <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <!-- <li><a href="index.php?p=input_anggaran_manual"><i class="fa fa-chevron-right"></i>Input Anggaran Manual</a></li> -->
              <!-- <li><a href="index.php?p=cost_center"><i class="fa fa-chevron-right"></i>Costcenter</a></li> -->
              <li><a href="index.php?p=program_kerja"><i class="fa fa-chevron-right"></i>Program Kerja</a></li>
              <li><a href="index.php?p=anggaran&sp=budget"><i class="fa fa-chevron-right"></i>Anggaran</a></li>
              <li><a href="index.php?p=header_subheader"><i class="fa fa-chevron-right"></i>Header/Sub Header</a></li>
          </li>
        </ul>
        </li>

        <!-- <li class="header">Monitoring Transaksi</li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart"></i>
            <span>Monitoring Transaksi</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.php?p=monitoring_mr"><i class="fa fa-check-square"></i>Transaksi MR</a>
            </li>
            <li><a href="index.php?p=monitoring_kasbon&sp=mk_purchasing"><i class="fa fa-check-square"></i>Transaksi Kasbon</a></li>
            <li><a href="index.php?p=monitoring_po"><i class="fa fa-check-square"></i>Transaksi PO</a></li>
            <li><a href="index.php?p=monitoring_so"><i class="fa fa-check-square"></i>Transaksi SO</a></li>
            <li><a href="index.php?p=monitoring_pettycash"><i class="fa fa-check-square"></i>Transaksi Petty Cash</a></li>
            <li><a href="index.php?p=monitoring_bu"><i class="fa fa-check-square"></i>Transaksi Biaya Umum</a></li>
          </ul>
        </li>

        <li class="header">Transaksi</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-check-square"></i>
            <span>Verifikasi</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <?php if ($data['jumlah'] >= 1) { ?>
              <span class="pull-right-container">
                <span class="label label-primary pull-right"><?= $data['jumlah']; ?></span>
              </span>
            <?php } ?>
            <li><a href="index.php?p=transaksi_bu"><i class="fa fa-calendar-check-o"></i>Biaya Umum</a></li>
            <li><a href="index.php?p=transaksi_kasbon"><i class="fa fa-money"></i>Kasbon</a></li>
            <li><a href="index.php?p=transaksi_po"><i class="fa fa-list"></i>PO</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart-o"></i>
            <span>Transaksi</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-calendar-check-o"></i>Biaya Umum</a></li>
            <li><a href="#"><i class="fa fa-money"></i>Kasbon</a></li>
            <li><a href="#"><i class="fa fa-list"></i>PO</a></li>
          </ul>
        </li> -->

        <li class="header">Laporan</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Laporan</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <!-- <li><a href="index.php?p=laporan-xls"><i class="fa fa-file-excel-o"></i>Export To Excel</a></li> -->
            <li><a href="index.php?p=laporan_lr&sp=lr_01"><i class="fa fa-file-excel-o"></i>Laba Rugi</a></li>
            <li><a href="index.php?p=laporan_rk&sp=rk_01"><i class="fa fa-file-excel-o"></i>Rencana Kerja</a></li>
            <li><a href="index.php?p=bkk_petty"><i class="fa fa-print"></i>BKK</a></li>
          </ul>
        </li>

        <li class="header">User Management</li>

        <li class="treeview">
          <a href="index.php?p=user">
            <i class="fa fa-users"></i>
            <span>User Management</span>
          </a>
        </li>
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

    <script>
      // $('.ktkPK').hide();
      // input anggaran (program kerja)
      $('.id_divisi').on('change', function() {
        let divisiId = this.value;
        let tahun = document.getElementById('tahun').value
        // if (divisiId == '') {
        // $('.ktkPK').hide();
        // } else {
        // $('.ktkPK').show();

        // console.log(divisiId);
        $.ajax({
          url: host + 'api/anggaran/getPK.php',
          data: {
            id: divisiId,
            tahun: tahun
          },
          method: 'post',
          dataType: 'json',
          success: function(data) {
            console.log(data);

            $('#id_programkerja').empty();
            $.each(data, function(i, value) {
              $('#id_programkerja').append($('<option>').text(value.kd_programkerja + " [" + value.nm_programkerja + "]").attr('value', value.id_programkerja));
            });
          }
        });
        // }
      });
      $('.id_programkerja').on('change', function() {
        let programKerjaId = this.value;
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

</body>

</html>