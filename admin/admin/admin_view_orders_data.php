<?php
require_once('admin_class.php');

$rest=new Admin($db);
date_default_timezone_set('Asia/Calcutta');

if($_POST['action']=='fetch_rest_and_del_person_latlong' && $_POST['auto_order_id']!=null)
{
  $auto_order_id = $_POST['auto_order_id'];

  $lat_long_q=$db->prepare("select delivery_person.lat as del_person_lat,delivery_person.lng as del_person_lng,station_master.lat as station_lat,station_master.lng as station_long,restaurants.lat as rest_lat, restaurants.lng as rest_long from orders JOIN restaurants ON orders.restaurant_id=restaurants.restaurant_id JOIN station_master ON orders.delivery_station=station_master.station_name JOIN delivery_person ON orders.delivery_person_id=delivery_person.delivery_person_id where orders.auto_order_id=?");
  
  $lat_long_q->execute(array($auto_order_id));

  $lat_long_result=$lat_long_q->fetch();
  echo json_encode($lat_long_result);
}

else{
  echo "Something went wrong";
}



?>