 
<?php

$sub_page = isset($_GET['sp']) ? $_GET['sp'] : "";

if ($sub_page == 'pk_purchasing') {
    include_once "pk_purchasing.php";
} else if ($sub_page == "pk_user") {
    include_once "pk_user.php";
} else if ($sub_page == 'pnk_purchasing') {
    include_once "pnk_purchasing.php";
} else if ($sub_page == "pnk_user") {
    include_once "pnk_user.php";
} else if ($sub_page == 'vlk_purchasing') {
    include_once "vlk_purchasing.php";
} else if ($sub_page == "vlk_user") {
    include_once "vlk_user.php";
} else if ($sub_page == "pk_sr") {
    include_once "pk_sr.php";
} else if ($sub_page == "vlk_sr") {
    include_once "vlk_sr.php";
} else if ($sub_page == "pnk_sr") {
    include_once "pnk_sr.php";
}

?>
 
 