 
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
} else if ($sub_page == "vk_sr") {
    include_once "vk_sr.php";
}

?>
