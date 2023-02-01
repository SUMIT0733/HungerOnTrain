<?php

    include_once('dbConnect.php');

    $id = $_REQUEST['id'];
    
    //$id = 10;
    $sql2 = "SELECT orders.order_id,restaurants.restaurant_name,orders.order_time FROM `orders` JOIN `restaurants` ON restaurants.restaurant_id = orders.restaurant_id WHERE 
    `delivery_person_id`='$id' AND `order_status` = 4 AND `order_status` != -1 ORDER BY orders.create_date ASC";
    $res2 = mysqli_query($con,$sql2) or die("Error in 2");
    if(mysqli_num_rows($res2) > 0){
        while($c = mysqli_fetch_assoc($res2)){
            $val['responce'] = "success";
            $val['data'][] = $c;
        }
    }else{
        $val['responce'] = "no order";
    }

    echo json_encode($val);
    mysqli_close($con);    
?>