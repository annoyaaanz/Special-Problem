$(document).ready(function(){
	var admin_username = $("#admin-username-hidden").val();
	
    $("#error").fadeIn("slow").delay(3000).fadeOut("slow");
	$("#add-lgu-office-panel").hide();
	$("#add-lgu-office-button").click(function(){
		$("#add-lgu-office-panel").slideDown();
		$("#add-lgu-office-button").hide();
	});
	$("#cancel-add-office").click(function(){
		$("#add-lgu-office-panel").slideUp();
		$("#add-lgu-office-button").show();
		$("#lgu-office").val("");
	});
	/* Upon loading the page */
	$("#dashboard-sidebar").attr("class", "admin-sidebar side-active");
	$("#edit-login-sidebar").attr("class", "admin-sidebar inactive");
	$("#manage-lgu-sidebar").attr("class", "admin-sidebar inactive");
	$("#dashboard").fadeIn();
	$("#edit-login-credentials").hide();
	$("#manage-lgu").hide();
	$("#manage-lgu-acc").hide();

	$("#dashboard-sidebar").click(function(){
		$("#admin-title").html("Dashboard - "+admin_username);
		$("#dashboard").fadeIn();
		$("#edit-login-credentials").hide();
		$("#manage-lgu").hide();
		$("#manage-lgu-acc").hide();
		$("#dashboard-sidebar").attr("class", "admin-sidebar side-active");
		$("#edit-login-sidebar").attr("class", "admin-sidebar inactive");
		$("#manage-lgu-sidebar").attr("class", "admin-sidebar inactive");
		$("#manage-lgu-acc-sidebar").attr("class", "admin-sidebar inactive");
	});
	$("#edit-login-sidebar").click(function(){
		$("#admin-title").html("Edit Login Credentials");
		$("#dashboard").hide();
		$("#edit-login-credentials").fadeIn();
		$("#manage-lgu").hide();
		$("#manage-lgu-acc").hide();
		$("#dashboard-sidebar").attr("class", "admin-sidebar inactive");
		$("#edit-login-sidebar").attr("class", "admin-sidebar side-active");
		$("#manage-lgu-sidebar").attr("class", "admin-sidebar inactive");
		$("#manage-lgu-acc-sidebar").attr("class", "admin-sidebar inactive");
	});
	$("#manage-lgu-sidebar").click(function(){
		dynamic_add_tables();
		$("#admin-title").html("Manage Local Government Unit Offices");
		$("#dashboard").hide();
		$("#edit-login-credentials").hide();
		$("#manage-lgu").fadeIn();
		$("#manage-lgu-acc").hide();
		$("#dashboard-sidebar").attr("class", "admin-sidebar inactive");
		$("#edit-login-sidebar").attr("class", "admin-sidebar inactive");
		$("#manage-lgu-sidebar").attr("class", "admin-sidebar side-active");
		$("#manage-lgu-acc-sidebar").attr("class", "admin-sidebar inactive");
	});
	$("#manage-lgu-acc-sidebar").click(function(){
		lgu_accounts();
		$("#admin-title").html("Manage Local Government Unit Officials");
		$("#dashboard").hide();
		$("#edit-login-credentials").hide();
		$("#manage-lgu").hide();
		$("#manage-lgu-acc").fadeIn();
		$("#dashboard-sidebar").attr("class", "admin-sidebar inactive");
		$("#edit-login-sidebar").attr("class", "admin-sidebar inactive");
		$("#manage-lgu-sidebar").attr("class", "admin-sidebar inactive");
		$("#manage-lgu-acc-sidebar").attr("class", "admin-sidebar side-active");
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



    var lgu_office_name = $("#lgu-office-to-add").val();
    $("#lgu-office-to-add").keyup(function(){
    	if($("#lgu-office-to-add").val().length < 5){
    		$("#error-message").html("Please enter a valid input!").delay(500).fadeIn();
    	}
    	else {
	    	$("#error-message").html("&nbsp;");
	    	$.ajax({
	    		url: "../database/admin-lgu-office-existence-checker.php",
	    		data: "lgu_office_name="+$("#lgu-office-to-add").val(),
	    		type: "GET", 
	    		async: true,
	    		success: function(object){
	    			var obj = JSON.parse(object);
	    			if(obj == 1){
	    				$("#error-message").html("Office is already in the database!").delay(500).fadeIn();
	    			}
	    			else {
	    				$("#error-message").html("<span class='valid-office-name'>Office name is valid.</span>");
	    			}
	    		}
	    	});
    	}
    });
    $("#add-lgu-office").click(function(){
    	var input_text = $("#lgu-office-to-add").val();
    	if(input_text.length< 5){
			$("#error-message").html("Please enter a valid input!");;
    	}
    	console.log($("#lgu-office-to-add").val());
	    if(input_text.length > 5) {
	    	$.ajax({
	    		url: "../database/admin-add-lgu-office.php",
	    		data: "lgu_office_name="+$("#lgu-office-to-add").val(),
	    		type: "GET",
	    		async: true, 
	    		success: function(result){
	    			var object = JSON.parse(result);
	    			if(object==1){
	    				alert("New Local Government Unit (LGU) Office has been added!\nLGU: "+$("#lgu-office-to-add").val());
		    			$("#lgu-office-to-add").val("");
		    			$("#error-message").html("");
		    			$("#admin-title").html("Manage Local Government Unit Offices");
						$("#dashboard").hide();
						$("#edit-login-credentials").hide();
						$("#manage-lgu").show();
						$("#dashboard-sidebar").attr("class", "admin-sidebar inactive");
						$("#edit-login-sidebar").attr("class", "admin-sidebar inactive");
						$("#manage-lgu-sidebar").attr("class", "admin-sidebar side-active");
						dynamic_add_tables();
	    			}
	    			else {
	    				$("#error-message").html(object);
	    			}
	    		}
	    	});
	    }
    });

    // FUNCTIONS
    function dynamic_add_tables(){
		$.ajax({
			url: "../database/table-lgu-offices.php",
			data: "", 
			async: true, 
			success: function(offices){
				var table_values = JSON.parse(offices);
				console.log(table_values);
				$("#table-lgus").html(table_values);
			} 
		});
    }
    function lgu_accounts(){
		$.ajax({
			url: "../database/lgu-accounts.php",
			data: "", 
			async: true, 
			success: function(offices){
				var table_values = JSON.parse(offices);
				console.log(table_values);
				$("#table-lgu-accs").html(table_values);
			} 
		});
    }
});