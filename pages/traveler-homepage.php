<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Traveler</title>
	<?php require("references.php");?>
	<!-- Traveler Homepage CSS -->
	<link rel="stylesheet" type="text/css" href="../css/traveler-home.css">
    <!-- Traveler JS -->
    <script type="text/javascript" src="../js/traveler.js"></script>
    <script type="text/javascript" src="../js/traveler-homepage.js"></script>
    <!-- Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbQT4GUyiir-qKMv0CSfJdalOjHjYDnvI&callback=initMap" async defer></script>
    <!-- <script type="text/javascript" src="../js/lgu-sendreport-travelers.js"></script> -->
    <style type="text/css">#map{height: 430px; background: red;}</style>
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
	            <a class="navbar-brand" href="home.php" title="Back to main page">
	            	<span class="glyphicon glyphicon-chevron-left"></span>
	            	<span>Weather Disaster Adviser</span>
	            </a>
	        </div>
            <ul class="nav navbar-top-links navbar-right tooltip-demo">
                <li data-toggle="tooltip" data-placement="bottom" title data-original-title="Weather Update">
                    <a class="top-icons">
                        <i class="fa fa-cloud fa-fw"></i>&nbsp;
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
	        <div class="navbar-default sidebar" role="navigation">
	            <div class="sidebar-nav navbar-collapse">
	                <ul class="nav tooltip-demo" id="side-menu">
                        <li class="traveler-sidebar" data-toggle="tooltip" data-placement="right" title data-original-title="Map" id="map-sidebar">
                            <span>
                                <i class="fa fa-map-marker fa-fw"></i>&nbsp;Map
                            </span>
                        </li>
                        <li class="traveler-sidebar" data-toggle="modal" data-target="#traveler-login-modal" data-backdrop="static" title="Login as Traveler" id="login-as-traveler-sidebar">
                            <div data-toggle="tooltip" data-placement="right" title data-original-title="Weather Update">
                                <i class="fa fa-sign-in fa-fw"></i>&nbsp;Login
                            </div>
                        </li>
                        <li class="traveler-sidebar" data-toggle="tooltip" data-placement="right" title data-original-title="Create Account" id="register-sidebar">
                            <span>
                                <i class="fa fa-user fa-fw"></i>&nbsp;Register
                            </span>
                        </li>
	                </ul>
	            </div>
	        </div>
	    </nav>

	    <div id="page-wrapper">
            <div class="row" id="map-display">
                <div class="col-lg-12">
                    <h1 class="page-header">Map</h1>
                    <div class="col-lg-12" id="map"></div>
                </div>
            </div>
	        <div class="row" id="traveler-register">
                <div class="col-lg-12">
                    <h1 class="page-header">Register</h1>
                </div>
                <div class="col-lg-10">
                    <div id="success-message"></div>
                    <div class="panel panel-primary">
                        <div class="panel-heading panel-label">Create Traveler Account</div>
                        <div class="panel-body">
                            <div class="col-lg-12 error-messages-div"><span id="error-messages">&nbsp;</span></div>
                            <form action="javascript: void();" method="post" id="register-form">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="traveler-first-name" id="traveler-first-name" placeholder="e.g.: Juan" autofocus required= "required" autocomplete="off"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="traveler-last-name" id="traveler-last-name" placeholder="dela Cruz" required= "required" autocomplete="off"/>
                                    </div>
                                    <div class="form-group">
                                        <label>E-mail</label>
                                        <input type="text" class="form-control" name="traveler-email" id="traveler-email" pattern="[A-z]+@[A-z]+.[A-z]+" title="E-mail should follow this pattern > email@address.com" placeholder="email@address.com" required="required" autocomplete="off"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="traveler-username" id="traveler-username" placeholder="juandlcruz"  required="required" autocomplete="off"/>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input type="text" class="form-control" name="traveler-mobile" id="traveler-mobile" pattern="[0-9]{11}" placeholder="09123456789" title="Mobile number should follow this format > e.g.: 09123456789!" maxlength="11" required="required" autocomplete="off"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="traveler-password" pattern="[A-Za-z0-9]{6,}" id="traveler-password" placeholder="Passwords must be identical" required="required" title="Password must be at least 6 characters and must contain at least one numeric character and one capital letter." autocomplete="off"/>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" name="traveler-reenter-password" id="traveler-reenter-password" placeholder="Passwords must be identical" required="required" autocomplete="off"/>
                                    </div>
                                    <div class="form-group" id="hidden-elem">
                                        <label>&nbsp;</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group col-lg-2 col-lg-offset-5">
                                    <button type="submit" class="form-control btn btn-outline btn-primary" id="register" name="register">Sign up!</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
	        </div>
            <!-- MODAL FOR LOGIN -->
	        <div id="traveler-login-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content panel panel-primary">
                        <div class="modal-header panel-heading">
                            <button type="button" class="close" data-dismiss="modal" id="exit" title="Exit">&times;</button>
                            <h2 class="panel-title">Traveler Login</h2>
                        </div>
                        <div class="modal-body panel-body">
                            <form role="form" class="form" method="post">
                                <fieldset>
                                    <div><span id="error-messages">&nbsp;</span></div>
                                    <div class="left-align form-group">
                                        <label>Username:</label>
                                        <br/>
                                        <input type="text" class="form-control" name="traveler-username" placeholder="e.g.: juandelacruz" autofocus>
                                    </div>
                                    <div class="left-align form-group">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" id="traveler-password" placeholder="Password" name="password" value="">
                                    </div>
                                </fieldset>
                        </div>
                        <div class="panel-footer">
                            <div class="login-btn">
                                <input type="submit" class="btn btn-success btn-block" value="Login" title="Login" name="traveler-login"/>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!-- modal-->
	    </div><!-- /#page-wrapper -->
	</div><!-- /#wrapper -->
    <script type="text/javascript">
    </script>
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