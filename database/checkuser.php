<?php

$dbconn = mysqli_connect("localhost", "root", "", "wdadviser") or die("Could not connect to database!");
$userinfo = json_encode($_POST['username']);
if(isset($userinfo))
 {
	$query = "SELECT * FROM resident_household WHERE username = $userinfo ";
	$result = mysqli_query($dbconn,$query);					   
	if (mysqli_num_rows($result)==0) { 
		echo json_encode(0);
	}
	else{
		echo json_encode(1);
	}

 }
?>