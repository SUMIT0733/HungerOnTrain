<?php
require_once('admin_class.php');

$rest=new Admin($db);
date_default_timezone_set('Asia/Calcutta');

if(isset($_POST['action']) && $_POST['action']=='add_new_offer'){
	$offer_name=$_POST['offer_name'];
	$offer_code=$_POST['offer_code'];
	$perc_or_flat=$_POST['perc_or_flat'];
	$discount_units=$_POST['discount_units'];
	$discount_upto_rs=$_POST['discount_upto_rs'];
	$min_order_amount=$_POST['min_order_amount'];
	$start_date=$_POST['start_date'];
	$expiry_date=$_POST['expiry_date'];
	$usage_per_user=$_POST['usage_per_user'];
	$offer_description=$_POST['offer_description'];
	$status=$_POST['status'];


	if($offer_name==null || $offer_code==null || $perc_or_flat==null || $discount_units==null || $discount_upto_rs==null || $min_order_amount==null || $start_date==null || $expiry_date==null || $usage_per_user==null || $offer_description==null || $status==null )
	{
		echo "Please fill all fields";
	}
	else
	{

/*		$check_q=$db->prepare("select offer_id from offers where offer_code=:off_code and status=:stts");

		$check_q->execute(array('off_code'=>$offer_code,'stts'=>1));
		$result=$check_q->fetchAll();
		if(count($result)!=0)
		{
			echo "Offer is already active";
		}
		else
		{*/
			//Here directly $status==true or false not working. also if($status){}else{} not working.

			//Returns TRUE for "1", "true", "on" and "yes". Returns FALSE otherwise.
			$bool1 = filter_var($status, FILTER_VALIDATE_BOOLEAN);

			if($bool1==1)
			{
				$status=1;
			}
			else
			{
				$status=0;
			}

			$bool2 = filter_var($perc_or_flat, FILTER_VALIDATE_BOOLEAN);

			if($bool2==1)
			{
				$perc_or_flat=1;
			}
			else
			{
				$perc_or_flat=0;
			}

			$create_datetime=date("Y-m-d H:i:s");

			$insert_q=$db->prepare("insert into offers(`offer_name`,`offer_code`,`percentage_or_flat`,`unit`,`discount_upto_rs`,`min_order_amount`,`start_date`,`expiry_date`,`usage_per_user`,`description`,`status`,`created_at`)values(:offr_name,:offr_code,:per_or_flat,:unit,:discount_upto,:min_amount,:strt_date,:expr_date,:usage,:desc,:stts,:creat_at)");

/*			echo "insert into offers(`offer_name`,`offer_code`,`percentage_or_flat`,`unit`,`start_date`,`expiry_date`,`usage_per_user`,`description`,`status`,`created_at`)values($offer_name,$offer_code,$perc_or_flat, $discount_units,$discount_upto_rs,$start_date,$expiry_date,$usage_per_user,$offer_description,$status,$create_datetime";
*/

			$inserted=$insert_q->execute(array('offr_name'=>$offer_name,'offr_code'=>$offer_code,'per_or_flat'=>$perc_or_flat,'unit'=>$discount_units,'discount_upto'=>$discount_upto_rs,'min_amount'=>$min_order_amount,'strt_date'=>$start_date,'expr_date'=>$expiry_date,'usage'=>$usage_per_user,'desc'=>$offer_description,'stts'=>$status,'creat_at'=>$create_datetime));


			//execute() returns true of false.
			if($inserted)
			{
				echo "success";
			}
			else{
				echo "Something went wrong hhgfh";
			}

		//}
		//end else

	}
}
else if(isset($_POST['deactivate_offer_id'])){
	$offer_id=json_decode($_POST['deactivate_offer_id']);
	$update_q=$db->prepare("update offers set `status`=? where `offer_id`=?");
	$update_q->execute(array(0,$offer_id));
	echo "success";
}
else if(isset($_POST['activate_offer_id'])){
	$offer_id=json_decode($_POST['activate_offer_id']);
	$update_q=$db->prepare("update offers set `status`=? where `offer_id`=?");
	$update_q->execute(array(1,$offer_id));
	echo "success";
}
else{
	echo "Something went wrong";
}



?>