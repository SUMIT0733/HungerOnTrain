<?php
require_once('admin_class.php');

$rest=new Admin($db);

if(isset($_POST['verify_delivery_person_id'])){
	$delivery_person_id=json_decode($_POST['verify_delivery_person_id']);
	$update_q=$db->prepare("update delivery_person set `verified`=? where `delivery_person_id`=?");
	$update_q->execute(array(1,$delivery_person_id));
	echo "success";
}
else if(isset($_POST['unverify_delivery_person_id'])){
	$delivery_person_id=json_decode($_POST['unverify_delivery_person_id']);
	$update_q=$db->prepare("update delivery_person set `verified`=? where `delivery_person_id`=?");
	$update_q->execute(array(0,$delivery_person_id));
	echo "success";
}
else{
	echo "Something went wrong";
}



?>