<?php

    include_once('dbConnect.php');
    
    //$id = "20190308101";
    $id = $_REQUEST['id'];
    $sql1 = "SELECT orders.*,restaurants.*,city_master.city_name FROM orders JOIN restaurants ON orders.restaurant_id = restaurants.restaurant_id JOIN city_master ON restaurants.restaurant_city = city_master.city_id WHERE `order_id`={$id}";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        while($a = mysqli_fetch_assoc($res1)){
            $val['data'][0] = $a;
            //$order_id = $a['order_id'];
            $code = $a['offer_code'];
            
            $sql3 = "SELECT `offer_code` FROM `offers` WHERE `offer_id`='$code'";
            $res3 = mysqli_query($con,$sql3) or die("Error in 3");
            if(mysqli_num_rows($res3) > 0){
                $d = mysqli_fetch_assoc($res3);
                $val['data'][0]['offer_code'] = $d['offer_code'];
            }else{
                $val['data'][0]['offer_code'] = "NOCODE";
            }
    
            $sql2 = "SELECT orders_inventory.unit,orders_inventory.subtotal,menu.food_item_name,menu.Veg FROM orders_inventory JOIN menu ON menu.food_item_id = orders_inventory.food_id WHERE `order_id`={$id}";
            $res2 = mysqli_query($con,$sql2) or die("Error in 2");
            if($res2){
                while($b = mysqli_fetch_assoc($res2)){
                    $val['data'][0]['cart'][] = $b;
                }
            }else{
                
            }

        }
    }
    echo json_encode($val);
    mysqli_close($con);
?>