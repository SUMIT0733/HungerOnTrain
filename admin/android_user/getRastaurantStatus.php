<?php

    include_once("dbConnect.php");
    $id = $_REQUEST['rest_id'];

    //$id = "8";
    $sql = "SELECT `status` FROM `restaurants` WHERE `restaurant_id` = '$id'";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    $a = mysqli_fetch_assoc($res);
    $status = $a['status'];
    $val['status'] = $status;
    echo json_encode($val);
    mysqli_close($con);
?>