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

	$check_user_q=$db->prepare("select `admin_id` from admin where `admin_email`=? and `password`=?");
	$check_user_q->execute(array($email,$pass));
	$result=$check_user_q->fetchAll();
	//print_r($result);
	if(count($result)!=0)
	{
		$_SESSION['usertype']='admin';
		$_SESSION['admin_email']=$email;

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