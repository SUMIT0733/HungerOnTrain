<?php
	$host = "localhost";
	$user = "hungeron_root";
	$pass = "HungerOnTrain";
	$db = "hungeron_train";	
	date_default_timezone_set("Asia/Kolkata");
 	$con = mysqli_connect($host,$user,$pass,$db) or die("error in connection");
?>