<?php
session_start();
require_once('database/dbconfig.php');
if(isset($_POST['email']) && isset($_POST['pass']))
{

	$email=$_POST['email'];
	$pass=$_POST['pass'];
	
	if($email=='' || $pass=='')
	{
		echo "Please fill all fields";
		die;
	}

	$check_user_q="select `email`,`restaurant_name` from restaurants where `email`='$email' and `password`='$pass'";
	$check_user_q_ex=$db->query($check_user_q);
	$result=$check_user_q_ex->fetchAll();
	//print_r($result);
	if(count($result)!=0)
	{
		
		$_SESSION['usertype']='restaurant';
		$_SESSION['logintype']='normal';
		$_SESSION['restaurant_email']=$email;
		$_SESSION['restaurant_name']=$result[0]['restaurant_name'];

		echo "success";
	}
	else
	{
		echo "Invalid Credentials";
	}
}
else
{
	echo "Something went wrong! Try again later.";
}
?>