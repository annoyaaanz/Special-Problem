<!DOCTYPE html>
<html>
<head>
	<title>Traveler - Register</title>
	<?php require('references.php');?>
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
	            <a class="navbar-brand" href="javascript: void()" title="Back to main page">
	            	<span class="glyphicon glyphicon-chevron-left"></span>
	            	<span>Weather Disaster Adviser</span>
	            </a>
	        </div>
	        <div class="navbar-default sidebar" role="navigation">
	            <div class="sidebar-nav navbar-collapse">
	                <ul class="nav" id="side-menu">
	                    <li class="traveler-login-sign" data-toggle="modal" data-target="#traveler-login-modal" data-backdrop="static" title="Login as Traveler">
                            <span><i class="fa fa-user fa-fw"></i>&nbsp;Login/Create Account</span>
	                    </li>
	                </ul>
	            </div><!-- /.sidebar-collapse -->
	        </div><!-- /.navbar-static-side -->
	    </nav>

	    <div id="page-wrapper">
	        <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Create Traveler Account</h1>
                </div>
	        </div>
	        <div class="row">
                <div id="map">
                </div>
	        </div><!-- /.row -->
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
</body>
</html>