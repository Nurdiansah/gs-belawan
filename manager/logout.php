<?php

session_start();

session_unset($_SESSION['username_blw']);
session_unset($_SESSION['level_blw']);

header("location:../index.php");
