<?php
if( !defined('COMPONENT_IMAGE_PATH') )
	define("COMPONENT_IMAGE_PATH", JURI::base()."components/com_jbusinessdirectory/assets/images/");

$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
$lang = JFactory::getLanguage()->getTag();
$key="";
if(!empty($appSettings->google_map_key))
	$key="&key=".$appSettings->google_map_key;
JHtml::_('script', "https://maps.googleapis.com/maps/api/js?language=".$lang.$key);

$map_latitude = $appSettings->map_latitude;
$map_longitude = $appSettings->map_longitude;
$map_zoom = (int)$appSettings->map_zoom;

if ((empty($map_latitude)) || (!is_numeric($map_latitude)))
	$map_latitude = 37.4419;

if ((empty($map_longitude)) || (!is_numeric($map_longitude)))
	$map_longitude = -122.1419;

if ((empty($map_zoom)) || (!is_numeric($map_zoom)))
	$map_zoom = 3;

if($appSettings->map_apply_search!='1') {
	$map_latitude = 37.4419;
	$map_longitude = -122.1419;
	$map_zoom = 3;
}

$map_enable_auto_locate = "";
if($appSettings->map_enable_auto_locate){
	$map_enable_auto_locate = "map.fitBounds(bounds);";
}

//If selected the Style 5 layout from General settings
$layout_style_5 = false;
if($appSettings->search_result_view == 5 && empty($param)){
	$layout_style_5 = true;
}

$mapId = rand(1000,10000);

if($appSettings->enable_google_map_clustering) {
	JHtml::_('script', 'components/com_jbusinessdirectory/assets/js/markercluster.js');
}

 ?>

