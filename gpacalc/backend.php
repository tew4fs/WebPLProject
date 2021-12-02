<?php
// REQUIRED HEADERS FOR CORS
// Allow access to our development server, localhost:4200
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT");
header("Content-Type: application/json");

include("../database_credentials.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli($dbhost, $dbusername, $dbpasswd, $dbname);

$request = file_get_contents("php://input");

$email = $request;

$get_user_id = $db->prepare("select id from user where email = ?;");
$get_user_id->bind_param("s", $email);
if (!$get_user_id->execute()) {
  die("Error: ");
}
$user_id_res = $get_user_id->get_result();
$user_id_data = $user_id_res->fetch_all(MYSQLI_ASSOC);
$user_id = $user_id_data[0]["id"];

function get_user_classes($db, $user_id_arg){
  $get_classes_id_stmt = $db->prepare("select class_id from user_class where user_id = ?;");
  $get_classes_id_stmt->bind_param("i", $user_id_arg);
  if (!$get_classes_id_stmt->execute()) {
    die("Error: Database Failed");
  }
  $classes_id_res = $get_classes_id_stmt->get_result();
  $classes_id_data = $classes_id_res->fetch_all(MYSQLI_ASSOC);

  $class_list = [];
  foreach($classes_id_data as $c){
    $get_class_stmt = $db->prepare("select * from class where id = ?;");
    $get_class_stmt->bind_param("i", $c["class_id"]);
    if (!$get_class_stmt->execute()) {
      die("Error: Database Failed");
    }
    $class_res = $get_class_stmt->get_result();
    $class_data = $class_res->fetch_all(MYSQLI_ASSOC);
    array_push($class_list, $class_data[0]);
  }
  return $class_list;
}

$output = get_user_classes($db, $user_id);

echo json_encode($output, JSON_PRETTY_PRINT);