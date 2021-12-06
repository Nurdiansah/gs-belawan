 
<?php

$sub_page = isset($_GET['sp']) ? $_GET['sp'] : "";

if ($sub_page == 'vk_purchasing') {
    include_once "vk_purchasing.php";
} else if ($sub_page == "vk_user") {
    include_once "vk_user.php";
} else if ($sub_page == "tolak_purchasing") {
    include_once "tolak_purchasing.php";
} else if ($sub_page == "tolak_user") {
    include_once "tolak_user.php";
} else if ($sub_page == "kp_user") {
    include_once "kp_user.php";
} else if ($sub_page == "kp_purchasing") {
    include_once "kp_purchasing.php";
} else if ($sub_page == "kt_purchasing") {
    include_once "kt_purchasing.php";
} else if ($sub_page == "kt_user") {
    include_once "kt_user.php";
} else if ($sub_page == "vk_sr") {
    include_once "vk_sr.php";
} else if ($sub_page == "tolak_sr") {
    include_once "tolak_sr.php";
}
?>
 
 