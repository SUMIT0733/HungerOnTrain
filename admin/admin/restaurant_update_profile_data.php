<?php
require_once('restaurant_class.php');

$rest=new Restaurant($db);

if(isset($_POST['restaurant_name']) && isset($_POST['state']) && isset($_POST['city']) && isset($_POST['contact_no']) && isset($_POST['pincode']) && isset($_POST['restaurant_address']) && isset($_POST['website']))
{
	$restaurant_name=$_POST['restaurant_name'];
	$state=$_POST['state'];
	$city=$_POST['city'];
	$contact_no=$_POST['contact_no'];
	$pincode=$_POST['pincode'];
	$restaurant_address=$_POST['restaurant_address'];
	$website=$_POST['website'];

	if($restaurant_name==''||$state==''||$city==''||$contact_no==''||$pincode==''||$restaurant_address=='')
	{
		echo "Please fill all fields";
		die;
	}

	$contactpattern="~^[0-9]{10}/~";
	if(preg_match($contactpattern,$contact_no))
	{
		echo "Please enter valid contact number";
		die;
	}

	$pincodepattern="~^[0-9]{6}/~";
	if(preg_match($pincodepattern,$pincode))
	{
		echo "Please enter valid pincode";
		die;
	}

	if($website=='')
	{
		$website='NA';
	}
		
	$restaurant_id=$rest->get_rest_id();

	if(isset($_FILES['file']['name']))
	{
		if (($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/png"))
		{

		}
		else
		{
			echo "Please upload image having extensions .jpeg/.jpg/.png only.";
		}


		$filename=$_FILES['file']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		move_uploaded_file($_FILES['file']['tmp_name'], 'images/profile/'.$restaurant_id.'.'.$ext);
		$profile_url="images/profile/".$restaurant_id.".".$ext;

		$update_q=$db->prepare("update restaurants set `restaurant_name`=?,`restaurant_address`=?,`restaurant_pincode`=?,`restaurant_city`=?,`restaurant_state`=?,`contact_no`=?,`website`=?,`profile_url`=? where `restaurant_id`=?");

		$updated=$update_q->execute(array("$restaurant_name","$restaurant_address","$pincode","$city","$state","$contact_no","$website","$profile_url","$restaurant_id"));
	}
	else
	{
		$update_q=$db->prepare("update restaurants set `restaurant_name`=?,`restaurant_address`=?,`restaurant_pincode`=?,`restaurant_city`=?,`restaurant_state`=?,`contact_no`=?,`website`=? where `restaurant_id`=?");

		$updated=$update_q->execute(array("$restaurant_name","$restaurant_address","$pincode","$city","$state","$contact_no","$website","$restaurant_id"));	
	}

	if($updated)
	{
		echo "success";
	}
	

}
else
{
	echo "Something went wrong! Try again later.";
}
?>