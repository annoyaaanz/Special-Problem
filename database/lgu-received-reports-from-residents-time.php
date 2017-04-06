<?php 
	$connect = mysqli_connect("localhost", "root", "", "wdadviser");

	$date_1 = $_REQUEST['date_1'];
	$date_2 = $_REQUEST['date_2'];
	$months = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	
	$table = "<div>&nbsp;</div>";
	if($date_2 == null || $date_1 == null){
		$table.= "<div class=\"panel panel-danger\">";
		$table.= "<div class=\"panel-heading\"><h4>Safehouse Requests</h4></div>";
		$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-times-circle fa-fw\"></span>&nbsp;Please make sure to fill in both fields properly!</h4></div></div>";
		$table.= "</div>";
	}
	else {
		$date_1_t = strtotime($date_1." 00:00:00");
		$date_2_t = strtotime($date_2." 23:59:59");

		$date_1_new = date('Y-m-d H:i:s', $date_1_t);
		$date_2_new = date('Y-m-d H:i:s', $date_2_t);

		$month_num_f = explode("-", $date_1);
		$month_num_s = explode("-", $date_2);

		$month_num_1 = (int)$month_num_f[1];
		$month_string_1 = $months[$month_num_1];
		$month_num_2 = (int)$month_num_s[1];
		$month_string_2 = $months[$month_num_2];

		if($date_1 > $date_2){
			$table.= "<div class=\"panel panel-danger\">";
			$table.= "<div class=\"panel-heading\"><h4>Reports</h4></div>";
			$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-times-circle fa-fw\"></span>&nbsp;Please make sure that you put the right values!</h4></div></div>";
			$table.= "</div>";
		}
		else {
			$query_all_reports = mysqli_query($connect, "SELECT * FROM reports WHERE time_sent > '$date_1_new' AND time_sent < '$date_2_new' ORDER BY time_sent DESC");
			$rows = mysqli_num_rows($query_all_reports);
			if($rows == 0){
				$table.= "<div class=\"panel panel-warning\">";
				$table.= "<div class=\"panel-heading\"><h4>Reports</h4></div>";
				$table.= "<div class=\"panel-body\"><div class=\"alert alert-warning\"><h4><span class=\"fa fa-exclamation-circle fa-fw\"></span>&nbsp;There are no reports received during this date!</h4></div></div>";
				$table.= "</div>";
			}
			else{	
				$table.= "<div class=\"panel panel-success\">";
				$table.= "<div class=\"panel-heading\"><h4>Reports Received (".$month_string_1." ".$month_num_f[2].", ".$month_num_f[0]." - ".$month_string_2." ".$month_num_s[2].", ".$month_num_s[0].")</h4></div>";
				$table.= "<div class=\"panel-body\">";
		        $table.= "<div class=\"table-responsive\">";
		        $table.= "<table width=\"100%\" class=\"table\">";
		        $table.= "<thead>";
		        $table.= "<tr>";
		        $table.= "<th>Household Head</th>";
		        $table.= "<th>Category</th>";
		        $table.= "<th>Message</th>";
		        $table.= "<th class=\"center-text\">Date and Time</th>";
		        $table.= "</tr>";
		        $table.= "</thead>";
		        $table.= "<tbody>";
		        $num = 0;
				while($report = mysqli_fetch_assoc($query_all_reports)){
		            $timestamp = explode(" ", $report['time_sent']);
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
					$table.="<td>".$name_ret."</td>";
					$table.="<td>".$report['category']."</td>";
					$table.="<td><p style=\"width: 550px; word-break: keep-all;\">".$report['report_details']."</p></td>";
					$table.="<td class=\"center-text\"> $date_month $date_day, $date_year at $fin_hr:$new_min $iden</td>";
					$table.="</tr>";
				}
				$table.= "</tbody>";
				$table.= "</table>";
				$table.= "</div>";
				$table.="</div><!-- panel body -->";
				$table.="</div><!-- panel -->";
			}	
		}
	}
	echo json_encode($table);