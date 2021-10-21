
<?php
/** DATABASE SETUP **/
include("../database_credentials.php"); // define variables
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Extra Error Printing
$mysqli = new mysqli($dbhost, $dbusername, $dbpasswd, $dbname);

$error_msg = "";
// Join the session or start a new one
session_start();

// Check if the user submitted the form (the form in the HTML below
// submits back to this page, which is okay for now.  We will check for
// form data and determine whether to re-show this form with a message
// or to redirect the user to the trivia game.

function validatePassword($userpassword)
{
    $regex1 = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/";
    if (preg_match($regex1, $userpassword)===1){
        return true;
    }
    else{
        return false;
    } 
}



if (isset($_POST["email"])) { /// validate the email coming in
    $stmt = $mysqli->prepare("select * from user where email = ?;");
    $stmt->bind_param("s", $_POST["email"]);
    if (!$stmt->execute()) {
        $error_msg = "Error checking for user";
    } else { 
        // result succeeded
        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);     
        if (!empty($data)) { //(isset($data[0])) {
            // user was found!          
            // validate the user's password
            if (password_verify($_POST["password"], $data[0]["password"])) {
                // Save user information into the session to use later
                $_SESSION["username"] = $data[0]["username"];
                $_SESSION["email"] = $data[0]["email"];
                header("Location: ../home/home.php");
                exit();
            } else {
                // User was found but entered an invalid password
                $error_msg = "Invalid Password";
            }
        } else {
            // user was not found, create an account
            // NEVER store passwords into the database, use a secure hash instead:
            //Validate Password using regex

            if (validatePassword($_POST["password"]) == TRUE){
              $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
              $insert = $mysqli->prepare("insert into user (username, email, password) values (?, ?, ?);");
              $insert->bind_param("sss", $_POST["username"], $_POST["email"], $hash);
              if (!$insert->execute()) {
                  $error_msg = "Error creating new user";
              }            
              // Save user information into the session to use later
              $_SESSION["username"] = $_POST["username"];
              $_SESSION["email"] = $_POST["email"];
              header("Location: ../home/home.php");
              exit();
            }
            else{
              $error_msg = "Password must contain Minimum eight characters, at least one letter and one number:";
            }

        }
    }
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
  <link rel="stylesheet" href="./login.css">

  <title>Login</title>
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
                <a class="nav-link" href="../login/login.php">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-person" viewBox="0 0 16 16">
                    <path
                      d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                  </svg>
                  Sign In
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>

  <section class="container-fluid">
    <form class="row" action="login.php" method="post">
      <div class="col-lg-5 col-md-7 col-sm-10 mx-auto my-5 login-container rounded-3">
        <h3 class="col-md-10 mx-auto signin">Sign In</h3>
        <!---->
        <?php
        if (!empty($error_msg)) {echo "<div class='alert alert-danger'>$error_msg</div>";}?>
        <div class="col-md-10 mx-auto">
          <label class="form-label" for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Email">
        </div>
        <div class="col-md-10 mx-auto">          
          <label class="form-label" for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Username">
        </div>
        <div class="col-md-10 mx-auto">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password with 8 or more characters, at least one letter and one number">
        </div>
        <div class="col-10 mx-auto signin">
          <button type="submit" class="btn btn-primary">Sign in / Create Account</button>
        </div>
      </div>
    </form>
  </section>

  <footer id="footer">
    <small class="container-fluid" style="color: white;">
      &copy; ak3rej & tew4fs. All Rights Reserved
    </small>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
    crossorigin="anonymous"></script>
</body>

</html>