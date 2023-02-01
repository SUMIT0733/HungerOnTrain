<?php

    include_once("dbConnect.php");

    $station = $_REQUEST['station'];
    //$station = "ANAND JN";
    $sql1 = "SELECT `city_id` FROM `station_master` WHERE `station_name`= '$station' AND `status` = 1";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if(mysqli_num_rows($res1) > 0){
        $val['responce'] = "Success";
        while($a = mysqli_fetch_assoc($res1)){
            $val['city_id'] = $a['city_id'];
            $city_id = $a['city_id'];

            $sql2 = "SELECT `city_name` FROM `city_master` WHERE `city_id` = '$city_id'";
            $res2 = mysqli_query($con,$sql2) or die("Error in 2");

            if(mysqli_num_rows($res2)){
                while($b = mysqli_fetch_assoc($res2)){
                    $val['city_name'] = $b['city_name'];
                }
            }else{
                $val['city_name'] = "Not Found";
            }
        }
    }else{
        $val['responce'] = "Error";
    }

    echo json_encode($val);
    mysqli_close($con);
?>