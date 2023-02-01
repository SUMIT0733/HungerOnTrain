<?php
require_once('admin_class.php');

$rest=new Admin($db);
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['action']) && $_POST['action']=='add_station'){
	$city_id=$_POST['city_id'];
	$station_name=$_POST['station_name'];
	$status=$_POST['status'];

	if($city_id=='? undefined:undefined ?' || $city_id==null || $station_name=='? undefined:undefined ?' || $station_name==null || $status==null)
	{
		echo "Please fill all fields";
	}
	else
	{

		$check_q=$db->prepare("select station_id from station_master where station_name=:stn_name");

		$check_q->execute(array('stn_name'=>$station_name));
		$result=$check_q->fetchAll();
		if(count($result)!=0)
		{
			echo "Station has already been added";
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

			$create_datetime=date("Y-m-d H:i:s");

			$insert_q=$db->prepare("insert into station_master(`station_name`,`city_id`,`status`,`created_at`)values(:st_name,:ct_id,:status,:created_at)");

			$inserted=$insert_q->execute(array('st_name'=>$station_name,'ct_id'=>$city_id,'status'=>$status,'created_at'=>$create_datetime));

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
	/*$update_q=$db->prepare("update city_master set `status`=? where `city_id`=?");
	$update_q->execute(array(1,$city_id));
	echo "success";*/
}
else if(isset($_POST['deactivate_station_id'])){
	$station_id=json_decode($_POST['deactivate_station_id']);
	$update_q=$db->prepare("update station_master set `status`=? where `station_id`=?");
	$update_q->execute(array(0,$station_id));
	echo "success";
}
else if(isset($_POST['activate_station_id'])){
	$station_id=json_decode($_POST['activate_station_id']);
	$update_q=$db->prepare("update station_master set `status`=? where `station_id`=?");
	$update_q->execute(array(1,$station_id));
	echo "success";
}
else{
	echo "Something went wrong";
}



?>