<?php

    include_once("dbConnect.php");
    
    // $name = $_REQUEST['name'];
    // $email = $_REQUEST['email'];
    // $pass = $_REQUEST['pass'];
    // $contact = $_REQUEST['contact'];
    // $city_name = $_REQUEST['city'];
    // $account = $_REQUEST['account'];
    // $ifsc = $_REQUEST['ifsc'];
    $date = date("Y-m-d H:i:s");
    
    $city_name = "SURAT";
    $name = "abcd";
    $email = "abcg@b.com";
    $pass = "123456";
    $contact = "1234568790";
    $account = "123456789901";
    $ifsc = "abcde123456";

    $sql3 = "SELECT `city_id` FROM `city_master` WHERE `city_name`= '$city_name'";
    $res3 = mysqli_query($con,$sql3) or die("Error in 3");
    if($res3){
        while($c = mysqli_fetch_assoc($res3)){
            $city_id = $c['city_id'];
        }
    }

    $sql1 = "SELECT `email` FROM `delivery_person` WHERE `email` = '$email'";
    $res1 = mysqli_query($con,$sql1) or die("Error in 1");
    if(mysqli_num_rows($res1) > 0){
        $val['responce'] = "exist";
    }else{

        $sql2 = "INSERT INTO `delivery_person`(`name`, `email`, `password`, `contact_no`,`account_no`, `IFSC_code`,`city_id`,`created_at`) 
        VALUES ('$name','$email','$pass','$contact','$account','$ifsc','$city_id','$date')";
        $res2 = mysqli_query($con,$sql2) or die ("Error in 2");
        if($res2){
            $val['responce'] = "Success";
            $last_id = mysqli_insert_id($con);
            
            $filename = $_FILES['photo']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            move_uploaded_file($_FILES['photo']['tmp_name'], '../delivery_person/images/'.$last_id.'.'.$ext); 
            $photo_url = 'https://hungerontrain.ml/images/'.$last_id.'.'.$ext;
            
            $docname = $_FILES['document']['name'];
            $doc_ext = pathinfo($docname, PATHINFO_EXTENSION);
            
            move_uploaded_file($_FILES['document']['tmp_name'], '../delivery_person/document/'.$last_id.'.'.$ext);
            $proof_url = 'https://hungerontrain.ml/document/'.$last_id.'.'.$ext;
            
            $sql4 = "UPDATE `delivery_person` SET `profile_url`= '$photo_url',`proof_url` = '$proof_url' WHERE `delivery_person_id`= '$last_id'";
            $res4 = mysqli_query($con,$sql4) or die("Error in 4");
            
        }else{
            $val['responce'] = "Error";
        }
    }

    echo json_encode($val);
    mysqli_close($con);
?>