 
<?php

$sub_page = isset($_GET['sp']) ? $_GET['sp'] : "";

if ($sub_page == 'ditolak_kasbon') {
    include_once "ditolak_kasbon.php";
} else if ($sub_page == "ditolak_po") {
    include_once "ditolak_po.php";
} else if ($sub_page == "ditolak_kasbon_sr") {
    include_once "ditolak_kasbon_sr.php";
} else if ($sub_page == "ditolak_so") {
    include_once "ditolak_so.php";
} else if ($sub_page == "lpj_kmr") {
    include_once "lpj_kmr.php";
} else if ($sub_page == "lpj_ksr") {
    include_once "lpj_ksr.php";
} else if ($sub_page == "vlk_sr") {
    include_once "vlk_sr.php";
} else if ($sub_page == "proses_kasbon_mr") {
    include_once "proses_kasbon_mr.php";
} else if ($sub_page == "proses_kasbon_sr") {
    include_once "proses_kasbon_sr.php";
}
