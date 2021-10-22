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
    }else if(!isset($_GET["assignment"])){
        header("Location: ./class.php?class={$_GET['class']}");
        exit();
    }

    $assignment_id = $_GET["assignment"];
    $delete_assignment = $db->prepare("delete from assignment where id = ?;");
    $delete_assignment->bind_param("i", $assignment_id);
    if (!$delete_assignment->execute()) {
      die("Error: Database Failed.");
    }

    header("Location: ./class.php?class={$_GET['class']}");
    exit();

    