<script>
	function initialize(tmapId) {
		var center = new google.maps.LatLng(<?php echo $map_latitude ?>, <?php echo $map_longitude ?>);

		<?php
		$width = "100%";
		$height = "450px";

		if($layout_style_5) {
			$height = "600px";
		} else {
			if(isset($mapHeight))
				$height = $mapHeight;
			if(isset($mapWidth))
				$width = $mapWidth;
		} ?>

		var mapId = <?php echo $mapId?>;
		if(typeof tmapId !== 'undefined') {
			mapId = tmapId;
		}
		var mapdiv = document.getElementById("companies-map-"+mapId);

		mapdiv.style.width =  "<?php echo $width ?>";
		mapdiv.style.height = "<?php echo $height ?>";

		var map = new google.maps.Map(mapdiv, {
			zoom: <?php echo $map_zoom ?>,
			center: center,
			scrollwheel: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			styles: [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#808080"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.neighborhood","elementType":"geometry.fill","stylers":[{"color":"#de2929"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.fill","stylers":[{"color":"#de1616"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"lightness":"61"},{"saturation":"-62"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"color":"#ff0000"},{"visibility":"off"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"landscape.natural.terrain","elementType":"labels.text.fill","stylers":[{"color":"#b2b2b2"},{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#C5E3BF"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"labels.text","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"color":"#e8e8e8"}]},{"featureType":"poi.government","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#b8e695"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.place_of_worship","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.school","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.sports_complex","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#D1D1B8"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e4e4e4"},{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#e4e4e4"},{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#b2b2b2"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"visibility":"on"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"color":"#e4e4e4"},{"visibility":"on"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"color":"#b2b2b2"}]},{"featureType":"transit","elementType":"geometry.fill","stylers":[{"color":"#e1e1e1"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#b2b2b2"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#accff7"}]}]
		});

		var companies = [
			<?php 
			$db = JFactory::getDBO();

			if(!isset($companies))
				$companies = $this->companies;

			$index = 1;

			foreach($companies as $company) {

				$marker = 0;
				
				if(!empty($company->categoryMaker)) {
					$marker = JURI::root().PICTURES_PATH.$company->categoryMaker;
				}

				$contentPhone = (isset($company->packageFeatures) && in_array(PHONE,$company->packageFeatures) || !$appSettings->enable_packages)?
				'<div class="info-phone"><i class="dir-icon-phone"></i> '.$db->escape($company->phone).'</div>':"";
				$contentString = '<div class="info-box">'.
					'<div class="title">'.$db->escape($company->name).'</div>'.
					'<div class="info-box-content">'.
					'<div class="address" itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">'.$db->escape(JBusinessUtil::getAddressText($company)).'</div>'.
					$contentPhone.
					'<a href="'.$db->escape(JBusinessUtil::getCompanyLink($company)).'"><i class="dir-icon-external-link"></i> '.$db->escape(JText::_("LNG_MORE_INFO",true)).'</a>'.
					'</div>'.
					'<div class="info-box-image">'.
					(!empty($company->logoLocation)?'<img src="'. JURI::root().PICTURES_PATH.$db->escape($company->logoLocation).'" alt="'.$db->escape($company->name).'">':"").
					'</div>'.
					'</div>';
					
				if($layout_style_5) {
					$contentString = intval($company->id);
				}

				if(!empty($company->latitude) && !empty($company->longitude) && (isset($company->packageFeatures) && in_array(GOOGLE_MAP,$company->packageFeatures) || !$appSettings->enable_packages)) {
					echo "['".$db->escape($company->name)."', \"$company->latitude\",\"$company->longitude\", 4,'".$contentString."','".$index."','".$marker."'],"."\n";
				}

				if(!empty($company->locations) && (isset($company->packageFeatures) && in_array(GOOGLE_MAP,$company->packageFeatures) || !$appSettings->enable_packages)) {
					$locations = explode(",",$company->locations);
					
					foreach($locations as $location) {
						$loc = explode("|",$location);
						$address = JBusinessUtil::getLocationAddressText($loc[2],$loc[3],$loc[4],$loc[5],$loc[6]);

						$contentPhoneLocation = (isset($company->packageFeatures) && in_array(PHONE,$company->packageFeatures) || !$appSettings->enable_packages)?
						'<div class="info-phone"><i class="dir-icon-phone"></i> '.$db->escape($loc[7]).'</div>':"";
							
						$contentStringLocation = '<div class="info-box">'.
								'<div class="title">'.$db->escape($company->name).'</div>'.
								'<div class="info-box-content">'.
								'<div class="address" itemtype="http://schema.org/PostalAddress" itemscope="" itemprop="address">'.$db->escape($address).'</div>'.
								$contentPhoneLocation.
								'<a href="'.$db->escape(JBusinessUtil::getCompanyLink($company)).'"><i class="dir-icon-external-link"></i> '.$db->escape(JText::_("LNG_MORE_INFO",true)).'</a>'.
								'</div>'.
								'<div class="info-box-image">'.
								(!empty($company->logoLocation)?'<img src="'. JURI::root().PICTURES_PATH.$db->escape($company->logoLocation).'" alt="'.$db->escape($company->name).'">':"").
								'</div>'.
								'</div>';
						
						if($layout_style_5) {
							$contentStringLocation = intval($company->id);
						}
						
						echo "['".htmlspecialchars($company->name, ENT_QUOTES)."', \"$loc[0]\",\"$loc[1]\", 4,'".$contentStringLocation."','".$index."','".$marker."'],"."\n";
						
					}
				}

				$index++;
			} ?>
		];

		setMarkers(map, companies);
	}

	function setMarkers(map, locations) {
		// Add markers to the map

		// Marker sizes are expressed as a Size of X,Y
		// where the origin of the image (0,0) is located
		// in the top left of the image.

		// Origins, anchor positions and coordinates of the marker
		// increase in the X direction to the right and in
		// the Y direction down.

		var bounds = new google.maps.LatLngBounds();

		var markers = [];

		for (var i = 0; i < locations.length; i++) {
			var company = locations[i];

			var pinColor = "0071AF";
			var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+(company[5])+"|" + pinColor+"|FFFFFF",
				new google.maps.Size(30, 35),
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

			if(company[6] != '0') {
				pinImage = new google.maps.MarkerImage(company[6],
				// This marker is 20 pixels wide by 32 pixels tall.
				new google.maps.Size(32, 32),
				// The origin for this image is 0,0.
				new google.maps.Point(0,0),
				// The anchor for this image is the base of the flagpole at 0,32.
				new google.maps.Point(0, 32));
			} else {
				var ms_ie = false;
				var ua = window.navigator.userAgent;
				var old_ie = ua.indexOf('MSIE ');
				var new_ie = ua.indexOf('Trident/');

				if ((old_ie > -1) || (new_ie > -1)) {
					ms_ie = true;
				}
			
				if(ms_ie) {
					pinImage = new google.maps.MarkerImage("<?php echo JURI::root().PICTURES_PATH.'/marker_home.png' ?>",
					// This marker is 20 pixels wide by 32 pixels tall.
					new google.maps.Size(32, 32),
					// The origin for this image is 0,0.
					new google.maps.Point(0,0),
					// The anchor for this image is the base of the flagpole at 0,32.
					new google.maps.Point(0, 32));
				}
			}

			var myLatLng = new google.maps.LatLng(company[1], company[2]);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				icon: pinImage,
				shadow: pinShadow,
				shape: shape,
				title: company[0],
				<?php if($layout_style_5) { ?>
					zIndex: parseInt(company[4])
				<?php } else { ?>
					zIndex: company[3]
				<?php } ?>
				
			});

			markers.push(marker);
			
			<?php if($layout_style_5) { ?>
				(function(Marker) {
					google.maps.event.addListener(marker, 'click', function() {
						var target = "#company"+this.getZIndex();
						window.location = target;

						jQuery(target).fadeOut(1, function() {
							jQuery(target).css("background-color", "#469021").fadeIn(500);
						});

						setTimeout(function() {
							jQuery(target).removeClass('selected-company');
							jQuery(target).fadeOut(1, function() {
								jQuery(target).css("background-color", "transparent").fadeIn(700);
							});
						}, 1200);
					});
				}(marker));
			<?php } else { ?>
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
				} (contentBody));

			<?php } ?>

			bounds.extend(myLatLng);
		}

		<?php if($layout_style_5) { ?>
			jQuery(".btn-show-marker").click(function() {
				var companyID = jQuery(this).closest('.grid-item-holder').attr('id');
				var id = companyID.match(/\d/g);
				id = id.join('');

				for (i = 0; i < markers.length; i++) {
					if( markers[i].getZIndex() == id ) {
						map.setZoom(16);
						map.setCenter(markers[i].getPosition());
					}
				}
			});
		<?php } ?>

		<?php if($appSettings->enable_google_map_clustering) { ?>
			mcOptions = {
				imagePath: 
				"<?php echo COMPONENT_IMAGE_PATH ?>mapcluster/m"
				};
			var markerCluster = new MarkerClusterer(map, markers,mcOptions);
		<?php } ?>

		<?php if(isset($this) && !empty($this->location["latitude"])) { ?>
			var pinImage = new google.maps.MarkerImage("https://maps.google.com/mapfiles/kml/shapes/library_maps.png",
			new google.maps.Size(31, 34),
			new google.maps.Point(0, 0),
			new google.maps.Point(10, 34));

			var myLatLng = new google.maps.LatLng(<?php echo $this->location["latitude"] ?>,  <?php echo $this->location["longitude"] ?>);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				icon: pinImage
			});

			<?php $session = JFactory::getSession(); $radius = $session->get("radius"); ?>

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