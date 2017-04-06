$(document).ready(function(){   
      if (window.Notification && Notification.permission !== "granted") {
        Notification.requestPermission(function (status) {
          if (Notification.permission !== status) {
            Notification.permission = status;
          }
        });
      }

    $("#dashboard-sidebar").attr("class", "resident-sidebar side-active");
    $("#reports").hide();
    $("#reports-sent").hide();
    $("#enter-evacuation").hide();
    $("#send-report").hide();
    $("#requests").hide();
    $("#dashboard").fadeIn();

    $("#error").fadeIn("slow").delay(3000).fadeOut("slow");
    
    $("#dashboard-sidebar").click(function(){
        $("#badge-red").attr("class", "badge-normal");
        $("#dashboard-sidebar").attr("class", "resident-sidebar side-active");
        $("#send-report-sidebar").attr("class", "resident-sidebar inactive");
        $("#enter-evacuation-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sidebar").attr("class", "resident-sidebar inactive");
        $("#requests-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sent-sidebar").attr("class", "resident-sidebar inactive");
        $("#dashboard").fadeIn();
        $("#send-report").hide();
        $("#enter-evacuation").hide();
        $("#requests").hide();
        $("#reports").hide();
        $("#reports-sent").hide();
    });
    $("#send-report-sidebar").click(function(){
        $("#badge-red").attr("class", "badge-normal");
        $("#dashboard-sidebar").attr("class", "resident-sidebar inactive");
        $("#send-report-sidebar").attr("class", "resident-sidebar side-active");
        $("#enter-evacuation-sidebar").attr("class", "resident-sidebar inactive");
        $("#requests-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sent-sidebar").attr("class", "resident-sidebar inactive");
        $("#dashboard").hide();
        $("#send-report").fadeIn();
        $("#enter-evacuation").hide();
        $("#requests").hide();
        $("#reports").hide();
        $("#reports-sent").hide();
    });
    $("#enter-evacuation-sidebar").click(function(){
        displayEvacCenters();
        $("#badge-red").attr("class", "badge-normal");
        $("#dashboard-sidebar").attr("class", "resident-sidebar inactive");
        $("#send-report-sidebar").attr("class", "resident-sidebar inactive");
        $("#enter-evacuation-sidebar").attr("class", "resident-sidebar side-active");
        $("#requests-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sent-sidebar").attr("class", "resident-sidebar inactive");
        $("#dashboard").hide();
        $("#send-report").hide();
        $("#enter-evacuation").fadeIn();
        $("#requests").hide();
        $("#reports").hide();
        $("#reports-sent").hide();
    });
    $("#requests-sidebar").click(function(){
        var res_id = $("#resident-id-hidden").val();
        displayRequests(res_id);
        $("#badge-red").attr("class", "badge-red");
        $("#dashboard-sidebar").attr("class", "resident-sidebar inactive");
        $("#send-report-sidebar").attr("class", "resident-sidebar inactive");
        $("#enter-evacuation-sidebar").attr("class", "resident-sidebar inactive");
        $("#requests-sidebar").attr("class", "resident-sidebar side-active");
        $("#reports-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sent-sidebar").attr("class", "resident-sidebar inactive");
        $("#dashboard").hide();
        $("#send-report").hide();
        $("#enter-evacuation").hide();
        $("#requests").fadeIn();
        $("#reports").hide();
        $("#reports-sent").hide();
    });
    $("#reports-sidebar").click(function(){
        var res_id = $("#resident-id-hidden").val();
        console.log(res_id);
        displayReports(res_id);

        $("#badge-red").attr("class", "badge-red");
        $("#dashboard-sidebar").attr("class", "resident-sidebar inactive");
        $("#send-report-sidebar").attr("class", "resident-sidebar inactive");
        $("#enter-evacuation-sidebar").attr("class", "resident-sidebar inactive");
        $("#requests-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sidebar").attr("class", "resident-sidebar side-active");
        $("#reports-sent-sidebar").attr("class", "resident-sidebar inactive");
        $("#dashboard").hide();
        $("#send-report").hide();
        $("#enter-evacuation").hide();
        $("#requests").hide();
        $("#reports").fadeIn();
        $("#reports-sent").hide();
    });
    $("#reports-sent-sidebar").click(function(){
        var res_id = $("#resident-id-hidden").val();
        console.log(res_id);
        displayReportsSent(res_id);
        $("#badge-red").attr("class", "badge-red");
        $("#dashboard-sidebar").attr("class", "resident-sidebar inactive");
        $("#send-report-sidebar").attr("class", "resident-sidebar inactive");
        $("#enter-evacuation-sidebar").attr("class", "resident-sidebar inactive");
        $("#requests-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sidebar").attr("class", "resident-sidebar inactive");
        $("#reports-sent-sidebar").attr("class", "resident-sidebar side-active");
        $("#dashboard").hide();
        $("#send-report").hide();
        $("#enter-evacuation").hide();
        $("#requests").hide();
        $("#reports").hide();
        $("#reports-sent").fadeIn();
    });

    $("#submit-capacity").click(function(e){
        var capacity = $("#safehouse-capacity").val();
        console.log(capacity);
        if(capacity <= 0 || capacity == null){
            $("#error-msgs").attr("class", "error-messages-div error-messages-red");
            $("#error-msgs").html("<span class='fa fa-times-circle'></span>&nbsp; Capacity should be greater than 0!");
            alert("Please input a valid number!");
            e.preventDefault();
        }
        else if(capacity > 4){
            $("#error-msgs").attr("class", "error-messages-div error-messages-red");
            $("#error-msgs").html("<span class='fa fa-times-circle'></span>&nbsp; Capacity should only have a maximum of 4!");
            e.preventDefault();
        }
    });
    $("#safehouse-capacity").keyup(function(){
        var capacity = $("#safehouse-capacity").val();
        console.log(capacity);
        if(capacity <= 0 || capacity == null){
            $("#error-msgs").attr("class", "error-messages-div error-messages-red");
            $("#error-msgs").html("<span class='fa fa-times-circle'></span>&nbsp; Capacity should be greater than 0!");
            e.preventDefault();
        }
        else if(capacity > 4){
            $("#error-msgs").attr("class", "error-messages-div error-messages-red");
            $("#error-msgs").html("<span class='fa fa-times-circle'></span>&nbsp; Capacity should only have a maximum of 4!");
        }
        else {
            $("#error-msgs").attr("class", "error-messages-div error-messages-green");
            $("#error-msgs").html("<span class='fa fa-check-circle'></span>&nbsp; Capacity is valid!");

        }
    });
    $("#message-details").keyup(function(){
        var current = $("#message-details").val();
        var limit = 500;
        if(current.length > limit || current.length == 501){
            alert("Youve reached the maximum number of characters!");
            $("#message-details").val(current.substring(0, limit));
        }
        else {
            $("#text-counter").attr("class", "error-messages-transparent");
            $("#text-counter").text(limit-current.length);
        }
    });
    $("#submit-report").click(function(e){
        var cat = $("#report-category").val();
        var details = $("#message-details").val();
        if(cat==""||details==""){
            e.preventDefault();
            alert("Please fill out all fields!");
        }
    });
	getLocation();
	displayDate();
    setInterval(function(){
        var date = new Date();
        var identifier;
        console.log(date.getTimezoneOffset());
        var currentHours = date.getHours();
        var currentMinutes = date.getMinutes();
        var currentSeconds = date.getSeconds();
        if(currentHours > 12) identifier = "PM";
        else identifier = "AM";
        if(currentHours > 12) currentHours = currentHours - 12;
        if(currentSeconds < 10) currentSeconds = "0"+currentSeconds;
        if(currentMinutes < 10) currentMinutes = "0"+currentMinutes;
        if(currentHours < 10) currentHours = "0"+currentHours; 
        var currentTimeString = "<b>" + currentHours + ":" + currentMinutes + "</b>:" + currentSeconds + " "+ identifier;
        $("#time").html(currentTimeString);
    }, 1000);

    $("#delete-sent-report").click(function(){
        $.ajax({
            url: "../database/table-evacuation-centers-and-safehouses-inside.php",
            async: true, 
            success: function(results) {
                var res = JSON.parse(results);
                $("#safehouses").html(res);
            }
        });
    });

	/** FUNCTIONS **/
    function getLocation(){
        var coordinates;
        if(navigator.geolocation){
        	navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    function showPosition(position) {
		$.getJSON('http://api.openweathermap.org/data/2.5/weather?lat='+position.coords.latitude+'&lon='+position.coords.longitude+'&APPID=582f23535d3eafc293b3877d5ee74c27&units=metric', function(data){
			console.log(data.name);
			console.log("Latitude: "+position.coords.latitude+" Longitude: "+position.coords.longitude);
			$("#city").text(data.name+", ");
			$("#temperature").html("<b>"+data.main.temp+"°C</b>, ");
			$("#weather-description").html("<b style>"+data.weather[0].description+"<b>");
		});
    }

    function displayDate(){
    	var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    	var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        var date = new Date();
    	date.setDate(date.getDate());
    	$("#date").html("<b>"+days[date.getDay()] + ", " + months[date.getMonth()] + ' ' +date.getDate() + ', ' + date.getFullYear() + "<b> |</b>");
    }

    function displayRequests(resident_id){
        $.ajax({
            url: "../database/table-requests.php",
            data: {id: resident_id},
            async: true, 
            success: function(requests){
                var table = JSON.parse(requests);
                $("#table-requests").html(table);
            }
        });
    } 
    function displayReports(resident_id){
        $.ajax({
            url: "../database/table-reports.php",
            data: {id: resident_id},
            async: true, 
            success: function(requests){
                var table = JSON.parse(requests);
                $("#table-reports").html(table);
            }
        });
    }

    function displayEvacCenters(){
        $.ajax({
            url: "../database/add-to-evac-center.php",
            async: true,
            success: function(centers){
                var table = JSON.parse(centers);
                $("#evacuation-centers").html(table);
            }
        });
    }

    function displayReportsSent(resident_id){
        $.ajax({
            url: "../database/table-reports-sent.php",
            data: {id: resident_id},
            async: true, 
            success: function(requests){
                var table = JSON.parse(requests);
                $("#table-reports-sent").html(table);
            }
        });
    }

    function getNewNumRows(resident_id){
        var result;
        $.ajax({
            url: "../database/notifs.php",
            data: {id: resident_id},
            async: true, 
            success: function(requests){
                result = JSON.parse(requests);
                console.log(result);
            }
        });
    }

    function notify(x){
        var res_id = $("#resident-id-hidden").val();
        $.ajax({
            url: "../database/notifs.php",
            data: {id: res_id},
            async: true, 
            success: function(requests){

                var num_rows = JSON.parse(requests);;
                var prev_rows = x;
                // console.log('prev' +prev_rows);
                // console.log('new'+num_rows);
                if(num_rows>prev_rows){
                    if (window.Notification && Notification.permission === "granted") {
                      var i = 1;
                      var diff = num_rows-prev_rows;
                      var interval = window.setInterval(function () {
                        // Thanks to the tag, we should only see the "Hi! 9" notification 
                        var n = new Notification("You have " + i + ' new notification', {tag: 'soManyNotification'});
                        if (i++ >= diff) {
                          window.clearInterval(interval);
                        }
                      }, 200);

                    }

                    else if (window.Notification && Notification.permission !== "denied") {
                      Notification.requestPermission(function (status) {
                        if (status === "granted") {
                          var i = 0;
                            // var n = new Notification("You have a new alert!");
                        }
                        else {
                          alert("You have a new alert!");
                        }
                      });
                    }

                    else {
                      alert("You have a new alert!");
                    }
                }
                prev_rows = num_rows;
            setTimeout(function(){notify(prev_rows)}, 3000);
            }
        });

    }

    var res_id = $("#resident-id-hidden").val();

    $.ajax({
        url: "../database/notifs.php",
        data: {id: res_id},
        async: true, 
        success: function(requests){
            notify(JSON.parse(requests));
        }
    });


});