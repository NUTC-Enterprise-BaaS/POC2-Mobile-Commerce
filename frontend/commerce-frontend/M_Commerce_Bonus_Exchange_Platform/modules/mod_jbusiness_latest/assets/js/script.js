
function addCoordinatesToUrl(position){
	
	var latitude = position.coords.latitude;
	var longitude = position.coords.longitude;
	
	var newURLString = window.location.href;
	newURLString += ((newURLString.indexOf('?') == -1) ? '?' : '&');
	newURLString += "latitude="+latitude;
	newURLString += ((newURLString.indexOf('?') == -1) ? '?' : '&');
	newURLString += "longitude="+longitude;

	window.location.href = newURLString;    // The page will redirect instantly 
	
}