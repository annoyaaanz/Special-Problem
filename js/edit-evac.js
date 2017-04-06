$(document).ready(function(){
    var prev_cap = $("#evac-capacity").val();
    $("#evac-capacity").bind('keyup mouseup change click', function(){    });
    $("#evac-capacity").keyup(function(){
        var capacity = $("#evac-capacity").val();
        var regex = /^[0-9]+$/;
        if(regex.test(capacity)) {
            if(capacity == 0){
                $("#error-messages-evac").attr("class", "col-lg-6 error-messages-div error-messages-red");
                $("#error-messages-evac").html("<span class='fa fa-times-circle'></span>&nbsp; Capacity should be greater than 0!");
            }
            else if(capacity == prev_cap){
                $("#error-messages-evac").attr("class", "col-lg-6 error-messages-div error-messages-warn");
                $("#error-messages-evac").html("<span class='fa fa-exclamation-circle'></span>&nbsp; Capacity is the same as the old one!");
            }
            else if(capacity > 500){
                $("#error-messages-evac").attr("class", "col-lg-6 error-messages-div error-messages-red");
                $("#error-messages-evac").html("<span class='fa fa-times-circle'></span>&nbsp; Capacity should not exceed 500!");
            }
            else {
                $("#error-messages-evac").attr("class", "col-lg-6 error-messages-div error-messages-green");
                $("#error-messages-evac").html("<span class='fa fa-check-circle'></span>&nbsp; Input is valid!");
                console.log("Valid!");
            }
        }
        else{ 
            $("#error-messages-evac").attr("class", "col-lg-6 error-messages-div error-messages-red");
            $("#error-messages-evac").html("<span class='fa fa-times-circle'></span>&nbsp; Please enter a valid input!");
        }
    });
    var lgu_username = $("#lgu-username-hidden").val();
    $("#weather-icon").hide();
    $("#city").hide();
    $("#temperature").hide();
    $("#weather-description").hide();
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
        if((currentHours-12) == 0) currentHours = 12;
        else if(currentHours < 12) currentHours = currentHours;
        else currentHours = currentHours-12;
        if(currentSeconds < 10) currentSeconds = "0"+currentSeconds;
        if(currentMinutes < 10) currentMinutes = "0"+currentMinutes;
        if(currentHours < 10) currentHours = "0"+currentHours; 
        var currentTimeString = "<b>" + currentHours + ":" + currentMinutes + "</b>:" + currentSeconds + " "+ identifier;
        $("#time").html(currentTimeString);
    }, 1000);

    $("#delete").click(function(){
    });
    $("#add").click(function(){
        alert("Hello!");
    });
    $("#add-safehouse-btn").click(function(){
        $.ajax({
            url: "../database/table-evacuation-centers-and-safehouses-inside.php",
            async: true, 
            success: function(results) {
                var res = JSON.parse(results);
                $("#safehouses").html(res);
            }
        });
    });
    $("#edit-evac").click(function(e){
        var lat = $("#latitude-evac").val();
        var lon = $("#longitude-evac").val();
        var capacity = $("#evac-capacity").val();
        var name = $("#evac-name").val();
        if(lat==""||lon==""||capacity==""||name==""){
            alert("Warning: \nPlease fill out all the fields!");
            e.preventDefault();
        }else {
            var valid = confirm("Do you want to add this evacuation center?");
            if(valid == 0){
                e.preventDefault();
            }
        }
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
            $("#weather-icon").fadeIn("slow");
            $("#city").fadeIn("slow");
            $("#temperature").fadeIn("slow");
            $("#weather-description").fadeIn("slow");
			$("#city").text("Miagao"+", ");
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

});