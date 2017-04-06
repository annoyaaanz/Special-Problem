<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $query_all_household_ids = mysqli_query($connect, "SELECT DISTINCT household_id FROM reports ORDER BY household_id ASC");
    $rows = mysqli_num_rows($query_all_household_ids);
   	$table = "";
   	if($rows<1){
		$table.= "<div class=\"panel panel-danger\">";
		$table.= "<div class=\"panel-heading\"><h4>Safehouse Requests</h4></div>";
		$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-times-circle fa-fw\"></span>&nbsp;There are no requests!</h4></div></div>";
		$table.= "</div>";
   	}
   	else {
		$table.= "<div>&nbsp;</div>";
		$table.= "<div class=\"panel panel-success\">";
		$table.= "<div class=\"panel-heading\"><h4>Reports Received</h4></div>";
		$table.= "<div class=\"panel-body\">";
		while($household_report = mysqli_fetch_assoc($query_all_household_ids)){
			$household = $household_report['household_id'];
			$identify_user = mysqli_query($connect, "SELECT DISTINCT household_head_name FROM resident_household WHERE household_id = $household");
			while($iden = mysqli_fetch_assoc($identify_user)){
				$household_name = $iden['household_head_name'];	
			}
			$query_all_reports_with_curr_id = mysqli_query($connect, "SELECT * FROM reports WHERE household_id = $household ORDER BY time_sent DESC");
	        $number_of_rows = mysqli_num_rows($query_all_reports_with_curr_id);

	        if($number_of_rows == 1) $rep = "report";
	        else $rep = "reports";
			$table.= "<div class=\"panel-group\" id=\"accordion\">";
			$table.= "<div class=\"panel panel-success\">";
			$table.= "<div class=\"panel-heading\">";
			$table.= "<h4 class=\"panel-title\">";
			$table.= "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#$household\">$household_name ($number_of_rows $rep)</a>";
			$table.= "</h4>";
			$table.= "</div>";//panelheading
        	$table.= "<div id=\"$household\" class=\"panel-collapse collapse\">";
	        $table.= "<div class=\"panel-body\">";
	        $table.= "<div class=\"table-responsive\">";
	        $table.= "<table width=\"100%\" class=\"table\">";
	        $table.= "<thead>";
	        $table.= "<tr>";
	        $table.= "<th>Category</th>";
	        $table.= "<th>Message</th>";
	        $table.= "<th class=\"center-text\">Date and Time</th>";
	        $table.= "</tr>";
	        $table.= "</thead>";
	        $table.= "<tbody>";
			while($all_reps = mysqli_fetch_assoc($query_all_reports_with_curr_id)){
	            $months = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	            $timestamp = explode(" ", $all_reps['time_sent']);
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
            
				$table.= "<tr>";
				$table.= "<td>".$all_reps['category']."</td>";
				$table.= "<td style=\"width: 200px;\"><p style=\"width: 480px; word-break: keep-all;\">".$all_reps['report_details']."</p></td>";
				$table.= "<td class=\"center-text\"> $date_month $date_day, $date_year at $fin_hr:$new_min $iden</td>";
			}
			$table.= "</tbody>";
			$table.= "</table>";
			$table.= "</div>";
	        $table.= "</div>";
            $table.= "</div>";
			$table.= "</div><!--Panel Body Inner--> ";
			$table.= "</div><!--Panel Group--> ";
		}
		$table.= "</div><!--panel-body-->";
		$table.= "</div><!--panel-->";
   	}
   	echo json_encode($table);