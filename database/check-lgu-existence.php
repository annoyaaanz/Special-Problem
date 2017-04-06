<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $lgu_exists_query = mysqli_query($connect, "SELECT * FROM lgu_official WHERE lgu_username = '$username' AND password = md5('$password')");
    $retrieve_lgu = mysqli_fetch_array($lgu_exists_query);
    $num_lgu = mysqli_num_rows($lgu_exists_query);
    if($num_lgu < 1){
    	echo json_encode(0);
    }
    else {
    	echo json_encode(1);
    }