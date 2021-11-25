 
<?php

$sub_page = isset($_GET['sp']) ? $_GET['sp'] : "";

if ($sub_page == 'tolak_purchasing') {
    include_once "tolak_purchasing.php";
} else if ($sub_page == "tolak_user") {
    include_once "tolak_user.php";
}
