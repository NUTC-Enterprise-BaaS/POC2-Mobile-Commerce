
var map_cf_maps = [];
var map_cf_marker = [];

function init_map_cf(name, rand, lng, lat, zoom, isset) {

    //console.log("name " + name + lng + lat);
    var mapCanvas = document.getElementById('map-cf-' + name + '-' + rand);

    if (!mapCanvas)
        return;

    var mapOptions = {
        center: new google.maps.LatLng(lng, lat),
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(mapCanvas, mapOptions);
    var marker = 0;

    if (isset >= 2) {
        marker = new google.maps.Marker({
            draggable: false,
            animation: google.maps.Animation.DROP,
            position: new google.maps.LatLng(lng, lat)

        });

        marker.setMap(map);
    }

    if (isset < 2) {
        marker = new google.maps.Marker({
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: new google.maps.LatLng(lng, lat)

        });

        marker.setMap(map);

        google.maps.event.addListener(map, 'click', function (event) {
            map_cf_changed(name, event);
        });

        google.maps.event.addListener(marker, 'dragend', function (event) {
            map_cf_changed(name, event);
        });

        var input = document.getElementById('custom_' + name);
        var autocomplete = new google.maps.places.Autocomplete(input, {
            types: ["geocode"]
        });

        autocomplete.bindTo('bounds', map);

        google.maps.event.addListener(autocomplete, 'place_changed', function (event) {
            var place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            marker.setPosition(place.geometry.location);
            jQuery('#custom_' + name + '_lat').val(place.geometry.location.lat());
            jQuery('#custom_' + name + '_lng').val(place.geometry.location.lng());
        });
    }

    map_cf_maps[name] = map;
    map_cf_marker[name] = marker;
}

// marker dragged or map clicked and location changed
function map_cf_changed(name, event)
{
    // create or move marker
    if (map_cf_marker[name]) {
        map_cf_marker[name].setPosition(event.latLng);
    } else {
        map_cf_marker[name] = new google.maps.Marker({
            position: event.latLng,
            draggable: true,
            animation: google.maps.Animation.DROP
        });


        map_cf_marker[name].setMap(map);
    }

    // update fields
    jQuery('#custom_' + name + '_lng').val(event.latLng.lng());
    jQuery('#custom_' + name + '_lat').val(event.latLng.lat());
    jQuery('#custom_' + name + '_zoom').val(map_cf_maps[name].getZoom());

    // update address with clicked location
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        "latLng": event.latLng
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            jQuery("#custom_" + name).val(results[0].formatted_address);
        }
    });
}

function map_cf_geocode(name) {

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(onGeoSuccess, onGeoError);
    } else {
        alert("Your browser or device doesn't support Geolocation");
    }

    // If we have a successful location update
    function onGeoSuccess(event) {
        jQuery('#custom_' + name + '_lat').val(event.coords.latitude);
        jQuery('#custom_' + name + '_lng').val(event.coords.longitude);
        jQuery('#custom_' + name + '_zoom').val(17);

        var latlng = new google.maps.LatLng(event.coords.latitude, event.coords.longitude);

        map_cf_marker[name].setPosition(latlng);
        map_cf_maps[name].setCenter(latlng);
        map_cf_maps[name].setZoom(17);

        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            "latLng": latlng
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                jQuery("#custom_" + name).val(results[0].formatted_address);
            }
        });
    }

    // If something has gone wrong with the geolocation request
    function onGeoError(event) {
        alert("Error code " + event.code + ". " + event.message);
    }
}

