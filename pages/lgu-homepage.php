<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    session_start();
	$lgu_username = $_SESSION['lgu_username'];
    $query_lgu_id = mysqli_query($connect, "SELECT lgu_id FROM lgu_official WHERE lgu_username = '$lgu_username'");
    $lgu_id_arr = mysqli_fetch_array($query_lgu_id);
    $lgu_id_ret = $lgu_id_arr['lgu_id'];

    if(isset($_REQUEST['e'])!=null){ 
        $error = $_REQUEST['e'];
        if($error == "<br/>"){
            $error_f = $error;
        }
        else $error_f = "<div class=\"error-messages-div error-messages-green\" style=\"width: 60%; margin-left: 20%; text-align: center; font-size: 17px;\"><span><span class=\"fa fa-check-circle\"></span>&nbsp;$error</span></div>";
    }
    else if(isset($_REQUEST['error'])!= null){
        $error = $_REQUEST['error'];
        if($error == "<br/>"){
            $error_f = $error;
        }
        else $error_f = "<div class=\"error-messages-div error-messages-red\" style=\"width: 60%; margin-left: 20%; text-align: center; font-size: 17px;\"><span><span class=\"fa fa-times-circle\"></span>&nbsp;$error</span></div>";
    }
    else if(isset($_REQUEST['e'])==null || isset($_REQUEST['error'])==null){
        $error_f = "";
    }

    if($_SESSION['lgu_username'] == null){
        header("location: home.php?e=Please log in to continue!");
    }
	if(isset($_POST["lgu-logout"])){
		session_destroy();
		session_unset();
		header("location: home.php");
	}  
    if(isset($_POST['add-safehouse'])){
        $household_id = $_POST['household-id'];
        $name = $_POST['safehouse-name'];
        $status = "request-sent";
        $request_safehouse = mysqli_query($connect, "INSERT INTO safehouses VALUES (0, $household_id, 0, '$status', '$name', now())");
        $rows = mysqli_affected_rows($connect);
        if($rows != 1) header("location: error.php?hhid=$household_id&name=$name&rows=$rows");
        else header("location: lgu-homepage.php?e=Household has been sent a request to become a safehouse!");
    }
    if(isset($_POST['delete-safehouse'])){
        $safehouse_id = $_POST['safehouse_id'];
        $delete_safehouse_query = mysqli_query($connect, "DELETE FROM safehouses WHERE safehouse_id = $safehouse_id");
        header("location: lgu-homepage.php?e=Safehouse has been removed!");
    }
    if(isset($_POST['cancel-safehouse'])){
        $safehouse_id = $_POST['safehouse_id'];
        $delete_safehouse_query = mysqli_query($connect, "DELETE FROM safehouses WHERE safehouse_id = $safehouse_id");
        header("location: lgu-homepage.php?e=Safehouse request has been cancelled!");
    }
    if (isset($_POST['submit-evac'])){
        $location = $_POST['latitude-evac'].",".$_POST['longitude-evac'];
        $lat = $_POST['latitude-evac'];
        $long = $_POST['longitude-evac'];
        $capacity = $_POST['evac-capacity'];
        $add_evac = mysqli_query($connect, "INSERT INTO evacuation_centers VALUES(0, $capacity, '$location', 'Evacuation Center')");
        if(mysqli_affected_rows($connect) == 1){
            header("location: lgu-homepage.php?e=Successfully added Evacuation Center!");
        }
        else{
            header("location: lol.php?e=failed");
        }
    }

    if(isset($_POST['delete-evac-center'])){
        $evacuation_center_id = $_POST['evacuation_center_id'];
        $success_message = $_POST['success-message'];
        $delete_evac_center = mysqli_query($connect, "DELETE FROM evacuation_centers WHERE evacuation_center_id = $evacuation_center_id");
        if(mysqli_affected_rows($connect)==1){
            header("location: lgu-homepage.php?e=Evacuation Center has been successfully deleted!");
        }
    }
    if(isset($_POST['accept-alert'])){
        $alert_id = $_POST['traveler-alert-id'];
        $accept_query = mysqli_query($connect, "UPDATE `traveler_alerts` SET traveler_alert_status = 'Approved' WHERE traveler_alert_id = $alert_id");
        if(mysqli_affected_rows($connect)==1){
            header("location: lgu-homepage.php?e=Traveler alert has been accepted!");
        }
    }
    else if(isset($_POST['delete-alert'])){
        $alert_id = $_POST['traveler-alert-id'];
        $delete_alert = mysqli_query($connect, "DELETE FROM traveler_alerts WHERE traveler_alert_id = $alert_id");
        if(mysqli_affected_rows($connect)==1){
            header("location: lgu-homepage.php?e=Traveler alert has been deleted!");
        }
    }
    if(isset($_POST['submit-hazard'])){
        $location = $_POST['latitude-alert'].",".$_POST['longitude-alert'];
        $category = $_POST['hazard-category'];
        $details = $_POST['additional-info'];
        $lgu_id = $_POST['lgu-id'];
        $add_hazard = mysqli_query($connect, "INSERT INTO lgu_hazards VALUES(0, $lgu_id, '$location', '$category', '$details', now())");
        if(mysqli_affected_rows($connect) ==1){
            header("location: lgu-homepage.php?e=Hazard has been added successfully !");
        }
        else {
            header("location: lgu-homepage.php?error=$location<br/>$category<br/>$details<br/>$lgu_id<br/>");
        }
    }
    else if(isset($_POST['remove-hazard'])){
        $hazard_id = $_POST['hazard_id'];
        $delete_hazard_query = mysqli_query($connect, "DELETE FROM lgu_hazards WHERE hazard_id = $hazard_id");
        if(mysqli_affected_rows($connect) == 1){
            header("location: lgu-homepage.php?e=Hazard has been deleted successfully!");
        }
    }

    if(isset($_POST['submit-alert'])){
        $rec = $_POST['resident-indiv'];
        $purpose = $_POST['purpose'];
        if($purpose == "Others"){
            $purpose = $_POST['textfield-others'];
        }
        $details = $_POST['alert-details'];
        $query_send_alert = mysqli_query($connect, "INSERT INTO lgu_alerts VALUES(0,'$details','$purpose', $rec, $lgu_id_ret, now())");
        if(mysqli_affected_rows($connect) == 1){
            header("location: lgu-homepage.php?e=Alert has been successfully sent!");
        }
        else {
            header("location: lgu-homepage.php?error=Sorry, something went wrong!");
        }
    }
    if(isset($_POST['submit-alert-all'])){
        $purpose = $_POST['purpose-all'];
        if($purpose == "Others"){
            $purpose  = $_POST['textfield-others-all'];
        }
        $details = $_POST['alert-details-all'];
        $query_all_resident = mysqli_query($connect, "SELECT * FROM resident_household");
        $nums = mysqli_num_rows($query_all_resident);
        while($resident = mysqli_fetch_assoc($query_all_resident)){
            $resident_household_id = $resident['household_id'];
            $query_send_to_all = mysqli_query($connect, "INSERT INTO lgu_alerts VALUES(0, '$details', '$purpose', $resident_household_id, $lgu_id_ret, now())");
        }
        header("location: lgu-homepage.php?e=Alert has been sent to all the residents!");
    }
    //submit barangay
    if(isset($_POST['submit-alert-barangay'])){
        $barangay = $_POST['barangay-rec'];
        $purpose = $_POST['purpose-barangay'];
        if($purpose == "Others"){
            $purpose  = $_POST['textfield-others-barangay'];
        }
        $details = $_POST['alert-details-barangay'];
        $query_all_resident = mysqli_query($connect, "SELECT * FROM resident_household WHERE address = '$barangay'");
        $nums = mysqli_num_rows($query_all_resident);
        $num = 0;
        $household_ids = "";
        while($resident_rep = mysqli_fetch_assoc($query_all_resident)){
            $num++;
            $household_id = $resident_rep['household_id'];
            $household_ids .="[".$household_id."]";
            $send_report = mysqli_query($connect, "INSERT INTO lgu_alerts VALUES (0, '$details', '$purpose', $household_id, $lgu_id_ret, now())");
        }
        if($nums == $num){
            header("location: lgu-homepage.php?e=Alert has been sent to all the specified barangay!");
        }
        else {
            header("location: lgu-homepage.php?e=Alert failed to send!$nums, $num, $details, $household_ids");
        }
    }
    if(isset($_POST['edit-e-status'])){
        $stat = $_POST['evac-stat'];
        $query_change_stat = mysqli_query($connect, "UPDATE `evacuation_status` SET evacuation_status_current = '$stat' WHERE evacuation_status_id = 1");
        if(mysqli_affected_rows($connect) == 1){
            header("location: lgu-homepage.php?e=Evacuation status has been successfully changed.");
        }
        else {
            header("location: lgu-homepage.php?error=Evacuation status failed to update.");
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title id="user-title">Dashboard - <?php echo $lgu_username;?></title>
	<?php require("references.php");?>
	<!-- LGU CSS -->
	<link rel="stylesheet" type="text/css" href="../css/lgu-home.css">
    <!-- LGU JS -->
    <script type="text/javascript" src="../js/lgu.js"></script>
    <!-- Google Maps -->
    <script type="text/javascript" src="../js/lgu-maps.js"></script>
    <script type="text/javascript" src="../js/lgu-add-evacuation.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbQT4GUyiir-qKMv0CSfJdalOjHjYDnvI&callback=initMap" async defer></script>
    <!-- LGU SEND REPORT JS -->
</head>
<body>
    <!-- Username hidden -->
    <input type="hidden" id="lgu-username-hidden" value="<?php echo $lgu_username;?>"/>
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
                <span class="navbar-brand" href="javascript:void();">
                    <span class="navbar-icon"><i class="fa fa-umbrella fa-fw"></i></span>
                    <span class="navbar-icon">Weather Disaster Adviser</span>
                </span>
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
            <!-- /.navbar-top-links -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="lgu-sidebar" id="dashboard-sidebar">
                            <span><i class="fa fa-home fa-fw"></i>&nbsp;Dashboard</span>
                        </li>
                        <li class="lgu-sidebar" id="received-alerts-traveler-sidebar" title="Alerts and Hazards">
                            <span><i class="fa fa-exclamation-triangle fa-fw"></i>&nbsp;Alerts and Hazards</span>
                        </li>
                        <li class="lgu-sidebar" id="send-alerts-residents-sidebar">
                            <span><i class="fa fa-send fa-fw"></i>&nbsp;Send Alerts to Residents</span>
                        </li>
                        <li class="lgu-sidebar" id="received-alerts-residents-sidebar">
                            <span><i class="fa fa-arrow-circle-down fa-fw"></i>&nbsp;Received Alerts - Residents</span>
                        </li>
                        <li class="lgu-sidebar" id="sent-alerts-residents-sidebar">
                            <span><i class="fa fa-arrow-circle-up fa-fw"></i>&nbsp;Sent Alerts - Residents</span>
                        </li>
                        <li class="lgu-sidebar" id="manage-evacuation-centers-sidebar">
                            <span style="text-align: center;"><i class="fa fa-flag fa-fw"></i>&nbsp;Manage Evacuation Centers &amp; Safehouses</span>
                        </li>
                        <li class="lgu-sidebar">
                        	<form action="#" method="POST">
                            	<button class="btn btn-block logout-btn" type="submit" name="lgu-logout" title="Logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;Logout</button></form>
                        </li>
                    </ul>
                </div><!-- /.sidebar-collapse -->
            </div><!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">
            <div class="row" id="dashboard">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <div class="col-lg-12">
                    <div id="error"><?php echo $error_f;?></div>
                    <br/>
                    <div id="map"></div>
                </div>
            </div>
            <div class="row" id="received-alerts-traveler">
                <div class="col-lg-12">
                    <h1 class="page-header">Alerts and Hazards!</h1>
                </div>
                <div class="col-lg-10" id="table-alerts">
                </div>
                <div class="tooltip-demo">
                    <div class="col-lg-2" data-toggle="tooltip" data-placement="bottom" title data-original-title="Add Hazards">
                        <button class="btn btn-primary btn-block btn-outline" id="send-alert-to-travelers" data-toggle="modal" data-target="#send-alert-modal" data-backdrop="static">Add Hazard</button>
                    </div>
                </div>
                <!-- MODAL FOR SENDING ALERTS TO TRAVELERS -->
                <div id="send-alert-modal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-width">
                    <div class="modal-content panel panel-primary">
                        <div class="modal-header panel-heading">
                            <button type="button" class="close" data-dismiss="modal" title="Exit">&times;</button>
                            <h2 class="panel-title">Send Alert to Travelers</h2>
                        </div>

                        <form role="form" class="form" method="post" action="#">
                        <div class="modal-body panel-body">
                            <div id="alert-map"></div>
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Location:</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" readonly name="latitude-alert" id="latitude-alert" value="" placeholder="Latitude"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" readonly name="longitude-alert" id="longitude-alert" value="" placeholder="Longitude" />
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Category:</label>
                                    <div class="col-lg-12">
                                        <select id="hazard-category" name="hazard-category" class="form-control" >
                                            <option selected disabled value="">Please Select Category</option>
                                            <optgroup label="Road Incidents">
                                                <option value="Road Accident">Road Accident</option>
                                                <option value="Road Blocked">Road Blocked</option>
                                                <option value="Severe Traffic">Severe Traffic</option>
                                            </optgroup>
                                            <optgroup label="Natural Disaster">
                                                <option value="Severe Flooding">Severe Flooding</option>
                                                <option value="Heavy Rain">Heavy Rain</option>
                                                <option value="Landslide">Landslide</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Additional Information:</label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" rows="6" name="additional-info" id="additional-info" placeholder="Hazard Description"></textarea>
                                    </div>
                                    <div class="col-lg-3 col-lg-offset-9" id="characters-left"><span id="chars-left">500</span><span>&nbsp; characters left.</span></div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-group col-lg-4 col-lg-offset-4">
                                <input type="hidden" name="lgu-id" id="lgu-id" value="<?php echo $lgu_id_ret;?>" />
                                <button class="btn btn-outline btn-block btn-success" type="submit" name="submit-hazard" id="submit-hazard">Submit Hazard</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
            <div class="row" id="send-alerts-residents">
                <div class="col-lg-12">
                    <h1 class="page-header">Send Alerts to Residents</h1>
                </div>
                <div class="col-lg-12">
                    <div class="col-lg-8" id="form-send-alert-indiv">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><h4>Send Alerts to Residents
                                <button type="button" class="close" id="send-indiv-cancel" title="Close"><span style="color: white;">&times;</span></button>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <form action="#" method="POST" id="indiv-form">
                                    <div class="form-group col-lg-12">
                                        <label class="col-lg-12">Recipient<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="form-group col-lg-12">
                                            <select id="resident-indiv" name="resident-indiv" class="form-control">
                                                <option selected disabled value="">Please Select the Recipient</option>
                                            <?php
                                            $residents_address_query = mysqli_query($connect, "SELECT DISTINCT address FROM resident_household ORDER BY address ASC");
                                            while($resident = mysqli_fetch_assoc($residents_address_query)){
                                                $address_all = $resident['address'];
                                                $residents_ind = mysqli_query($connect, "SELECT household_head_name, household_id FROM resident_household WHERE address = '$address_all'");
                                                $string = "<optgroup label=\"".$address_all."\">";
                                                while($resident_indiv = mysqli_fetch_assoc($residents_ind)){
                                                    $household_head_name = $resident_indiv['household_head_name'];
                                                    $household_id = $resident_indiv['household_id'];
                                                    $string.="<option value=\"$household_id\"><b>".$household_head_name."</option>";
                                                }   
                                                $string.="</optgroup>";
                                                echo $string;
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12" id="other-purp-div">
                                        <label class="col-lg-12">Purpose<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="form-group col-lg-12">
                                            <select id="purpose" name="purpose" class="form-control">
                                                <option selected disabled value="" id="def-val">Select the Purpose</option>
                                                <optgroup label="Evacuation">
                                                    <option value="Mandatory Evacuation">Mandatory Evacuation</option>
                                                    <option value="Voluntary Evacuation">Voluntary Evacuation</option>
                                                </optgroup>
                                                <optgroup label="Update">
                                                    <option value="Weather Update">Weather Update</option>
                                                    <option value="Weather Disturbance Warning">Weather Disturbance Warning</option>
                                                </optgroup>
                                                <optgroup label="Others">
                                                    <option value="Others" id="other-purpose">Please Specify</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="others-purp" class="form-group col-lg-12">
                                        <label class="col-lg-12">Other Purposes<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="col-lg-12">
                                            <input type="text" id="textfield-others" class="form-control" name="textfield-others" placeholder="Please specify" autocomplete="off" value="" />
                                        </div>
                                        <div>&nbsp;</div>
                                        <div class="col-lg-12">
                                            <button type="button" class="col-lg-2 col-lg-offset-5 btn btn-danger" id="clear-textbox"><span class="fa fa-times"></span>&nbsp;Cancel</button>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="col-lg-12">Details<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="col-lg-12">
                                            <textarea class="form-control" rows="6" name="alert-details" id="alert-details" placeholder="Additional details about the alert to send..."></textarea>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <span class="error-messages-transparent" id="text-counter">1000</span> characters left.
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 center-text">
                                        <button type="submit" class="btn btn-success" name="submit-alert" id="submit-alert"><span class="fa fa-send-o fa-fw"></span>&nbsp;Send Alert</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button class="col-lg-3 btn btn-outline btn-success" id="send-indiv">
                            <span class="fa fa-user fa-fw"></span>&nbsp;Send Alert to Individual Resident
                        </button>
                    </div>
                    <div>&nbsp;</div>
                    <div class="col-lg-8" id="form-send-alert-all">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4>Send Alerts to all Residents
                                    <button type="button" class="close" id="send-all-cancel" title="Close"><span style="color: white;">&times;</span></button>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <form action="#" method="POST" id="all-form">
                                    <div class="form-group col-lg-12" id="other-purp-div-all">
                                        <label class="col-lg-12">Purpose<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="form-group col-lg-12">
                                            <select id="purpose-all" name="purpose-all" class="form-control">
                                                <option selected disabled value="" id="def-val-all">Select the Purpose</option>
                                                <optgroup label="Evacuation">
                                                    <option value="Mandatory Evacuation">Mandatory Evacuation</option>
                                                    <option value="Voluntary Evacuation">Voluntary Evacuation</option>
                                                </optgroup>
                                                <optgroup label="Update">
                                                    <option value="Weather Update">Weather Update</option>
                                                    <option value="Weather Disturbance Warning">Weather Disturbance Warning</option>
                                                </optgroup>
                                                <optgroup label="Others">
                                                    <option value="Others" id="other-purpose-all">Please specify</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="others-purp-all" class="form-group col-lg-12">
                                        <label class="col-lg-12">Other Purposes<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="col-lg-12">
                                            <input type="text" id="textfield-others-all" class="form-control" name="textfield-others-all" placeholder="Please specify" autocomplete="off" value="" />
                                        </div>
                                        <div>&nbsp;</div>
                                        <div class="col-lg-12">
                                            <button type="button" class="col-lg-2 col-lg-offset-5 btn btn-danger" id="clear-textbox-all"><span class="fa fa-times"></span>&nbsp;Cancel</button>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="col-lg-12">Details<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="col-lg-12">
                                            <textarea class="form-control" rows="6" name="alert-details-all" id="alert-details-all" placeholder="Additional details about the alert to send..."></textarea>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <span class="error-messages-transparent" id="text-counter-all">1000</span> characters left.
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 center-text">
                                        <button type="submit" class="btn btn-success" name="submit-alert-all" id="submit-alert-all"><span class="fa fa-send-o fa-fw"></span>&nbsp;Send Alert</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button class="col-lg-3 btn btn-outline btn-success" id="send-all"><span class="fa fa-users fa-fw"></span>&nbsp;Send Alert to All Residents</button>
                    </div>
                    <div>&nbsp;</div>
                    <div class="col-lg-8" id="form-send-alert-barangay">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4>Send Alerts to Barangays
                                <button type="button" class="close" id="send-barangay-cancel" title="Close"><span style="color: white;">&times;</span></button>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <form action="#" method="POST" id="barangay-form">
                                    <div class="form-group col-lg-12">
                                        <label class="col-lg-12">Recipient<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="form-group col-lg-12">
                                            <select id="barangay-rec" name="barangay-rec" class="form-control">
                                                <option selected disabled value="">Please Select the Barangay</option>
                                            <?php
                                                $query_all_barangay = mysqli_query($connect, "SELECT DISTINCT address FROM resident_household ORDER BY address ASC");
                                                while ($address_barangay = mysqli_fetch_assoc($query_all_barangay)) {
                                                    $address = $address_barangay['address'];
                                                    echo "<option value=\"$address\">$address</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12" id="other-purp-div-barangay">
                                        <label class="col-lg-12">Purpose<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="form-group col-lg-12">
                                            <select id="purpose-barangay" name="purpose-barangay" class="form-control">
                                                <option selected disabled value="" id="def-val-barangay">Select the Purpose</option>
                                                <optgroup label="Evacuation">
                                                    <option value="Mandatory Evacuation">Mandatory Evacuation</option>
                                                    <option value="Voluntary Evacuation">Voluntary Evacuation</option>
                                                </optgroup>
                                                <optgroup label="Update">
                                                    <option value="Weather Update">Weather Update</option>
                                                    <option value="Weather Disturbance Warning">Weather Disturbance Warning</option>
                                                </optgroup>
                                                <optgroup label="Others">
                                                    <option value="Others" id="other-purpose-barangay">Please specify</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="others-purp-barangay" class="form-group col-lg-12">
                                        <label class="col-lg-12">Other Purposes<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="col-lg-12">
                                            <input type="text" id="textfield-others-barangay" class="form-control" name="textfield-others-barangay" placeholder="Please specify" autocomplete="off" value="" />
                                        </div>
                                        <div>&nbsp;</div>
                                        <div class="col-lg-12">
                                            <button type="button" class="col-lg-2 col-lg-offset-5 btn btn-danger" id="clear-textbox-barangay"><span class="fa fa-times"></span>&nbsp;Cancel</button>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label class="col-lg-12">Details<span class="required-color">&nbsp;*</span>&nbsp;:</label>
                                        <div class="col-lg-12">
                                            <textarea class="form-control" rows="6" name="alert-details-barangay" id="alert-details-barangay" placeholder="Additional details about the alert to send..."></textarea>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <span class="error-messages-transparent" id="text-counter-barangay">1000</span> characters left.
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12 center-text">
                                        <button type="submit" class="btn btn-success" name="submit-alert-barangay" id="submit-alert-barangay"><span class="fa fa-send-o fa-fw"></span>&nbsp;Send Alert</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button class="col-lg-3 btn btn-outline btn-success" id="send-barangay"><span class="fa fa-users fa-fw"></span>&nbsp;Send Alert to a Barangay</button>
                    </div>
                </div>
            </div>
            <div class="row" id="received-alerts-residents">
                <div class="col-lg-12">
                    <h1 class="page-header">Received Reports from Residents!</h1>
                </div>
                <div class="col-lg-12">
                    <div class="col-lg-2 pull-left"><span class="view-by">View by:</span>
                    </div>
                    <div class="col-lg-3">
                        <select class="form-control" id="choices-views">
                            <option value="Household" selected>&nbsp;Household</option>
                            <option value="Time">&nbsp;Time</option>
                            <option value="Category">&nbsp;Category</option>
                        </select>
                    </div>
                    <div class="col-lg-12" id="date-range-div">
                        <div class="col-lg-2">
                            <label>Range (From - To):</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control date-range" value="<?php echo date('Y-m-d')?>" id="date-range-1"/>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control date-range" value="<?php echo date('Y-m-d')?>" id="date-range-2"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" id="category-view-div">
                    <div class="col-lg-2 pull-left"><span class="category">Category:</span></div>
                    <div class="col-lg-3">
                        <select class="form-control" id="category-views">
                        <?php
                            $query_all_cats = mysqli_query($connect, "SELECT DISTINCT category FROM reports");
                            $string = "";
                            while($cat = mysqli_fetch_assoc($query_all_cats)){
                                if($cat['category'] == "Evacuation"){
                                    $string.= "<option value=\"".$cat['category']."\" selected>".$cat['category']."</option>";
                                }
                                else{
                                    $string.= "<option value=\"".$cat['category']."\">".$cat['category']."</option>";   
                                }
                            }
                            echo $string;
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12" id="received-reports-from-residents"></div>
            </div>
            <div class="row" id="sent-alerts-residents">
                <div class="col-lg-12">
                    <h1 class="page-header">Sent Alerts to Residents</h1>
                </div>
                <div class="col-lg-12">
                    <div class="col-lg-2"><span class="view-by">View by:</span>
                    </div>
                    <div class="col-lg-3">
                        <select class="form-control" id="choices-views-sent">
                            <option value="" disabled selected>--Please select a category--</option>
                            <option value="Household">&nbsp;Household</option>
                            <option value="Date">&nbsp;Date</option>
                            <option value="Alert Type">&nbsp;Alert Type</option>
                        </select>
                    </div>
                    <div class="col-lg-12" id="date-range-div-sent">
                        <div class="col-lg-2">
                            <label>Date (From - To):</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control date-range" value="<?php echo date('Y-m-d')?>" id="date-range-1-sent"/>
                        </div>
                        <div class="col-lg-3">
                            <input type="date" class="form-control date-range" value="<?php echo date('Y-m-d')?>" id="date-range-2-sent"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" id="households-div">
                    <div class="col-lg-2 pull-left"><span class="category">Households:</span></div>
                    <div class="col-lg-3">
                        <select class="form-control" id="households-views">
                        <?php
                            $hh_indiv = mysqli_query($connect, "SELECT DISTINCT lgu_alerts.household_id as household_id, resident_household.household_head_name as hh_name FROM lgu_alerts, resident_household WHERE lgu_alerts.household_id = resident_household.household_id ORDER BY resident_household.household_head_name ASC");
                            $str_hh = "<option value=\"\" selected disabled>--Please select the household--</option>";
                            while($hh = mysqli_fetch_assoc($hh_indiv)){
                                $str_hh.="<option value=\"".$hh['household_id']."\">".$hh['hh_name']."</option>";
                            }
                            echo $str_hh;
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12" id="alert-type-view-div">
                    <div class="col-lg-2 pull-left"><span class="category">Alert Type:</span></div>
                    <div class="col-lg-3">
                        <select class="form-control" id="alert-type-views">
                        <?php
                            $alert_type = mysqli_query($connect, "SELECT DISTINCT alert_type FROM lgu_alerts ORDER BY alert_type ASC");
                            $str = "<option value=\"\" selected disabled>--Please select the alert type--</option>";
                            while($cat = mysqli_fetch_assoc($alert_type)){
                                $str.= "<option value=\"".$cat['alert_type']."\">".$cat['alert_type']."</option>";   
                            }
                            echo $str;
                        ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-10" id="warning-no-cat">
                    <div class="panel panel-info">
                        <div class="panel-heading"><h4>Reports</h4></div>
                        <div class="panel-body">
                            <div class="alert alert-info"><h4><span class="fa fa-info-circle fa-fw"></span>&nbsp;Please select the category for viewing.</h4></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10" id="table-sent-alerts-residents"></div>
            </div>
            <div class="row" id="manage-evacuation-centers">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage Evacuation Centers</h1>
                </div>
                <div class="col-lg-10" id="table-evacuation-or-safehouses"></div>
                <div class="col-lg-2">
                    <button class="btn btn-outline btn-success btn-block" data-toggle="modal" data-target="#add-evac-center-modal" data-backdrop="static"><span class="fa fa-plus fa-fw"></span>Evac Center</button>
                    <button class="btn btn-outline btn-success btn-block" id="add-safehouse-btn" data-toggle="modal" data-target="#add-safehouse-modal" data-backdrop="static"><span class="fa fa-plus fa-fw"></span>Safehouse</button>
                    <button class="btn btn-outline btn-success btn-block" id="edit-evac-stat-btn" data-toggle="modal" data-target="#edit-evac-stat-modal" data-backdrop="static"><span class="fa fa-edit fa-fw"></span>Evac Status</button>
                </div>
                <div id="edit-evac-stat-modal" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-width">
                        <div class="modal-content panel panel-success">
                            <div class="modal-header panel-heading">
                                <button type="button" class="close" data-dismiss="modal" title="Exit">&times;</button>
                                <h2 class="panel-title">Edit Evacuation Status</h2>
                            </div>
                            <div class="modal-body panel-body">
                                <div>
                                    <div class="col-lg-12 error-messages-div"><span id="error-messages">&nbsp;</span></div>
                                    <form action="#" method="post" id="evacuation-status-form">
                                        <div class="col-lg-10 col-lg-offset-1">
                                            <div class="form-group">
                                                <label>Current Evacuation Status: </label>
                                            </div>
                                            <select id="evac-stat" class="form-control" name="evac-stat">
                                                <?php
                                                $query_evac_stat = mysqli_query($connect, "SELECT evacuation_status_current FROM evacuation_status WHERE evacuation_status_id = 1");
                                                $evac_stat_arr = mysqli_fetch_array($query_evac_stat);
                                                $evac_stat = $evac_stat_arr['evacuation_status_current'];
                                                $string = "";
                                                if($evac_stat == "Enable"){
                                                    $string.="<option value=\"$evac_stat\" selected>$evac_stat</option>";
                                                    $string.="<option value=\"Disable\">Disable</option>";
                                                }
                                                else {
                                                    $string.="<option value=\"$evac_stat\" selected>$evac_stat</option>";
                                                    $string.="<option value=\"Enable\">Enable</option>";
                                                }
                                                echo $string;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-4 col-lg-offset-4" style="margin-top: 12px;">
                                            <button type="submit" class="form-control btn btn-success" id="edit-e-status" name="edit-e-status" onclick="return confirm('Are you sure you want to change the status?')">Change Evacuation Status</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 

                <!-- Modals -->
                <?php require('modals-safehouse-evac.php');?>
            </div>
        </div><!-- /#page-wrapper -->
    </div><!-- /#wrapper -->
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
</body>
</html>
