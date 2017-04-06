<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $resident_exists_query = mysqli_query($connect, "SELECT * FROM resident_household WHERE username = '$username' AND password = md5('$password')");
    $retrieve_resident = mysqli_fetch_array($resident_exists_query);
    $num_res = mysqli_num_rows($resident_exists_query);
    if($num_res < 1){
    	echo json_encode(0);
    }
    else {
    	echo json_encode(1);
    }