<?php

    include_once("dbConnect.php");

    $s = $_REQUEST['state'];
    //$s = "JAMMU AND KASHMIR";
    $sql1 = "SELECT `city_name` FROM `city_master` WHERE `state_name` = '$s'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        $val['responce'] = "Success";
        while($a = mysqli_fetch_assoc($res1)){
            $val['city'][] = $a['city_name'];
        }
    }else{
        $val['responce']= "Error";
    }

    echo json_encode($val);
    mysqli_close($con);
    
    ?>