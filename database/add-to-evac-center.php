<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    session_start();
    $hh_username = $_SESSION['resident_username'];
    //queries
    $user_id = mysqli_query($connect, "SELECT household_id FROM resident_household WHERE username = '$hh_username'");
    $all_evac = mysqli_query($connect, "SELECT * FROM evacuation_centers");
    $stat_q = mysqli_query($connect, "SELECT evacuation_status_current FROM evacuation_status WHERE evacuation_status_id = 1");

    $user_id_arr = mysqli_fetch_array($user_id);
    $user_id_ret = $user_id_arr['household_id'];
    $stat_a = mysqli_fetch_array($stat_q);
    $rows_evac = mysqli_num_rows($all_evac);
    $stat = $stat_a['evacuation_status_current'];
    $table = "<div>&nbsp;</div>";

    if($stat == "Disable"){
		$table.= "<div class=\"panel panel-warning\">";
		$table.= "<div class=\"panel-heading\"><h4>Evacuation Centers</h4></div>";
		$table.= "<div class=\"panel-body\"><div class=\"alert alert-warning\"><h4><span class=\"fa fa-exclamation-circle fa-fw\"></span>&nbsp;You can't enter any evacuation center yet! Please wait for the LGU to enable the functionality.</h4></div></div>";
		$table.= "</div>";
    }
    else if($rows_evac == 0){
		$table.= "<div class=\"panel panel-danger\">";
		$table.= "<div class=\"panel-heading\"><h4>Evacuation Centers</h4></div>";
		$table.= "<div class=\"panel-body\"><div class=\"alert alert-danger\"><h4><span class=\"fa fa-exclamation-circle fa-fw\"></span>&nbsp;There are no evacuation centers available.</h4></div></div>";
		$table.= "</div>";
    }
    else {
		$table.= "<div class=\"panel panel-success\">";
		$table.= "<div class=\"panel-heading\"><h4>Evacuation Centers</h4></div>";
		$table.= "<div class=\"panel-body\">";
        $table.= "<div class=\"table-responsive\">";
        $table.= "<table width=\"100%\" class=\"table table-striped table-bordered table-hover\">";
        $table.= "<thead>";
        $table.= "<tr>";
        $table.= "<th class=\"center-text\">#</th>";
        $table.= "<th class=\"center-text\" colspan=\"1\">Evacuation Center</th>";
        $table.= "<th class=\"center-text\">Current Population</th>";
        $table.= "<th class=\"center-text\">Available Slots</th>";
        $table.= "<th class=\"center-text\">Capacity</th>";
        $table.= "</tr>";
        $table.= "</thead>";
        $table.= "<tbody>";
        $num = 0;
		while($evac = mysqli_fetch_assoc($all_evac)){
			$num = $num+1;
			$id = $evac['evacuation_center_id'];
			$name = $evac['evacuation_center_name'];
			$cap = $evac['evacuation_center_capacity'];
			$query_current_population = mysqli_query($connect, "SELECT COUNT(evacuation_center_id) AS population FROM evacuation_center_population WHERE evacuation_center_id = $id");
			$pop = mysqli_fetch_array($query_current_population);
			$pop_ret = (int)$pop['population'];
            $curr_cap = $cap - $pop_ret;
            if($curr_cap == 0) $dis="disabled=\"disabled\"";
            else $dis = "";
			$table.= "<tr>";
			$table.= "<td class=\"center-text\">$num</td>";
			$table.= "<td class=\"center-text\"><form action=\"add-to-evac-center-household.php\" method=\"POST\"><input type=\"hidden\" name=\"evac_id\" value=\"$id\"/><input type=\"hidden\" name=\"household_id\" value=\"$user_id_ret\"/><button $dis class=\"btn btn-success\"type=\"submit\" name=\"add-to-evac\">$name</button></td></form>";
            $table.= "<td class=\"center-text\">$pop_ret</td>";
            $table.= "<td class=\"center-text\">$curr_cap</td>";
			$table.= "<td class=\"center-text\">$cap</td>";
			$table.= "</tr>";
		}
		$table.= "</tbody>";
		$table.= "</table>";
		$table.= "</div>";
		$table.= "</div>";	
    }
    echo json_encode($table);