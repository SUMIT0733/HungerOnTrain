<?php

    include_once('dbConnect.php');
    $id = $_REQUEST['id'];
    //$id = 1;
    
    $sql1 = "SELECT orders.order_id,restaurants.restaurant_name,orders.create_date,orders.order_amount,orders.effect_amount FROM `orders` JOIN `restaurants` ON orders.restaurant_id = restaurants.restaurant_id WHERE orders.customer_id = '$id' ORDER BY orders.create_date DESC";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        $val['responce'] = "Success";
        while($a = mysqli_fetch_assoc($res1)){
            $val['data'][] = $a;
        }
    }else{
        $val['responce'] = "Error";
    }

    echo json_encode($val);
    mysqli_close($con);
?>