function initMap(){
    var point;
    navigator.geolocation.getCurrentPosition(function(position) {  

      point = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    // $("#send-rep-link").on("focus",function(){

      $("#latitude").val(position.coords.latitude);
      $("#longitude").val(position.coords.longitude);

    // });
    // Initialize the Google Maps API v3
    
      var map = new google.maps.Map(document.getElementById('map'), {
         zoom: 17,
        center: point,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });
      /* Map 2 */
      var map2 = new google.maps.Map(document.getElementById('evac-map'), {
         zoom: 17,
        center: point,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });
      /* Map 3 */
      var map3 = new google.maps.Map(document.getElementById('alert-map'), {
        zoom: 17,
        center: point,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      });

    // Instantiate a directions service.
    var directionsService = new google.maps.DirectionsService;

    // Create a renderer for directions and bind it to the map.
    var directionsDisplay = new google.maps.DirectionsRenderer({map: map});
    directionsDisplay.setOptions( { suppressMarkers: true } );
    // Place a marker
    new google.maps.Marker({
      position: point,
      map: map
    });    
    /* Map 2 */
    var directionsDisplay = new google.maps.DirectionsRenderer({map: map2});
    directionsDisplay.setOptions( { suppressMarkers: true } );
    /* Map 3 */
    var directionsDisplay = new google.maps.DirectionsRenderer({map: map3});
    directionsDisplay.setOptions( { suppressMarkers: true } );
    // Place a marker
    new google.maps.Marker({
      position: point,
      map: map2
    });

    map.addListener('click', function(event) {
       var lat = event.latLng.lat();
       var long = event.latLng.lng();
       var end = lat+','+long
       console.log(end);
       calculateAndDisplayRoute(directionsDisplay, directionsService, map,end);
     }); 
    /* Map 2*/ 
    // map2.addListener('click', function(event) {
    //    var lat = event.latLng.lat();
    //    var long = event.latLng.lng();
    //    var end = lat+','+long
    //    console.log("Map2: "+end);
    //    calculateAndDisplayRoute(directionsDisplay, directionsService, map,end);
    //  }); 
    var current = new google.maps.Marker({
      position: point,
      map: map
    });


    var iconBase = '../images/';
    var icons = {
      parking: {
        icon: iconBase + 'evacuate.png'
      },
      hazard: {
        icon: iconBase + 'hazard.png'
      },
        accident: {
          icon: iconBase + 'accident.png'
        },
        flood: {
          icon: iconBase + 'flood.png'
        },
        heavyrain: {
          icon: iconBase + 'heavyrain.png'
        },
        landslide: {
          icon: iconBase + 'landslide.png'
        },
        traffic: {
          icon: iconBase + 'traffic.png'
        },
        unknown: {
          icon: iconBase + 'unknown.png'
        },
    };

    google.maps.event.addListener(current, 'click', function(){
      placeMarker(point);
    });
    map2.addListener('click', function(event) {
      var latitude_val = event.latLng.lat();
      var longitude_val = event.latLng.lng();
      $("#latitude-evac").val(latitude_val);
      $("#longitude-evac").val(longitude_val);
      var coordinates= latitude_val+','+longitude_val;
      // placeMarker(event.latLng);
      console.log("Coordinates is: "+coordinates);
    });
    map3.addListener('click', function(event) {
      var latitude_val = event.latLng.lat();
      var longitude_val = event.latLng.lng();
      $("#latitude-alert").val(latitude_val);
      $("#longitude-alert").val(longitude_val);
      var coordinates= latitude_val+','+longitude_val;
      // placeMarker(event.latLng);
      console.log("Coordinates is: "+coordinates);
    });
    var evacMarker;
    function placeMarker(location) {
      if ( evacMarker ) {
        evacMarker.setPosition(location);
      } else {
        evacMarker = new google.maps.Marker({
          position: location,
          icon: icons['evacuate'].icon,
          map: map
        });
      }
    }

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
      });
    }

    var features = [
      {
        position: new google.maps.LatLng(10.642040004746152,122.23085045814514),
        type: 'parking'
      }, {
        position: new google.maps.LatLng(10.640819499429378,122.22763985395432),
        type: 'parking'
      },
    ];

    for (var i = 0, feature; feature = features[i]; i++) {
      addMarker(feature);
    }


       var reports;

       $.ajax({
            url: "../database/approvedtraveleralerts.php", 
            dataType:'json',
            success: function(result){
                reports = result;
                var travelerMarker = new Array(result.length);  
              for(var i = 0; i<result.length; i++){
               var str = result[i].traveler_location;
                var latlng = str.split(",");
                var loc = new google.maps.LatLng(latlng[0],latlng[1]);
                var situation = result[i].traveler_alert_situation;
                var status = result[i].traveler_alert_status;
                var siticon = 'unknown';
                  if(situation=='Road Accident'){
                    siticon = 'accident';
                  }

                  if(situation=='Severe Traffic'){
                    siticon = 'traffic';
                  }

                  if(situation=='Severe Flooding'){
                    siticon = 'flood';
                  }

                  if(situation=='Landslide'){
                    siticon = 'landslide';
                  }
                

                travelerMarker[i] = new google.maps.Marker({
                  position: loc,
                  icon: icons[siticon].icon,
                  map: map
                });

                // InfoWindow content
                var content;

                  content = '<div id="iw-container">' +
                    '<div class="iw-title">' + result[i].traveler_alert_situation + '</div>' +
                    '<div class="iw-content">' +
                      '<div class="iw-subTitle">'+result[i].traveler_first_name +' ' +result[i].traveler_last_name +'</div>' +
                      '<p>' + result[i].traveler_alert_message + '</p>' +
                   '</div>' +
                  '</div>';


                travelerMarker[i].info = new google.maps.InfoWindow({
                  content: content
                });
                // console.log(travelerMarker[i].info);
                google.maps.event.addListener(travelerMarker[i], 'click', function(innerKey) {
                    return function() {
                        travelerMarker[innerKey].info.open(map, travelerMarker[innerKey]);
                    }
                  }(i));
                    }
                  }
              });





  }); 


        // Display the route between the initial start and end selections.

        // Listen to change events from the start and end lists.
/*        var onChangeHandler = function() {
          calculateAndDisplayRoute(
              directionsDisplay, directionsService, markerArray, stepDisplay, map);
        };
        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
      }*/

      function calculateAndDisplayRoute(directionsDisplay, directionsService, map, end) {

        // Retrieve the start and end locations and create a DirectionsRequest using
        // WALKING directions.
        directionsService.route({
          origin: point,
          destination: end,
          travelMode: 'DRIVING'
        }, function(response, status) {
          // Route the directions and pass the response to a function to create
          // markers for each step.
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
}