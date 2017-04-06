<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    session_start();
    if((isset($_REQUEST['evac-id'])==null) ) header("location: lgu-homepage.php?error=Invalid request!");
    else{
        $id = $_REQUEST['evac-id'];
        if((isset($_SESSION['lgu_username']))== null){
        	header("location: home.php?e=Please login to continue!");
        }
        else if(isset($_POST['edit-evac'])){
            $capacity = $_POST['evac-capacity'];
            $name = $_POST['evac-name'];
            $evacuation_center_id = $_POST['evac-id'];
            $edit_evac_query = mysqli_query($connect, "UPDATE evacuation_centers SET evacuation_center_capacity = $capacity, evacuation_center_name = '$name' WHERE evacuation_center_id = $evacuation_center_id");
            header("location: lgu-homepage.php?e=Changes on evacuation center has been saved!$capacity, $evacuation_center_id, $name");
        }
        else {
        	$error_msg = "Evacuation Center ID is incorrect!";
    		$query_evac_c = mysqli_query($connect, "SELECT * FROM evacuation_centers WHERE evacuation_center_id = $id");
    		if(mysqli_num_rows($query_evac_c) == 1){
    			$evacs = mysqli_fetch_assoc($query_evac_c);
    		    $evac_location = $evacs['evacuation_center_location'];
                $evac_capacity = $evacs['evacuation_center_capacity'];
                $evac_name = $evacs['evacuation_center_name'];
    		    $evac_loc_arr = explode(",", $evac_location);
    		    $evac_lat = $evac_loc_arr[0];
    		    $evac_long = $evac_loc_arr[1];
    		}
        }
    }
?>
<!DOCTYPE html>
<html>
<head>	
	<title id="user-title">Edit Evacuation Center <?php echo $id;?></title>
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
                <h1 class="page-header">Edit Evacuation Center</h1>
            </div>
            <div class="col-lg-10 col-lg-offset-1">
                <div class="panel panel-primary">
                	<div class="panel-heading">Edit Evacuation Center</div> 
                    <form class="form" method="POST" action="#">
                    <div class="panel-body">
                			<div>
                                <input type="hidden" name="evac-id" value="<?php echo $id;?>" />
    	                        <div class="form-group col-lg-12">
    	                            <label class="col-lg-12">Evacuation Center ID: <span><?php echo $id;?></span></label>
    	                        </div>
    	                        <div class="form-group col-lg-12">
    	                            <label class="col-lg-12">Coordinates: </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" readonly name="latitude-evac" id="latitude-evac" value="<?php echo $evac_lat;?>" placeholder="Latitude" />
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" readonly name="longitude-evac" id="longitude-evac" value="<?php echo $evac_long;?>" placeholder="Longitude" />
                                    </div>
    	                        </div>
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Evacuation Center Name: </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="evac-name" id="evac-name" value="<?php echo $evac_name;?>" placeholder="Name" />
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Capacity: </label>
                                    <div class="col-lg-6">
                                        <input type="number" class="form-control" name="evac-capacity" id="evac-capacity" value="<?php echo $evac_capacity;?>" placeholder="Capacity" />
                                    </div>
                                    <div class="col-lg-6 error-messages-div error-messages-transparent" id="error-messages-evac"></div>
                                </div>
    	                        <div class="form-group col-lg-4 col-lg-offset-4">
    	                        </div>
                			</div>
                    	</div>
                        <div class="modal-footer">
                            <button type="submit" class="col-lg-4 col-lg-offset-4 btn btn-outline btn-success"  name="edit-evac" id="edit-evac" >Save Changes</button></div>	
                </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!-- onclick="return confirm('Do you want to save the changes on this evacuation center?');" -->