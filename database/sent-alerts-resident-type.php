<?php
	$connect = mysqli_connect("localhost", "root", "", "wdadviser");
	$table = "<div>&nbsp;</div>";
	if(isset($_REQUEST['type'])==null){
		$table.= "<div class=\"panel panel-danger\">";
		$table.= "<div class=\"panel-heading\"><h4>Alerts Sent</h4></div>";
		$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-exclamation-circle fa-fw\"></span>&nbsp;Please select an alert type!</h4></div></div>";
		$table.= "</div>";
	}
	else if($_REQUEST['type'] == null || $_REQUEST['type'] == ""){
		$table.= "<div class=\"panel panel-danger\">";
		$table.= "<div class=\"panel-heading\"><h4>Alerts Sent</h4></div>";
		$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-exclamation-circle fa-fw\"></span>&nbsp;Please select an alert type!</h4></div></div>";
		$table.= "</div>";	
	}
	else {
		$cat = $_REQUEST['type'];
		$query_reports = mysqli_query($connect, "SELECT * FROM lgu_alerts WHERE alert_type = '$cat' ORDER BY lgu_alert_time_inserted DESC");
		
		$table.= "<div class=\"panel panel-success\">";
		$table.= "<div class=\"panel-heading\"><h4>Alerts Sent (Alert Type: $cat)</h4></div>";
		$table.= "<div class=\"panel-body\">";
        $table.= "<div class=\"table-responsive\">";
        $table.= "<table width=\"100%\" class=\"table\">";
        $table.= "<thead>";
        $table.= "<tr>";
        $table.= "<th style=\"width: 170px;\">Household Head</th>";
        $table.= "<th>Message</th>";
        $table.= "<th  style=\"width: 240px;\"class=\"center-text\">Date and Time</th>";
        $table.= "</tr>";
        $table.= "</thead>";
        $table.= "<tbody>";
        $num = 0;
		while($report = mysqli_fetch_assoc($query_reports)){
			$months = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $timestamp = explode(" ", $report['lgu_alert_time_inserted']);
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
			$hh_id = $report['household_id'];
			$query_hh_name = mysqli_query($connect, "SELECT household_head_name FROM resident_household WHERE household_id = $hh_id");
			$name_arr = mysqli_fetch_array($query_hh_name);
			$name_ret = $name_arr['household_head_name'];
			$table.="<tr>";
			$table.="<td>$name_ret</td>";
			$table.="<td><p style=\"width: 500px; word-break: keep-all;\">".$report['alert_message']."</p></td>";
			$table.="<td class=\"center-text\"> $date_month $date_day, $date_year at $fin_hr:$new_min $iden</td>";
			$table.="</tr>";
		}
		$table.="</tbody>";
		$table.="</table>";
		$table.="</div>";
		$table.="</div>";
	}
	echo json_encode($table);
