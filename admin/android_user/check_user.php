<?php

	include_once("dbConnect.php");
	$email = $_REQUEST['email'];
	$tocken = $_REQUEST['tocken'];
	//$email = "sumitmonapara@gmail.com";
	$sql = "SELECT `customer_status` from customer where `customer_email`='$email'";
	$ans = mysqli_query($con,$sql) or die("Error in query operation");
	$res = array();
	while($a = mysqli_fetch_array($ans)){
		if($a['customer_status'] == 1){
			$res['valid'] = "valid";
			$sql2 = "UPDATE `customer` SET `token` = '$tocken'  WHERE `customer_email` = '$email'";
			mysqli_query($con,$sql2) or die("Error in 2");
		}else{
			$res['valid'] = "block";
		}
	}
	echo json_encode($res);
	mysqli_close($con);

?>