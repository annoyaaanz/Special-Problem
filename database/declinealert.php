<?php

$dbconn = mysqli_connect("localhost", "root", "", "wdadviser") or die("Could not connect to database!");
$id = $_POST['traveler_alert_id'];
if(isset($id)){  
$query = "DELETE FROM traveler_alerts WHERE `traveler_alert_id` = $id";
mysqli_query($dbconn, $query);

$rows_affected = mysqli_affected_rows($dbconn);

if($rows_affected >= 1){
 	echo json_encode("Success!");
}
}
?>