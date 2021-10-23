<?php
  include("../database_credentials.php");

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

  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $db = new mysqli($dbhost, $dbusername, $dbpasswd, $dbname);
  $user = null;
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

  
  // Check if class is not set in URL
  if(!isset($_GET["class"])){
    header("Location: ../home/");
    exit();
  }
  // Check if class is not a valid class
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

  $class_nav = "";
  $form_options = "";
  foreach($class_list as $c){
    if($c === $class_name){
      $class_nav .= "
      <li class='nav-item'>
        <a class='nav-link active' aria-current='page' href='../class/class.php?class=$c'>$c</a>
      </li>";
      $form_options .= "<option value='$c' selected>$c</option>";
    }else{
      $class_nav .= "
      <li class='nav-item'>
        <a class='nav-link' href='../class/class.php?class=$c'>$c</a>
      </li>";
      $form_options .="<option value='$c'>$c</option>";
    }
  }
  $classes = get_user_classes($db, $user_id);


  // Add assignments
  $classes = get_user_classes($db, $user_id);
  if(isset($_POST["assignmentName"])){
    $assignment_name = $_POST["assignmentName"];
    $assignment_description = $_POST["assignmentDescription"];
    $due_date = $_POST["dueDate"];
    $assignment_class = $_POST["assignmentClass"];
    foreach($classes as $c){
      if($c["name"] === $assignment_class){
        $assignment_class_id = $c["id"];
      }
    }
    if(isset($_POST["assignmentID"])){
      $assignment_ID = $_POST["assignmentID"];
      $update_assignment = $db->prepare("update assignment set title = ?, description = ?, class_id = ?, due_date = ? where id = ?;");
      $update_assignment->bind_param("ssisi", $assignment_name, $assignment_description, $assignment_class_id, $due_date, $assignment_ID);
      if (!$update_assignment->execute()) {
        die("Error: Database failed");
      }
    }else{
      $add_assignment = $db->prepare("insert into assignment (title, description, class_id, due_date) values (?, ?, ?, ?);");
      $add_assignment->bind_param("ssis", $assignment_name, $assignment_description, $assignment_class_id, $due_date);
      if (!$add_assignment->execute()) {
        die("Error: Database failed");
      }
    }
  }

   // Add bookmarks
   if(isset($_POST["bookmarkName"])){
     $bookmark_name = $_POST["bookmarkName"];
     $bookmark_url = $_POST["bookmarkURL"];
     $bookmark_class = $_POST["bookmarkClass"];
 
     $add_bookmark = $db->prepare("insert into bookmark (name, url, class_id) values (?, ?, ?);");
     foreach($classes as $c){
       if($c["name"] === $bookmark_class){
         $bookmark_class_id = $c["id"];
       }
     }
     $add_bookmark->bind_param("ssi", $bookmark_name, $bookmark_url, $bookmark_class_id);
     if (!$add_bookmark->execute()) {
       die("Error: Database failed");
     }
   }

  // Get assignments
  $get_assignments_stmt = $db->prepare("select * from assignment where class_id = ? order by due_date asc;");
  $get_assignments_stmt->bind_param("i", $class_id);
  if (!$get_assignments_stmt->execute()) {
    die("Error: ");
  }
  $assignments_res = $get_assignments_stmt->get_result();
  $assignments_data = $assignments_res->fetch_all(MYSQLI_ASSOC);

  $assignments_html = "";
  $assignment_modal_html = "";
  foreach($assignments_data as $a){
    $due_date_status = "bg-success";
    $date = time();
    $due_date = strtotime($a["due_date"]);
    $diff = round(($due_date-$date) / (60 * 60 * 24));
    if($diff < 0){
      $due_date_status = "bg-danger";
    }else if($diff < 5){
      $due_date_status = "bg-warning";
    }
    $assignments_html .= "
    <li class='list-group-item bg-light'>{$a['title']}
      <div class='assignment-icons'>
        <a href='./assignment.php?class=$class_name&assignment={$a['id']}'>
          <span class='badge check'>
              <svg xmlns='http://www.w3.org/2000/svg' width='23' height='23' fill='currentColor'
                class='bi bi-check-circle' viewBox='0 0 16 16'>
                <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z' />
                <path
                  d='M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z' />
              </svg>
          </span>
        </a>
        <a data-bs-toggle='modal' href='#editAssignmentModalToggle-{$a["id"]}' role='button' aria-label='Edit Assignment'>
          <span class='badge edit'>
            <svg xmlns='http://www.w3.org/2000/svg' width='23' height='23' fill='currentColor'
              class='bi bi-pencil-square' viewBox='0 0 16 16'>
              <path
                d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
              <path fill-rule='evenodd'
                d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z' />
            </svg>
          </span>
        </a>
      </div>
      <div>
        <span class='badge $due_date_status'>{$a['due_date']}</span>
      </div>
    </li>";
    $assignment_modal_html .= "<div class='modal fade' id='editAssignmentModalToggle-{$a["id"]}' aria-hidden='true'
          aria-labelledby='editAssignmentModalToggleLabel-{$a["id"]}' tabindex='-1'>
          <div class='modal-dialog modal-dialog-centered'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title' id='editAssignmentModalToggleLabel-{$a["id"]}'>Edit Assignment</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
              </div>
              <form method='POST' action='./class.php?class=$class_name'>
                <div class='modal-body'>
                  <div class='row'>
                    <div class='col-md-7'>
                      <div class='mb-3'>
                        <input type='hidden' name='assignmentID' value={$a["id"]}/>
                        <label for='assignmentNameFormLabel-{$a["id"]}' class='form-label'>Assignment Name</label>
                        <input type='text' class='form-control' id='assignmentNameFormLabel-{$a["id"]}' placeholder='Assignment Name'
                          name='assignmentName' value='{$a["title"]}' required/>
                      </div>
                      <div class='mb-3'>
                        <label for='descriptionFormLabel-{$a["id"]}' class='form-label'>Description</label>
                        <textarea class='form-control' id='descriptionFormLabel-{$a["id"]}' rows='3'
                          placeholder='Description' name='assignmentDescription' value='{$a["description"]}'></textarea>
                      </div>
                    </div>
                    <div class='col-md-5'>
                      <div class='mb-3'>
                        <label for='classSelect-{$a["id"]}' class='form-label'>Class</label>
                        <select name='assignmentClass' id='classSelect-{$a["id"]}' class='form-select' required>
                          <?=$form_options?>
                        </select>
                      </div>
                      <div class='mb-3'>
                        <label for='dateSelect-{$a["id"]}' class='form-label'>Due Date</label>
                        <input type='date' name='dueDate' class='form-control' id='dateSelect-{$a["id"]}' value='{$a["due_date"]}'  required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class='modal-footer'>
                  <div class='google-calendar'>
                    <div class='form-check'>
                      <input class='form-check-input' type='checkbox' value='' id='googleCalendarCheckDefault-{$a["id"]}'>
                      <label class='form-check-label' for='googleCalendarCheckDefault-{$a["id"]}'>
                        Add to Google Calendar
                      </label>
                    </div>
                  </div>
                  <div>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                    <button type='submit' class='btn btn-primary'>Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>";
  }

  if($assignments_html === ""){
    $assignments_html = "<li class='list-group-item bg-light'>
      <h4 class='text-center'>
      You currently have no assignments.
      </h4>
      <div class='text-center'>
        <a data-bs-toggle='modal' href='#assignmentsModalToggle' role='button' aria-label='Add Assignment'>
        <button class='btn btn-primary'>Add Assignment</button>
        </a>
      </div>
    </li>";
  }

  $upcoming_assignments_html = "";
  $i = 0;
  foreach($assignments_data as $a){
    $due_date_status = "bg-success";
    $date = time();
    $due_date = strtotime($a["due_date"]);
    $diff = round(($due_date-$date) / (60 * 60 * 24));
    if($diff < 0){
      $due_date_status = "bg-danger";
    }else if($diff < 5){
      $due_date_status = "bg-warning";
    }
    $upcoming_assignments_html .= "
      <div class='col-md-6 ml-auto'>
        {$a["title"]}
        <span class='badge $due_date_status px-2'>{$a["due_date"]}</span>
      </div>";
    $i++;
    if($i>=4)
      break;
  }

  if($upcoming_assignments_html === ""){
    $upcoming_assignments_html = "
    <div class='col-md-6 mx-auto'>
      <h4 class='text-center'>
      You currently have no assignments.
      </h4>
      <div class='text-center'>
        <a data-bs-toggle='modal' href='#assignmentsModalToggle' role='button' aria-label='Add Assignment'>
        <button class='btn btn-primary'>Add Assignment</button>
        </a>
      </div>
    </div>";
  }
  

  
  // Get bookmarks
  $get_bookmarks_stmt = $db->prepare("select * from bookmark where class_id = ?;");
  $get_bookmarks_stmt->bind_param("i", $class_id);
  if (!$get_bookmarks_stmt->execute()) {
    die("Error: Database Failed");
  }
  $bookmarks_res = $get_bookmarks_stmt->get_result();
  $bookmarks_data = $bookmarks_res->fetch_all(MYSQLI_ASSOC);

  $bookmarks_html = "";
  foreach($bookmarks_data as $b){
    $bookmarks_html .= "
    <li class='list-group-item bg-light'>
      {$b["name"]}
      <div class='bookmark-icons'>
        <a href='{$b["url"]}' aria-label='{$b["name"]} Link'>
          <span class='badge link'>
            <svg xmlns='http://www.w3.org/2000/svg' width='22' height='22' fill='currentColor'
              class='bi bi-box-arrow-up-right' viewBox='0 0 16 16'>
              <path fill-rule='evenodd'
                d='M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z' />
              <path fill-rule='evenodd'
                d='M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z' />
            </svg>
          </span>
        </a>
        <a href='./bookmark.php?class=$class_name&bookmark={$b['id']}'>
          <span class='badge trash'>
            <svg xmlns='http://www.w3.org/2000/svg' width='22' height='22' fill='currentColor' class='bi bi-trash'
              viewBox='0 0 16 16'>
              <path
                d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z' />
              <path fill-rule='evenodd'
                d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z' />
            </svg>
          </span>
        </a>
      </div>
    </li>";
  }

  if($bookmarks_html === ""){
    $bookmarks_html = "<li class='list-group-item bg-light'>
      <h4 class='text-center'>
      You currently have no bookmarks.
      </h4>
      <div class='text-center'>
        <a data-bs-toggle='modal' href='#bookmarksModalToggle' role='button' aria-label='Add Bookmark'>
        <button class='btn btn-primary'>Add bookmark</button>
        </a>
      </div>
    </li>";
  }
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Areeba Kauser">
  <meta name="author" content="Trevor Williams">
  <meta name="description" content="Organize your classes">
  <meta name="keywords" content="school ogranize bookmark">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" href="./class.css">

  <title><?=$class_name?></title>
