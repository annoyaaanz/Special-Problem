$(document).ready(function(){
    $("#inner-wall-left").css("visibility", "hidden");
	getLocation();
	displayDate();
    // showPosition();
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
        // var currentTimeString = "The time is: <b>" + currentHours + ":" + currentMinutes + "</b>:" + currentSeconds + " "+ identifier;
        $("#hours").html(currentHours+"");
        $("#minutes").html(currentMinutes+"");
        $("#seconds").html(currentSeconds);
        $("#period").html(" "+identifier);
    }, 1000);
    $("#lgu-login").click(function(e){
        var username = $("#lgu-username").val();
        var password = $("#lgu-password").val();
        console.log("Username: "+ username+", Password:"+ password);
        var auth = checkLGU(username, password);
        console.log(auth);
        if(auth == 0){
            e.preventDefault();
            $("#error-messages-lgu").attr("class", "error-messages-red");
            $("#error-messages-lgu").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;Username or Password is incorrect!");
        }
        if(username == "" || password == ""){
            e.preventDefault();
            $("#error-messages-lgu").attr("class", "error-messages-red");
            $("#error-messages-lgu").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;Please don't leave any field blank!");
        }
    });
    $("#resident-login").click(function(e){
        var username = $("#resident-username").val();
        var password = $("#resident-password").val();
        console.log("Username: "+username+"\nPassword: "+password);
        var authenticate = checkResident(username, password);
        console.log("Resident Exists: "+authenticate);
        if(authenticate == 0){
            e.preventDefault();
            $("#error-messages-resident").attr("class", "error-messages-red");
            $("#error-messages-resident").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;Username or Password is incorrect!");
        }
        if(username == "" || password == ""){
            e.preventDefault();
            $("#error-messages-resident").attr("class", "error-messages-red");
            $("#error-messages-resident").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;Please don't leave any field blank!");
        }
    });
	/** FUNCTIONS **/
    function getLocation(){
        var coordinates;
        if(navigator.geolocation){
        	navigator.geolocation.getCurrentPosition(showPosition);
        }
    }
    // function showPosition(){
    function showPosition(position) {
		$.getJSON('http://api.openweathermap.org/data/2.5/weather?lat='+position.coords.latitude+'&lon='+position.coords.longitude+'&APPID=582f23535d3eafc293b3877d5ee74c27&units=metric', function(data){
            // $.getJSON('http://api.openweathermap.org/data/2.5/weather?lat=10.670547&lon=122.197308&APPID=582f23535d3eafc293b3877d5ee74c27&units=metric', function(data){
            $("#inner-wall-left").css("visibility", "visible");
            $("#inner-wall-left").fadeIn("slow");
			console.log(data.name);
            var name = "Miagao";
			console.log("Latitude: "+position.coords.latitude+" Longitude: "+position.coords.longitude);
			$("#city").html("City/Municipality: <b>"+name+"</b>");
            $("#weather-img").attr("src","http://openweathermap.org/img/w/"+ data.weather[0].icon+ ".png");
            $("#weather-img").width(100).height(100);
			$("#temperature").html("Temperature: <b>"+data.main.temp+"°C</b>");
            $("#wind-speed").html("Wind Speed: <b>" +data.wind.speed+" mps</b>");
			$("#weather-description").html("Weather Description: <b>"+data.weather[0].description+"<b>");
			$("#coordinates").text("Coordinates: Latitude: "+position.coords.latitude+" Longitude: "+position.coords.longitude);
		});
    }
    function displayDate(){
    	var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    	var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        var date = new Date();
    	date.setDate(date.getDate());
    	$("#date").html(days[date.getDay()] + ", " + months[date.getMonth()] + ' ' +date.getDate() + ', ' + date.getFullYear());
    }
    function checkLGU(username, password){
        $.ajax({
            url: "../database/check-lgu-existence.php",
            data: "username="+username+"&password="+password,
            type: "POST", 
            async: false,
            success: function(result){
                res = JSON.parse(result);
                console.log(res);
                return res;
            }
        });
        return res;
    }
    function checkResident(username, password){
        $.ajax({
            url: "../database/check-resident-existence.php", 
            data: {username: username, password: password},
            type: "POST", 
            async: false, 
            success: function(result){
                res = JSON.parse(result);
                return res;
            }
        });
        return res;
    }
});