<?php

if (isset($_SESSION['login_blw'])) {
	if ($_SESSION['level_blw'] == "unit_pelayanan") {
		header("location:checker/index.php");
	} else if ($_SESSION['level_blw'] == "kasir") {
		header("location:kasir/index.php");
	} else {
		header("location:index.php");
	}
}
