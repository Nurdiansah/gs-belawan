<?php

session_start();

if (!isset($_SESSION['login_blw'])) {
	header("location:../index.php");
}

if ($_SESSION['level_blw'] != "upengadaan") {
	header("location:../index.php");
}
