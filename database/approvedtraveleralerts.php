<?php

$host ='localhost';
$db = 'wdadviser';
$username = 'root';
$password ='';
$dbconn = mysqli_connect($host,$username,$password,$db) or die("Could not connect to database!");

	$query = "SELECT * FROM traveler_alerts WHERE traveler_alert_status='Approved'";
	$result = mysqli_query($dbconn,$query);					   
	
while ($row=mysqli_fetch_object($result)){
 $data[]=$row;
}
echo json_encode($data);
//$kek = $data[0];
//print_r($kek);
//echo $kek->location;
?>