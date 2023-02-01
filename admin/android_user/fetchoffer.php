<?php

    include_once('dbConnect.php');
    $date = date('Y-m-d');
    $sql = "SELECT * FROM `offers` WHERE `status`= '1' AND `expiry_date` >= '$date' ";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    if(mysqli_num_rows($res) > 0){
        $val['responce'] = "success";
        while($a = mysqli_fetch_assoc($res)){
            $val['data'][] = $a;
        }
    }else{
        $val['responce'] = "No Offer Available";
    }

    echo json_encode($val);
    mysqli_close($con);
?>
