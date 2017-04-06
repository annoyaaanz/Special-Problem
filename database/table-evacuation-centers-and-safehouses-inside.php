<?php
	$connect = mysqli_connect("localhost", "root", "", "wdadviser");

	$table = "<table width=\"100%\" class=\"table-responsive\" id=\"dataTables-example\">";
	$table.= "<thead>";
	$table.= "<tr>";
	$table.= "<th class=\"center-text\">#</th>";
	$table.= "<th class=\"center-text\">Household Head</th>";
	$table.= "<th class=\"center-text\">Action</th>";
	$table.= "</tr>";
	$table.= "</thead>";
	$table.= "<tbody>";
	$all_resident_household = mysqli_query($connect, "SELECT * FROM resident_household");
	$rows = mysqli_num_rows($all_resident_household);
	$curr = 0;
	while($house = mysqli_fetch_assoc($all_resident_household)) {
		$curr = $curr+1;
		$hh_head = $house['household_head_name'];
		$hh_id = $house['household_id'];
		$action = "";

		$stats = mysqli_query($connect, "SELECT * FROM safehouses WHERE household_id = $hh_id AND safehouse_stat = 'accepted'");
		$stat_num = mysqli_num_rows($stats);

		$status_curr_q = mysqli_query($connect, "SELECT safehouse_stat FROM safehouses WHERE household_id = $hh_id");
		$status_array = mysqli_fetch_array($status_curr_q);
		$status_array_ret = $status_array['safehouse_stat'];

		if($status_array_ret == 'request-sent'){
			$action = "<button disabled=\"disabled\" class=\"btn btn-info\"><span class=\"fa fa-check-circle\"></span>&nbsp;$hh_head's household has been requested!</button>";
		}
		else if($status_array_ret == 'accepted'){
			$action = "<button type=\"button\" disabled=\"disabled\" class=\"btn btn-success\" title=\"$hh_head's house has been confirmed to be available as a safehouse.\"><span class=\"fa fa-check-circle-o\"></span>&nbsp;Request has been confirmed!</button>";
		}
		else if($status_array_ret == 'rejected'){
			$action = "<button type=\"button\" disabled=\"disabled\" class=\"btn btn-danger\" title=\"$hh_head's house rejected the request.\"><span class=\"fa fa-check-circle-o\"></span>&nbsp;Request has been rejected!</button>";			
		}
		else{
			$action = "<form method=\"POST\" action=\"#\"><button type=\"submit\" class=\"btn btn-warning\" name=\"add-safehouse\" id=\"add-safehouse\"><span class=\"fa fa-check\"></span>&nbsp;Add $hh_head's House as Safehouse</button><input type=\"hidden\" name=\"household-id\" id=\"add\" value=\"$hh_id\"/><input type=\"hidden\" name=\"safehouse-name\" id=\"add\" value=\"$hh_head Residence\"/></form>";
		}
		$table.= "<tr class=\"center-text\">";
		$table.= "<td class=\"center-text\">$curr</td>";
		$table.= "<td class=\"left-text\">$hh_head</td>";
		$table.= "<td class=\"center-text\">$action</td>";
		$table.= "</tr>";
	}
	$table.= "</tbody>";
	$table.= "</table>";

	echo json_encode($table);
