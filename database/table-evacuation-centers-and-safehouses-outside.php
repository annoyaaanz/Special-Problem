<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");


    $all_evacuation_centers = mysqli_query($connect, "SELECT * FROM evacuation_centers");
    $all_safehouses = mysqli_query($connect, "SELECT * FROM safehouses ORDER BY safehouse_stat");

    $ev_nums = mysqli_num_rows($all_evacuation_centers);
    $sh_nums = mysqli_num_rows($all_safehouses);
    $panel_choice = "";
    $panel_choice_sh = "";
    if($ev_nums < 1) $panel_choice = "danger";
    else $panel_choice = "success";
    if($sh_nums < 1) $panel_choice_sh = "danger";
    else $panel_choice_sh = "success";
    $table_evacuation_centers = "<div class=\"panel panel-$panel_choice\">";
    $table_evacuation_centers.= "<div class=\"panel-heading\"><h4>Evacuation Centers </h4></div>";
    $table_evacuation_centers.= "<div class=\"panel-body\">";
    if($ev_nums < 1){
    	$table_evacuation_centers.= "<div class=\"alert alert-danger\">";
    	$table_evacuation_centers.= "<h4><span class=\"fa fa-times-circle\"></span>&nbsp;There are no evacuation centers yet!</h4>";
    	$table_evacuation_centers.= "</div>";
    }
    else {
    	$table_evacuation_centers.= "<div class=\"table-responsive\">";
    	$table_evacuation_centers.= "<table width=\"70%\" class=\"table table-striped table-bordered table-hover\">";
    	$table_evacuation_centers.= "<thead>";
    	$table_evacuation_centers.= "<tr>";
    	$table_evacuation_centers.= "<th class=\"center-text\">#</th>";
        $table_evacuation_centers.= "<!--<th class=\"center-text\">Coordinates</th>-->";
        $table_evacuation_centers.= "<th class=\"center-text\">Evacuation Center Name</th>";
    	$table_evacuation_centers.= "<th class=\"center-text\">Capacity</th>";
        $table_evacuation_centers.= "<th colspan=\"2\" class=\"center-text\">Action</th>";
        $table_evacuation_centers.= "<th colspan=\"2\" class=\"center-text\">List of Evacuees</th>";
    	$table_evacuation_centers.= "<tr>";
    	$table_evacuation_centers.= "</thead>";
    	$table_evacuation_centers.= "<tbody>";
    	$num = 0;
    	while($evacs = mysqli_fetch_assoc($all_evacuation_centers)){
    		$evac_name = $evacs['evacuation_center_name'];
    		$evac_id = $evacs['evacuation_center_id'];
    		$num = $num+1;
    		$table_evacuation_centers.= "<tr>";
    		$table_evacuation_centers.= "<td class=\"center-text\">$num</td>";
            $table_evacuation_centers.= "<!--<td class=\"center-text\">".$evacs['evacuation_center_location']."</td> -->";
            $table_evacuation_centers.= "<td class=\"center-text\">".$evacs['evacuation_center_name']."</td>";
    		$table_evacuation_centers.= "<td class=\"center-text\">".$evacs['evacuation_center_capacity']."</td>";
    		$table_evacuation_centers.= "<td class=\"center-text\"><form method=\"POST\" action=\"edit-evac-center.php\"><button class=\"btn btn-success\" type=\"submit\" name=\"edit-evac-center\" id=\"edit-evac-center\">Edit</button><input type=\"hidden\" name=\"evac-id\" value=\"".$evacs['evacuation_center_id']."\" /></form></td>";
    		$table_evacuation_centers.= "<td class=\"center-text\"><form method=\"POST\" action=\"#\"><input type=\"hidden\" value=\"".$evacs['evacuation_center_id']."\" name=\"evacuation_center_id\"/><button type=\"submit\" class=\"btn btn-danger\" id=\"delete\" name=\"delete-evac-center\" onclick=\"return confirm('Are you sure you want to delete ".$evacs['evacuation_center_name']."?')\">Delete</button><input type=\"hidden\" name=\"success-message\" value=\"Successfully deleted the evacuation center.\"/></td></form>";
            $table_evacuation_centers.="<td class=\"center-text\"><form method=\"POST\" action=\"evacuees.php\"><input type=\"hidden\" name=\"evac_id\" value=\"$evac_id\"/><button class=\"plain btn-trans\" type=\"submit\">View List of Evacuees</button class=\"btn btn-info\"></form></td>";
    		$table_evacuation_centers.= "</tr>";
    	}
    	$table_evacuation_centers.= "</tbody>";
    	$table_evacuation_centers.= "</table>";
    	$table_evacuation_centers.= "</div>";
    }
    $table_evacuation_centers.= "</div><!-- panel body -->";
    $table_evacuation_centers.= "</div> <!-- panel -->";
    /* SAFE HOUSES */
    $table_evacuation_centers.= "<div class=\"panel panel-$panel_choice_sh\">";
    $table_evacuation_centers.= "<div class=\"panel-heading\"><h4>Safe Houses</h4></div>";
    $table_evacuation_centers.= "<div class=\"panel-body\">";
	if($sh_nums < 1){
    	$table_evacuation_centers.= "<div class=\"alert alert-danger\">";
    	$table_evacuation_centers.= "<h4><span class=\"fa fa-times-circle\"></span>&nbsp;There are no safe houses yet!</h4>";
    	$table_evacuation_centers.= "</div>";
    }
    else {
    	$table_evacuation_centers.= "<div class=\"table-responsive\">";
    	$table_evacuation_centers.= "<table width=\"50%\" class=\"table table-striped table-bordered table-hover\">";
    	$table_evacuation_centers.= "<thead>";
    	$table_evacuation_centers.= "<tr>";
    	$table_evacuation_centers.= "<th class=\"center-text\">#</th>";
        $table_evacuation_centers.= "<th class=\"center-text\">Status</th>";
        $table_evacuation_centers.= "<th class=\"center-text\">Capacity</th>";
    	$table_evacuation_centers.= "<th class=\"center-text\">Household Head</th>";
    	$table_evacuation_centers.= "<th class=\"center-text\">Action</th>";
    	$table_evacuation_centers.= "<tr>";
    	$table_evacuation_centers.= "</thead>";
    	$table_evacuation_centers.= "<tbody>";
    	$num_sh = 0;
    	while($safe = mysqli_fetch_assoc($all_safehouses)){
    		$sh_name = $safe['safehouse_name'];
    		$sh_capacity = $safe['safehouse_capacity'];
    		$sh_id = $safe['safehouse_id'];
    		$sh_hh_id = $safe['household_id'];
            $sh_stat = $safe['safehouse_stat'];
    		$num_sh = $num_sh+1;
            $hh_head_query = mysqli_query($connect, "SELECT household_head_name FROM resident_household WHERE household_id = $sh_hh_id");
            $hh_name = mysqli_fetch_array($hh_head_query);
            $hh_name_ret = $hh_name['household_head_name'];
            if($sh_stat == "accepted"){
        		$table_evacuation_centers.= "<tr>";
        		$table_evacuation_centers.= "<td class=\"center-text\">$num_sh</td>";
                $table_evacuation_centers.= "<td class=\"center-text\">Approved</td>";
                $table_evacuation_centers.= "<td class=\"center-text\">$sh_capacity</td>";
        		$table_evacuation_centers.= "<td class=\"center-text\">$hh_name_ret</td>";
        		$table_evacuation_centers.= "<td class=\"center-text\"><form method=\"POST\" action=\"#\"><button type=\"submit\" class=\"btn btn-danger\" id=\"delete-safehouse\" name=\"delete-safehouse\" onclick=\"return confirm('Are you sure you want to delete $sh_name?')\">Delete</button><input type=\"hidden\" value=\"$sh_id\" name=\"safehouse_id\" id=\"\"/></form></td>";
        		$table_evacuation_centers.= "</tr>";
            }
            else if($sh_stat == "rejected"){

                $table_evacuation_centers.= "<tr>";
                $table_evacuation_centers.= "<td class=\"center-text\">$num_sh</td>";
                $table_evacuation_centers.= "<td class=\"center-text\"><b>Rejected</b></td>";
                $table_evacuation_centers.= "<td class=\"center-text\"><b>Unavailable</b></td>";
                $table_evacuation_centers.= "<td class=\"center-text\"><b>$hh_name_ret</b></td>";
                $table_evacuation_centers.= "<td class=\"center-text\"><form method=\"POST\" action=\"#\"><button type=\"submit\" class=\"btn btn-danger\" id=\"delete-safehouse\" name=\"delete-safehouse\" onclick=\"return confirm('Are you sure you want to delete this rejected request?')\"><b>Remove</b></button><input type=\"hidden\" value=\"$sh_id\" name=\"safehouse_id\" id=\"\"/></form></td>";
                $table_evacuation_centers.= "</tr>";
            }
            else 
                if($sh_stat == "request-sent"){
                $table_evacuation_centers.= "<tr>";
                $table_evacuation_centers.= "<td class=\"center-text\">$num_sh</td>";
                $table_evacuation_centers.= "<td class=\"center-text\"><i>Pending</i></td>";
                $table_evacuation_centers.= "<td class=\"center-text\"><i>Unavailable</i></td>";
                $table_evacuation_centers.= "<td class=\"center-text\">$hh_name_ret</td>";
                $table_evacuation_centers.= "<td class=\"center-text\"><form method=\"POST\" action=\"#\"><button type=\"submit\"  onclick=\"return confirm('Are you sure you want to cancel the request to make $hh_name_ret\'s residence a safehouse?')\" class=\"btn btn-warning\" id=\"cancel-safehouse\" name=\"cancel-safehouse\">Cancel Request</button><input type=\"hidden\" value=\"$sh_id\" name=\"safehouse_id\" id=\"safehouse_id\"/></form></td>";
                $table_evacuation_centers.= "</tr>";
            }
    	}
    	$table_evacuation_centers.= "</tbody>";
    	$table_evacuation_centers.= "</table>";
    	$table_evacuation_centers.= "</div>";
    }
    $table_evacuation_centers.= "</div><!-- panel body -->";
    $table_evacuation_centers.= "</div> <!-- panel -->";


    echo json_encode($table_evacuation_centers);