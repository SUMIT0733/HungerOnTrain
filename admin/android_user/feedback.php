<?php

    include_once("dbConnect.php");
    $ratting = $_REQUEST['ratting'];
    $order = $_REQUEST['order'];
    //$order = "20190328101";
    //$ratting = "3";

    $sql = "SELECT `restaurant_id` FROM `orders` WHERE `order_id` = '$order'";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    $a = mysqli_fetch_assoc($res);
    $rest_id = $a['restaurant_id'];

    $sql1 = "UPDATE `orders` SET `order_rating`= '$ratting' WHERE `order_id` = '$order'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        $sql2 = "SELECT `rest_rating` FROM `restaurants` WHERE `restaurant_id` = '$rest_id'";
        $res2 = mysqli_query($con,$sql2) or die("Error in 2");
        $b = mysqli_fetch_assoc($res2);
        $rat = $b['rest_rating'];
        $new_rat = ($rat + (int)$ratting)/2;

        $sql3 = "UPDATE `restaurants` SET `rest_rating` = '$new_rat' WHERE `restaurant_id` = '$rest_id'";
        $res3 = mysqli_query($con,$sql3) or die("Error in 3");
        $val['responce'] = "Success";
        $val['ratting'] = $ratting;
        $val['rest_rating'] = $rat;
        $val['new'] = $new_rat;
    }

    echo json_encode($val);
    mysqli_close($con);
?>