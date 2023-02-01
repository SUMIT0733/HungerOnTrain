<?php 
	date_default_timezone_set('Asia/Calcutta');
	
	$menu_details=json_decode($_POST['menu_details']);

	/*
	//If we pass 'true' arguments in json_encode() then return result will be pure array, otherwise json object

	$menu_details=json_decode($_POST['menu_details'],true);
	echo $menu_details['cuisines'][0]['cuisine_name'];
	*/

	require_once('restaurant_class.php');
	$rest=new Restaurant($db);
	$restaurant_id=$rest->get_rest_id();

  	$cuisines_list=$rest->get_all_cuisines_list();
	$cuisines_id_list = array_column($cuisines_list, 'cuisine_id');

	foreach ($menu_details->cuisines as $cuisine) {
		$cuisine_id=$cuisine->cuisine_id;
		$cuisine_name=$cuisine->cuisine_name;
		$cuisine_food_items=$cuisine->food_items;
		
		if(!in_array($cuisine->cuisine_id,$cuisines_id_list))
		{
			$cuisine_datetime=date("Y-m-d H:i:s");
			$insert_new_cuisine=$db->prepare("insert into cuisines(`cuisine_name`,`created_at`)values(:cuisine_name,:created_at)");

			$cuisine_inserted=$insert_new_cuisine->execute(array('cuisine_name'=>ucwords($cuisine->cuisine_name),'created_at'=>$cuisine_datetime));

			$cuisine_id = $db->lastInsertId();
		}

		foreach($cuisine_food_items as $food_item)
		{
			$food_item_datetime=date("Y-m-d H:i:s");

			$food_type_veg=1;
			if($food_item->food_type==true)
			{
				//non-veg
				$food_type_veg=0;
			}

			$insert_new_food_item=$db->prepare("insert into menu(`restaurant_id`,`cuisine_id`,`food_item_name`,`food_item_price`,`ingredients`,`Veg`,`created_at`)values(:rest_id,:cuis_id,:food_name,:food_price,:food_ingredients,:isVeg,:created_at)");

			$food_item_inserted=$insert_new_food_item->execute(array('rest_id'=>$restaurant_id,'cuis_id'=>$cuisine_id,'food_name'=>$food_item->food_name,'food_price'=>$food_item->food_price,'food_ingredients'=>$food_item->food_ingredients,'isVeg'=>$food_type_veg,'created_at'=>$food_item_datetime));
			
		}

		echo "success";


	}


?>