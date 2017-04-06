$(document).ready(function(){
    var lgu_username = $("#lgu-username-hidden").val();
    
    displayReceivedReports();
    $("#date-range-div").hide();
    $("#category-view-div").hide();
    $("#households-div").hide();
    $("#alert-type-view-div").hide();
    $("#date-range-div-sent").hide();

    // hide send alert form
    $("#form-send-alert-indiv").hide();
    $("#form-send-alert-all").hide();
    $("#form-send-alert-barangay").hide();
    $("#send-indiv-cancel").hide();
    $("#send-all-cancel").hide();
    // deals with event when user clicks others
    $("#others-purp").hide();
    $("#purpose").change(function(){
        var purpose_value = $("#purpose").val();
        if(purpose_value == "Others"){
            $("#others-purp").fadeIn("slow");
            $("#other-purp-div").hide();
        }
    });
    $("#clear-textbox").click(function(){
        $("#others-purp").hide();
        $("#purpose #def-val").prop('selected', true);
        $("#textfield-others").val("");
        $("#other-purp-div").fadeIn("slow");
    });

    // deals with event when user clicks others - ALL
    $("#others-purp-all").hide();
    $("#purpose-all").change(function(){
        var purpose_value = $("#purpose-all").val();
        if(purpose_value == "Others"){
            $("#others-purp-all").fadeIn("slow");
            $("#other-purp-div-all").hide();
        }
    });
    $("#clear-textbox-all").click(function(){
        $("#others-purp-all").hide();
        $("#purpose-all #def-val-all").prop('selected', true);
        $("#textfield-others-all").val("");
        $("#other-purp-div-all").fadeIn("slow");
    }); 
    // deals with event when user clicks others - BRGY
    $("#others-purp-barangay").hide();
    $("#purpose-barangay").change(function(){
        var purpose_value = $("#purpose-barangay").val();
        if(purpose_value == "Others"){
            $("#others-purp-barangay").fadeIn("slow");
            $("#other-purp-div-barangay").hide();
        }
    });
    $("#clear-textbox-barangay").click(function(){
        $("#others-purp-barangay").hide();
        $("#purpose-barangay #def-val-barangay").prop('selected', true);
        $("#textfield-others-barangay").val("");
        $("#other-purp-div-barangay").fadeIn("slow");
    }); 
    // send individual
    $("#send-indiv").click(function(){
        $("#form-send-alert-indiv").fadeIn("slow");
        $("#send-indiv").fadeOut("slow");
        $("#send-indiv-cancel").show();
    });
    $("#send-indiv-cancel").click(function(){
        $("#form-send-alert-indiv").fadeOut();
        // clear fields
        $("#resident-indiv").val(""); 
        $("#purpose").val("");
        $("#textfield-others").val("");
        $("#alert-details").val("");
        $("#send-indiv").fadeIn("slow");
        $("#send-indiv-cancel").hide();
    });
    // send all
    $("#send-all").click(function(){
        $("#form-send-alert-all").fadeIn("slow");
        $("#send-all").hide();
        $("#send-all-cancel").fadeIn();
    });
    $("#send-all-cancel").click(function(){
        $("#form-send-alert-all").fadeOut("slow");
        // clear fields
        $("#purpose-all").val("");
        $("#textfield-others-all").val("");
        $("#alert-details-all").val("");
        $("#send-all").fadeIn("slow");
        $("#send-all-cancel").hide();
    });
    // send all
    $("#send-barangay").click(function(){
        $("#form-send-alert-barangay").fadeIn("slow");
        $("#send-barangay").hide();
        $("#send-barangay-cancel").fadeIn();
    });
    $("#send-barangay-cancel").click(function(){
        $("#form-send-alert-barangay").fadeOut("slow");
        // clear fields
        $("#purpose-barangay").val("");
        $("#textfield-others-barangay").val("");
        $("#alert-details-barangay").val("");
        $("#send-barangay").fadeIn("slow");
        $("#send-barangay-cancel").hide();
    });
    $("#error").fadeIn("slow").delay(3000).fadeOut("slow");
    $("#dashboard-sidebar").attr("class", "lgu-sidebar side-active");
    $("#dashboard").show();
    $("#received-alerts-traveler").hide();
    $("#send-alerts-residents").hide();
    $("#received-alerts-residents").hide();
    $("#sent-alerts-residents").hide();
    $("#manage-evacuation-centers").hide();
    
    $("#weather-icon").hide();
    $("#city").hide();
    $("#temperature").hide();
    $("#weather-description").hide();

    $("#dashboard-sidebar").click(function(){
        $("#user-title").html("Dashboard - "+lgu_username);
        $("#dashboard").fadeIn();
        $("#received-alerts-traveler").hide();
        $("#send-alerts-residents").hide();
        $("#received-alerts-residents").hide();
        $("#sent-alerts-residents").hide();
        $("#manage-evacuation-centers").hide();
        $("#dashboard-sidebar").attr("class", "lgu-sidebar side-active");
        $("#received-alerts-traveler-sidebar").attr("class", "lgu-sidebar inactive");
        $("#send-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#sent-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#manage-evacuation-centers-sidebar").attr("class", "lgu-sidebar inactive");
    });
    $("#received-alerts-traveler-sidebar").click(function(){
        displayAlerts();
        $("#user-title").html("Received Alerts from Travelers");
        $("#dashboard").hide();
        $("#received-alerts-traveler").fadeIn();
        $("#send-alerts-residents").hide();
        $("#received-alerts-residents").hide();
        $("#sent-alerts-residents").hide();
        $("#manage-evacuation-centers").hide();
        $("#dashboard-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-traveler-sidebar").attr("class", "lgu-sidebar side-active");
        $("#send-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#sent-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#manage-evacuation-centers-sidebar").attr("class", "lgu-sidebar inactive");
    });
    $("#send-alerts-residents-sidebar").click(function(){
        $("#user-title").html("Send Alerts to Residents");
        $("#dashboard").hide();
        $("#received-alerts-traveler").hide();
        $("#send-alerts-residents").fadeIn();
        $("#received-alerts-residents").hide(); 
        $("#sent-alerts-residents").hide();
        $("#manage-evacuation-centers").hide();
        $("#dashboard-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-traveler-sidebar").attr("class", "lgu-sidebar inactive");
        $("#send-alerts-residents-sidebar").attr("class", "lgu-sidebar side-active");
        $("#received-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#sent-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#manage-evacuation-centers-sidebar").attr("class", "lgu-sidebar inactive");
    });
    $("#received-alerts-residents-sidebar").click(function(){
        $("#user-title").html("Received Alerts from Residents");
        $("#dashboard").hide();
        $("#received-alerts-traveler").hide();
        $("#send-alerts-residents").hide();
        $("#received-alerts-residents").fadeIn();
        $("#sent-alerts-residents").hide(); 
        $("#manage-evacuation-centers").hide();
        $("#dashboard-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-traveler-sidebar").attr("class", "lgu-sidebar inactive");
        $("#send-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-residents-sidebar").attr("class", "lgu-sidebar side-active");
        $("#sent-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#manage-evacuation-centers-sidebar").attr("class", "lgu-sidebar inactive");
    });    
    $("#sent-alerts-residents-sidebar").click(function(){

        // displaySentAlertsToResidents();
        $("#user-title").html("Received Alerts from Residents");
        $("#dashboard").hide();
        $("#received-alerts-traveler").hide();
        $("#send-alerts-residents").hide();
        $("#received-alerts-residents").hide();
        $("#sent-alerts-residents").fadeIn(); 
        $("#manage-evacuation-centers").hide();
        $("#dashboard-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-traveler-sidebar").attr("class", "lgu-sidebar inactive");
        $("#send-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#sent-alerts-residents-sidebar").attr("class", "lgu-sidebar side-active");
        $("#manage-evacuation-centers-sidebar").attr("class", "lgu-sidebar inactive");
    });

    $("#manage-evacuation-centers-sidebar").click(function(){
        displayEvacsAndSafehouse();
        $("#user-title").html("Manage Evacuation Centers");
        $("#dashboard").hide();
        $("#received-alerts-traveler").hide();
        $("#send-alerts-residents").hide();
        $("#received-alerts-residents").hide(); 
        $("#sent-alerts-residents").hide();
        $("#manage-evacuation-centers").fadeIn();
        $("#dashboard-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-traveler-sidebar").attr("class", "lgu-sidebar inactive");
        $("#send-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#received-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#sent-alerts-residents-sidebar").attr("class", "lgu-sidebar inactive");
        $("#manage-evacuation-centers-sidebar").attr("class", "lgu-sidebar side-active");
    });
    // on change of choices in view
    $("#choices-views").change(function(){
        $("#category-views selected").val("Evacuation");
        var choice = $("#choices-views option:selected").val();
        if(choice == "Household") {
            $("#category-views selected").val("Evacuation");
            $("#date-range-div").hide();
            $("#category-view-div").hide();
            displayReceivedReports();
        }
        else if(choice == "Time") {
            $("#date-range-div").fadeIn("slow");
            $("#category-view-div").hide();
            $("#category-views selected").val("Evacuation");
            var date_1 = $("#date-range-1").val();
            var date_2 = $("#date-range-2").val();
            if(date_1 == null || date_2 == null){
                alert("Please fill out the necessary field.");
            }
            displayReceivedReportsTime(date_1, date_2);
            console.log("Date_1: "+date_1+"\nDate_2: "+date_2);
        }
        else if(choice == "Category") {
            $("#date-range-div").hide();
            $("#category-view-div").fadeIn("slow");
            displayReceivedReportsCat();
        }
        console.log("Choice is: "+choice);
    });
    //onchange of Date
    $("#date-range-1").change(function(){
        var date_1 = $("#date-range-1").val();
        var date_2 = $("#date-range-2").val();
        displayReceivedReportsTime(date_1, date_2);
    });
    $("#date-range-2").change(function(){
        var date_1 = $("#date-range-1").val();
        var date_2 = $("#date-range-2").val();
        displayReceivedReportsTime(date_1, date_2);
    });
    // onchange of category
    $("#category-views").change(function(){
        var cat = $("#category-views option:selected").val();
        if(cat == null || cat == ""){
            displayReceivedReportsCat("Evacuation");
        }
        else {
            displayReceivedReportsCat(cat);
        }
        console.log("Category: "+ cat);
    });

    //onchange sent alerts view
    $("#choices-views-sent").change(function(){
        var choice = $("#choices-views-sent").val();
        if(choice == "Household"){
            var hh_id = $("#households-views").val();
            $("#households-div").fadeIn("slow");
            $("#date-range-div-sent").hide();
            $("#alert-type-view-div").hide();
            displaySentHouse(hh_id);
        }
        else if(choice == "Date"){
            var date_1 = $("#date-range-1-sent").val();
            var date_2 = $("#date-range-2-sent").val();            
            $("#date-range-div-sent").fadeIn("slow");
            $("#households-div").hide();
            $("#alert-type-view-div").hide();
            displaySentDate(date_1, date_2);
        }
        else if(choice == "Alert Type"){
            $("#alert-type-view-div").fadeIn("slow");
            var type = $("#alert-type-views option:selected").val();
            $("#households-div").hide();
            $("#date-range-div-sent").hide();
            displaySentType(type);
        }
    });
    $("#households-views").change(function(){
        var hh_id = $("#households-views").val();
        displaySentHouse(hh_id);
    });
    //on change date sent alerts
    $("#date-range-1-sent").change(function(){
        var date_1 = $("#date-range-1-sent").val();
        var date_2 = $("#date-range-2-sent").val();
        displaySentDate(date_1, date_2);
    });
    $("#date-range-2-sent").change(function(){
        var date_1 = $("#date-range-1-sent").val();
        var date_2 = $("#date-range-2-sent").val();
        displaySentDate(date_1, date_2);
    });
    // onchange of alert types
    $("#alert-type-views").change(function(){
        var type = $("#alert-type-views option:selected").val();
        displaySentType(type);
    });
    $("#evac-capacity").keyup(function(){
        var capacity = $("#evac-capacity").val();
        var regex = /^[0-9]+$/;
        if(regex.test(capacity)) {
            if(capacity == 0){
                $("#error-messages-evac").attr("class", "col-lg-6 error-messages-div error-messages-red");
                $("#error-messages-evac").html("<span class='fa fa-times-circle'></span>&nbsp; Capacity should be greater than 0!");
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
    $("#alert-details").keyup(function(){
        var current = $("#alert-details").val();
        var limit = 1000;
        if(current.length > limit || current.length == 501){
            alert("Youve reached the maximum number of characters!");
            $("#alert-details").val(current.substring(0, limit));
        }
        else {
            $("#text-counter").attr("class", "error-messages-transparent");
            $("#text-counter").text(limit-current.length);
        }
    });
    $("#alert-details-all").keyup(function(){
        var current = $("#alert-details-all").val();
        var limit = 1000;
        if(current.length > limit || current.length == 501){
            alert("Youve reached the maximum number of characters!");
            $("#alert-details-all").val(current.substring(0, limit));
        }
        else {
            $("#text-counter-all").attr("class", "error-messages-transparent");
            $("#text-counter-all").text(limit-current.length);
        }
    });
    $("#alert-details-barangay").keyup(function(){
        var current = $("#alert-details-barangay").val();
        var limit = 1000;
        if(current.length > limit || current.length == 501){
            alert("Youve reached the maximum number of characters!");
            $("#alert-details-barangay").val(current.substring(0, limit));
        }
        else {
            $("#text-counter-barangay").attr("class", "error-messages-transparent");
            $("#text-counter-barangay").text(limit-current.length);
        }
    });
    $("#submit-alert").click(function(e){
        var rec = $("#resident-indiv").val();
        var purpose = $("#purpose").val();
        if(purpose == ""){
            purpose = $("#textfield-others").val();
        }
        var alert_det = $("#alert-details").val();
        if(rec == "" || purpose == "" || alert_det == "" || rec == null || purpose == null || alert_det == null){
            alert("Please fill in all the needed details!");
            e.preventDefault();
        }
        else {
            var val = confirm('Do you want to send this report?');
            if(!val){
                e.preventDefault();
            }
        }
    });
    $("#submit-alert-all").click(function(e){
        var purpose = $("#purpose-all").val();
        if(purpose == ""){
            purpose = $("#textfield-others-all").val();
        }
        var alert_det = $("#alert-details-all").val();
        if(purpose == "" || alert_det == "" || purpose == null || alert_det == null){
            alert("Please fill in all the needed details!");
            e.preventDefault();
        }
        else {
            var val = confirm('Do you want to send this report to all the residents?');
            if(!val){
                e.preventDefault();
            }
        }
    });
    $("#submit-alert-barangay").click(function(e){
        var rec = $("#barangay-rec").val();
        var purpose = $("#purpose-barangay").val();
        if(purpose == ""){
            purpose = $("#textfield-others-barangay").val();
        }
        var alert_det = $("#alert-details-barangay").val();
        if(purpose == "" || alert_det == "" || rec == "" || purpose == null || alert_det == null || rec == null){
            alert("Please fill in all the needed details!");
            e.preventDefault();
        }
        else {
            var val = confirm('Do you want to send this report to all the residents?');
            if(!val){
                e.preventDefault();
            }
        }
    });
	getLocation();
	displayDate();
    setInterval(function(){
        var date = new Date();
        var identifier;
        // console.log(date.getTimezoneOffset());
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
    $("#submit-evac").click(function(e){
        var lat = $("#latitude-evac").val();
        var lon = $("#longitude-evac").val();
        var capacity = $("#evac-capacity").val();
        if(lat==""||lon==""||capacity==""){
            alert("Warning: \nPlease fill out all the fields!");
            e.preventDefault();
        }else {
            var valid = confirm("Do you want to add this evacuation center?");
            if(!valid){
                e.preventDefault();
            }
        }
    });
    $("#additional-info").keyup(function(){
        var current = $("#additional-info").val();
        var limit = 500;
        if(current.length > limit){
            $("#additional-info").val(current.substring(0, limit));
        }
        else {
            $("#chars-left").text(limit-current.length);
        }
    });
    $("#submit-hazard").click(function(e){
        var lat = $("#latitude-alert").val();
        var lon = $("#longitude-alert").val();
        var category = $("#hazard-category").val();
        var details = $("#additional-info").val();
        if(lat==""||lon==""||category==""||details==""){
            alert("Please fill out all the fields with necessary values!");
            e.preventDefault();
        }
        else {
            var valid = confirm("Do you want to add this hazard?");
            if(!valid){
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
			// console.log(data.name);
			// console.log("Latitude: "+position.coords.latitude+" Longitude: "+position.coords.longitude);
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
    function changeEvacStat(stat){
        $.ajax({
            url:"../database/edit-evac-status.php",
            data: {status: stat}, 
            async: true,
            success: function(){
                window.location.replace("lgu-homepage.php?e=Successfully changed evacuation status.");
            }
        });
    }
    function displayAlerts(){
        $.ajax({
            url: "../database/table-received-traveler-alerts.php",
            async: true,
            success: function(object){
                var table_values = JSON.parse(object);
                // console.log(table_values);
                $("#table-alerts").html(table_values);
            }
        });
    }
    function displayEvacsAndSafehouse(){
        $.ajax({
            url: "../database/table-evacuation-centers-and-safehouses-outside.php",
            async: true,
            success: function(results){
                var table_values = JSON.parse(results);
                $("#table-evacuation-or-safehouses").html(table_values);
            }
        });
    }
    function displayReceivedReports(){
        $.ajax({
            url: "../database/lgu-received-reports-from-residents.php",
            async: true,
            success: function(results){
                var table_values = JSON.parse(results);
                $("#received-reports-from-residents").hide().html(table_values).fadeIn("slow");
            }
        });
    }
    function displayReceivedReportsTime(date_1, date_2){
        $.ajax({
            url: "../database/lgu-received-reports-from-residents-time.php",
            data: {date_1: date_1, date_2: date_2},
            async: true,
            success: function(results){
                var table_values = JSON.parse(results);
                $("#received-reports-from-residents").hide().html(table_values).fadeIn("slow");
            }
        });
    }
    function displayReceivedReportsCat(category){
        $.ajax({
            url: "../database/lgu-received-reports-from-residents-cat.php",
            data: {category: category}, 
            async: true,
            success: function(results){
                var table_values = JSON.parse(results);
                $("#received-reports-from-residents").hide().html(table_values).fadeIn("slow");
            }
        });
    }
    function displaySentHouse(household_id){
        $.ajax({
            url: "../database/sent-alerts-resident.php",
            data: {hh_id: household_id}, 
            async: true,
            success: function(results){
                var table_values = JSON.parse(results);
                $("#warning-no-cat").fadeOut("slow");
                $("#table-sent-alerts-residents").hide().html(table_values).fadeIn("slow");
            }
        });
    }
    function displaySentDate(date1sent, date2sent){
        $.ajax({
            url: "../database/sent-alerts-resident-date.php",
            data: {date1: date1sent, date2: date2sent}, 
            async: true,
            success: function(results){
                var table_values = JSON.parse(results);
                $("#warning-no-cat").fadeOut("slow");
                $("#table-sent-alerts-residents").hide().html(table_values).fadeIn("slow");   
            }
        });
    }
    function displaySentType(type){
        $.ajax({
            url: "../database/sent-alerts-resident-type.php",
            data: {type: type}, 
            async: true,
            success: function(results){
                var table_values = JSON.parse(results);
                $("#warning-no-cat").fadeOut("slow");
                $("#table-sent-alerts-residents").hide().html(table_values).fadeIn("slow");
            }
        });
    }
    function getCoordsMap(){
        var map = new google.maps.Map(document.getElementById(''))
        google.maps.event.addListener(map, 'click', function( event ){
            alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() ); 
        });
    }
});