<?php

    include_once("getOrder.php");
    
    $order_id = $new_order;
    $user_id = $_REQUEST['user_id'];
    $rest_id = $_REQUEST['rest_id'];
    $user_name = $_REQUEST['user_name'];
    $user_contact = $_REQUEST['user_contact'];
    $address = $_REQUEST['address'];
    $station = $_REQUEST['station'];
    $instruction = $_REQUEST['instruction'];
    $time = $_REQUEST['time'];
    $offer_id = $_REQUEST['offer_id'];
    $effect_amount = $_REQUEST['effect_amount'];
    $amount = $_REQUEST['amount'];
    $payment_id = $_REQUEST['payment_id'];
    $payment_status = $_REQUEST['payment_status'];
    $order_time = date("Y-m-d H:i:s");
    $otp = mt_rand(100000, 999999);

    $sql1 = "INSERT INTO `orders`(`order_id`, `customer_id`, `restaurant_id`,
     `end_customer_name`, `end_customer_contact`,`otp`, `delivery_address`, `delivery_station`, `order_instruction`,
      `order_status`,`order_time` , `order_amount`,`offer_code`, `effect_amount`, `payment_id`, `payment_status`, `create_date`)
     VALUES ('$order_id','$user_id','$rest_id','$user_name','$user_contact','$otp','$address','$station','$instruction',0,'$time'
     ,'$amount','$offer_id','$effect_amount','$payment_id','$payment_status','$order_time')";
     
     $sql3 = "INSERT INTO `notifications` (`notification_type`, `order_id`, `intended_user_type`, 
            `intended_user_id`, `created_at`)
            VALUES ('0','$order_id' , '0', '$rest_id',CURRENT_TIMESTAMP)";
            $res3 = mysqli_query($con,$sql3) or die("Error in 3");
            
     $sql6 = "SELECT city_master.city_name,restaurants.restaurant_pincode,restaurants.contact_no,restaurants.restaurant_name,restaurants.restaurant_address FROM restaurants JOIN city_master ON restaurants.restaurant_city = city_master.city_id WHERE `restaurant_id`= '$rest_id'";
    $res6 = mysqli_query($con,$sql6) or die("Error in 6");
    if($res6){
        while($d = mysqli_fetch_assoc($res6)){
            $val['rest'][] = $d;
        }
    }
    
    $sql7 = "SELECT `offer_code` FROM `offers` WHERE `offer_id`='$offer_id'";
    $res7 = mysqli_query($con,$sql7) or die("Error in 7");
    if(mysqli_num_rows($res7) > 0){
        $d = mysqli_fetch_assoc($res7);
        $val['delivery'][0]['offer_code'] = $d['offer_code'];
    }else{
        $val['delivery'][0]['offer_code'] = "NOCODE";
    }
    

     $res1 = mysqli_query($con,$sql1) or die("Error in query");
     if($res1){
         $val['responce'] = "Success";
        
        $sql2 = "SELECT cart.*,menu.food_item_name FROM `cart` JOIN `menu` ON cart.food_id = menu.food_item_id WHERE `user_id`= '$user_id'";
        $res2 = mysqli_query($con,$sql2) or die("Error in 0");
        if($res2){
            $val['cart_responce'] = "Success";
            $val['delivery'][0]['user_name'] = $user_name;
            $val['delivery'][0]['contact'] = $user_contact;
            $val['delivery'][0]['address'] = $address;
            $val['delivery'][0]['station'] = $station;
            $val['delivery'][0]['instruction'] = $instruction;
            $val['delivery'][0]['time'] = $time;
            $val['delivery'][0]['amount'] = $amount;
            $val['delivery'][0]['order_time'] = $order_time;
            $val['delivery'][0]['offer_id'] = $offer_id;
            $val['delivery'][0]['effect_amount'] = $effect_amount;

            while($b = mysqli_fetch_assoc($res2)){
                $val['cart'][] = $b;
                $cart_id = $b['cart_id'];
                $food_id = $b['food_id'];
                
                $price = $b['price'];
                $unit = $b['unit'];
                $total = (int)$price * (int)$unit;

                $sql4 = "INSERT INTO `orders_inventory`(`order_id`, `food_id`, `unit`, `subtotal`)
                 VALUES ('$order_id','$food_id','$unit','$total')";
                 $res4 = mysqli_query($con,$sql4) or  die("Error in 4");
                 if($res4){
                     $sql5 = "DELETE FROM `cart` WHERE `cart_id`='$cart_id'";
                     $res5 = mysqli_query($con,$sql5) or die("Error in 5");
                    if($res5){
                        
                        $val['delete_reponce'] = "Success";
                    }else{
                        $val['delete_responce'] = "Error";
                    }
                    $val['order_inventery_responce'] = "Success";        
                 }else{
                    $val['order_inventery_responce'] = "Error";
                 }
            }
            $val['order_id'] = $order_id;

        }else{
            $val['cart_responce'] = "Error";
        }
     }else{
         $val['responce'] = "Error";
     }

    echo json_encode($val);
    mysqli_close($con);

?>

