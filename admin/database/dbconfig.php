<?php

include('connection.php');

try
{
	$database = new Connection();
	$db = $database->openConnection();
}
catch (PDOException $e)
{
	echo "There is some problem in connection: " . $e->getMessage();
}

?>