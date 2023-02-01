<?php

    include_once("dbConnect.php");
    $id = $_REQUEST['id'];

    $sql1 = "SELECT `lat`, `lng` FROM `delivery_person` WHERE `delivery_person_id` = '$id'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        $a = mysqli_fetch_assoc($res1);
        $val['lat'] = $a['lat'];
        $val['lng'] = $a['lng'];
    }
    
    echo json_encode($val);
    mysqli_close($con);
?>