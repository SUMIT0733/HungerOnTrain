<?php

if($_POST['action']=='send_notification_to_del_person' && $_POST['auto_order_id']!=null && $_POST['message']!=null)
{
	require_once('../database/dbconfig.php');
	
	$auto_order_id=$_POST['auto_order_id'];
	$message=$_POST['message'];
	$message="";

	$del_person_detail_q=$db->prepare("select delivery_person.token,restaurants.restaurant_name,orders.order_id from orders join delivery_person on orders.delivery_person_id=delivery_person.delivery_person_id JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id where auto_order_id=?");

	$del_person_detail_q->execute(array($auto_order_id));

	$del_person_detail_q_result=$del_person_detail_q->fetch();
	$del_person_token=$del_person_detail_q_result['token'];
	$order_id=$del_person_detail_q_result['order_id'];
	$restaurant_name=$del_person_detail_q_result['restaurant_name'];
	
	$message="New order from ".$restaurant_name;

	// API access key from Google API's Console
	define('API_ACCESS_KEY','AAAAkddVFnE:APA91bGDrXPDsVuS8Cniwl5BqpmT2jCoKYs6pyzJ1Ufzh_8ePSIew_fPN7FfVPrrfQXsPIB_4kGcEcs0rDV8iJn9oJza7xrbTcNecULvutmmdMGUbvLwpS1LJg4qcp1CaeLaxYtp17SV');
	$url = 'https://fcm.googleapis.com/fcm/send';

	$registrationIds = array($del_person_token);
	// prepare the message
	$message = array( 
		'title'     => 'Order id: '.$order_id,
		'body'      => $message,
		'vibrate'   => 1,
		'sound'      => 1
	);
	$fields = array( 
		'registration_ids' => $registrationIds, 
		'data'             => $message
	);
	$headers = array( 
		'Authorization: key='.API_ACCESS_KEY, 
		'Content-Type: application/json'
	);
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL,$url);
	curl_setopt( $ch,CURLOPT_POST,true);
	curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers);
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt( $ch,CURLOPT_POSTFIELDS,json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);
	echo $result;

}

?>