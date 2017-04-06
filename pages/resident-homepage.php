<?php
    $connect = mysqli_connect("localhost", "root", "", "wdadviser");
    session_start();

    $resident_username = $_SESSION['resident_username'];
    $query_household_id = mysqli_query($connect, "SELECT household_id FROM resident_household WHERE username = '$resident_username'");
    $row = mysqli_fetch_array($query_household_id);
    $res_id = $row['household_id'];
    $query_requests = mysqli_query($connect, "SELECT * FROM safehouses WHERE household_id = $res_id AND safehouse_stat = 'request-sent'");
    $rows_requests = mysqli_num_rows($query_requests);
    if(isset($_POST['resident-logout'])){
        session_destroy();
        session_unset();
        header("location: home.php?e=<br/>");
    }
    if(isset($_SESSION['resident_username']) == null){
      header("location: home.php?error=Please login to continue!");
    }

    /* Error and Success Messages */ 
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

    /* Send Report */
    if(isset($_POST['submit-report'])){
        $details = $_POST['message-details'];
        $hh_id = $_POST['household-id'];
        $cat = $_POST['report-category'];
        $insert_query = mysqli_query($connect, "INSERT INTO reports VALUES(0, '$details', $hh_id, now(), '$cat')");
        if(mysqli_affected_rows($connect) == 1){
            header("location: resident-homepage.php?e=Report has been sent!");
        }
    }
    else if(isset($_POST['delete-sent-report'])){
        $report_id = $_POST['report_id'];
        $success_message = $_POST['success-message'];
        $delete_evac_center = mysqli_query($connect, "DELETE FROM evacuation_centers WHERE evacuation_center_id = $evacuation_center_id");
        if(mysqli_affected_rows($connect)==1){
            header("location: lgu-homepage.php?e=Evacuation Center has been successfully deleted!");
        }
    }



?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $resident_username." ".$rows_requests;?></title>
	<?php require("references.php");?>
	<!-- Resident Sign Up CSS -->
	<link rel="stylesheet" type="text/css" href="../css/resident-home.css">
	<!-- Resident JS -->
	<script type="text/javascript" src="../js/resident.js"></script>
