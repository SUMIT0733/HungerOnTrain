<?php

    include_once("dbConnect.php");

    $rest_id = $_REQUEST['rest_id'];
    $food_id = $_REQUEST['food_id'];
    // $name = $_REQUEST['name'];
    $cuisine = $_REQUEST['cuisine'];
    $price = $_REQUEST['price'];
    $unit = $_REQUEST['unit'];
    $user_id = $_REQUEST['user_id'];

    // $user_id = 2;
    // $rest_id = 2;
    // $food_id = 6;
    // $price = 120;
    // $unit = 5;
    $new_unit = 0;

    $sql3 = "SELECT `rest_id` FROM cart  WHERE `user_id` = '$user_id' GROUP BY `rest_id`";
    $res3 = mysqli_query($con,$sql3) or die("3");

    if(mysqli_num_rows($res3) != 0){
        while($a = mysqli_fetch_assoc($res3)){
            //echo "{$a['rest_id']}   {$rest_id}   {$user_id}";

            if($a['rest_id'] == $rest_id){
                $sql1 = "SELECT * FROM cart WHERE `food_id` = '$food_id' AND `user_id` = '$user_id'";
                $res1 = mysqli_query($con,$sql1) or die("Error in query 1");
                if(mysqli_num_rows($res1) > 0){
                    while($b = mysqli_fetch_assoc($res1)){
                        //$val['menu'][] = $b;
                        $new_unit = $b['unit'] + $unit;
                        $sql2 = "UPDATE `cart` SET `unit`= {$new_unit} WHERE `cart_id` = {$b['cart_id']}";
                        $res2 = mysqli_query($con,$sql2) or die("2");
                        if($res2){
                            $val['responce'] = "Update success";
                        }else {
                            $val['responce'] = "error";
                        }

                    }
                } 
                else{
                    //$val['menu'][] = $a;
                    //$val['data'] = "No Data of food";
                    $sql = "INSERT INTO `cart`(`user_id`,`rest_id`, `food_id`,`cuisine`, `price`, `unit`) VALUES ('$user_id','$rest_id','$food_id','$cuisine','$price','$unit')";
                    $res = mysqli_query($con,$sql) or die("Error in query");
                    if($res){
                        $val['responce'] = "Add successfully";
                    }else{
                        $val['responce'] = "error";
                    }
                }
            }
            else{
                //$val['menu'][] = $a;
                $val['responce'] = "No Food From Other Restaurant Allowed";
            }
        }
    }
    else{
        //$val['data'] = "No Data of user";
        $sql = "INSERT INTO `cart`(`user_id`,`rest_id`, `food_id`,`cuisine`, `price`, `unit`) VALUES ('$user_id','$rest_id','$food_id','$cuisine','$price','$unit')";
        $res = mysqli_query($con,$sql) or die("Error in query");
        if($res){
            $val['responce'] = "Add successfully";
        }else{
            $val['responce'] = "error";
        } 
    }


    echo json_encode($val);
    mysqli_close($con);
?>