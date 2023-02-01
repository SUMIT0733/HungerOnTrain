<?php
session_start();
date_default_timezone_set('Asia/Calcutta');

require_once('database/dbconfig.php');
if(isset($_POST['restaurant_name']) && isset($_POST['state']) && isset($_POST['city']) && isset($_POST['contact_no']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['cpass']))
{
	$restaurant_name=$_POST['restaurant_name'];
	$state=$_POST['state'];
	$city=$_POST['city'];
	$contact_no=$_POST['contact_no'];
	$email=$_POST['email'];
	$pass=$_POST['pass'];
	$cpass=$_POST['cpass'];
	
	if($restaurant_name==''||$state==''||$city==''||$contact_no==''||$email==''||$pass==''||$cpass=='')
	{
		echo "Please fill all fields";
		die;
	}

	// For All patterns in php....
	// 	Here ~^ /~ are used as a delimiter. 
	// 	/^ $/ not working. 
	// 	#^ /# works for contact and email pattern ,but not working for password pattern(error says '?' in the pattern is a problem)

	$contactpattern="~^[0-9]{10}/~";
	if(preg_match($contactpattern,$contact_no))
	{
		echo "Please enter valid contact number";
		die;
	}


	$emailpattern="~^\w+([\._]?\w+)*@\w+([\._]?\w+)*(\.\w{2,3})+/~";
	if(preg_match($emailpattern,$email))
	{
		echo "Please enter valid email";
		die;
	
	}

	$passpattern="~^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,15}/~";
	if(preg_match($passpattern,$pass))
	{
		echo "Password does not match with pattern";
		die;
	}

	if($pass != $cpass)
	{
		echo "Password and Confirm Password does not match";
		die;
	}

	//Checking if user exists
	$check_user_q="select `email` from restaurants where `email`='$email' or `contact_no`='$contact_no'";
	$check_user_q_ex=$db->query($check_user_q);
	$result=$check_user_q_ex->fetchAll();
	if(count($result)!=0)
	{
		echo "User already exists.";
	}
	else
	{
		$registration_datetime=date("Y-m-d H:i:s");

		$insert_q=$db->prepare("insert into restaurants(`restaurant_name`,`restaurant_city`,`restaurant_state`,`contact_no`,`email`,`password`,`created_at`)values(:res_name,:res_city,:res_state,:contact_num,:email_id,:passwd,:created_at)");
		
		$inserted=$insert_q->execute(array('res_name' => $restaurant_name,'res_city' => $city,'res_state' =>$state ,'contact_num' =>$contact_no ,'email_id' => $email,'passwd' => $pass,'created_at'=>$registration_datetime));
		//execute() returns true of false.
		if($inserted)
		{
			$_SESSION['usertype']='restaurant';
    		$_SESSION['logintype']='normal';
			$_SESSION['restaurant_email']=$email;
			$_SESSION['restaurant_name']=$restaurant_name;
			echo "success";

		}
		else
		{
			echo "Something went wrong! Try again later.";
		}
	}

}
else
{
	echo "Something went wrong! Try again later.";
}
?>