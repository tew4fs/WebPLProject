<?php
    include("../database_credentials.php");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli($dbhost, $dbusername, $dbpasswd, $dbname);

    // Join session or start one
    session_start();
    // If the user's email is not set in the session, then it's not
    // a valid session (they didn't get here from the login page),
    // so we should send them over to log in first before doing
    // anything else!
    if (!isset($_SESSION["email"]) or !isset($_COOKIE["username"])) {
        header("Location: ../login/login.php");
        exit();
    }

    if(!isset($_GET["class"])){
        header("Location: ../home/");
        exit();
    }else if(!isset($_GET["bookmark"])){
        header("Location: ./class.php?class={$_GET['class']}");
        exit();
    }

    $bookmark_id = $_GET["bookmark"];
    $delete_bookmark = $db->prepare("delete from bookmark where id = ?;");
    $delete_bookmark->bind_param("i", $bookmark_id);
    if (!$delete_bookmark->execute()) {
      die("Error: Database Failed.");
    }

    header("Location: ./class.php?class={$_GET['class']}");
    exit();

    