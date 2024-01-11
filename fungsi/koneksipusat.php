<?php

$host = "localhost";
$username = "usr_system";
// $password = "";
$password = base64_decode("U3lzdGVtMTM1Nzk=");
$database = "gs";

$koneksiPusat = mysqli_connect($host, $username, $password, $database);

// $mysqli = new mysqli("localhost", "my_user", "my_password", "world");

if (!$koneksiPusat) {
    echo "Koneksi gagal " . mysqli_connect_error();
}
