<?php  
require_once('database/dbconfig.php');
$state_id=$_POST['state_id'];
$city_q="select city_id,city_name from city_master where state_id='$state_id' order by city_name asc";
$city_q_ex=$db->query($city_q);
echo "<option value=\"\">Select City</option>";
foreach($city_q_ex as $row)
{
	echo "<option value=".$row['city_id'].">".$row['city_name']."</option>";
}
?>