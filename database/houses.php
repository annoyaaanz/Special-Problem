<?php

$host ='localhost';
$db = 'wdadviser';
$username = 'root';
$password ='';
$dbconn = mysqli_connect($host,$username,$password,$db) or die("Could not connect to database!");

	$query = "SELECT household_id,location,household_head_name FROM resident_household";
	$result = mysqli_query($dbconn,$query);					   
	
while ($row=mysqli_fetch_object($result)){
 $data[]=$row;
}
echo json_encode($data);
//$kek = $data[0];
//print_r($kek);
//echo $kek->location;
?>