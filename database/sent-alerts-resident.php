<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    if(isset($_REQUEST['hh_id'])==null){
    	$hh_id = -1;
    }
    else {
    	$hh_id = $_REQUEST['hh_id'];
    	$hh_name = mysqli_query($connect, "SELECT household_head_name FROM resident_household WHERE household_id = $hh_id");
    	$arr_n = mysqli_fetch_assoc($hh_name);
    	$name = $arr_n['household_head_name'];
    	$all_reports = mysqli_query($connect, "SELECT * FROM lgu_alerts WHERE household_id = $hh_id ORDER BY lgu_alert_time_inserted DESC");
    	$rows = mysqli_num_rows($all_reports);
    	$table = "";
    	if($rows<1){
			$table.= "<div class=\"panel panel-danger\">";
			$table.= "<div class=\"panel-heading\"><h4>Alerts Sent to $name's Household</h4></div>";
			$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-times-circle fa-fw\"></span>&nbsp;There are no reports sent to this household yet!</h4></div></div>";
			$table.= "</div>";
    	}
    	else {
			$table.= "<div>&nbsp;</div>";
			$table.= "<div class=\"panel panel-success\">";
			$table.= "<div class=\"panel-heading\"><h4>Alerts Sent to $name's Household</h4></div>";
			$table.= "<div class=\"panel-body\">";
			$table.= "<div class=\"table-responsive\">";
	        $table.= "<table width=\"100%\" class=\"table\">";
	        $table.= "<thead>";
	        $table.= "<tr>";
	        $table.= "<th style=\"width: 200px;\">Alert Type</th>";
	        $table.= "<th style=\"width: 480px;\">Message</th>";
	        $table.= "<th  style=\"width: 200px;\" class=\"center-text\">Date and Time</th>";
	        $table.= "</tr>";
	        $table.= "</thead>";
	        $table.= "<tbody>";
			while($alert = mysqli_fetch_assoc($all_reports)){
	            $months = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	            $timestamp = explode(" ", $alert['lgu_alert_time_inserted']);
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

				$table.="<tr>";
				$table.="<td style=\"width: 200px; word-break: keep-all;\"><p>".$alert['alert_type']."</p></td>";
				$table.="<td style=\"width: 480px;\"><p style=\"word-break: break-all;\">".$alert['alert_message']."</p></td>";
				$table.="<td style=\"width: 200px; \"class=\"center-text\"><p style=\"word-break: keep-all;\">$date_month $date_day, $date_year at $fin_hr:$new_min $iden</p></td>";
				$table.="</tr>";
			}
			$table.="</tbody>";
			$table.="</table>";
			$table.="</div>";
			$table.="</div>";
			$table.="</div>";
    	}
    }
    echo json_encode($table);
