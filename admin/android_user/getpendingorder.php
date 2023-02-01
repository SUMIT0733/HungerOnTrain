<?php

    include_once('dbConnect.php');

    $id = $_REQUEST['id'];
    //$id = 10;
    $sql = "SELECT `city_id` FROM `delivery_person` WHERE `delivery_person_id` = '$id'";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    $a = mysqli_fetch_assoc($res);
    $city_id = $a['city_id'];
    //echo $city_id."\n";

    $sql1 = "SELECT `station_name` FROM `station_master` WHERE `city_id` = '$city_id'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    $b = mysqli_fetch_assoc($res1);
    $station_name = $b['station_name'];
    //echo $station_name."\n";

    $sql2 = "SELECT orders.order_id,restaurants.restaurant_name,orders.order_time FROM `orders` JOIN `restaurants` ON restaurants.restaurant_id = orders.restaurant_id WHERE 
    `delivery_person_id`='$id'AND `order_status` != 4 AND `order_status` != -1 AND `delivery_station`='$station_name' ORDER BY orders.order_time ASC";
    $res2 = mysqli_query($con,$sql2) or die("Error in 2");
    if(mysqli_num_rows($res2) > 0){
        while($c = mysqli_fetch_assoc($res2)){
            $val['responce'] = "success";
            $val['data'][] = $c;
        }
    }else{
        $val['responce'] = "no order";
    }

    mysqli_close($con);
    echo json_encode($val);
    
?>