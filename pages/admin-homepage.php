<?php    
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    session_start();
    $admin_username = $_SESSION['admin_username'];
    if(isset($_SESSION['admin_username'])==null){
        header("location: home.php?error=Please login to continue!");
    }
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
    if(isset($_POST['admin-logout'])){
        session_unset();
        session_destroy();
        header("location: home.php");
    }
    if(isset($_POST['delete-lgu-official-acct'])){
        $lgu_official_id = $_POST['lgu-id'];
        $delete_lgu_query = mysqli_query($connect, "UPDATE lgu_official WHERE lgu_id = $lgu_official_id");
        if(mysqli_affected_rows($connect) == 1){
            header("location: admin-homepage.php?e=LGU Account has been removed!");
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title id="admin-title">Dashboard - <?php echo $admin_username;?></title>
    <?php require("references.php");?>
    <!-- Admin CSS -->
    <link rel="stylesheet" type="text/css" href="../css/admin-home.css">
    <!-- Admin JS -->
    <script type="text/javascript" src="../js/traveler.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>
</head>
<body>
    <!-- Hidden value username admin -->
    <input type="hidden" name="admin-username-hidden" id="admin-username-hidden" value="<?php echo $admin_username;?>"/>
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

            <!-- /.navbar-top-links -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="admin-sidebar" id="dashboard-sidebar">
                            <span><i class="fa fa-home fa-fw"></i>&nbsp;Dashboard</span>
                        </li>
                        <li class="admin-sidebar" id="edit-login-sidebar">
                            <span><i class="fa fa-edit fa-fw"></i>&nbsp;Edit Login Credentials</span>
                        </li>
                        <li class="admin-sidebar" id="manage-lgu-sidebar">
                            <span><i class="fa fa-institution fa-fw"></i>&nbsp;Manage LGU Offices</span>
                        </li>
                        <li class="admin-sidebar" id="manage-lgu-acc-sidebar">
                            <span><i class="fa fa-wrench fa-fw"></i>&nbsp;LGU Accounts</span>
                        </li>
                        <li class="admin-sidebar" id="logout-sidebar">
                            <form action="#" method="POST">
                                <button class="btn btn-block logout-btn" type="submit" name="admin-logout" title="Logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;Logout</button></form>
                        </li>
                    </ul>
                </div><!-- /.sidebar-collapse -->
            </div><!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row" id="dashboard">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbQT4GUyiir-qKMv0CSfJdalOjHjYDnvI&callback=initMap" async defer></script>
                    <div id="error"><?php echo $error_f;?></div>
                    <br/>
                    <div id="map"></div>
                </div>
            </div>
            <div class="row" id="edit-login-credentials">
                <div class="col-lg-12">
                    <h1 class="page-header">Edit Login Credentials!</h1>
                </div>
            </div>
            <div class="row" id="manage-lgu">
                <div class="col-lg-12">
                    <h1 class="page-header">Manage LGU Accounts!</h1>
                </div>
                <div class="col-lg-8">
                    <div id="table-lgus"></div>
                </div>
                <div class="col-lg-4">
                    <span><button class="btn btn-primary btn-outline btn-block" id="add-lgu-office-button">Add Local Government Office</button></span>
                    <div class="col-lg-12" id="add-lgu-office-panel">
                        <div class="panel panel-primary" style="margin-left: -15px; margin-right: -15px;">
                            <div class="panel-heading"> 
                                <div>
                                    <span class="center-text">Add Local Government Office</span>
                                    <span class="right-text pull-right"><button class="btn btn-no-padding btn-transparent" id="cancel-add-office" title="Close this form"><i class="fa fa-times fa-fw"></i></button></span>
                                </div>
                                <div class="sidenote">(Note: Avoid acronyms or abbreviations)</div>
                            </div>
                            <div class="panel-body">
                                <form action="#" method="post">
                                    <div id="error-message" class="error-messages alert">&nbsp;</div>
                                    <div class="form-group">
                                        <label>Local Government Unit Office:</label>
                                        <input type="text" class="form-control" name="lgu-office-to-add" id="lgu-office-to-add" autofocus/>
                                    </div>
                                    <div>
                                        <input class="btn btn-primary btn-block btn-outline" type="button" name="add-lgu-office" id="add-lgu-office" value="Add" />
                                    </div>
                                </form>
                            </div>
                            <div class="panel-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="manage-lgu-acc">
                <div class="col-lg-12">
                    <h1 class="page-header">Local Government Unit Officials</h1>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <div id="table-lgu-accs"></div>
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

    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
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