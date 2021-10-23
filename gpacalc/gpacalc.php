<?php
    $data =array();
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        array_push($data, $_POST['first'],$_POST['second'],$_POST['third'],
        $_POST['four'],$_POST['five'],$_POST['six'],$_POST['seven'],$_POST['eight']);
        $data = json_encode(array_values($data));
        $arr = json_decode($data);
        $number = 0;
        $result = 0;
        foreach ($arr as $v) {
            if ($v != NULL){
                $result += $v;
                $number += 1;
            }
        }
        
        if ($number > 0){
            $result = $result / $number;
        }
        else {
            $message = "Enter atleast one class";
        }
        
    }
    
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
    <title>CGPA Calculator</title>
</head>
<body>

    <div class="container">
        <div class="card">
            <h2 class="text-center text-success p-4">GPA Calculator</h2>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="" class="w-50 m-auto" method="post">
                <?php if(isset($msg)){
                    echo $msg;
                } ?>
                <?php 
                        if(isset($result)){?>
                            <p class="text-center text-success">Your Result is : <strong><?php echo $result; ?></strong></p>
                    <?php }?>
                    <table class="table border">
                        <tr>
                            <td><label for="">First Semester</label></td>
                            <td><input class="form-control" type="text" name="first"></td>
                        </tr>
                        <tr>
                            <td><label for="">Second Semester</label></td>
                            <td><input class="form-control" type="text" name="second"></td>
                        </tr>
                        <tr>
                            <td><label for="">Third Semester</label></td>
                            <td><input class="form-control" type="text" name="third"></td>
                        </tr>
                        <tr>
                            <td><label for="">Four Semester</label></td>
                            <td><input class="form-control" type="text" name="four"></td>
                        </tr>
                        <tr>
                            <td><label for="">Five Semester</label></td>
                            <td><input class="form-control" type="text" name="five"></td>
                        </tr>
                        <tr>
                            <td><label for="">Six Semester</label></td>
                            <td><input class="form-control" type="text" name="six"></td>
                        </tr>
                        <tr>
                            <td><label for="">Seven Semester</label></td>
                            <td><input class="form-control" type="text" name="seven"></td>
                        </tr>
                        <tr>
                            <td><label for="">Eight Semester</label></td>
                            <td><input class="form-control" type="text" name="eight"></td>
                        </tr>
                    </table>
                    <input class="btn btn-success" type="submit" name="submit" value="Calculate">
                    
                </form>
            </div>
        </div>
    </div>
</body>
</html>