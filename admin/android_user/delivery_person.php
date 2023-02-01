<?php

    include_once("dbConnect.php");
    if($_REQUEST['function'] == 'updateStatus'){
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        
        // $id = 10;
        // $status = 0;
        $sql2 = "SELECT `assigned` FROM `delivery_person` WHERE `delivery_person_id`='$id'";
        $res2 = mysqli_query($con,$sql2) or die("Error in 2");
        $a = mysqli_fetch_assoc($res2);
        $assign = $a['assigned'];
        if($assign == 1){
            $val['responce'] = "Assigned";
        }else{
            $sql1 = "UPDATE `delivery_person` SET `status` = '$status' WHERE `delivery_person_id` = '$id'";
            $res1 = mysqli_query($con,$sql1) or die("Error in 1");
            $val['status'] = $status;
            $val['responce'] = "success";
        }
        echo json_encode($val);
    }
    elseif($_REQUEST['function'] == 'getStatus'){
        $id = $_REQUEST['id'];
        $sql3 = "SELECT`status` FROM `delivery_person` WHERE `delivery_person_id` = '$id'";
        $res3 = mysqli_query($con,$sql3) or die("Error in 3");
        if(res3){
            $val['responce'] = "success";
            $b = mysqli_fetch_assoc($res3);
            $status = $b['status'];
            $val['status'] = $status;
        }
        echo json_encode($val);
    }
    else if($_REQUEST['function'] == 'UpdateLatLng'){
        $id = $_REQUEST['userid'];
        $lat = $_REQUEST['lat'];
        $lng = $_REQUEST['lng'];
        $sql = "UPDATE `delivery_person` SET `lat`='$lat',`lng`= '$lng' WHERE `delivery_person_id` = '$id'";
        $res = mysqli_query($con,$sql) or die("ERROR IN 0");
        if($res){
            $val['responce'] = "success";
        }else{
            $va['responce'] = "Error";
        }
        echo json_encode($val);
    }

    mysqli_close($con);
?>