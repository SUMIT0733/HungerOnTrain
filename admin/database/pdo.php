<?php

include('connection.php');

try
{
	$database = new Connection();
	$db = $database->openConnection();
	
	$restaurant_id=1;
	$cuisine_list_q=$db->prepare("select cuisines.cuisine_name from menu JOIN cuisines ON menu.cuisine_id=cuisines.cuisine_id where restaurant_id=? GROUP BY menu.cuisine_id");
	$cuisine_list_q->execute(array($restaurant_id));
	$result=$cuisine_list_q->fetchAll(PDO::FETCH_COLUMN);
	print_r($result);


	$sql = "SELECT * FROM restaurants " ;
	
	$sql_exec=$db->query($sql);
	
	$result=$sql_exec->fetchAll();
	echo $cnt=count($result);

	/*print_r($result);
	foreach($result as $row)
	{
		echo $row['restaurant_name'];
	}

	foreach ($sql_exec as $row) {
		echo " restaurant name: ".$row['restaurant_name'] . "<br>";
		echo " email: ".$row['email'] . "<br>";
	}*/

	



}
catch (PDOException $e)
{
	echo "There is some problem in connection: " . $e->getMessage();
}

?>