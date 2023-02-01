<?php 
session_start();
function logout()
{
	unset($_SESSION['usertype']);
	unset($_SESSION['logintype']);
	unset($_SESSION['restaurant_email']);
	unset($_SESSION['restaurant_name']);
	session_destroy();
}

if(isset($_POST['logout_type']))
{
	if($_POST['logout_type']=='normal_logout')
	{
		logout();
		header('Location: ../restaurant_login.php');
	}
	else if($_POST['logout_type']=='google_logout')
	{
		logout();
		echo "success";
	}
}

?>