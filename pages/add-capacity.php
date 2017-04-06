<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    session_start();
    $resident_username = $_SESSION['resident_username'];
    $safehouse_id = $_REQUEST['safehouse-id'];
    $hh_id = $_REQUEST['hh-id'];
    if(isset($_POST['submit-capacity'])){
        $sh_id = $_POST['safehouse-id'];
        $capacity = $_POST['safehouse-capacity'];
        $accept = mysqli_query($connect, "UPDATE safehouses SET safehouse_capacity = $capacity, safehouse_stat = 'accepted' WHERE safehouse_id = $sh_id");
        if (mysqli_affected_rows($connect) == 1){
            header("location: resident-homepage.php?e=Request has been accepted!");
        }
        else {
            header("location: resident-homepage.php?e=Something went wrong!$sh_id, $stat, $capacity");
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Accept Request - <?php echo $resident_username." ".$safehouse_id?></title>
	<?php require("references.php");?>
	<!-- Resident Sign Up CSS -->
	<link rel="stylesheet" type="text/css" href="../css/resident-home.css">
	<!-- Resident JS -->
	<script type="text/javascript" src="../js/resident.js"></script>
</head>
<body>
    <!-- Hidden value username admin -->
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
                <a class="navbar-brand" href="javascript: void();" title="Back to main page">
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
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4>Make Household as a Safehouse</h4></div>
                        <div class="panel-body">
                            <form action="#" method="POST">
                                <input type="hidden" name="safehouse-id" value="<?php echo $safehouse_id;?>"/>
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Safehouse Capacity: </label>
                                    <div class="col-lg-12"><input type="number"  class="form-control" name="safehouse-capacity" id="safehouse-capacity" value="" pattern="[0-9]+" placeholder="Capacity should be greater than 1"/></div>
                                    <div class="col-lg-12">
                                        <div id="error-msgs"></div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <div style="text-align: center;"><button type="submit" name="submit-capacity" id="submit-capacity" class="btn btn-success"><span class="fa fa-check-circle fa-fw"></span>&nbsp; Submit</button></div>
                                </div>
                            </form>
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
