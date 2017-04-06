<?php

$dbconn = mysqli_connect("localhost", "root", "", "wdadviser") or die("Could not connect to database!");
$description = $_POST['description'];
$alert_type = $_POST['alert_type'];
$household_id = $_POST['household_id'];
if(isset($household_id)){  
$query = "INSERT INTO lgu_alerts(`alert_id`, `alert_message`, `alert_type`, `household_id`,`lgu_office_id`) VALUES(0,'$description','$alert_type','$household_id',3)";

mysqli_query($dbconn, $query);
$rows_affected = mysqli_affected_rows($dbconn);

if($rows_affected >= 1){
 	echo json_encode("Success!");
}
}
?>