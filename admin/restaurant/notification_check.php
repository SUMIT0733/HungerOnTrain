<?php
require_once('restaurant_class.php');

if(isset($_POST['action']) && $_POST['action']=='check_new_notification')
{
	$rest=new Restaurant($db);
	$restaurant_id=$rest->get_rest_id();


	$notifications_q=$db->prepare('select notification_id,notification_type,order_id,created_at from notifications where intended_user_type=? and intended_user_id=? and status=? ORDER BY notification_type');
	$notifications_q->execute(array(0,$restaurant_id,0));
	$notifications_result=$notifications_q->fetchAll();

	//this function filters all notifications.
	function filter_notifications($notif_type_val)
	{
		//access global varible outside of function, we have to first write this syntax.
		global $notifications_result;

		$notif_type_value = $notif_type_val;

		$filtered_notifs=array_filter($notifications_result, function ($var) use ($notif_type_value){
		    return ($var['notification_type'] == $notif_type_value);
		});

		return $filtered_notifs;
	}

	$new_order_notifs=array();
	$delperson_assigned_notifs=array();
	$order_picked_notifs=array();
	$order_delivered_notifs=array();

	$new_order_notifs = filter_notifications(0);
	$delperson_assigned_notifs = filter_notifications(2);
	$order_picked_notifs = filter_notifications(3);
	$order_delivered_notifs = filter_notifications(4);

	$notifications=array('new_orders'=>array_values($new_order_notifs),'delivery_person_assigned'=>array_values($delperson_assigned_notifs),'orders_picked_up'=>array_values($order_picked_notifs),'orders_delivered'=>array_values($order_delivered_notifs));

	if(count($notifications_result)>0)
	{
		$notification_ids = array_column($notifications_result, 'notification_id');
		$notif_ids = implode(',',$notification_ids);
		$notifications_update_q=$db->prepare("update notifications set status=? where notification_id IN ( $notif_ids )");
		$update_result=$notifications_update_q->execute(array(1));
	}

	echo json_encode($notifications);

}

else if(isset($_POST['action']) && $_POST['action']=='delete_notifications')
{
	//Performing delete notifications before echo stmt

	$notification_ids = $_POST['del_notification_ids'];
	$notif_ids = implode(',',$notification_ids);

	$notifications_delete_q=$db->prepare("delete from notifications where notification_id IN ( $notif_ids )");
	
	//echo "delete from notifications where notification_id IN ( $notif_ids )";
	$del_result=$notifications_delete_q->execute();	
	if($del_result)
	{
		echo "success";		
	}
}

else if(isset($_POST['action']) && $_POST['action']=='load_notifications_cart')
{
	$rest=new Restaurant($db);
	$restaurant_id=$rest->get_rest_id();

	$notifications_q=$db->prepare('select notification_id,notification_type,order_id,created_at from notifications where intended_user_type=? and intended_user_id=? and status=? ORDER BY notification_type');
	$notifications_q->execute(array(0,$restaurant_id,1));
	$notifications_result=$notifications_q->fetchAll();

	echo json_encode($notifications_result);

}

?>