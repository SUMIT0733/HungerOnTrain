<?php

    include_once("dbConnect.php");

    $email = $_REQUEST['id'];
    $pass = $_REQUEST['pass'];
    $token = $_REQUEST['token'];
    $sql1 = "SELECT * FROM `delivery_person` WHERE `email` = '$email' AND `password` = '$pass'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if(mysqli_num_rows($res1) > 0){
        while($a = mysqli_fetch_assoc($res1)){
            if($a['verified'] == 1){
                $val['responce'] = "Success";
                $val['data'][] = $a;
                
                $id = $a['delivery_person_id'];
                $sql2 = "UPDATE `delivery_person` SET `token`= '$token' WHERE `delivery_person_id` = '$id'";
                $res2 = mysqli_query($con,$sql2) or die("Error in 2");
                
            }else{
                $val['responce'] = "not";
            }
        }
    }else{
        $val['responce'] = "Error";
    }

    echo json_encode($val);
    mysqli_close($con);
?>