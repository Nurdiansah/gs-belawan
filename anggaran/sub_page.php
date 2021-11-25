 
<?php

$sub_page = isset($_GET['sp']) ? $_GET['sp'] : "";

if ($sub_page == 'mk_purchasing') {
    include_once "mk_purchasing.php";
} else if ($sub_page == "mk_user") {
    include_once "mk_user.php";
}

?>
 
 