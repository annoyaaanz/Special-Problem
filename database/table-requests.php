<?php
	$connect = mysqli_connect("localhost", "root", "", "wdadviser");
	$res_id = $_REQUEST['id'];
	$query_requests = mysqli_query($connect, "SELECT * FROM safehouses WHERE household_id = $res_id");
	$number = mysqli_num_rows($query_requests);
	$table = "";
	if($number < 1){
		$table.= "<div class=\"panel panel-danger\">";
		$table.= "<div class=\"panel-heading\"><h4>Safehouse Requests</h4></div>";
		$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-times-circle fa-fw\"></span>&nbsp;There are no requests!</h4></div></div>";
		$table.= "</div>";
	}
	else {
        $table.="<div class=\"col-lg-10\">";
		$table.= "<div class=\"panel panel-success\">";
		$table.= "<div class=\"panel-heading\"><h4>Safehouse Requests</h4></div>";
		$table.= "<div class=\"panel-body\">";

        $table.= "<div class=\"table-responsive\">";
        $table.= "<table width=\"70%\" class=\"table table-striped table-bordered table-hover\">";
        $table.= "<thead>";
        $table.= "<tr>";
        $table.= "<th class=\"center-text\">#</th>";
        $table.= "<th class=\"center-text\">Date and Time Received</th>";
        $table.= "<th class=\"center-text\" colspan=\"2\">Action</th>";
        $table.= "<tr>";
        $table.= "</thead>";
        $table.= "<tbody>";
        $num = 0;
        while($reqs = mysqli_fetch_assoc($query_requests)){
        	$safehouse_id = $reqs['safehouse_id'];
        	$safehouse_stat = $reqs['safehouse_stat'];
            $months = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $timestamp = explode(" ", $reqs['safehouse_time_inserted']);
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
            $table.= "<tr>";
            $table.= "<td class=\"center-text\">$num</td>";
            $table.= "<td class=\"center-text\">$date_month $date_day, $date_year at $fin_hr:$new_min $iden</td>";
            if($safehouse_stat == 'request-sent'){
	            $table.="<td class=\"center-text\"><form method=\"POST\" action=\"add-capacity.php\"> <input type=\"hidden\" name=\"hh-id\" value=\"$res_id\" /><input type=\"hidden\" name=\"safehouse-id\" value=\"$safehouse_id\"/><button type=\"submit\" class=\"btn btn-success\" name=\"accept-request\"><span class=\"fa fa-check-circle fa-fw\"></span>&nbsp;Accept Request</button></form></td>";
                $table.="<td class=\"center-text\"><form method=\"POST\" action=\"#\"><input type=\"hidden\" name=\"hh-id\" value=\"$res_id\" /><button type=\"submit\" class=\"btn btn-danger\" name=\"reject-request\" onclick=\"return confirm('Do you really want to reject the request?')\"><span class=\"fa fa-times-circle fa-fw\"></span>&nbsp;Reject Request</button></form></td>";
	        }
            else if($safehouse_stat == 'rejected'){
                $table.="<td class=\"center-text\"><button type=\"submit\" disabled=\"disabled\" class=\"btn btn-danger\" ><span class=\"fa fa-times-circle fa-fw\"></span>&nbsp;Request has been rejected!</button></td>";
            }
	        else {
	        	$table.="<td class=\"center-text\"><button type=\"submit\" disabled=\"disabled\" class=\"btn btn-info\" name=\"accept-request\"><span class=\"fa fa-check-circle fa-fw\"></span>&nbsp;Request has been accepted!</button></td>";
	        }
            $table.="</tr>";
            $table.="</form>";

        }
        $table.="</div>";
        $table.="</div>";
		$table.= "</div>";	
	}
	echo json_encode($table);