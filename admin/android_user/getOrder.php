<?php

    include_once('dbConnect.php');
    $sql1 = "SELECT * FROM `order_temp_id` WHERE `temp_auto_id`= 1";
    
    $date = date('Ymd');
    
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        while($a = mysqli_fetch_assoc($res1)){
            $id = $a['temp_order_id'];
            
            $id_1 = substr($id,0,8);

            if($date == $id_1){
                $id_2 = substr($id,8,3);
                $int_id = (int)$id_2;
                $int_id = $int_id + 1;
                $new_id = $id_1."".$int_id;
            }else{
                $new_id = $date."100";
            }

            $sql2 = "UPDATE `order_temp_id` SET `temp_order_id`= '$new_id' WHERE `temp_auto_id` = 1";
            $new_order = $new_id;
                $res2 = mysqli_query($con,$sql2) or die("Error in update");
                if($res2){
                    //echo "Update success";
                }else{
                    //echo "Error in Update final";
                }
        }
    }else{
        echo "Error";
    }

    //echo $new_id;

?>
