<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    if(isset($_REQUEST['evac_id']) == null) header("location: resident-homepage.php?error=Invalid request!");
    else {
        $hh_id = $_REQUEST['household_id'];
        $evac_id = $_REQUEST['evac_id'];

        $household = $hh_id;
        $query_evacuation_center = mysqli_query($connect, "SELECT evacuation_center_name FROM evacuation_centers WHERE evacuation_center_id = $evac_id");
        $query_household_head = mysqli_query($connect, "SELECT household_head_name FROM resident_household WHERE household_id = $hh_id");
        $query_all_members = mysqli_query($connect, "SELECT * FROM resident_info WHERE household_id = $hh_id ORDER BY household_position ASC");

        $household_head = mysqli_fetch_array($query_household_head);
        $hh_head_name = $household_head['household_head_name'];
        $evac_arr = mysqli_fetch_array($query_evacuation_center);
        $evac = $evac_arr['evacuation_center_name'];
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Household to Evacuation Center </title>
	<?php require("references.php");?>
	<!-- Resident Sign Up CSS -->
	<link rel="stylesheet" type="text/css" href="../css/resident-home.css">
	<!-- Resident JS -->
    <!-- // <script type="text/javascript" src="../js/resident.js"></script> -->
    <script type="text/javascript" src="../js/add-member-to-evac.js"></script>
</head>
<body>
    <!-- Hidden values -->
    <input type="hidden" id="evac-id" value="<?php echo $evac_id;?>"/>
    <input type="hidden" id="household-id" value="<?php echo $household;?>"/>
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
                <a class="navbar-brand" href="resident-homepage.php" title="Back to main page">
                    <span class="fa fa-umbrella fa-fw"></span>
                    <span>Weather Disaster Adviser</span>
                </a>
            </div>
            <ul class="nav navbar-top-links navbar-right tooltip-demo">
                <li data-toggle="tooltip" data-placement="bottom" title data-original-title="Weather Update">
                    <a class="top-icons" title="Current Weather Update">
                        <i class="fa fa-cloud fa-fw"></i>&nbsp;
                        <span id="city">&nbsp;</span>
                        <span id="temperature">&nbsp;</span>
                        <span id="weather-description">&nbsp;</span>
                    </a>
                </li>
                <li data-toggle="tooltip" data-placement="bottom" title data-original-title="Date and Time">
                    <a class="top-icons" title="Date and Time">
                        <i class="fa fa-clock-o fa-fw"></i>&nbsp;
                        <span id="date">&nbsp;</span>
                        <span id="time">&nbsp;</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-success">
                        <div class="panel-heading"><h4>Add to Evacuation Center (<?php echo $evac;?>) </h4></div>
                        <div class="panel-body">
                        	<div class="form-group">
                                <label>Family Members: </label>
                        		<?php
                        			$string = "<div class=\"form-group\">";
                                    $counter_occ = 0;
                                    $fam = 0;
                        			while($member = mysqli_fetch_assoc($query_all_members)){
                                        $fam++;
                        				$member_name_fname = $member['first_name'];
                        				$member_name_lname = $member['last_name'];
                        				$resident_id = $member['resident_id'];
                                        $resident_position = $member['household_position'];
                                        $query_select_if_exists = mysqli_query($connect, "SELECT COUNT(*) as lol FROM  evacuation_center_population WHERE resident_id = $resident_id");
                                        $numbers = mysqli_fetch_assoc($query_select_if_exists);
                                        $lol = $numbers['lol'];
                                        $num1 = (int)$lol;
                                        $counter_occ = $counter_occ+$lol;
                                        if($lol == 1){
                                            $disabled = "disabled=\"disabled\"";
                                            $span = "<span class=\"fa fa-check fa-fw\"></span>";
                                        }
                                        else {
                                            $disabled = "";
                                            $span = "";
                                        }

                                        if($resident_position == "head"){
                                            $string.="<label><input type=\"checkbox\" $disabled id=\"$resident_id\" name=\"resident-id\" value=\"$resident_id\"> $member_name_fname $member_name_lname (Household Head)</label> $span $num1<br/>";
                                        }
                                        else {
                                            $string.="<label><input type=\"checkbox\" $disabled id=\"$resident_id\" name=\"resident-id\" value=\"$resident_id\"> $member_name_fname $member_name_lname </label> (Member) $span $num1<br/>";
                                        }
                                    $lol = 0;
                        			}
                        			$string.="</div> $counter_occ";
                        			echo $string;
                        		?>
                        	</div>
                        </div>
                        <div class="panel-footer center-text">
                            <?php if($counter_occ < $fam++){?>
                                    <button class="btn btn-success"  <?php echo "";?> type="button" id="add-member-to-evac" name="add-to-evacuation-center" onclick="return confirm('Do you want to add the family members to this Evacuation Center?')"><span class="fa fa-building fa-fw"></span> Add Members to Evacuation Center</button>
                            <?php }
                                else{?>
                                    <div>All family members have been added!</div>
                                    <a href="resident-homepage.php">Back to Home</a>
                                <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../vendor/raphael/raphael.min.js"></script>
    <!-- <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script> -->
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>