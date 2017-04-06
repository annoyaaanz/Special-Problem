$(document).ready(function() {



  $('[id^=approve_button]').click(function(){   
          console.log('lo');
          $.ajax({
                url: "database/acceptalert.php", 
                data: {'traveler_alert_id': this.value},
                type: "POST",
                dataType:'json', 
                success: function(result){
                    console.log(result);
                }
            });
    console.log(this.value);
  });
});
    function initMap(){
        var point;


        navigator.geolocation.getCurrentPosition(function(position) {  

        point = new google.maps.LatLng(position.coords.latitude, 
                                           position.coords.longitude);

        // Initialize the Google Maps API v3
        var map = new google.maps.Map(document.getElementById('map'), {
           zoom: 17,
          center: point,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Place a marker
        new google.maps.Marker({
          position: point,
          map: map
        });


        var iconBase = 'images/';
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
        var geocoder = new google.maps.Geocoder;
        var reports;

       $.ajax({
            url: "database/traveleralerts.php", 
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
                if(status=='Approved'){
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
                }

                travelerMarker[i] = new google.maps.Marker({
                  position: loc,
                  icon: icons[siticon].icon,
                  map: map
                });

                // InfoWindow content
                var content;

                if(result[i].traveler_alert_status!='Pending'){
                  content = '<div id="iw-container">' +
                    '<div class="iw-title">' + result[i].traveler_alert_situation + '</div>' +
                    '<div class="iw-content">' +
                      '<div class="iw-subTitle">'+result[i].traveler_first_name +' ' +result[i].traveler_last_name +'</div>' +
                      '<p>' + result[i].traveler_alert_message + '</p>' +
                   '</div>' +
                  '</div>';
                }else{
                    content = '<div id="iw-container">' +
                    '<div class="iw-title">' + result[i].traveler_alert_situation + '</div>' +
                    '<div class="iw-content">' +
                      '<div class="iw-subTitle">'+result[i].traveler_first_name +' ' +result[i].traveler_last_name +'</div>' +
                      '<p>' + result[i].traveler_alert_message + '</p>' +
                   '</div>' +
                  '<button type="button" onclick="approvealert('+result[i].traveler_alert_id+')" class="btn btn-success btn-sm" style="margin-right: 10px">Approve</button>' +
                  '<button type="button" onclick="declinealert('+result[i].traveler_alert_id+')" class="btn btn-danger btn-sm">Decline</button>' +
                  '</div>';
                }





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


    }

  function approvealert(id){   
    console.log(id);
          $.ajax({
                url: "database/approvealert.php", 
                data: {'traveler_alert_id': id},
                type: "POST",
                dataType:'json', 
                success: function(result){
                    console.log(result);
                    location.reload();
                }
            });
  }

  function declinealert(id){   
    console.log(id);
          $.ajax({
                url: "database/declinealert.php", 
                data: {'traveler_alert_id': id},
                type: "POST",
                dataType:'json', 
                success: function(result){
                    console.log(result);
                     location.reload();
                }
            });
  }