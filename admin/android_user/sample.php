<?php

    include_once("dbConnect.php");

    $name = "abcd";
    // $sql = "INSERT INTO autoincr(`name`)  VALUES ('$name')";
    $res = mysqli_query($con,"INSERT INTO autoincr(`name`)  VALUES ('$name')") or die("Error 1");
    if($res){
        sleep(3);
        echo mysqli_insert_id($con);
    }else{
        echo("Query error");
    }
?>