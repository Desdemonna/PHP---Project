<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "pizza_orders";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn)
{
	die("Имаме сериозен проблем!");
}

?>

