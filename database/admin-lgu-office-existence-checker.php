<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $lgu_office_name = $_GET['lgu_office_name'];

    $check_office = mysqli_query($connect, "SELECT * FROM lgu_office WHERE lgu_agency = '$lgu_office_name'");
    $retrieve_office = mysqli_fetch_array($check_office);
    $nums = mysqli_num_rows($check_office);
    if($nums == 0){
    	echo json_encode(0);
    }
    else {
    	echo json_encode(1);
    }