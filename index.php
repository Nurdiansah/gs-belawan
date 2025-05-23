<?php
session_start();
include "fungsi/koneksi.php";
include "fungsi/ceklogin.php";

// redirect ke sub domain

if ($_SERVER['SERVER_NAME'] == "103.167.112.237" || $_SERVER['HTTP_HOST'] == "103.167.112.237") {
    
    // No-cache headers (untuk halaman saat ini)
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    $subdomain = "https://efin.enc.co.id/gs-belawan";
    header("Location: " . $subdomain);
    
    // JavaScript untuk bersihkan cache/cookies (fallback jika redirect PHP gagal)
    echo '<script>
            // Hapus semua cookies
            document.cookie.split(";").forEach(function(c) {
                document.cookie = c.trim().split("=")[0] + "=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            });

            // Hapus cache storage (localStorage, sessionStorage, Service Worker)
            localStorage.clear();
            sessionStorage.clear();
            if (window.caches) {
                caches.keys().then(function(names) {
                    for (let name of names) caches.delete(name);
                });
            }

            // Force reload halaman tujuan (jika redirect PHP gagal)
            window.location.replace("' . $subdomain . '");
          </script>';
    exit();
}

// biar kalo udh masuk sebelumnya, klo ngakses URL dia masuk lagi
if (isset($_SESSION['username_blw']) || !empty($_SESSION['username_blw'])) {
	if ($_SESSION['level_blw'] == "anggaran") {
		header('Location: anggaran/index.php');
	} elseif ($_SESSION['level_blw'] == "direktur") {
		header('Location: direktur/index.php');
	} elseif ($_SESSION['level_blw'] == "kasir") {
		header('Location: kasir/index.php');
	} elseif ($_SESSION['level_blw'] == "manager") {
		header('Location: manager/index.php');
	} elseif ($_SESSION['level_blw'] == "manager_ga") {
		header('Location: manager_ga/index.php');
	} elseif ($_SESSION['level_blw'] == "manager_keuangan") {
		header('Location: manager_keuangan/index.php');
	} elseif ($_SESSION['level_blw'] == "kordinator_pajak") {
		header('Location: pajak/index.php');
	} elseif ($_SESSION['level_blw'] == "purchasing") {
		header('Location: purchasing/index.php');
	} elseif ($_SESSION['level_blw'] == "admin_divisi") {
		header('Location: user/index.php');
	}
}
// end

$err = "";

// biar klo login, pas masuk lngsung masuk ke URL (klo klik dari email)
if (isset($_GET['url']) && isset($_GET['lvl']) || isset($_GET['sp'])) {
	setcookie('url', $_GET['url'], time() + 1);
	setcookie('lvl', $_GET['lvl'], time() + 1);
	if (isset($_GET['sp'])) {
		setcookie('sp', $_GET['sp'], time() + 1);
	}
}

if (isset($_POST['login'])) {
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars(md5($_POST['password']));

	$query = "SELECT * FROM user WHERE username='$username' && password='$password'";
	$hasil = mysqli_query($koneksi, $query);


	if (!$hasil) {
		echo "ada error";
	}

	if (mysqli_num_rows($hasil) == 0) {
		$err = "
		<div class='row animate__animated animate__flipInY' style='margin-top: 15px';>
	       <div class='col-md-12'>
	        	<div class='box box-solid bg-red'>
	        		<div class='box-header'>
	        			<h3 class='box-title'>Login Gagal!</h3>
	        		</div>
	        		<div class='box-body'>
	        			<p>Username atau password yang anda masukan salah.</p>
	        		</div>
			    </div>
			 </div>
		 </div>
	</div>";
	} else {

		$row = mysqli_fetch_array($hasil);
		$_SESSION['id_usr_blw'] = $row['id_user'];
		$_SESSION['username_blw'] = $row['username'];
		$User = $_SESSION['username_blw'];
		$_SESSION['level_blw'] = $row['level'];

		date_default_timezone_set('Asia/Jakarta');
		$tanggal = date("Y-m-d H:i:s");

		$level = $_POST['level'];

		$queryLog = "INSERT INTO log_system (waktu, nama_user, keterangan) VALUES
									('$tanggal', '$User', 'login' );

									";
		mysqli_query($koneksi, $queryLog);

		if ($row['level'] == "kasir") {
			$_SESSION['login_blw'] = true;
			header("location:kasir/index.php");
		} else if ($row['level'] == "admin_divisi") {
			$_SESSION['login_blw'] = true;
			header("location:user/index.php");
		} else if ($row['level'] == "anggaran") {
			$_SESSION['login_blw'] = true;
			header("location:anggaran/index.php");
		} else if ($row['level'] == "kordinator_pajak") {
			$_SESSION['login_blw'] = true;
			header("location:pajak/index.php");
		} else if ($row['level'] == "manager") {
			$_SESSION['login_blw'] = true;
			header("location:manager/index.php");
		} else if ($row['level'] == "purchasing") {
			$_SESSION['login_blw'] = true;
			header("location:purchasing/index.php");
		} else if ($row['level'] == "manager_ga") {
			$_SESSION['login_blw'] = true;
			header("location:manager_ga/index.php");
		} else if ($row['level'] == "manager_keuangan") {
			$_SESSION['login_blw'] = true;
			header("location:manager_keuangan/index.php");
		} else if ($row['level'] == "direktur") {
			$_SESSION['login_blw'] = true;
			header("location:direktur/index.php");
		} else {
			$err = "
		<div class='row' style='margin-top: 15px';>
	       <div class='col-md-12'>
	        	<div class='box box-solid bg-red'>
	        		<div class='box-header'>
	        			<h3 class='box-title'>Login Gagal!</h3>
	        		</div>
	        		<div class='box-body'>
	        			<p>Anda salah memilih level login.</p>
	        		</div>
			    </div>
			 </div>
		 </div>
	</div>";
		}
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>E-FIN | GS - Login</title>
	<!-- Icon  -->
	<link rel="shortcut icon" type="image/icon" href="gambar/fav-gs.png">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/bootstrap/css/custom.css" rel="stylesheet">
	<link href="assets/dist/css/AdminLTE.min.css" rel="stylesheet">
	<link href="assets/plugins/iCheck/square/blue.css" rel="stylesheet">
	<link href="assets/fa/css/font-awesome.min.css" rel="stylesheet">
	<script src="assets/dist/js/warning.js" type="text/javascript"></script>

	<!-- Animate.css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body class="body">
	<div class="login-box">
		<div class="login-logo">
		</div><!-- /.login-logo -->
		<div class="login-box-body">
			<h3 class="text-center"></h3>
			<img src="gambar/logo-gs.jpeg" style="width: 250px; height: 120px;">
			<form method="post">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input type="text" class="form-control" placeholder="Username" name="username" value="<?= $_POST['username']; ?>" required />
					</div>
				</div>
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-unlock"></i></span>
						<input type="password" class="form-control" placeholder="Password" name="password" value="<?= $_POST['password']; ?>" required>
					</div>
				</div>
				<div class="form-group">
					<!-- <input type="hidden" value="kasir" name="level" required  />	             -->

					<!-- <div class="input-group col-md-7">          	
          		<span class="input-group-addon"><i class="fa fa-shield"></i></span>
	            <select class="form-control" name="level" required>            	
	            	<option value>[Pilih Level]</option>
	            	<option value="pemohon_kas">User</option>
					<option value="kasir">Kasir</option>
					<option value="tax">Tax</option>
	            </select>
            </div>             -->
				</div>
				<br>
				<div class="row">
					<div class="col-xs-12">
						<input type="submit" class="btn btn-primary btn-block btn-flat pull-right" value="Login" name="login" />

					</div><!-- /.col -->
				</div>
			</form>
			<br>

		</div>
		<?= $err; ?>
		<!-- /.login-box-body 
      <div class="row" style="margin-top: 15px;">
	       <div class="col-md-12">
	        	<div class="box box-solid bg-red">
	        		<div class="box-header">
	        			<h3 class="box-title">Gagal Login</h3>
	        		</div>
	        		<div class="box-body">
	        			<p>Username atau password salah</p>
	        		</div>
	        	</div>
	        </div>
        </div>
    </div>
	-->
		<!-- /.login-box -->

		<script src="assets/plugins/jQuery/jquery.min.js" type="text/javascript"></script>
		<script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>

</html>