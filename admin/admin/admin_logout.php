<?php 
session_start();
function logout()
{
	unset($_SESSION['usertype']);
	unset($_SESSION['admin_email']);
	session_destroy();
}

if(isset($_POST['logout_type']))
{
	if($_POST['logout_type']=='admin_logout')
	{
		logout();
		header('Location: ../admin_login.php');
	}
}
else{
	echo "Ooops! Something went wrong!";
}

?>