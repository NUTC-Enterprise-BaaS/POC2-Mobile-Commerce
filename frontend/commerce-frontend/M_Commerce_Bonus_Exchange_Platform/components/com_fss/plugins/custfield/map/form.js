jQuery(document).ready( function () {
	map_plugin_selected();
});

function map_plugin_selected() {
	var mapCanvas = document.getElementById('map-canvas');

	var mapOptions = {
	    center: new google.maps.LatLng(cf_map_lat, cf_map_lng),
	    zoom: parseInt(cf_map_zoom),
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	var map = new google.maps.Map(mapCanvas, mapOptions);

	map.addListener('center_changed', function() {
		jQuery('#map_lat').val(map.getCenter().lat());
		jQuery('#map_lng').val(map.getCenter().lng());
	});

	map.addListener('zoom_changed', function() {
		jQuery('#map_zoom').val(map.getZoom());
	});
}