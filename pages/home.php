<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    session_start();
    if(isset($_SESSION['lgu_username'])!= null){
        $lgu_user = $_SESSION['lgu_username'];
    }else {$lgu_user = "";}
    if(isset($_SESSION['admin_username'])!=null){
        $admin_user = $_SESSION['admin_username'];
    }else {$admin_user = "";}
    if(isset($_POST['resident-login'])){
        $resident_username = $_POST['resident-username'];
        $resident_password = $_POST['resident-password'];

        $check_resident = mysqli_query($connect, "SELECT * FROM resident_household WHERE username = '$resident_username' AND password = md5('$resident_password')");
        $retrieve_resident = mysqli_fetch_array($check_resident);
        if(mysqli_num_rows($check_resident) == 1){
            $_SESSION['resident_username'] = $retrieve_resident['username'];
            header("location: resident-homepage.php");
        }
        else header("location: error.php?+user does not exist");
    }
    else if(isset($_POST['admin-login'])){
        $admin_username = $_POST['admin-username'];
        $admin_password = $_POST['admin-password'];

        $check_admin = mysqli_query($connect, "SELECT * FROM admin WHERE admin_username = '$admin_username' AND admin_password = md5('$admin_password')");
        $retrieve_admin = mysqli_fetch_array($check_admin);
        $nums = mysqli_num_rows($retrieve_admin);
        if(mysqli_num_rows($check_admin) == 1){
            $_SESSION['admin_username'] = $retrieve_admin['admin_username'];
            header("location: admin-homepage.php");
        }
    }
    else if(isset($_POST['lgu-login'])){
        $lgu_username = $_POST['lgu-username'];
        $lgu_password = $_POST['lgu-password'];

        $check_user = mysqli_query($connect, "SELECT * FROM lgu_official WHERE lgu_username ='$lgu_username' AND password = md5('$lgu_password')");
        $retrieve_user = mysqli_fetch_array($check_user);
        if(mysqli_num_rows($check_user) == 1){
            $_SESSION['lgu_username'] = $retrieve_user['lgu_username'];
            header("location: lgu-homepage.php?e=<br/>");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Weather Disaster Adviser</title>
    <?php require("references.php");?>
    <link rel="stylesheet" type="text/css" href="../css/homepage.css">
    <!-- JavaScript -->
    <script type="text/javascript" src="../js/homepage.js"></script>
</head>

<body>
    <div id="wrapper">
        <div class="col-md-12" id="outer-wall">
            <div class="col-md-6" id="inner-wall-left">
                <div class="weather-update-label weather-variable">Weather Update</div>
                <div class="weather-variable">
                    <span id="city"></span>
                </div>
                <div class="weather-variable">
                    <span id="temperature"></span>
                </div>
                <div class="weather-variable">
                    <span id="wind-speed"></span>
                </div>
                <div class="weather-variable">
                    <span id="weather-description"></span>
                    <span id="weather-img"></span>
                </div>
                <!-- <div id="coordinates"></div> -->
            </div>
            <div class="col-md-6" id="inner-wall-right">
                <div class="col-md-12" id="inner-right-left">
                    <div class="login-as-label">Login as: </div>
                    <div>
                        <button type="button" class="buttons" data-toggle="modal" data-target="#resident-login-modal" data-backdrop="static" id="resident-button" title="Resident Login">Resident</button>
                        <button type="button" class="buttons" data-toggle="modal" data-target="#lgu-login-modal" data-backdrop="static" title="Local Government Unit Login" id="lgu-button">LGU</button>
                        <a href="traveler-homepage.php">
                            <button class="buttons">Traveler</button>
                        </a>
                        <button type="button" class="buttons" data-toggle="modal" data-target="#admin-login-modal" id="admin-button" title="Admin Login">Admin</button>
                    </div>
                    <?php require('modals.php');?>
                </div>
                <div class="col-md-12" id="inner-right-right">
                    <div class="time-holder">
                        <span id="hours"></span>
                        <span class="colon">:</span>
                        <span id="minutes"></span>
                        <span class="colon">:</span>
                        <span id="seconds"></span>
                        <span id="period"></span>
                    </div>
                    <div>
                        <span id="date"></span>
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
    <script type="text/javascript">
        $("#lgu-button").click(function(){
            if("<?php echo $lgu_user;?>" != ""){
                window.location.href = "lgu-homepage.php";
            }
        });
        $("#admin-button").click(function(){
            if("<?php echo $admin_user;?>" != ""){
                window.location.href = "admin-homepage.php";
            }
        });
    </script>
</body>
</html>
