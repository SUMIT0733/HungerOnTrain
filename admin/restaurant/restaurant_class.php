<?php
	require_once('../database/dbconfig.php');
	session_start();

	class Restaurant
	{
		protected $dbobj;
	    public function __construct($db)
	    {
	        $this->dbobj = $db;
	    }
	    function get_rest_id()
	    {
	    	$email=$_SESSION['restaurant_email'];
			$rest_id_q=$this->dbobj->prepare("select restaurant_id from restaurants where `email`=?");
			$rest_id_q->execute(array($email));
			$rest_id_result=$rest_id_q->fetch();
			return $rest_id_result['restaurant_id'];
	    }
	    function get_states_list()
	    {
	    	$state_q="select state_id,state_name from city_master group by state_id order by state_name asc";
			$state_q_ex=$this->dbobj->prepare($state_q);
			$state_q_ex->execute();
			$state_q_result=$state_q_ex->fetchAll();
			return $state_q_result;
	    }

	    function get_city_list($state_id)
	    {
	    	//This function fetches city list of selected state
	    	$city_q="select city_id,city_name from city_master where state_id=? order by city_name asc";
			$city_q_ex=$this->dbobj->prepare($city_q);
			$city_q_ex->execute(array($state_id));
			$city_q_result=$city_q_ex->fetchAll();
			return $city_q_result;
	    }

		function get_rest_details()
		{
			$restaurant_id=$this->get_rest_id();
			$rest_details_q=$this->dbobj->prepare("select * from restaurants where `restaurant_id`=?");
			$rest_details_q->execute(array($restaurant_id));
			$rest_details_result=$rest_details_q->fetch();

			// Here in database for state and city ids are stored. so to get actual names, we fire query and append state and city name in the resultant array.
			if($rest_details_result['restaurant_city']!='')
			{
				$statecityname_q=$this->dbobj->prepare("select city_name,state_name from city_master where `city_id`=?");

				$statecityname_q->execute(array($rest_details_result['restaurant_city']));
				$statecity_result=$statecityname_q->fetch();
				//append
				$rest_details_result+=["city_name"=>$statecity_result['city_name'],"state_name"=>$statecity_result['state_name']];			
			}
			return $rest_details_result;
		}
		function get_all_cuisines_list()
		{
			$cuisine_list_q=$this->dbobj->prepare("select cuisine_id,cuisine_name from cuisines");
			$cuisine_list_q->execute();
			$cuisines_list_result=$cuisine_list_q->fetchAll();
			return $cuisines_list_result;
		}
		function get_rest_cuisines_list()
		{
			$restaurant_id=$this->get_rest_id();

			$cuisine_list_q=$this->dbobj->prepare("select cuisines.cuisine_name from menu JOIN cuisines ON menu.cuisine_id=cuisines.cuisine_id where restaurant_id=? GROUP BY menu.cuisine_id");
			$cuisine_list_q->execute(array($restaurant_id));
			//PDO::FETCH_COLUMN in fetchAll() gives one dimensional array(used for fetching data from one column only)

			$cuisines_list_result=$cuisine_list_q->fetchAll(PDO::FETCH_COLUMN);
			return $cuisines_list_result;
		}

		function get_restaurant_menu($rest_id)
		{
			$restaurant_id=$rest_id;
			
			$menu_q=$this->dbobj->prepare("select food_item_name,food_item_price,ingredients,Veg,cuisines.cuisine_name from menu JOIN cuisines ON menu.cuisine_id=cuisines.cuisine_id where restaurant_id=? ORDER BY menu.cuisine_id");
			$menu_q->execute(array($restaurant_id));

			$menu=$menu_q->fetchAll();
			return $menu;
		}

		function get_restaurant_menu_for_json($rest_id)
		{
			$menu=['cuisines'=>[]];

			$cuisine_list_q=$this->dbobj->prepare("select cuisines.cuisine_id,cuisines.cuisine_name from menu JOIN cuisines ON menu.cuisine_id=cuisines.cuisine_id where restaurant_id=? GROUP BY menu.cuisine_id");

			$cuisine_list_q->execute(array($rest_id));
			
			//PDO::FETCH_COLUMN in fetchAll() gives one dimensional array(used for fetching data from one column only)
			$cuisines_list_result=$cuisine_list_q->fetchAll();

			foreach ($cuisines_list_result as $cuisine) {
				
				$cuisine_arr=['cuisine_id'=>$cuisine['cuisine_id'],'cuisine_name'=>$cuisine['cuisine_name'],'food_items'=>[]];

				$food_items_q=$this->dbobj->prepare("select food_item_name,food_item_price,ingredients,Veg from menu where restaurant_id=? and cuisine_id=?");

				$food_items_q->execute(array($rest_id,$cuisine['cuisine_id']));

				$food_items_result=$food_items_q->fetchAll();

				foreach ($food_items_result as $food_item) {
					$food_type=false;
					if($food_item['Veg']==0)
					{
						$food_type=true;
					}

					$food_item_arr=['food_name'=>$food_item['food_item_name'],'food_price'=>$food_item['food_item_price'],'food_ingredients'=>$food_item['ingredients'],'food_type'=>$food_type];
					array_push($cuisine_arr['food_items'],$food_item_arr);
				}

				array_push($menu['cuisines'],$cuisine_arr);
			}

			return $menu;

		}

		function fetch_current_pending_orders()
		{
			//Fetch current all orders
			$restaurant_id=$this->get_rest_id();
			$orders_q=$this->dbobj->prepare("select orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders where `restaurant_id`=? and order_status=? and payment_status=? ORDER BY create_date DESC");
			$orders_q->execute(array($restaurant_id,0,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_accepted_orders()
		{
			//Fetch current all orders
			$restaurant_id=$this->get_rest_id();
			$orders_q=$this->dbobj->prepare("select orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders where `restaurant_id`=? and order_status=? and payment_status=? ORDER BY create_date DESC");
			$orders_q->execute(array($restaurant_id,1,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_prepared_orders()
		{
			//Fetch current all orders
			$restaurant_id=$this->get_rest_id();
			$orders_q=$this->dbobj->prepare("select orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders where `restaurant_id`=? and order_status=? and payment_status=? ORDER BY create_date DESC");
			$orders_q->execute(array($restaurant_id,2,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_picked_orders()
		{
			//Fetch current all orders
			$restaurant_id=$this->get_rest_id();
			$orders_q=$this->dbobj->prepare("select orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders where `restaurant_id`=? and order_status=? and payment_status=? ORDER BY create_date DESC");
			$orders_q->execute(array($restaurant_id,3,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_delivered_orders()
		{
			//Fetch current all orders
			$restaurant_id=$this->get_rest_id();
			$orders_q=$this->dbobj->prepare("select orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders where `restaurant_id`=? and order_status=? and payment_status=? ORDER BY create_date DESC");
			$orders_q->execute(array($restaurant_id,4,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_rejected_orders()
		{
			//Fetch current all orders
			$restaurant_id=$this->get_rest_id();
			$orders_q=$this->dbobj->prepare("select orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders where `restaurant_id`=? and order_status=? and payment_status=? ORDER BY create_date DESC");
			$orders_q->execute(array($restaurant_id,-1,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}





	}

?>