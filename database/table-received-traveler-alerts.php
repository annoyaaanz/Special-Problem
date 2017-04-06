<?php    
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $table_alerts = "<div class=\"panel panel-success\">";
    $table_alerts.= "<div class=\"panel-heading\"><h4>";
    $table_alerts.="Received Alerts from Travelers";
    $table_alerts.="</h4></div>";
    $table_alerts.="<div class=\"panel-body\">";
    $table_alerts.="<div class=\"table-responsive\">";
    $table_alerts.="<table width=\"100%\" class=\"table table-striped table-bordered table-hover\">";
    $table_alerts.="    <thead>";
    $table_alerts.="        <tr>";
    $table_alerts.="            <th class=\"center-text\">First Name</th>";
    $table_alerts.="            <th class=\"center-text\">Last Name</th>";
    $table_alerts.="            <th class=\"center-text\">Situation</th>";
    $table_alerts.="            <th class=\"center-text\">Message</th>";
    $table_alerts.="            <!-- <th class=\"center-text\">Location</th> -->";
    $table_alerts.="            <th class=\"center-text\">Status</th>";
    $table_alerts.="            <th colspan=\"2\" class=\"center-text\">Action</th>";
    $table_alerts.="        </tr>";
    $table_alerts.="    </thead>";
    $table_alerts.="    <tbody>";
    /* for retrieving all the hazards */
    $retrieve_all_alerts = mysqli_query($connect, "SELECT * FROM traveler_alerts ORDER BY traveler_alert_status DESC, traveler_insert_time DESC");
    $number_of_alerts = mysqli_num_rows($retrieve_all_alerts);
    $current_no = 0;
    while($alert = mysqli_fetch_assoc($retrieve_all_alerts)){
        $traveler_fname = $alert['traveler_first_name'];
        $traveler_lname = $alert['traveler_last_name'];
        $traveler_situation = $alert['traveler_alert_situation'];
        $traveler_message = $alert['traveler_alert_message'];
        $traveler_status = $alert['traveler_alert_status'];
        $disabled = "";
        $title = "";
        $delete_or_reject = "";
        $highlight = "";
        $highlight_form = "";
        if($traveler_status == 'Approved') {
            $confirm_box_accept = "";
            $confirm_box_delete  ="onclick=\"return confirm('Do you want to delete this alert?')\"";
            $highlight = "";
            $highlight_form = "";
            $delete_or_reject = "Delete";
            $disabled = "disabled='disabled'";
            $title="title=\"Alert already accepted\"";
        }else {
            $confirm_box_delete = "onclick=\"return confirm('Do you want to reject this alert?')\"";
            $confirm_box_accept = "onclick=\"return confirm('Do you want to accept this alert?')\""; 
            $highlight_form=" highlight";
            $highlight = " class=\"highlight\"";
            $delete_or_reject = "Reject";
            $disabled = "";
            $title="title=\"Accept Post\"";
        }
        $current_no = $current_no+1;
        $table_alerts.="    <tr class=\"plain\">";
        $table_alerts.="        <td$highlight>$traveler_fname</td>";
        $table_alerts.="        <td$highlight>$traveler_lname</td>";
        $table_alerts.="        <td$highlight>$traveler_situation</td>";
        $table_alerts.="        <td$highlight>$traveler_message</td>";
        $table_alerts.="        <!-- <td></td> -->";
        $table_alerts.="        <td$highlight>$traveler_status</td>";
        $table_alerts.="        ";
        $table_alerts.="            <td class=\"center-text$highlight_form\">";
        $table_alerts.="                <form action=\"#\" method=\"POST\"><input type=\"hidden\" name=\"traveler-alert-id\" value=\"".$alert['traveler_alert_id']."\" ><button type=\"submit\" class=\"btn btn-success\" $disabled name=\"accept-alert\" $confirm_box_accept value=\"Accept\" $title>Accept</button></form>";
        $table_alerts.="            </td>";
        $table_alerts.="        ";
        $table_alerts.="            <td class=\"center-text$highlight_form\">";
        $table_alerts.="                <form method=\"POST\" action=\"#\" ><input type=\"hidden\" name=\"traveler-alert-id\" value=\"".$alert['traveler_alert_id']."\"/><button type=\"submit\" class=\"btn btn-danger\" name=\"delete-alert\" $confirm_box_delete value=\"Delete\" title=\"Delete Post\">$delete_or_reject</button></form>";
        $table_alerts.="            </td>";
        $table_alerts.="        ";
        $table_alerts.="    </tr>";
    }
    $table_alerts.="    </tbody>";
    $table_alerts.="</table>";
    $table_alerts.="</div>";
    $table_alerts.="</div>";
    $table_alerts.="</div>";


    /* FOR SENT HAZARDS */     
    $query_all_hazards = mysqli_query($connect, "SELECT * FROM lgu_hazards ORDER BY lgu_hazard_time DESC");
    $rows = mysqli_num_rows($query_all_hazards);
    if($rows < 1){
        $panel = "danger";
    }
    else {
        $panel = "success";
    }
    $table_alerts.="<div class=\"panel panel-$panel\">";
    $table_alerts.="<div class=\"panel-heading\"><h4>Hazards Sent </h4></div>";
    $table_alerts.="<div class=\"panel-body\">";
    if($rows < 1){
        $table_alerts.= "<div class=\"alert alert-danger\">";
        $table_alerts.= "<h4><span class=\"fa fa-times-circle\"></span>&nbsp;There are no hazards yet!</h4>";
        $table_alerts.= "</div>";
    }
    else {
        $table_alerts.= "<div class=\"table-responsive\">";
        $table_alerts.= "<table width=\"70%\" class=\"table table-striped table-bordered table-hover\">";
        $table_alerts.= "<thead>";
        $table_alerts.= "<tr>";
        $table_alerts.= "<th class=\"center-text\">#</th>";
        $table_alerts.= "<th class=\"center-text\">Category</th>";
        $table_alerts.= "<th class=\"center-text\">Details</th>";
        $table_alerts.= "<th class=\"center-text\">Date and Time Sent</th>";
        $table_alerts.= "<th class=\"center-text\">Action</th>";
        $table_alerts.= "<tr>";
        $table_alerts.= "</thead>";
        $table_alerts.= "<tbody>";
        $num = 0;
        while($hazards = mysqli_fetch_assoc($query_all_hazards)){
            $months = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $timestamp = explode(" ", $hazards['lgu_hazard_time']);
            $date_not_formatted = $timestamp[0];
            $date = date('m-d-Y', strtotime($date_not_formatted));
            $date_arr = explode("-", $date);
            $date_month_num = $date_arr[0]+0;
            $date_month = $months[$date_month_num];
            $date_day = $date_arr[1];
            $date_year = $date_arr[2];
            $time = $timestamp[1];
            $time_arr = explode(":", $time);
            $hr = $time_arr[0]+0;
            $min = $time_arr[1]+0;

            if($hr>=12) $iden = "PM";
            else $iden = "AM";
            if($iden == "PM") $new_hr = $hr - 12;
            else $new_hr = $hr;
            if($new_hr < 10) $fin_hr = "0".$new_hr;
            else $fin_hr = $new_hr;
            if($min < 10) $new_min = "0".$min;
            else $new_min = $min;
            
            $num = $num+1;
            $table_alerts.= "<tr>";
            $table_alerts.= "<td class=\"center-text\">$num</td>";
            $table_alerts.= "<td class=\"center-text\">".$hazards['hazard_category']."</td>";
            $table_alerts.= "<td class=\"center-text\">".$hazards['hazard_details']."</td>";
            $table_alerts.= "<td class=\"center-text\">".$date_month." ".$date_day.", ".$date_year." at $fin_hr:$new_min $iden </td>";
            $table_alerts.= "<td class=\"center-text\"><form method=\"POST\" action=\"#\"><input type=\"hidden\" value=\"".$hazards['hazard_id']."\" name=\"hazard_id\"/><button type=\"submit\" class=\"btn btn-danger\" id=\"remove-hazard\" name=\"remove-hazard\" onclick=\"return confirm('Are you sure you want to delete this hazard?')\">Delete</button><input type=\"hidden\" name=\"success-message\" value=\"The hazard has been deleted!.\"/></td></form>";
            $table_alerts.= "</tr>";
        }
    }
    echo json_encode($table_alerts);