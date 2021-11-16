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
    }

    $get_user_id = $db->prepare("select id from user where email = ?;");
    $get_user_id->bind_param("s", $_SESSION["email"]);
    if (!$get_user_id->execute()) {
        die("Error: ");
    }
    $user_id_res = $get_user_id->get_result();
    $user_id_data = $user_id_res->fetch_all(MYSQLI_ASSOC);
    
    $user = [
        "username" => $_SESSION["username"],
        "email" => $_SESSION["email"],
        "id" => $user_id_data[0]["id"]
    ];
    $class_name = $_GET["class"];
    $user_id = $user["id"];

    $get_classes_id_stmt = $db->prepare("select class_id from user_class where user_id = ?;");
    $get_classes_id_stmt->bind_param("i", $user_id);
    if (!$get_classes_id_stmt->execute()) {
        die("Error: ");
    }
    $classes_id_res = $get_classes_id_stmt->get_result();
    $classes_id_data = $classes_id_res->fetch_all(MYSQLI_ASSOC);

    $class_list = [];
    $class_id = -1;
    foreach($classes_id_data as $c){
        $get_class_stmt = $db->prepare("select name from class where id = ?;");
        $get_class_stmt->bind_param("i", $c["class_id"]);
        if (!$get_class_stmt->execute()) {
        die("Error: ");
        }
        $class_res = $get_class_stmt->get_result();
        $class_data = $class_res->fetch_all(MYSQLI_ASSOC);
        array_push($class_list, $class_data[0]["name"]);
        if($class_data[0]["name"] === $class_name){
        $class_id = $c["class_id"];
        }
    }

    if($class_id < 0){
        header("Location: ../home/");
        exit();
    }

    if(isset($_GET["delete_assignment"])){
        $assignment_id = $_GET["delete_assignment"];
        $delete_assignment = $db->prepare("delete from assignment where id = ?;");
        $delete_assignment->bind_param("i", $assignment_id);
        if (!$delete_assignment->execute()) {
          die("Error: Database Failed.");
        }
    }

    $get_assignments_stmt = $db->prepare("select * from assignment where class_id = ? order by due_date asc;");
    $get_assignments_stmt->bind_param("i", $class_id);
    if (!$get_assignments_stmt->execute()) {
      die("Error: ");
    }
    $assignments_res = $get_assignments_stmt->get_result();
    $assignments_data = $assignments_res->fetch_all(MYSQLI_ASSOC);

    header("Content-type: application/json");
    echo json_encode($assignments_data, JSON_PRETTY_PRINT);
  
    