<?php
require_once('admin_class.php');

$rest=new Admin($db);

if(isset($_POST['verify_restaurant_id'])){
	$restaurant_id=json_decode($_POST['verify_restaurant_id']);
	$update_q=$db->prepare("update restaurants set `verified`=? where `restaurant_id`=?");
	$update_q->execute(array(1,$restaurant_id));
	echo "success";
}
else if(isset($_POST['unverify_restaurant_id'])){
	$restaurant_id=json_decode($_POST['unverify_restaurant_id']);
	$update_q=$db->prepare("update restaurants set `verified`=? where `restaurant_id`=?");
	$update_q->execute(array(0,$restaurant_id));
	echo "success";
}
else{
	echo "Something went wrong";
}



?>