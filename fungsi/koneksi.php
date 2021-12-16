<?php

$host = "localhost";
$username = "root";
$password = "";
// $password = "kambingjawa";
$database = "gs_belawan";

$koneksi = mysqli_connect($host, $username, $password, $database);

// $mysqli = new mysqli("localhost", "my_user", "my_password", "world");

if (!$koneksi) {
	echo "Koneksi gagal " . mysqli_connect_error();
}
