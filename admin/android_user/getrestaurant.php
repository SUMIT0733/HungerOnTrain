<?php

    include_once("dbConnect.php");

    $city_id = $_REQUEST['id'];
    //$city_id = 440;
    
    $sql1 = "SELECT * FROM `restaurants` WHERE `restaurant_city`='$city_id' AND `verified` = 1 AND `status` = 1";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if($res1){
        if(mysqli_num_rows($res1) > 0){
            
            $val['responce'] = "Success";
            $i=0;
            while($a = mysqli_fetch_assoc($res1)){
                $val['data'][$i] = $a;
                $rest = $a['restaurant_id'];
                $sql3 = "SELECT city_master.city_name,city_master.state_name FROM `city_master` JOIN `restaurants` ON city_master.city_id = restaurants.restaurant_city WHERE restaurant_id = '$rest'";
                $res3 = mysqli_query($con,$sql3) or die("Error in 3");
                $c = mysqli_fetch_assoc($res3);
                $val['data'][$i]['city'] = $c['city_name'];
                $val['data'][$i]['state'] = $c['state_name'];
                $sql2 = "SELECT cuisines.cuisine_name FROM menu JOIN cuisines ON menu.cuisine_id = cuisines.cuisine_id WHERE restaurant_id = $rest GROUP BY menu.cuisine_id";
                $res2 = mysqli_query($con,$sql2) or die("Error in 2");
                if($res2){
                    $cui = "";
                    while($b = mysqli_fetch_assoc($res2)){
                        $cui = $cui."".$b['cuisine_name']." , ";                        
                    }
                }
                $cui = substr($cui,0,strlen($cui)-3);
                $val['data'][$i]['cuisine'] = $cui; 
                $i++;
            }
        }else{
            $val['responce'] = "No data";
        }   
    }
    else{
        $val['responce'] = "Error";
    }
    
    echo json_encode($val);
    mysqli_close($con);
?>