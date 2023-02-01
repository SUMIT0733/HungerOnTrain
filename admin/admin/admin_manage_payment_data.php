<?php
require_once('admin_class.php');

$rest=new Admin($db);
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['action']) && $_POST['action']=='mark_all_payments_as_paid'){

	$payment_datetime=date("Y-m-d H:i:s");

	$update_q=$db->prepare("update orders set `is_restaurant_paid`=?, `rest_payment_date`=? where orders.order_status!=? and orders.is_restaurant_paid=?");
	$update_q->execute(array(1,$payment_datetime,-1,0));

	echo "success";
}
else{
	echo "Something went wrong";
}



?>