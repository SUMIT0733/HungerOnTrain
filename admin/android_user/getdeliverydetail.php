<?php

    include_once('dbConnect.php');
    
    //$id = "20190314100";
    $id = $_REQUEST['id'];
    $sql1 = "Select `order_status`,`delivery_person_id`,`order_rating` FROM orders WHERE order_id = '$id'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        while($a = mysqli_fetch_assoc($res1)){

            $val['responce'] = "Success";
            $val['order_status'] = $a['order_status'];
            $val['order_rating'] = $a['order_rating'];
            
            if($a['delivery_person_id'] != 0){
                $val['delivery'] = "Delivery";
                $del = $a['delivery_person_id'];
                $sql2 = "SELECT `name`, `contact_no`, `profile_url`, `lat`, `lng` FROM `delivery_person` WHERE `delivery_person_id` = '$del'";
                $res2 = mysqli_query($con,$sql2) or die("Error in 2");
                while($b = mysqli_fetch_assoc($res2)){
                    $val['data'][] = $b;
                    $val['id'] = $del;
                }
            }
            else{
                $val['delivery'] = "Not Delivery";
            }
        }
    }else{
        $val['responce'] = "Error";
    }

    echo json_encode($val);
    mysqli_close($con);
?>