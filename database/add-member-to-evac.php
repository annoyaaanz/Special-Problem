<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $members = $_REQUEST['members'];
    $evac_id = $_REQUEST['evac_id'];
    $hhid = $_REQUEST['hh_id'];
    $members_arr = explode(",", $members);
    
    $total_pop = mysqli_query($connect, "SELECT evacuation_center_capacity FROM evacuation_centers WHERE evacuation_center_id = $evac_id");
    $pops = mysqli_query($connect, "SELECT COUNT(*) as pop FROM evacuation_center_population WHERE evacuation_center_id ");
    $arr_pop = mysqli_fetch_assoc($total_pop);
    $t_pop = (int)$arr_pop['evacuation_center_capacity'];
    $curr_arr = mysqli_fetch_assoc($pops);
    $curr_len = (int)$curr_arr['pop'];

    // available
    $av = $t_pop - $curr_len;

    $count = -1;
    $success = -1;
    $ret_val = -1;
    $granted = -1;
    $str = "";
    do{
        if($members_arr[0] != null){
            $success = (count($members_arr));
            if($success > $av){
                break;
            }
            $count = 0;
            while($count < $success){
                $id = $members_arr[$count];
                $query_add = mysqli_query($connect, "INSERT INTO evacuation_center_population VALUES (0, $evac_id, $hhid, $id)");
                $str.="$id ";   
                $count++;
            }   
        }
    }while(false);
    if($count == $success) $ret_val = 1;
    else if($granted == -1) $ret_val = -1;
    else $ret_val = 0;
    echo json_encode($ret_val);