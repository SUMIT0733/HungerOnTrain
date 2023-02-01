<?php
	include_once("dbConnect.php");
	$id = $_REQUEST['id'];
	$sql1 = "SELECT menu.food_item_id,menu.food_item_name,menu.food_item_price,cuisines.cuisine_name,menu.ingredients,menu.Veg from menu JOIN cuisines ON menu.cuisine_id=cuisines.cuisine_id WHERE menu.restaurant_id = {$id} && menu.status = 1";
	$res = mysqli_query($con,$sql1) or die("error in query execution");

	if(mysqli_num_rows($res)>0)
	{
		
			while ($raw = mysqli_fetch_assoc($res)) 
			{
				$val['responce'] = "Success";
				$val['menu'][] = $raw;
			}
	}
	else
	{
		$val['responce']="No Record Found";
	}
	echo json_encode($val);
	mysqli_close($con);

?>