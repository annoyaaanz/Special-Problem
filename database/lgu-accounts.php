<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    $retrieve_all_lgu_offices = mysqli_query($connect, "SELECT * FROM lgu_office ORDER BY lgu_agency");
    $table_alerts = "";
    while($office = mysqli_fetch_assoc($retrieve_all_lgu_offices)){
    	$office_id = $office['lgu_office_id'];
    	$office_name = $office['lgu_agency'];
    	$q_1 = mysqli_query($connect, "SELECT * FROM lgu_official WHERE lgu_office_id = $office_id");
    	$rows_office = mysqli_num_rows($q_1);
    	if($rows_office < 1){
		    $table_alerts.="<div class=\"panel panel-danger\">";
		    $table_alerts.="<div class=\"panel-heading\"><h4>$office_name</h4></div>";
		    $table_alerts.="<div class=\"panel-body\">";
	        $table_alerts.= "<div class=\"alert alert-danger\">";
	        $table_alerts.= "<h4><span class=\"fa fa-times-circle\"></span>&nbsp;There are no officials in this office yet!</h4>";
	        $table_alerts.= "</div>";
	        $table_alerts.="</div>";
    	}
    	else{
		    $table_alerts.="<div class=\"panel panel-success\">";
		    $table_alerts.="<div class=\"panel-heading\"><h4>$office_name</h4></div>";
		    $table_alerts.="<div class=\"panel-body\">";

	        $table_alerts.= "<div class=\"table-responsive\">";
	        $table_alerts.= "<table width=\"100%\" class=\"table table-striped table-bordered table-hover\">";
	        $table_alerts.= "<thead>";
	        $table_alerts.= "<tr>";
	        $table_alerts.= "<th class=\"center-text\">#</th>";
	        $table_alerts.= "<th class=\"center-text\" colspan=\"2\">Name</th>";
	        $table_alerts.= "<!--<th class=\"center-text\">Username</th>-->";
	        $table_alerts.= "<th class=\"center-text\">Action</th>";
	        $table_alerts.= "</tr>";
	        $table_alerts.= "</thead>";
	        $table_alerts.= "<tbody>";
	        $num = 0;
	        $query_all_officials_at_curr_office = mysqli_query($connect, "SELECT * FROM lgu_official WHERE lgu_office_id = $office_id");
	        while($officials = mysqli_fetch_assoc($query_all_officials_at_curr_office)){
	        	$num = $num+1;
	        	$table_alerts.="<tr>";
	        	$table_alerts.="<td class=\"center-text\">$num</td>";
	        	$table_alerts.="<td class=\"center-text\">".$officials['first_name']."</td>";
	        	$table_alerts.="<td class=\"center-text\">".$officials['last_name']."</td>";
	        	$table_alerts.="<!--<td class=\"center-text\">".$officials['lgu_username']."</td>-->";
	        	$table_alerts.="<td class=\"center-text\"><form method=\"POST\" action=\"#\"><input type=\"hidden\" name=\"lgu-id\" value=\"".$officials['lgu_id']."\" /><button class=\"btn btn-danger\" onclick=\"return confirm('Do you really want to remove ".$officials['first_name']." ".$officials['last_name']." \'s account?')\" name=\"delete-lgu-official-acct\">Remove Account</button> </form></td>";
	        	$table_alerts.="</tr>";
	        }
	        $table_alerts.="</tbody> <!-- tbody-->";
	        $table_alerts.="</table> <!-- table-->";
	        $table_alerts.="</div> <!-- table-responsive -->";
		    $table_alerts.="</div> <!-- panel-body -->";
		    $table_alerts.="</div> <!-- panel -->";
    	}
    	$table_alerts.="</div>";
    }
    echo json_encode($table_alerts);