</head>

<body>
  <!-- ======= Navigation ======= -->
  <header class="row">
    <div class="col-12">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="../index.html">Web PL</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item mx-2">
                <a class="nav-link" href="../home/home.php">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                      d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                    <path fill-rule="evenodd"
                      d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
                  </svg>
                  Home
                </a>
              </li>
              <li class="nav-item mx-2">
                <a class="nav-link" href="#">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-pencil" viewBox="0 0 16 16">
                    <path
                      d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                  </svg>
                  Classes
                </a>
              </li>
              <li class="nav-item mx-2">
                <a class="nav-link" href="../gpacalc/gpacalc.php">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-calculator" viewBox="0 0 16 16">
                    <path
                      d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h8zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z" />
                    <path
                      d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5v-2zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-4z" />
                  </svg>
                  GPA Calculator
                </a>
              </li>
              <li class="nav-item mx-2">
                <a class="nav-link" href="../login/logout.php">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-person" viewBox="0 0 16 16">
                    <path
                      d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                  </svg>
                  Sign Out
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <div class="row class-nav my-2">
    <div class="col-12">
      <ul class="nav nav-pills">
        <?=$class_nav?>
      </ul>
    </div>
  </div>

  <div class="content container-fluid">
    <!-- Upcoming assignments -->
    <div class="row">
      <div class="col-11 title mx-auto">
        <h5>Upcoming Assignments</h5>
      </div>
      <div class="col-11 mx-auto bg-light rounded-3 upcoming-assignments">
        <div class="row container-fluid p-2">
          <?=$upcoming_assignments_html?>
        </div>
      </div>
    </div>

    <div class="row my-4">
      <!-- Assignments -->
      <div class="col-md-5 mx-auto">
        <!-- Add Assignment Modal -->
        <div class="modal fade" id="assignmentsModalToggle" aria-hidden="true"
          aria-labelledby="addAssignmentModalToggleLabel" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addAssignmentModalToggleLabel">Add Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="./class.php?class=<?=$class_name?>">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-7">
                      <div class="mb-3">
                        <label for="assignmentNameFormLabel" class="form-label">Assignment Name</label>
                        <input type="text" class="form-control" id="assignmentNameFormLabel" placeholder="Assignment Name"
                          name="assignmentName" required>
                      </div>
                      <div class="mb-3">
                        <label for="descriptionFormLabel" class="form-label">Description</label>
                        <textarea class="form-control" id="descriptionFormLabel" rows="3"
                          placeholder="Description" name="assignmentDescription"></textarea>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label for="classSelect" class="form-label">Class</label>
                        <select name="assignmentClass" id="classSelect" class="form-select" required>
                          <?=$form_options?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="dateSelect" class="form-label">Due Date</label>
                        <input type="date" name="dueDate" class="form-control" id="dateSelect" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="google-calendar">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="googleCalendarCheckDefault">
                      <label class="form-check-label" for="googleCalendarCheckDefault">
                        Add to Google Calendar
                      </label>
                    </div>
                  </div>
                  <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <h5>Active Assignments
          <a data-bs-toggle="modal" href="#assignmentsModalToggle" role="button" aria-label="Add Assignment">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle"
              viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
              <path
                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
          </a>
        </h5>
        <ul class="list-group assignment-group">
          <?=$assignments_html?>
        </ul>
      </div>
      <!-- Bookmarks -->
      <div class="col-md-5 mx-auto">
        <!-- Add Bookmark Modal -->
        <div class="modal fade" id="bookmarksModalToggle" aria-hidden="true" aria-labelledby="addBookmarkModalToggleLabel"
          tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addBookmarkModalToggleLabel">Add Bookmark</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="./class.php?class=<?=$class_name?>">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-7">
                      <div class="mb-3">
                        <label for="bookmarkNameFormLabel" class="form-label">Bookmark Name</label>
                        <input type="text" name="bookmarkName" class="form-control" id="bookmarkNameFormLabel" placeholder="Bookmark Name"
                          required>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="mb-3">
                        <label for="classSelectBookmarks" class="form-label">Class</label>
                        <select name="bookmarkClass" id="classSelectBookmarks" class="form-select" required>
                          <?=$form_options?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-10 mx-auto">
                      <div class="mb-3">
                        <label for="urlFormLabel" class="form-label">Url</label>
                        <input type="url" name="bookmarkURL" class="form-control" id="urlFormLabel" placeholder="https://" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <h5>Bookmarks
          <a data-bs-toggle="modal" href="#bookmarksModalToggle" role="button" aria-label="Add Bookmark">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle"
              viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
              <path
                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
          </a>
        </h5>
        <ul class="list-group bookmark-group">
          <?=$bookmarks_html?>
        </ul>
      </div>
    </div>
  </div>

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <small class="container-fluid">
      &copy; ak3rej & tew4fs. All Rights Reserved
    </small>
  </footer>
  <?=$assignment_modal_html?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
    crossorigin="anonymous"></script>
    
</body>

</html>