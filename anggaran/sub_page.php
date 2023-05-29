 
<?php

$sub_page = isset($_GET['sp']) ? $_GET['sp'] : "";

if ($sub_page == 'mk_purchasing') {
    include_once "mk_purchasing.php";
} else if ($sub_page == "mk_user") {
    include_once "mk_user.php";
} else if ($sub_page == "lr_01") {
    include_once "lr_01.php";
} else if ($sub_page == "lr_02") {
    include_once "lr_02.php";
} else if ($sub_page == "lr_03") {
    include_once "lr_03.php";
} else if ($sub_page == "lr_04") {
    include_once "lr_04.php";
} else if ($sub_page == "rk_01") {
    include_once "rk_01.php";
} else if ($sub_page == "rk_02") {
    include_once "rk_02.php";
} else if ($sub_page == "rk_03") {
    include_once "rk_03.php";
} else if ($sub_page == "rk_04") {
    include_once "rk_04.php";
} else if ($sub_page == "pra_nota") {
    include_once "pra_nota.php";
} else if ($sub_page == "realisasi_kas") {
    include_once "realisasi_kas.php";
}

?>
 
 