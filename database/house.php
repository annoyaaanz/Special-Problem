<?php

$host ='localhost';
$db = 'wdadviser';
$username = 'root';
$password ='';
$dbconn = mysqli_connect($host,$username,$password,$db) or die("Could not connect to database!");
$userinfo = json_encode($_POST['username']);

if(isset($userinfo))
 {
	$query = "SELECT location FROM resident_household WHERE username = $userinfo ";
	$result = mysqli_query($dbconn,$query);					   
	$row = mysqli_fetch_object($result);
	echo json_encode($row);
 }


//while ($row=mysqli_fetch_object($result)){
// $data[]=$row;
//}

//$kek = $data[0];
//print_r($kek);
//echo $kek->location;
?>