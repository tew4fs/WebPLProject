<?php
    header("Content-Type: application/json; charset=UTF-8");
    $data = array();
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        array_push($data, $_POST['first'],$_POST['second'],$_POST['third'],
        $_POST['four'],$_POST['five'],$_POST['six'],$_POST['seven'],$_POST['eight']);
        //$data = json_encode(array_values($data));
        //$arr = json_decode($data);
        $number = 0;
        $result = 0;
        foreach ($data as $v) {
            if ($v != NULL){
                $result += $v;
                $number += 1;
            }
        }
        
        if ($number > 0){
            $result = $result / $number;
            $response = ["Result" => $result];
            $json_response = json_encode($response);
        }
        else {
            $message = "Enter at least one class";
            $response = ["Message" => $message];
            $json_response = json_encode($response);
        }
        header("Location: ./gpacalc.php?response=$json_response");
    }
    
?>