</head>
<body>
    <!-- Hidden value username admin -->

    <input type="hidden" name="resident-id-hidden" id="resident-id-hidden" value="<?php echo $res_id;?>"/>
    <input type="hidden" name="resident-username" id="resident-username" value="<?php echo $resident_username;?>"/>
    <input type="hidden" name="numrow" id="numrow" value="0"/>

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
                        <li class="resident-sidebar" id="dashboard-sidebar">
                            <span><i class="fa fa-home fa-fw"></i>&nbsp;Dashboard</span>
                        </li>
                        <li class="resident-sidebar" id="send-report-sidebar">
                            <span><i class="fa fa-send fa-fw"></i>&nbsp;Send Report</span>
                        </li>
                        <li class="resident-sidebar" id="requests-sidebar">
                            <span><i class="fa fa-question fa-fw"></i>&nbsp;Requests
                                <?php  
                                    if($rows_requests == 1){?>
                                        <span class="badge" id="badge-red"><?php echo $rows_requests;?></span>
                                <?php } ?>
                            </span>
                        </li>
                        <li class="resident-sidebar" id="enter-evacuation-sidebar">
                            <span><i class="fa fa-building fa-fw"></i>&nbsp;Evacuation Centers
                            </span>
                        </li>
                        <li class="resident-sidebar" id="reports-sidebar">
                            <span><i class="fa fa-arrow-circle-down fa-fw"></i>&nbsp;Reports Received
                            </span>
                        </li>
                        <li class="resident-sidebar" id="reports-sent-sidebar">
                            <span><i class="fa fa-arrow-circle-up fa-fw"></i>&nbsp;Reports Sent
                            </span>
                        </li>
                        <li class="resident-sidebar" id="logout-sidebar">
                            <form action="#" method="POST">
                                <button class="btn btn-block logout-btn" type="submit" name="resident-logout" title="Logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;Logout</button></form>
                        </li>
                    </ul>
                </div><!-- /.sidebar-collapse -->
            </div><!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">
            <div class="row" id="dashboard">
                <div class="col-lg-12">
                    <div id="error"><?php echo $error_f;?></div>
                    <br/>
                    <div id="map"></div>
                </div>
            </div>
            <div class="row" id="send-report">
                <h1 class="page-header">Send Report to Local Government Unit</h1>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading"><h4>Report</h4></div>
                        <div class="panel-body">
                            <form action="#" method="POST">
                                <input type="hidden" name="household-id" value="<?php echo $res_id;?>"/>
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Category:</label>
                                    <div class="form-group col-lg-12">
                                        <select id="report-category" name="report-category" class="form-control">
                                            <option selected disabled value="">Please Select Category</option>
                                            <option value="Evacuation">Evacuation</option>
                                            <option value="Water Level">Water Level </option>
                                            <option value="Rescue">Medical Attention</option>
                                            <option value="Rescue">Rescue</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label class="col-lg-12">Message Details:</label>
                                    <div class="form-group col-lg-12">
                                        <textarea class="form-control" rows="6" name="message-details" id="message-details" placeholder="Additional details about the report..."></textarea>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <span class="error-messages-transparent" id="text-counter">500</span> characters left.
                                    </div>
                                </div>
                                <div class="form-group col-lg-12 center-text">
                                    <button type="submit" class="btn btn-success" name="submit-report" id="submit-report"><span class="fa fa-send fa-fw"></span>&nbsp;Send Report</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="reports">
                <div class="col-lg-12">
                    <div class="row" id="table-reports">
                        <div class="col-lg-12" id="table-reports"></div>
                    </div>
                </div>
            </div>
            <div class="row" id="reports-sent">
                <div class="row" id="table-reports">
                    <div class="col-lg-12" id="table-reports-sent"></div>
                </div>
            </div>
            <div class="row" id="requests">
                <div class="col-lg-12">
                    <h1 class="page-header">Requests Received</h1>
                    <div id="table-requests"></div>
                </div>
            </div>
            <div class="row" id="enter-evacuation">
                <div class="col-lg-12">
                    <h1 class="page-header">Evacuation Centers</h1>
                    <div id="evacuation-centers"></div>
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

