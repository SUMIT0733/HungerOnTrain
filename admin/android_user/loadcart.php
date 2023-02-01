<?php

    include_once("dbConnect.php");
     $id = $_REQUEST['id'];
    
    //$id = 1;
    $sql = "SELECT cart.cart_id,cart.food_id,cart.price,cart.unit,cart.cuisine,menu.food_item_name,menu.Veg FROM cart JOIN menu ON 
        cart.food_id = menu.food_item_id WHERE cart.user_id = {$id}";
    $sql1 = "SELECT restaurants.restaurant_name,cart.rest_id FROM cart JOIN restaurants ON cart.rest_id = restaurants.restaurant_id WHERE cart.user_id = {$id}";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    if(mysqli_num_rows($res) > 0){
        $val['responce'] = "success";
        $res1 = mysqli_query($con,$sql1) or die("Error in 1");
        while($b = mysqli_fetch_assoc($res1)){
            $val['restaurant'] = $b['restaurant_name'];
            $val['rest_id'] = $b['rest_id'];
        }
        while($a = mysqli_fetch_assoc($res)){
            $val['data'][] = $a;
        }
    }else{
        $val['responce'] = "no item found";
    }
    echo json_encode($val);
    mysqli_close($con);
?>