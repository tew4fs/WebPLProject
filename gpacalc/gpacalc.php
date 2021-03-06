<?php
/*
      if(isset($_GET["response"])){
        $res = json_decode($_GET["response"], true);
        if (isset($res["Result"])){
            $result = $res["Result"];
        }
        if (isset($res["Message"])){
            $msg = $res["Message"];
        }
    }
    */
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Areeba Kauser">
    <meta name="author" content="Trevor Williams">
    <meta name="description" content="Organize your classes">
    <meta name="keywords" content="school ogranize bookmark">
    <title>GPA Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>
<body onload="ret()">
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

    <div class="container">
        <div class="card">
            <h2 class="text-center text-success p-4">GPA Calculator</h2>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="w-50 m-auto" method="post" onsubmit="gpaResultCalc()">
                    <table class="table border">
                        <tr>
                            <td><label for="">First Semester</label></td>
                            <td><input class="form-control" type="text" id="first" name="first"></td>
                        </tr>
                        <tr>
                            <td><label for="">Second Semester</label></td>
                            <td><input class="form-control" type="text" id="second" name="second"></td>
                        </tr>
                        <tr>
                            <td><label for="">Third Semester</label></td>
                            <td><input class="form-control" type="text" id="third" name="third"></td>
                        </tr>
                        <tr>
                            <td><label for="">Four Semester</label></td>
                            <td><input class="form-control" type="text" id="four" name="four"></td>
                        </tr>
                        <tr>
                            <td><label for="">Five Semester</label></td>
                            <td><input class="form-control" type="text" id="five" name="five"></td>
                        </tr>
                        <tr>
                            <td><label for="">Six Semester</label></td>
                            <td><input class="form-control" type="text" id="six" name="six"></td>
                        </tr>
                        <tr>
                            <td><label for="">Seven Semester</label></td>
                            <td><input class="form-control" type="text" id="seven" name="seven"></td>
                        </tr>
                        <tr>
                            <td><label for="">Eight Semester</label></td>
                            <td><input class="form-control" type="text" id="eight" name="eight"></td>
                        </tr>
                    </table>
                    <input class="btn btn-success" type="submit" name="submit" value="Calculate">
                    
                </form>
            </div>
        </div>

        <div class="card">
            <h2 class="text-center text-success p-4"> Your GPA IS <span id="gparesult"> Unset </h2>
        </div>

    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
    crossorigin="anonymous"></script>
    <script src="./gpacalc.js"></script>
</html>