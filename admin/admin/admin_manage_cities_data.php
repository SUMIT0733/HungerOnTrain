<?php
require_once('admin_class.php');

$rest=new Admin($db);
if(isset($_POST['action']) && $_POST['action']=='add_city'){
	$state_id=$_POST['state_id'];
	$city_name=strtoupper($_POST['city_name']);
	$status=$_POST['status'];

	if($state_id=='? undefined:undefined ?' || $state_id==null || $city_name=='? undefined:undefined ?' || $city_name==null || $status==null)
	{
		echo "Please fill all fields";
	}
	else
	{

		$check_q=$db->prepare("select city_id from city_master where city_name=:ct_name");

		$check_q->execute(array('ct_name'=>$city_name));
		$result=$check_q->fetchAll();
		if(count($result)!=0)
		{
			echo "City has already been added";
		}
		else
		{
			//Here directly $status==true or false not working. also if($status){}else{} not working.

			//Returns TRUE for "1", "true", "on" and "yes". Returns FALSE otherwise.
			$bool = filter_var($status, FILTER_VALIDATE_BOOLEAN);

			if($bool==1)
			{
				$status=1;
			}
			else
			{
				$status=0;
			}

			$max_city_id_q=$db->prepare("SELECT MAX(city_id) FROM city_master");
			$max_city_id_q->execute();
			$max_city_id=$max_city_id_q->fetchColumn();

			$new_city_id=$max_city_id+1;

			$state_name_q=$db->prepare("SELECT state_name FROM city_master where state_id=?");
			$state_name_q->execute(array($state_id));
			$state_name=$state_name_q->fetchColumn();

			$insert_q=$db->prepare("insert into city_master(`city_id`,`city_name`,`state_id`,`state_name`,`status`)values(:ct_id,:ct_name,:st_id,:st_name,:status)");

			$inserted=$insert_q->execute(array('ct_id'=>$new_city_id,'ct_name'=>$city_name,'st_id'=>$state_id,'st_name'=>$state_name,'status'=>$status));

			//execute() returns true of false.
			if($inserted)
			{
				echo "success";
			}
			else{
				echo "Something went wrong";
			}

		}

	}
}
else if(isset($_POST['activate_city_id'])){
	$city_id=json_decode($_POST['activate_city_id']);
	$update_q=$db->prepare("update city_master set `status`=? where `city_id`=?");
	$update_q->execute(array(1,$city_id));
	echo "success";
}
else if(isset($_POST['deactivate_city_id'])){
	$city_id=json_decode($_POST['deactivate_city_id']);
	$update_q=$db->prepare("update city_master set `status`=? where `city_id`=?");
	$update_q->execute(array(0,$city_id));
	echo "success";
}
else{
	echo "Something went wrong";
}



?>