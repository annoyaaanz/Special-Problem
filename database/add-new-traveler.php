<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $uname = $_REQUEST['username'];
    $fname = $_REQUEST['first-name'];
    $lname = $_REQUEST['last-name'];
    $email = $_REQUEST['email'];
    $mobile_num = $_REQUEST['mobile-number'];
    $password = $_REQUEST['password'];

    $insert_traveler = mysqli_query($connect, "INSERT INTO traveler_info VALUES (0, '$fname', '$lname', '$email', '$mobile_num', '$uname', md5('$password'))");

    if(mysqli_affected_rows($connect) >= 1){
    	echo json_encode('success');
    }
    else {
    	echo json_encode('fail');
    }
    ?>