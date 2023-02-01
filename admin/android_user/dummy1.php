<?php

    include_once("dbConnect.php");
    $sql1 = "INSERT INTO `orders`(`order_id`, `customer_id`, `restaurant_id`, `end_customer_name`,
     `end_customer_contact`, `otp`, `delivery_address`, `delivery_station`, `order_instruction`, `order_status`, 
     `order_time`, `delivery_person_id`, `order_amount`, `payment_id`, `payment_status`, `create_date`) 
    VALUES ('20190328110','1','8','prashant','1234567890','232323','KARNAVATI EXP ( 12933 ) , TG - 545','SURAT','Spicy'
    ,'0','2019-04-19 01:35:00','0','220','5e258e71730241da96449668c889783f','1',CURRENT_TIMESTAMP)";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        $sql2 = "INSERT INTO `orders_inventory`(`order_id`, `food_id`, `unit`, `subtotal`) 
        VALUES ('20190328110','10','1','100'),('20190328110','12','1','120')";
        $res2 = mysqli_query($con,$sql2) or die("Error in 2");
        if($res2){

            $sql3 = "INSERT INTO `notifications` (`notification_type`, `order_id`, `intended_user_type`, 
            `intended_user_id`, `created_at`)
            VALUES ('0', '20190328110', '0', '8',CURRENT_TIMESTAMP)";
            $res3 = mysqli_query($con,$sql3) or die("Error in 3");
        }else{

        }
    }else{
        echo "Error";
    }
?>