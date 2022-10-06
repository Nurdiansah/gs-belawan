<?php

$host = "localhost";
$username = "usr_gs_jakarta";
// $password = "";
$password = "K@mbingjawir";
$database = "gs";

$koneksiPusat = mysqli_connect($host, $username, $password, $database);

// $mysqli = new mysqli("localhost", "my_user", "my_password", "world");

if (!$koneksiPusat) {
    echo "Koneksi gagal " . mysqli_connect_error();
}
