<?php 
require_once('restaurant_class.php');//

$rest=new Restaurant($db);


if($_POST['action']=='accept_order' && $_POST['auto_order_id']!=null)
{
	$auto_order_id=$_POST['auto_order_id'];
	$update_q=$db->prepare("update orders set `order_status`=? where `auto_order_id`=?");
	$update_q->execute(array(1,$auto_order_id));
	echo "success";
}
else if($_POST['action']=='reject_order' && $_POST['auto_order_id']!=null)
{
	$auto_order_id=$_POST['auto_order_id'];
	$update_q=$db->prepare("update orders set `order_status`=? where `auto_order_id`=?");
	$update_q->execute(array(-1,$auto_order_id));
	echo "success";
}
else if($_POST['action']=='mark_as_prepared' && $_POST['auto_order_id']!=null)
{
	$auto_order_id=$_POST['auto_order_id'];
	$update_q=$db->prepare("update orders set `order_status`=? where `auto_order_id`=?");
	$update_q->execute(array(2,$auto_order_id));
	echo "success";
}
else if($_POST['action']=='mark_as_picked' && $_POST['auto_order_id']!=null)
{
	$auto_order_id=$_POST['auto_order_id'];
	$update_q=$db->prepare("update orders set `order_status`=? where `auto_order_id`=?");
	$update_q->execute(array(3,$auto_order_id));
	echo "success";
}
else if($_POST['action']=='fetch_order_details' && $_POST['auto_order_id']!=null)
{
	$auto_order_id=$_POST['auto_order_id'];
	$order_details_q=$db->prepare("select orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_instruction,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.payment_id,orders.create_date from orders where auto_order_id=?");
	$order_details_q->execute(array($auto_order_id));
	$order_details=$order_details_q->fetch();

	$order_summary_q=$db->prepare("select orders_inventory.unit,orders_inventory.subtotal,cuisines.cuisine_name,menu.food_item_name,menu.Veg from orders_inventory join menu on orders_inventory.food_id=menu.food_item_id join cuisines on menu.cuisine_id=cuisines.cuisine_id where orders_inventory.order_id=?");
	$order_summary_q->execute(array($order_details['order_id']));
	$order_summary=$order_summary_q->fetchAll();
	$order_details['order_summary']=$order_summary;
	
	if($order_details['delivery_person_id']!=0)
	{
		$delivery_person_details_q=$db->prepare("select orders.delivery_person_id, orders.otp, delivery_person.name,delivery_person.contact_no from orders join delivery_person on orders.delivery_person_id= delivery_person.delivery_person_id where orders.order_id=?");

		$delivery_person_details_q->execute(array($order_details['order_id']));
		$delivery_person_details=$delivery_person_details_q->fetchAll();
		$order_details['delivery_person_details']=$delivery_person_details;
	}

	echo json_encode($order_details);
}

else if($_POST['action']=='fetch_multiple_orders_basic_details' && $_POST['orders_ids_arr']!=null)
{

	$orders_ids_arr = $_POST['orders_ids_arr'];
	$orders_ids = implode(',',$orders_ids_arr);

	$orders_q=$db->prepare("select orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders where order_id in ( $orders_ids ) ORDER BY create_date");
	
	$orders_q->execute();

	$orders_result=$orders_q->fetchAll();
	echo json_encode($orders_result);

}

else if($_POST['action']=='assign_delivery_person' && $_POST['order_id']!=null)
{

	$order_id = $_POST['order_id'];

	$order_detail_q=$db->prepare("select restaurants.restaurant_city,orders.order_time from orders JOIN restaurants ON orders.restaurant_id=restaurants.restaurant_id where orders.order_id=:orderid");

	$order_detail_q->execute(array('orderid'=>$order_id));

	$order_detail_result=$order_detail_q->fetch();

	$city_id=$order_detail_result['restaurant_city'];

	$order_time=$order_detail_result['order_time'];

	$newFromTime = strtotime('-1 hour',strtotime($order_time));
	$required_from_time=date('Y-m-d H:i:s', $newFromTime);

	$newToTime = strtotime('+30 minutes',strtotime($order_time));
	$required_to_time=date('Y-m-d H:i:s', $newToTime);

	$fetch_busy_del_persons_q=$db->prepare("SELECT DISTINCT `delivery_person_id` FROM `del_person_timetable` WHERE (`assigned_from_time` BETWEEN :requir_from_time AND :requir_to_time) OR (`assigned_to_time` BETWEEN :requir_from_time AND :requir_to_time) AND (`city_id`=:ct_id)");

	$fetch_busy_del_persons_q->execute(array('requir_from_time'=>$required_from_time,'requir_to_time'=>$required_to_time,'ct_id'=>$city_id));

	$busy_del_persons=$fetch_busy_del_persons_q->fetchAll(PDO::FETCH_COLUMN, 0);
	//print_r($busy_del_persons);
	if(count($busy_del_persons)>0)
	{
		$busy_del_persons_ids_string = implode(',',$busy_del_persons);

		$fetch_other_del_persons_q=$db->prepare("select `delivery_person_id` from delivery_person WHERE `delivery_person_id` NOT IN ($busy_del_persons_ids_string) and `city_id`=:ct_id and `verified`=:verif and status=:stts");
	}
	else
	{
		$fetch_other_del_persons_q=$db->prepare("select `delivery_person_id` from delivery_person WHERE `city_id`=:ct_id and `verified`=:verif and status=:stts");
	}

	$fetch_other_del_persons_q->execute(array('ct_id'=>$city_id,'verif'=>1,'stts'=>1));

	$other_del_persons=$fetch_other_del_persons_q->fetchAll(PDO::FETCH_COLUMN,0);

	/* IN, NOT IN doesn't work properly in pdo binding. so better we use full sql query for NOT IN operator

	$fetch_other_del_persons_q=$db->prepare("select `delivery_person_id` from delivery_person WHERE `delivery_person_id` NOT IN (:busy_del_persons_ids)");

	$fetch_other_del_persons_q->execute(array('busy_del_persons_ids'=>$busy_del_persons_ids_string));

	$other_del_persons=$fetch_other_del_persons_q->fetchAll();
	*/

	$random_index=array_rand($other_del_persons);
	$new_del_person=$other_del_persons[$random_index];

	$update_order_q=$db->prepare("update orders set `delivery_person_id`=? where `order_id`=?");

	$update_order_q->execute(array($new_del_person,$order_id));

	
	$update_order_q=$db->prepare("update notifications set `notification_type`=?, status=? where `order_id`=?");

	$update_order_q->execute(array(2,0,$order_id));
	
	$insert_del_person_time_q=$db->prepare("insert into del_person_timetable(`delivery_person_id`,`assigned_from_time`,`assigned_to_time`,`order_id`,`city_id`)values(?,?,?,?,?)");

	$insert_del_person_time_q->execute(array($new_del_person,$required_from_time,$required_to_time,$order_id,$city_id));

	echo "success";

}


?>