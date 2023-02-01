<?php 
  	require_once('admin_class.php');
	$admin=new Admin($db);
  	$restaurant_id=$_POST['view_menu_restaurant_id'];
	$menu=$admin->get_restaurant_menu_for_json($restaurant_id);
	echo json_encode($menu);

?>