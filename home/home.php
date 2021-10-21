<?php

/** DATABASE SETUP **/

include("../database_credentials.php"); // define variables
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Extra Error Printing
$mysqli = new mysqli($dbhost, $dbusername, $dbpasswd, $dbname);
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


$user = [
    "username" => $_SESSION["username"],
    "email" => $_SESSION["email"]
];
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
  <link rel="stylesheet" href="./home.css">

  <title>Home</title>
</head>

<body>
  <header class="row">
    <div class="col-12">
      <nav class="navbar navbar-expand-lg navbar-light">
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
                <a class="nav-link" href="#">
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

  <section>
    <!-- REPLACE WITH A PHP FUNCTION  -->
    <div class="container" id="class-cards">
      <div class="row">
        <div class="col-sm">
          <div class="card" style="width: 18rem;" id="class-card1">
            <div class="card-body">
              <h5 class="card-title"> CS 3240 </h5>
              <h6 class="card-subtitle mb-2 text-muted">M/W/F: 2:00 PM - 5:00 PM </h6>
              <p class="card-text"> 2 Upcoming Assignments.</p>
              <a href="#" class="card-link">Course Webpage</a>
              <a href="#" class="card-link">Schedule</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div class="card" style="width: 18rem;" id="class-card2">
            <div class="card-body">
              <h5 class="card-title"> STS 4500 </h5>
              <h6 class="card-subtitle mb-2 text-muted">Tu/Th: 1:00 PM - 2:15 PM</h6>
              <p class="card-text"> 3 Upcoming Assignments.<br> 1 Past Due Assignments.</p>
              <a href="../class/class.php" class="card-link">Course Webpage</a>
              <a href="#" class="card-link">Schedule</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div class="card" style="width: 18rem;" id="class-card3">
            <div class="card-body">
              <h5 class="card-title"> CS 4414 </h5>
              <h6 class="card-subtitle mb-2 text-muted"> M/W/F: 12:00 PM - 12:50 PM</h6>
              <p class="card-text"> 2 Upcoming Assignments</p>
              <a href="#" class="card-link">Course Webpage</a>
              <a href="#" class="card-link">Schedule</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div class="card" style="width: 18rem;" id="class-card4">
            <div class="card-body">
              <h5 class="card-title"> APMA 3120 </h5>
              <h6 class="card-subtitle mb-2 text-muted"> M/W: 5:00 PM - 6:15 PM</h6>
              <p class="card-text"> 1 Upcoming Assignments</p>
              <a href="#" class="card-link">Course Webpage</a>
              <a href="#" class="card-link">Schedule</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div class="card" style="width: 18rem;" id="class-card5">
            <div class="card-body">
              <h5 class="card-title"> CS 4640 </h5>
              <h6 class="card-subtitle mb-2 text-muted"> Tu/Th: 9:00 AM - 10:15 AM</h6>
              <p class="card-text"> 0 Upcoming Assignments</p>
              <a href="#" class="card-link">Course Webpage</a>
              <a href="#" class="card-link">Schedule</a>
            </div>
          </div>

        </div>
        <div class="col-sm">
          <div class="card" style="width: 18rem;" id="class-card6">
            <div class="card-body">
              <h5 class="card-title"> PHYS 2415 </h5>
              <h6 class="card-subtitle mb-2 text-muted"> M/W: 9:00 AM - 10:15 AM</h6>
              <p class="card-text"> 0 Upcoming Assignments</p>
              <a href="#" class="card-link">Course Webpage</a>
              <a href="#" class="card-link">Schedule</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer id="footer">
    <small class="container-fluid">
      &copy; ak3rej & tew4fs. All Rights Reserved
    </small>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
    crossorigin="anonymous"></script>
</body>

</html>