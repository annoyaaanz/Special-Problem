$(document).ready(function(){
	$("#map-display").show();
	$("#traveler-register").hide();
	$("#map-sidebar").attr("class", "traveler-sidebar side-active");
	$("#map-sidebar").click(function(){
		$("#map-display").show();
		$("#traveler-register").hide();
		$("#map-sidebar").attr("class", "traveler-sidebar side-active");
		$("#login-as-traveler-sidebar").attr("class", "traveler-sidebar inactive");
		$("#register-sidebar").attr("class", "traveler-sidebar inactive");
	});
	$("#login-as-traveler-sidebar").click(function(){
		$("#map-display").show();
		$("#traveler-register").hide();
		$("#map-sidebar").attr("class", "traveler-sidebar inactive");
		$("#login-as-traveler-sidebar").attr("class", "traveler-sidebar side-active");
		$("#register-sidebar").attr("class", "traveler-sidebar inactive");
  	});
  	$("#register-sidebar").click(function(){
		$("#map-display").hide();
		$("#traveler-register").show();
		$("#map-sidebar").attr("class", "traveler-sidebar inactive");
		$("#login-as-traveler-sidebar").attr("class", "traveler-sidebar inactive");
		$("#register-sidebar").attr("class", "traveler-sidebar side-active");
	});
	$("#exit").click(function(){
		$("#map-sidebar").attr("class", "traveler-sidebar side-active");
		$("#login-as-traveler-sidebar").attr("class", "traveler-sidebar inactive");
		$("#register-sidebar").attr("class", "traveler-sidebar inactive");
	});
    $("#traveler-username").keyup(function(){
        var username = $("#traveler-username").val();
        if(username.length <= 5){
            $("#error-messages").attr("class", "error-messages-red");
            $("#error-messages").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;Username must be at least 6 characters!");
        }
        else {
            check_user();
        }
    });
    $("#traveler-username").focusout(function(){
        $("#error-messages").attr("class", "error-messages-transparent");
        $("#error-messages").html("&nbsp;");
    });
    // $("#traveler-username").focus(function(){check_user();});
    $("#register").click(function(e){
        var fname = $("#traveler-first-name").val();
        var lname = $("#traveler-last-name").val();
        var email = $("#traveler-email").val();
        var mobile_num = $("#traveler-mobile").val();
        var uname = $("#traveler-username").val();
        var password = $("#traveler-password").val();
        var reentered = $("#traveler-reenter-password-password").val();
        var validity = validate_mobile(mobile_num);
        var validity_email = validate_email(email);
        if(validity == 0){
            e.preventDefault();
            $("#error-messages").attr("class", "error-messages-red");
            $("#error-messages").text("Mobile number should follow this format > e.g.: 09123456789");
        }
        if (validity_email == 0) {
            e.preventDefault();
            $("#error-messages").attr("class", "error-messages-red");
            $("#error-messages").text("Email should follow this format > e.g.: email@address.com");
        }
        if(fname==""||lname==""||email==""||mobile_num==""||uname==""||password==""||reentered==""){
            e.preventDefault();
            $("#error-messages").attr("class", "error-messages-red");
            $("#error-messages").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;"+"Please don't leave any field blank!").show(1).delay(1500);
        }
        else{ 
            $.ajax({
                url: "../database/check-traveler-user.php",
                data: "traveler-username="+uname,
                type: "POST", 
                async: true, 
                success: function(result){
                    var res = JSON.parse(result);
                    if(res == 1){
                        e.preventDefault();
                        $("#error-messages").attr("class", "error-messages-red");
                        $("#error-messages").text("Username has been taken!");
                    }
                    else {
                        add_traveler(fname, lname, email, mobile_num, uname, password);
                    }
                }
            });
        }   
    });
    $("#traveler-mobile").keyup(function(){
        var mobile_num = $("#traveler-mobile").val();
        var validity = validate_mobile(mobile_num);
        if(validity == 0){
            $("#error-messages").attr("class", "error-messages-red");
            $("#error-messages").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;"+"Mobile number should follow this pattern > e.g.: 09123456789!");
        }
        else {
            $("#error-messages").attr("class", "error-messages-green");
            $("#error-messages").html("<span class='fa fa-check-circle fa-fw'></span>&nbsp;"+"Phone is valid.");
        }
    });
    $("#traveler-email").keyup(function(){
        var email = $("#traveler-email").val();
        var validity = validate_email(email);
        if(validity == 0){
            $("#error-messages").attr("class", "error-messages-red");
            $("#error-messages").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;"+"Email should follow this format > e.g.: email@address.com");
        }
        else {
            $("#error-messages").attr("class", "error-messages-green");
            $("#error-messages").html("<span class='fa fa-check-circle fa-fw'></span>&nbsp;"+"Email is valid.");
        }
    });
    $("#traveler-reenter-password").keyup(function(){
        var password = $("#traveler-password").val();
        var reentered = $("#traveler-reenter-password").val();
        console.log("Password: "+password+" Re-entered: "+ reentered);
        if(password!==reentered){
            $("#error-messages").attr("class", "error-messages-red");
            $("#error-messages").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;"+"Passwords must be identical!");
        }
        else {
            $("#error-messages").attr("class", "error-messages-green");
            $("#error-messages").html("<span class='fa fa-check-circle fa-fw'></span>&nbsp;"+"Passwords matched!");
        }
    });
    $("#traveler-reenter-password").focusout(function(){
        $("#error-messages").attr("class", "error-messages-transparent");
        $("#error-messages").html("&nbsp;");
    });
	getLocation();
	displayDate();
    setInterval(function(){
        var date = new Date();
        var identifier;
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
    function check_user(){
        $.ajax({
            url: "../database/check-traveler-user.php",
            data: "traveler-username="+$("#traveler-username").val(),
            type: "POST", 
            async: true, 
            success: function(result){
                var res = JSON.parse(result);
                if(res == 1){
                    $("#error-messages").attr("class", "error-messages-red");
                    $("#error-messages").html("<span class='fa fa-times-circle fa-fw'></span>&nbsp;"+"Username has been taken!");
                }
                else {
                    $("#error-messages").attr("class", "error-messages-green");
                    $("#error-messages").html("<span class='fa fa-check-circle fa-fw'></span>&nbsp;"+"Username valid!");
                }
            }
        });
    }
    function add_traveler (fname, lname, email, mobile_num, uname, password) {
        var dataString = "first-name="+fname+"&last-name="+lname+"&email="+email+"&mobile-number="+mobile_num+"&username="+uname+"&password="+password;
        console.log(dataString);
        $.ajax({
            url: "../database/add-new-traveler.php",
            data: dataString, 
            type: "POST", 
            async: true, 
            success: function(result){
                var res = JSON.parse(result);
                if(res == 'success'){
                    $("#success-message").attr("class", "alert alert-success");
                    $("#success-message").html("<span class='fa fa-check-circle fa-fw'></span>&nbsp;"+"Account has been added.");
                    $("#traveler-first-name").val("");
                    $("#traveler-last-name").val("");
                    $("#traveler-email").val("");
                    $("#traveler-mobile").val("");
                    $("#traveler-username").val("");
                    $("#traveler-password").val("");
                    $("#traveler-reenter-password").val("");
                }
                else {
                    $("#success-message").attr("class", "alert alert-error");
                    $("#success-message").text("Something went wrong.");
                }
            },
            error: function(){
                alert("Something went wrong!");
            }
        });
    }
    function validate_mobile(mobile_num){
        var regex = /^09[0-9]{9}$/;
        if(regex.test(mobile_num)){
            return 1;
        }
        else {
            return 0;
        }
    }
    function validate_email(email){
        var regex = /^[A-z]+@[A-z]+.[A-z]+$/;
        if(regex.test(email)){
            return 1;
        }
        else {
            return 0;
        }
    }
});