function initializeEvacMap(){
	var pt;
	navigator.geolocation.getCurrentPosition(function(position){
		pt = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	});
	var map = new google.maps.Map(document.getElementById('evac-map'), {
		zoom: 10, 
		center: pt, 
		mapTypeId: google.maps.mapTypeId.ROADMAP
	});
}