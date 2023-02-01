<?php
 $con = mysqli_connect('localhost','hungeron_root','HungerOnTrain','hungeron_train') or die("error in connection");
 $str = "";
 $sql = "select * from customer";
 $res = mysqli_query($con,$sql) or die("error in execytion of query");
 // $a = mysqli_fetch_assoc($res);
 $b = array();
 while ($a = mysqli_fetch_assoc($res)) {
 	$c = array();
 	$c['name'] = $a['name'];
 	$c['age'] = $a['age'];
 	array_push($b, $c);
 	//$str = $str."name:- ".$a['name']." age:- ".$a['age']."\n";
 }
 // echo json_encode($b);
echo json_encode($b);
mysqli_close($con);
?>