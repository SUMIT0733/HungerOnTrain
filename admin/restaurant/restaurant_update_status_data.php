<?php
require_once('restaurant_class.php');

$rest=new Restaurant($db);

if(isset($_POST['status']))
{
	$status=$_POST['status'];

	$restaurant_id=$rest->get_rest_id();

	//Returns TRUE for "1", "true", "on" and "yes". Returns FALSE otherwise.
	$bool = filter_var($status, FILTER_VALIDATE_BOOLEAN);

	if($bool==1)
	{
		$status=1;
	}
	else
	{
		$status=0;
	}


	$update_q=$db->prepare("update restaurants set `status`=? where `restaurant_id`=?");

	$updated=$update_q->execute(array($status,$restaurant_id));	

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