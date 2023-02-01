<?php

    include_once("dbConnect.php");
    $cart_id = $_REQUEST['cart_id'];
    //$cart_id = 12;
    $sql = "DELETE FROM `cart` WHERE `cart_id`= {$cart_id}";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    if($res){
        $val['responce'] = "remove";
    }else{
        $val['responce'] = "error";
    }

    echo json_encode($val);
    mysqli_close($con);

?>