<script>
    function initMap(){
        var point;
        var destinationPoint;
        var currentLocation;

        navigator.geolocation.getCurrentPosition(function(position) {  

        point = new google.maps.LatLng(position.coords.latitude, 
                                           position.coords.longitude);

        // Initialize the Google Maps API v3
        var map = new google.maps.Map(document.getElementById('map'), {
           zoom: 17,
          center: point,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Instantiate a directions service.
        var directionsService = new google.maps.DirectionsService;

        // Create a renderer for directions and bind it to the map.
        var directionsDisplay = new google.maps.DirectionsRenderer({map: map});
        directionsDisplay.setOptions( { suppressMarkers: true } );
        directionsDisplay.setOptions( { preserveViewport: true } );

        // Place a marker
        currentLocation = new google.maps.Marker({
          position: point,
          map: map
        });

        var end = '10.642040004746152,122.23085045814514';
        //calculateAndDisplayRoute(directionsDisplay, directionsService, map,end);

        var iconBase = '../images/';
        var icons = {
          parking: {
            icon: iconBase + 'evacuate.png'
          },
          hazard: {
            icon: iconBase + 'hazard.png'
          },
          house: {
            icon: iconBase + 'house.png'
          },
          safehouse: {
            icon: iconBase + 'safehouse.png'
          }
        };

        var username = $("#resident-username").val();
        $.ajax({
            url: "../database/house.php", 
            data: {'username' : username},
            type: "POST",
            dataType:'json',
            success: function(result){
                // console.log(result.location);
                var str = result.location;
                var latlng = str.split(",");
                var loc = new google.maps.LatLng(latlng[0],latlng[1]);
                // console.log(loc);  
                var houseMarker = new google.maps.Marker({
                  position: loc,
                  icon: icons['house'].icon,
                  map: map
                });

                houseMarker.info = new google.maps.InfoWindow({
                  content: 'Your house'
                });

                google.maps.event.addListener(houseMarker, 'click', function() {
                  houseMarker.info.open(map, houseMarker);
                    calculateAndDisplayRoute(directionsDisplay, directionsService, map,loc);
                    destinationPoint = loc;
                });
                // console.log(result);

                // calculateAndDisplayRoute(directionsDisplay, directionsService, map,loc);
            }
        });


       $.ajax({
            url: "../database/safehouses.php", 
            dataType:'json',
            success: function(result){
                var safeMarker = new Array(result.length);  
              for(var i = 0; i<result.length; i++){
               var str = result[i].location;
                var latlng = str.split(",");
                var loc = new google.maps.LatLng(latlng[0],latlng[1]);

                safeMarker[i] = new google.maps.Marker({
                  position: loc,
                  icon: icons['safehouse'].icon,
                  map: map
                });

                // InfoWindow content
                var content;

                  content = '<div id="iw-container">' +
                    '<div class="iw-title">' + 'Safehouse' + '</div>' +
                    '<div class="iw-content">' +
                      '<div class="iw-subTitle">'+ result[i].household_head_name +'</div>' +
                   '</div>' +
                  '</div>';

                safeMarker[i].loc = loc;
                safeMarker[i].info = new google.maps.InfoWindow({
                  content: content
                });
                // console.log(travelerMarker[i].info);
                google.maps.event.addListener(safeMarker[i], 'click', function(innerKey) {
                    return function() {
                        safeMarker[innerKey].info.open(map, safeMarker[innerKey]);
                        // console.log(safeMarker[innerKey].loc);
                        calculateAndDisplayRoute(directionsDisplay, directionsService, map,safeMarker[innerKey].loc);
                        destinationPoint = safeMarker[innerKey].loc;
                    }
                  }(i));
                    }
                  }
        });



        function addMarker(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map
          });
          marker.info = new google.maps.InfoWindow({
            content: 'Evacuation Center'
          });

          google.maps.event.addListener(marker, 'click', function() {
            marker.info.open(map, marker);
            calculateAndDisplayRoute(directionsDisplay, directionsService, map,feature.position);
            destinationPoint = feature.position;
          });
        }

        var features = [
          {
            position: new google.maps.LatLng(10.642040004746152,122.23085045814514),
            type: 'parking'
          }, {
            position: new google.maps.LatLng(10.640819499429378,122.22763985395432),
            type: 'parking'
          }
        ];


        for (var i = 0, feature; feature = features[i]; i++) {
          addMarker(feature);
        }


        function geolocate(destinedPoint){
          console.log('start');

          navigator.geolocation.getCurrentPosition(function(position) {  

          point = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

            if(destinedPoint==undefined){
              destinedPoint = point;
              // console.log(destinationPoint);
            }
          $("#latitude").val(position.coords.latitude);
          $("#longitude").val(position.coords.longitude);

          currentLocation.setPosition(point);
          // map.setCenter(point);
          console.log(position.coords.latitude,position.coords.longitude);
          
          if(destinedPoint!=point){
              calculateAndDisplayRoute(directionsDisplay, directionsService, map,destinedPoint);
          }
          setTimeout(function(){geolocate(destinationPoint)}, 35000);

        },function(error) {
                // On error code..
            },
            {timeout: 30000, enableHighAccuracy: true, maximumAge: 75000}); 
      }

      geolocate(destinationPoint);

      }); 


      function calculateAndDisplayRoute(directionsDisplay, directionsService,map,end) {

        // Retrieve the start and end locations and create a DirectionsRequest using
        // WALKING directions.
        directionsService.route({
          origin: point,
          destination: end,
          travelMode: 'WALKING'
        }, function(response, status) {
          // Route the directions and pass the response to a function to create
          // markers for each step.
          if (status === 'OK') {
            directionsDisplay.setDirections(response);

            var distance = response.routes[0].legs[0].distance.text;
            var time = response.routes[0].legs[0].duration.text;
            $('#distance').text('Distance: ' + distance);
            $('#time').text('Time: ' + time);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }

    }

</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbQT4GUyiir-qKMv0CSfJdalOjHjYDnvI&callback=initMap"
    async defer></script>
<!-- End of Google maps -->



</body>
</html>
