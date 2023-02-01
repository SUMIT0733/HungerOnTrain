<?php
	require("dbConnect.php");
	$email = $_REQUEST['email'];
	$name = $_REQUEST['name'];
	$url = $_REQUEST['url'];
	$tocken = $_REQUEST['tocken'];
	$sql = "SELECT * from customer WHERE `customer_email`= '$email'";
	$ans = mysqli_query($con,$sql) or die(mysqli_error($con));
	
	$res = array();
	if($a = mysqli_num_rows($ans) > 0)
	{
		$res['response'] = "found";
		$res['data'][] = mysqli_fetch_assoc($ans);
	}
	else{

		$date = date('Y-m-d H:i:s');
		$k = mysqli_query($con,"INSERT INTO `customer`(`customer_name`, `customer_email`, `customer_url`,`customer_status`,`token`, `created_at`) VALUES ('$name','$email','$url',1,'$tocken','$date')") or die("Error in query operation.");
		if($k){
			$sql = "SELECT * from customer where `customer_email`='$email'";
			$ans = mysqli_query($con,"SELECT * from customer where `customer_email`='$email'") or die("Error in query operation 2");
			if($a = mysqli_num_rows($ans) > 0)
			{
				$res['data'][] = mysqli_fetch_assoc($ans);
			}
			$res['response'] = "insert success";
		}
		else {
			$res['response'] = "error in db";
		}
	}
	echo json_encode($res);
	mysqli_close($con);
?>