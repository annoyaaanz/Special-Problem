<?php
$connect = mysqli_connect("localhost", "root", "", "wdadviser");
if(isset($_REQUEST['evac_id']) == null)	header("location: lgu-homepage.php?error=Invalid Request!");
else {
	$evac_id = $_REQUEST['evac_id'];
	$evacuation_center_name = mysqli_query($connect, "SELECT * FROM evacuation_centers WHERE evacuation_center_id = $evac_id");
    $e_pop = mysqli_query($connect, "SELECT COUNT(*) as pop FROM evacuation_center_population WHERE evacuation_center_id = $evac_id");
    $unique_hhid = mysqli_query($connect, "SELECT DISTINCT household_id FROM evacuation_center_population WHERE evacuation_center_id = $evac_id");
	$arr_pop = mysqli_fetch_assoc($e_pop);
	$pop = (int)$arr_pop['pop'];
	$arr = mysqli_fetch_array($evacuation_center_name);
	$e_name = $arr['evacuation_center_name'];
	$e_cap = $arr['evacuation_center_capacity'];
}
?>

<!DOCTYPE html>
<html>
<head>	
	<title>List of Evacuees - <?php echo $e_name;?></title>
	<?php require("references.php");?>
	<!-- LGU CSS -->
	<link rel="stylesheet" type="text/css" href="../css/lgu-home.css">
	<!-- EDIT EVAC JS -->
	<script type="text/javascript" src="../js/edit-evac.js"></script>
</head>
<body>
	
    <div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">	
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="lgu-homepage.php">
                <span class="navbar-icon"><i class="fa fa-chevron-left fa-fw"></i></span>
                <span class="navbar-icon">Go Back to Homepage</span>
            </a>
        </div>
        <ul class="nav navbar-top-links navbar-right tooltip-demo">
            <li data-toggle="tooltip" data-placement="bottom" title data-original-title="Weather Update">
            	<a class="top-icons">
            		<i class="fa fa-cloud fa-fw" id="weather-icon"></i>&nbsp;
            		<span id="city">&nbsp;</span>
            		<span id="temperature">&nbsp;</span>
            		<span id="weather-description">&nbsp;</span>
            	</a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" title data-original-title="Date and Time">
            	<a class="top-icons">
            		<i class="fa fa-clock-o fa-fw"></i>&nbsp;
            		<span id="date">&nbsp;</span>
            		<span id="time">&nbsp;</span>
            	</a>
            </li>
        </ul>
    </nav>
    </div>
    <div id="page-wrapper">
        <div class="row" id="dashboard">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $e_name ;?></h1>
            </div>
        </div>
        <div class="row">
        	<div class="col-lg-12">
        		<div id="capacities">
		        	<h4>Total Capacity: <span style="font-weight: 700;"><?php echo $e_cap ;?></span></h4>
		        	<h4>Current Population: <span style="font-weight: 700;"><?php echo $pop ;?></span></h4>
		        	<h4>Available Slots: <span style="font-weight: 700;"><?php echo ($e_cap-$pop) ;?></span></h4>
	        	</div>
                <div class="col-lg-10" id="table-list"><?php
                    $table = "<div>&nbsp;</div>";
                    $table.= "<div class=\"panel panel-success\">";
                    $table.= "<div class=\"panel-heading\"><h4>Households and Evacuees Lists</h4></div>";
                    $table.= "<div class=\"panel-body\">";
                    while($household = mysqli_fetch_assoc($unique_hhid)){
                        $households = $household['household_id'];
                        $query_hh_name = mysqli_query($connect, "SELECT household_head_name FROM resident_household WHERE household_id = $households");
                        $arr_hh = mysqli_fetch_assoc($query_hh_name);
                        $household_head_name = $arr_hh['household_head_name'];
                        $count_mems = mysqli_query($connect, "SELECT COUNT(*) as members_present FROM evacuation_center_population WHERE household_id = $households AND evacuation_center_id = $evac_id");
                        $arr_num = mysqli_fetch_assoc($count_mems);
                        $mem_num = $arr_num['members_present'];

                        $table.= "<div class=\"panel-group\" id=\"accordion\">";
                        $table.= "<div class=\"panel panel-success\">";
                        $table.= "<div class=\"panel-heading\">";
                        $table.= "<h4 class=\"panel-title\">";
                        $table.= "<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#house$households\">$household_head_name ($mem_num members present in this evacuation center)</a>";
                        $table.= "</h4>";
                        $table.= "</div>";//panelheading
                        $table.= "<div id=\"house$households\" class=\"panel-collapse collapse\">";
                        $table.= "<div class=\"panel-body\">";
                        $table.= "<div class=\"table-responsive\">";
                        $table.= "<table width=\"100%\" class=\"table\">";
                        $table.= "<thead>";
                        $table.= "<tr>";
                        $table.= "<th>Names</th>";
                        $table.= "<th>Household Position</th>";
                        $table.= "</tr>";
                        $table.= "</thead>";
                        $table.= "<tbody>";

                        $each_member = mysqli_query($connect, "SELECT * FROM evacuation_center_population WHERE household_id = $households AND evacuation_center_id = $evac_id");
                        while($all_mems_here = mysqli_fetch_assoc($each_member)){
                            $resident_id = $all_mems_here['resident_id'];
                            $query_each_member_name = mysqli_query($connect, "SELECT first_name, last_name, household_position FROM resident_info WHERE resident_id = $resident_id");
                            $resident_indiv = mysqli_fetch_assoc($query_each_member_name);
                            $name = $resident_indiv['first_name']." ".$resident_indiv['last_name'];
                            $table.= "<tr>";
                            $table.= "<td>$name</td>";
                            $table.= "<td>".$resident_indiv['household_position']."</td>";
                            $table.= "</tr>";
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
	        		echo $table;
                ?></div>
        	</div>
        </div>
    </div>	
</body>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../vendor/raphael/raphael.min.js"></script>
    <!-- <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script> -->
    <script src="../dist/js/sb-admin-2.js"></script> 
    <script>
        // tooltip demo
        $('.tooltip-demo').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        })
        // popover demo
        $("[data-toggle=popover]")
            .popover()
    </script>
</html>