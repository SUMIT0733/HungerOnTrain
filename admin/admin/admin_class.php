<?php
	require_once('../database/dbconfig.php');
	session_start();

	class Admin
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
	    function get_pending_restaurants()
	    {
			//$pending_restaurants=[];

			$rest_list_q=$this->dbobj->prepare("select restaurants.restaurant_id,restaurants.restaurant_name,restaurants.email,restaurants.contact_no,restaurants.restaurant_address,restaurants.restaurant_pincode,city_master.city_name,city_master.state_name,restaurants.website,restaurants.account_no,restaurants.IFSC_code,restaurants.created_at from restaurants JOIN city_master ON (restaurants.restaurant_city=city_master.city_id AND restaurants.restaurant_state=city_master.state_id ) WHERE (restaurants.restaurant_name!='' AND restaurants.email!='' AND restaurants.contact_no!='' AND restaurants.restaurant_address!='' AND restaurants.restaurant_pincode!='' AND restaurants.restaurant_city!='' AND restaurants.restaurant_state!='' AND restaurants.account_no!='' AND restaurants.IFSC_code!='' AND restaurants.verified=? ) ORDER BY restaurants.created_at DESC");

			$rest_list_q->execute(array(0));

			$rest_list_result=$rest_list_q->fetchAll();

			/*foreach ($rest_list_result as $rest) {
				
				$rest=['restaurant_id'=>$rest['restaurant_id'],'restaurant_name'=>$rest['restaurant_name'],'email'=>$rest['email'],'contact_no'=>$rest['contact_no'],'restaurant_address'=>$rest['restaurant_address'],'restaurant_pincode'=>$rest['restaurant_pincode'],'city_name'=>$rest['city_name'],'state_name'=>$rest['state_name'],'website'=>$rest['website'],'created_at'=>$rest['created_at']];

				array_push($pending_restaurants,$rest);
			}

			return $pending_restaurants;*/	    
			return $rest_list_result;

		}
		function get_verified_restaurants()
	    {

			$rest_list_q=$this->dbobj->prepare("select restaurants.restaurant_id,restaurants.restaurant_name,restaurants.email,restaurants.contact_no,restaurants.restaurant_address,restaurants.restaurant_pincode,city_master.city_name,city_master.state_name,restaurants.website,restaurants.account_no,restaurants.IFSC_code,restaurants.created_at from restaurants JOIN city_master ON (restaurants.restaurant_city=city_master.city_id and restaurants.restaurant_state=city_master.state_id ) WHERE restaurants.verified=? ORDER BY restaurants.created_at DESC");

			$rest_list_q->execute(array(1));

			$rest_list_result=$rest_list_q->fetchAll();
	    
			return $rest_list_result;

		}

		function get_pending_delivery_persons()
	    {

			$delivery_persons_list_q=$this->dbobj->prepare("select delivery_person.delivery_person_id,delivery_person.name,delivery_person.email,delivery_person.contact_no,delivery_person.profile_url,delivery_person.proof_url,city_master.city_name,city_master.state_name,delivery_person.account_no,delivery_person.IFSC_code,delivery_person.created_at from delivery_person JOIN city_master ON (delivery_person.city_id=city_master.city_id ) WHERE (delivery_person.name!='' AND delivery_person.email!='' AND delivery_person.contact_no!='' AND delivery_person.city_id!='' AND delivery_person.account_no!='' AND delivery_person.IFSC_code!='' AND delivery_person.verified=? ) ORDER BY delivery_person.created_at DESC");

			$delivery_persons_list_q->execute(array(0));

			$delivery_persons_list_result=$delivery_persons_list_q->fetchAll();

			return $delivery_persons_list_result;

		}
		function get_verified_delivery_persons()
	    {
	    	$delivery_persons_list_q=$this->dbobj->prepare("select delivery_person.delivery_person_id,delivery_person.name,delivery_person.email,delivery_person.contact_no,delivery_person.profile_url,delivery_person.proof_url,city_master.city_name,city_master.state_name,delivery_person.account_no,delivery_person.IFSC_code,delivery_person.created_at from delivery_person JOIN city_master ON (delivery_person.city_id=city_master.city_id ) WHERE delivery_person.verified=? ORDER BY delivery_person.created_at DESC");

			$delivery_persons_list_q->execute(array(1));

			$delivery_persons_list_result=$delivery_persons_list_q->fetchAll();

			return $delivery_persons_list_result;

		}


		function get_activated_cities()
	    {
			$city_list_q=$this->dbobj->prepare("select city_id,state_id,city_name,state_name from city_master WHERE `status`=1 ORDER BY city_id ASC");
			$city_list_q->execute(array(0));
			$city_list_result=$city_list_q->fetchAll();
			return $city_list_result;
		}

		function get_deactivated_cities()
	    {
			$city_list_q=$this->dbobj->prepare("select city_id,state_id,city_name,state_name from city_master WHERE `status`=0 ORDER BY city_id ASC");
			$city_list_q->execute(array(0));
			$city_list_result=$city_list_q->fetchAll();
			return $city_list_result;
		}


	    function get_states_list()
	    {
	    	$state_q="select state_id,state_name from city_master group by state_id order by state_name asc";
			$state_q_ex=$this->dbobj->prepare($state_q);
			$state_q_ex->execute();
			$state_q_result=$state_q_ex->fetchAll();
			return $state_q_result;
	    }

	   	function get_all_cities_list()
	    {
	    	//This function fetches all cities list
	    	$city_q="select city_id,city_name from city_master order by city_name asc";
			$city_q_ex=$this->dbobj->prepare($city_q);
			$city_q_ex->execute();
			$city_q_result=$city_q_ex->fetchAll();
			return $city_q_result;
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


	   	function get_activated_stations()
	    {
			$stations_list_q=$this->dbobj->prepare("select station_id,station_name,city_master.city_name from station_master JOIN city_master ON station_master.city_id=city_master.city_id WHERE station_master.status=? ORDER BY station_id ASC");
			$stations_list_q->execute(array(1));
			$stations_list_result=$stations_list_q->fetchAll();
			return $stations_list_result;
		}

		function get_deactivated_stations()
	    {
			$stations_list_q=$this->dbobj->prepare("select station_id,station_name,city_master.city_name from station_master JOIN city_master ON station_master.city_id=city_master.city_id WHERE station_master.status=? ORDER BY station_id ASC");
			$stations_list_q->execute(array(0));
			$stations_list_result=$stations_list_q->fetchAll();
			return $stations_list_result;
		}



		function get_activated_offers()
	    {
			$offers_list_q=$this->dbobj->prepare("select offer_id,`offer_name`,`offer_code`,`percentage_or_flat`,`unit`,`discount_upto_rs`,`min_order_amount`,`start_date`,`expiry_date`,`usage_per_user`,`description`,`status`,`created_at` from offers WHERE `status`=? ORDER BY created_at DESC");

			$offers_list_q->execute(array(1));
			$offers_list_result=$offers_list_q->fetchAll();
			return $offers_list_result;
		}

		function get_deactivated_offers()
	    {
			$offers_list_q=$this->dbobj->prepare("select offer_id,`offer_name`,`offer_code`,`percentage_or_flat`,`unit`,`discount_upto_rs`,`min_order_amount`,`start_date`,`expiry_date`,`usage_per_user`,`description`,`status`,`created_at` from offers WHERE `status`=? ORDER BY created_at DESC");

			$offers_list_q->execute(array(0));
			$offers_list_result=$offers_list_q->fetchAll();
			return $offers_list_result;
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
			$orders_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name, orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.order_status=? and orders.payment_status=? ORDER BY orders.create_date DESC");
			$orders_q->execute(array(0,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_accepted_orders()
		{
			//Fetch current all orders
			$orders_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name, orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.order_status=? and orders.payment_status=? ORDER BY orders.create_date DESC");
			$orders_q->execute(array(1,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_prepared_orders()
		{
			//Fetch current all orders
			$orders_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name, orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.order_status=? and orders.payment_status=? ORDER BY orders.create_date DESC");
			$orders_q->execute(array(2,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_picked_orders()
		{
			//Fetch current all orders
			$orders_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name, orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.order_status=? and orders.payment_status=? ORDER BY orders.create_date DESC");
			$orders_q->execute(array(3,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_delivered_orders()
		{
			//Fetch current all orders
			$orders_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name, orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.order_status=? and orders.payment_status=? ORDER BY orders.create_date DESC");
			$orders_q->execute(array(4,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_current_rejected_orders()
		{
			//Fetch current all orders
			$orders_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name, orders.auto_order_id,orders.order_id,orders.end_customer_name,orders.end_customer_contact,orders.delivery_address,orders.delivery_station,orders.order_time,orders.delivery_person_id,orders.order_amount,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.order_status=? and orders.payment_status=? ORDER BY orders.create_date DESC");
			$orders_q->execute(array(-1,1));
			$orders_result=$orders_q->fetchAll();
			return $orders_result;
		}

		function fetch_rest_pending_payments()
		{
			//Fetch pending payments of restaurants
			$payments_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name,restaurants.account_no,restaurants.IFSC_code,orders.auto_order_id,orders.order_id,orders.order_amount,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.order_status!=? and orders.is_restaurant_paid=? ORDER BY orders.create_date ASC");
			$payments_q->execute(array(-1,0));
			$payments_result=$payments_q->fetchAll();
			return $payments_result;
		}

		function fetch_rest_paid_payments()
		{
			//Fetch paid payments to restaurants
			$payments_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name,restaurants.account_no,restaurants.IFSC_code,orders.auto_order_id,orders.order_id,orders.order_amount,orders.rest_payment_date,orders.create_date from orders JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.is_restaurant_paid=? ORDER BY orders.create_date ASC");
			$payments_q->execute(array(1));
			$payments_result=$payments_q->fetchAll();
			return $payments_result;
		}

		function fetch_payments_paid_by_customer()
		{
			//Fetch paid payments of customer
			$payments_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name,customer.customer_name,customer.customer_email,orders.auto_order_id,orders.order_id,orders.order_amount,orders.payment_id,orders.create_date from orders JOIN customer on orders.customer_id=customer.customer_id JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id ORDER BY orders.create_date DESC");
			$payments_q->execute();
			$payments_result=$payments_q->fetchAll();
			return $payments_result;
		}

		function fetch_customer_refunded_payments()
		{
			//Fetch refunded payments to customer
			$payments_q=$this->dbobj->prepare("select city_master.city_name,restaurants.restaurant_name,customer.customer_name,customer.customer_email,orders.auto_order_id,orders.order_id,orders.order_amount,orders.payment_id,orders.create_date from orders JOIN customer on orders.customer_id=customer.customer_id JOIN restaurants on orders.restaurant_id=restaurants.restaurant_id JOIN city_master on restaurants.restaurant_city=city_master.city_id where orders.is_refunded=? ORDER BY orders.create_date DESC");
			$payments_q->execute(array(1));
			$payments_result=$payments_q->fetchAll();
			return $payments_result;
		}




		//Report generation starts

		function count_new_orders()
		{
			$new_orders_q=$this->dbobj->prepare("select count(*) from orders where order_status!=? and order_status!=?");
			$new_orders_q->execute(array(-1,4));
			$new_orders_q_result=$new_orders_q->fetch(PDO::FETCH_COLUMN);
			return $new_orders_q_result;
		}


		function count_this_month_orders()
		{
			$current_month=date('m');
			$current_year=date('Y');

			$this_month_orders_q=$this->dbobj->prepare("select count(*) FROM orders WHERE MONTH(create_date)=? AND YEAR(create_date)=?");
			$this_month_orders_q->execute(array($current_month,$current_year));
			$this_month_orders=$this_month_orders_q->fetch(PDO::FETCH_COLUMN);
			return $this_month_orders;
		}

		function count_payments_in_today()
		{

			$payments_in_today_q=$this->dbobj->prepare("select sum(order_amount) FROM orders WHERE DATE(create_date)=DATE(NOW())");
			$payments_in_today_q->execute();
			$payments_in_today=$payments_in_today_q->fetch(PDO::FETCH_COLUMN);
			//if no records found, then sum() in query returns null
			if($payments_in_today==null || $payments_in_today=='')
			{
				$payments_in_today=0;
			}

			return $payments_in_today;
		}

		function count_payments_out_today()
		{
			$commission=20;//in percentage
			$payments_out_today=$this->count_payments_in_today()-($this->count_payments_in_today()*$commission/100);
			
			return $payments_out_today;
		}

		function count_profit_today()
		{
			$profit_today=$this->count_payments_in_today()-$this->count_payments_out_today();
			return $profit_today;
		}


		function count_payments_in_this_month()
		{
			$current_month=date('m');
			$current_year=date('Y');

			$payments_in_this_month_q=$this->dbobj->prepare("select sum(order_amount) FROM orders WHERE MONTH(create_date)=? AND YEAR(create_date)=?");
			$payments_in_this_month_q->execute(array($current_month,$current_year));

			$payments_in_this_month=$payments_in_this_month_q->fetch(PDO::FETCH_COLUMN);

			//if no records found, then sum() in query returns null
			if($payments_in_this_month==null || $payments_in_this_month=='')
			{
				$payments_in_this_month=0;
			}

			return $payments_in_this_month;
		}

		function count_payments_out_this_month()
		{
			$commission=20;//in percentage
			$payments_out_this_month=$this->count_payments_in_this_month()-($this->count_payments_in_this_month()*$commission/100);
			
			return $payments_out_this_month;
		}

		function count_profit_this_month()
		{
			$profit_this_month=$this->count_payments_in_this_month()-$this->count_payments_out_this_month();
			return $profit_this_month;
		}


		//reports in charts
		function orders_this_week()
		{
			//monday as first date of week
			$weekstart = date('Y-m-d',strtotime('monday this week'));
    		$weekstop = date('Y-m-d',strtotime('sunday this week 23:59:59'));

			$orders_this_week_q=$this->dbobj->prepare("select DATE(create_date) as period,count(order_id) as total FROM orders WHERE DATE(create_date) BETWEEN DATE(?) AND DATE(?) GROUP BY DATE(create_date)");
			$orders_this_week_q->execute(array($weekstart,$weekstop));

			$orders_this_week=$orders_this_week_q->fetchAll();

			return $orders_this_week;
		}



	}

?>