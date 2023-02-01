<?php 
require_once('restaurant_class.php');

$rest=new Restaurant($db);

$order_id = '20190311100';

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
$busy_del_persons_ids_string = implode(',',$busy_del_persons);

/* IN, NOT IN doesn't work properly in pdo binding. so better we use full sql query for NOT IN operator

$fetch_other_del_persons_q=$db->prepare("select `delivery_person_id` from delivery_person WHERE `delivery_person_id` NOT IN (:busy_del_persons_ids)");

$fetch_other_del_persons_q->execute(array('busy_del_persons_ids'=>$busy_del_persons_ids_string));

$other_del_persons=$fetch_other_del_persons_q->fetchAll();
*/

$fetch_other_del_persons_q=$db->prepare("select `delivery_person_id` from delivery_person WHERE `delivery_person_id` NOT IN ($busy_del_persons_ids_string) and `city_id`=:ct_id");

$fetch_other_del_persons_q->execute(array('ct_id'=>$city_id));

$other_del_persons=$fetch_other_del_persons_q->fetchAll(PDO::FETCH_COLUMN,0);

echo "Busy delivery persons:";
print_r($busy_del_persons);

echo "<br/>Available delivery persons:";
print_r($other_del_persons);

$random_index=array_rand($other_del_persons);
echo "<br/>Assigned Delivery Person:".$other_del_persons[$random_index];
?>

