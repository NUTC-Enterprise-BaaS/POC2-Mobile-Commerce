<?php
	$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
	$lang = JFactory::getLanguage()->getTag();
	$key="";
	if(!empty($appSettings->google_map_key))
		$key="&key=".$appSettings->google_map_key;
	
	JHtml::_('script', "https://maps.googleapis.com/maps/api/js?language=".$lang.$key);

	$map_latitude = (float)$appSettings->map_latitude;
	$map_longitude = (float)$appSettings->map_longitude;
	$map_zoom = (float)$appSettings->map_zoom;
	$map_enable_auto_locate = $appSettings->map_enable_auto_locate;
	$map_apply_search = $appSettings->map_apply_search;

	$map_latitude = (float)$appSettings->map_latitude;
	$map_longitude = (float)$appSettings->map_longitude;
	$map_zoom = (float)$appSettings->map_zoom;

	if ( (empty($map_latitude)) || (!is_numeric($map_latitude)) )
		$map_latitude = 43.749156;

	if ( (empty($map_longitude)) || (!is_numeric($map_longitude)) )
		$map_longitude = -79.411048;

	if ( (empty($map_zoom)) || (!is_numeric($map_zoom)) )
		$map_zoom = 6;

	if($map_apply_search=='0') {
		$map_latitude = 43.749156;
		$map_longitude = -79.411048;
		$map_zoom = 6;
	}

	$map_enable_auto_locate = "";
	if($appSettings->map_enable_auto_locate){
		$map_enable_auto_locate = "map.fitBounds(bounds);";
	}
	
	$mapId = rand(1000,10000);
?>

<script>
	function initialize() {

		<?php if( (!empty($map_latitude)) && (!empty($map_longitude)) ) { ?>
			var center = new google.maps.LatLng(<?php echo $map_latitude ?>, <?php echo $map_longitude ?>);
		<?php } ?>

		var mapOptions = {
				zoom: <?php echo $map_zoom ?>,
				<?php if( (!empty($map_latitude)) && (!empty($map_longitude)) ) { ?>
					center: center,
				<?php } ?>
				scrollwheel: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}

		var mapId = <?php echo $mapId?>;
		if(typeof tmapId !== 'undefined') {
			mapId = tmapId;
		}
		var mapdiv = document.getElementById("companies-map-"+mapId);
	
		mapdiv.style.width = '100%';
		mapdiv.style.height = '450px';

		var map = new google.maps.Map(mapdiv, mapOptions);

		setMarkers(map, events);
	}

	/**
	* Data for the markers consisting of a name, a LatLng and a zIndex for
	* the order in which these markers should display on top of each
	* other.
	*/
	var events = [
		<?php
		$db = JFactory::getDBO();

		if(!isset($events))
			$events = $this->events;

		$index = 1;
		foreach($events as $event){
			$marker = 0;
			if(!empty($event->categoryMaker)) {
				$marker = JURI::root().PICTURES_PATH.$company->categoryMaker;
			}

			$contentString = '<div class="info-box">'.
				'<div class="title">'.$db->escape($event->name).'</div>'.
				'<div class="info-box-content">'.
				'<div class="address" itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">'.$db->escape(JBusinessUtil::getLocationText($event)).'</div>'.
				'<div class="info-phone"><i class="dir-icon-phone"></i> '.$db->escape($event->phone).'</div>'.
				'<a href="'.$db->escape(JBusinessUtil::geteventLink($event->id, $event->alias)).'"><i class="dir-icon-external-link"></i> '.$db->escape(JText::_("LNG_MORE_INFO",true)).'</a>'.
				'</div>'.
				'<div class="info-box-image">'.
				(!empty($event->picture_path)?'<img src="'. JURI::root().PICTURES_PATH.$event->picture_path.'" alt="'.$db->escape($event->name).'">':"").
				'</div>'.
				'</div>';

			if(!empty($event->latitude) && !empty($event->longitude)){
				echo "['".$db->escape($event->name)."', ".$event->latitude.",".$event->longitude.", 4,'".$contentString."','".$index."','".$marker."'],"."\n";
			}

        	$index++;
     	} ?>
	];

	function setMarkers(map, locations) {
		// Add markers to the map

		// Marker sizes are expressed as a Size of X,Y
		// where the origin of the image (0,0) is located
		// in the top left of the image.

		// Origins, anchor positions and coordinates of the marker
		// increase in the X direction to the right and in
		// the Y direction down.

		var bounds = new google.maps.LatLngBounds();

		for (var i = 0; i < locations.length; i++) {
			var company = locations[i];

			var pinColor = "0071AF";
			var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+(company[5])+"|" + pinColor+"|FFFFFF",
				new google.maps.Size(21, 34),
				new google.maps.Point(0,0),
				new google.maps.Point(10, 34));
			var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
				new google.maps.Size(40, 37),
				new google.maps.Point(0, 0),
				new google.maps.Point(12, 35));

			var shape = {
				coord: [1, 1, 1, 20, 18, 20, 18 , 1],
				type: 'poly'
			};

			if(company[6] != '0'){
				pinImage = new google.maps.MarkerImage(company[6],
				// This marker is 20 pixels wide by 32 pixels tall.
				new google.maps.Size(32, 32),
				// The origin for this image is 0,0.
				new google.maps.Point(0,0),
				// The anchor for this image is the base of the flagpole at 0,32.
				new google.maps.Point(0, 32));
			}

			var myLatLng = new google.maps.LatLng(company[1], company[2]);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				icon: pinImage,
				shadow: pinShadow,
				shape: shape,
				title: company[0],
				zIndex: company[3]
			});
			 
			var contentBody = company[4];
			var infowindow = new google.maps.InfoWindow({
				content: contentBody,
				maxWidth: 210
			});

			google.maps.event.addListener(marker, 'click', function(contentBody) {
				return function() {
					infowindow.setContent(contentBody);//set the content
					infowindow.open(map,this);
				}
			}(contentBody));

			bounds.extend(myLatLng);
		}

		 <?php if(isset($this) && !empty($this->location["latitude"])) { ?>
		 	var pinImage = new google.maps.MarkerImage(" https://maps.google.com/mapfiles/kml/shapes/library_maps.png",
		 	new google.maps.Size(31, 34),
		 	new google.maps.Point(0,0),
		 	new google.maps.Point(10, 34));

			var myLatLng = new google.maps.LatLng(<?php echo $this->location["latitude"] ?>, <?php echo $this->location["longitude"] ?>);
		 	var marker = new google.maps.Marker({
		 		position: myLatLng,
		 		map: map,
		 		icon: pinImage
		 	});

			<?php $session = JFactory::getSession(); $radius = $session->get("ev-radius"); ?>

			<?php if(!empty($radius)) { ?>
			// Add circle overlay and bind to marker
			var circle = new google.maps.Circle({
				map: map,
				radius: <?php echo $radius * 1600;?>,
				strokeColor: "#006CD9",
				strokeOpacity: 0.7,
				strokeWeight: 2,
				fillColor: "#006CD9",
				fillOpacity: 0.15,
			});
			circle.bindTo('center', marker, 'position');
			<?php } ?>

		 	bounds.extend(myLatLng);
		 <?php } ?>

		<?php echo $map_enable_auto_locate ?>

		var listener = google.maps.event.addListener(map, "idle", function() { 
			if (map.getZoom() > 16) map.setZoom(16);
			google.maps.event.removeListener(listener);
		});
	}

	function loadMapScript() {
		initialize(<?php echo $mapId?>);
	}
    </script>

<div id="companies-map-<?php echo $mapId ?>" style="position: relative;"></div>