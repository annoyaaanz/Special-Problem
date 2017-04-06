<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $username = $_REQUEST['traveler-username'];

    $check_user = mysqli_query($connect, "SELECT * FROM traveler_info WHERE traveler_username = '$username'");
    if(mysqli_num_rows($check_user) == 1){
    	echo json_encode(1);
    }
    else {
    	echo json_encode(0);
    }