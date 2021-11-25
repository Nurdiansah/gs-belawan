 
<?php

$sub_page = isset($_GET['sp']) ? $_GET['sp'] : "";

if ($sub_page == 'kp_purchasing') {
    include_once "kp_purchasing.php";
} else if ($sub_page == "kp_user") {
    include_once "kp_user.php";
} else if ($sub_page == "tolak_purchasing") {
    include_once "tolak_purchasing.php";
} else if ($sub_page == "tolak_user") {
    include_once "tolak_user.php";
} else if ($sub_page == "tolak_sr") {
    include_once "tolak_sr.php";
}

?>
 
 