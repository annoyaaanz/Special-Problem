<?php

$host ='localhost';
$db = 'wdadviser';
$username = 'root';
$password ='';
$dbconn = mysqli_connect($host,$username,$password,$db) or die("Could not connect to database!");
$res_id = $_REQUEST['id'];

$query = "SELECT * FROM lgu_alerts WHERE household_id = $res_id ";
$result = mysqli_query($dbconn,$query);					   
// $rows = mysqli_fetch_object($result);
$rows = $result->num_rows;
echo json_encode($rows);


?>