<?php
	session_start();
	date_default_timezone_set('Asia/Calcutta');

	require_once('database/dbconfig.php');
	if(isset($_POST['email']) && isset($_POST['name']))
	{

		$email=$_POST['email'];

		//Checking if user exists
		$check_user_q="select `email`,`restaurant_name` from restaurants where `email`='$email'";
		$check_user_q_ex=$db->query($check_user_q);
		$result=$check_user_q_ex->fetchAll();
		if(count($result)!=0)
		{
			$restaurant_name=$result[0]['restaurant_name'];
			$_SESSION['usertype']='restaurant';
			$_SESSION['logintype']='google';
			$_SESSION['restaurant_email']=$email;
			$_SESSION['restaurant_name']=$restaurant_name;
			echo "success";

		}
		else
		{
			$registration_datetime=date("Y-m-d H:i:s");

			$google_user_name=$_POST['name'];
			
			$insert_q=$db->prepare("insert into restaurants(`restaurant_name`,`email`,`created_at`)values(:res_name,:email_id,:created_at)");
			
			$inserted=$insert_q->execute(array('res_name' => $google_user_name,'email_id' => $email,'created_at'=>$registration_datetime));
			//execute() returns true of false.
			if($inserted)
			{
				$_SESSION['usertype']='restaurant';
	    		$_SESSION['logintype']='google';
				$_SESSION['restaurant_email']=$email;
				$_SESSION['restaurant_name']=$google_user_name;
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
		echo "Oops! Something went wrong! ";
	}
?>