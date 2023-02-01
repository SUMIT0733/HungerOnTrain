<?php

    include_once('dbConnect.php');
    if($_REQUEST['function'] == "fetch"){
        $sql = "SELECT `station_id`, `station_name` FROM `station_master` WHERE `lat` = 0 AND `lng` = 0";
        $res = mysqli_query($con,$sql) or die("Error in 0");
        if($res){
            while($a = mysqli_fetch_assoc($res)){
                $val['data'][] = $a;
            }
        }
    }
    else if($_REQUEST['function'] == "set"){
        $array = $_REQUEST['data'];
        $json = json_decode($array,true);
        // echo $json;
        foreach($json['stations'] as $station){
            $station_id = $station['id'];
            echo $lat = $station['lat'];
            echo $lng = $station['lng'];
            $sql1 = "UPDATE `station_master` SET `lat`= '$lat',`lng`= '$lng' WHERE `station_id` = '$station_id'";
            $res1 = mysqli_query($con,$sql1) or die("Error in 1");
            if($res1){
                echo "done";
            }else{
                echo "not";
            }
        }
    }
?>
