<?php

/** DATABASE SETUP **/
include("../database_credentials.php"); // define variables
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Extra Error Printing
$mysqli = new mysqli($dbhost, $dbusername, $dbpasswd, $dbname);
$error_msg = "";
session_start();
setcookie("user", TRUE, time()-1, '/');
session_destroy();
// end of logout component
header("Location: ../login/login.php");

?>