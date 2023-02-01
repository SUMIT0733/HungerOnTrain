<?php
    
    include_once('dbConnect.php');
    
    $id = $_REQUEST['id'];
    $total = $_REQUEST['total'];
    $user_id = $_REQUEST['user_id'];

    $sql = "SELECT * FROM `offers` WHERE `offer_id`= '$id'";
    $res = mysqli_query($con,$sql) or die("Error in 0");
    if($res){
        $val['responce'] = "success";
        $a = mysqli_fetch_assoc($res);
        
        $upto = $a['discount_upto_rs'];
        $min = $a['min_order_amount'];
        $per = $a['percentage_or_flat'];
        $unit = $a['unit'];
        $use = $a['usage_per_user'];

        $sql1 = "SELECT `order_id` FROM `orders` WHERE `customer_id` = '$user_id' AND `offer_code` = '$id'";
        $res1 = mysqli_query($con,$sql1) or die("Error in 1");
        if(mysqli_num_rows($res1) > $use){
            $val['responce'] = "Maximum usage of promocode exceeds.";
        }else{
            if($min <= $total){
                $val['responce'] = "success";
                if($per == "1"){
                    //flat discount
                    $effect_amt = $total - $unit;
                    if($effect_amt < 0)
                        $val['effect'] = 0;
                    else
                        $val['effect'] = $effect_amt;
                }else if($per == "0"){
                    //percentage discount
                    $dis = ($total*($unit/100));
                    //$effect_amt = $total - $dis;
                    if($dis > $upto){
                        $effect_amt = $total - $upto;
                    }else{
                        $effect_amt = $total - $dis;
                    }
                    $val['effect'] = $effect_amt;
                }
            }else{
                $val['responce'] = "Order must be greater than ".$min;
            }
        }
    }else{
        $val['responce'] = "Error";
    }

    echo json_encode($val);
    mysqli_close($con);
?>