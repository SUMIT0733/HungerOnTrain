<?php

    include_once("dbConnect.php");
    $id = $_REQUEST['id'];
    //$id = 1;

    $sql1 = "DELETE FROM `cart` WHERE `user_id`= '$id'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");

    if($res1){
        $val['responce'] = "Success";
    }else{
        $val['responce'] = "Error";
    }

    echo json_encode($val);
    mysqli_close($con);
?>