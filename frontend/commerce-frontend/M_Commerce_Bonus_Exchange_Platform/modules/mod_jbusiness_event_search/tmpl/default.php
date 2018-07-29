<?php

/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('stylesheet', 'components/com_jbusinessdirectory/assets/css/font-awesome.css');
JHtml::_('stylesheet', 'modules/mod_jbusiness_event_search/assets/style.css');
JHtml::_('script', 'modules/mod_jbusiness_event_search/assets/js/script.js');

$preserve = $params->get('preserve');

?>

<div class="module-search-map">
	<?php
	if($params->get('showMap')) {
		require JPATH_SITE.'/components/com_jbusinessdirectory/views/events/tmpl/map.php';
	}
	?>
</div>

<?php if(!$params->get('showOnlyMap')) { ?>

<div id="companies-search" class="business-directory<?php echo $moduleclass_sfx ?>">
	<div id="searchform" class="ui-tabs <?php echo $layoutType?>">
			<?php $title = $params->get('title'); ?>
			<?php if(!empty($title)){ ?>
				<h1><?php echo $title ?></h1>
			<?php } ?>

			<?php $description = $params->get('description'); ?>
			<?php if(!empty($description)){ ?>
				<p><?php echo $description ?></p>
			<?php } ?>

			<form action="<?php echo JRoute::_('index.php?option=com_jbusinessdirectory'.$menuItemId) ?>" method="post" name="keywordSearch" id="keywordSearch" onsubmit="return checkSearch()">

				<div class="form-container">
					<?php if($params->get('showKeyword')){ ?>
						<div class="form-field">
							<input class="search-field" type="text" placeholder="<?php echo JText::_("LNG_SEARCH")?>" name="searchkeyword" id="searchkeyword" value="<?php echo $preserve?$session->get('ev-searchkeyword'):"";?>" />
						</div>
					<?php } ?>

				<?php if($params->get('showCategories')){ ?>
					<div class="form-field">
						<select name="categorySearch" id="categories">
							<option value="0"><?php echo JText::_("LNG_ALL_CATEGORIES") ?></option>
							<?php foreach($categories as $category){?>
								<option value="<?php echo $category->id?>" <?php echo $session->get('ev-categorySearch')==$category->id && $preserve?" selected ":"" ?> ><?php echo $category->name?></option>
								<?php if(!empty($category->subcategories)){?>
									<?php foreach($category->subcategories as $subCat){?>
											<option value="<?php echo $subCat->id?>" <?php  echo $session->get('ev-categorySearch')==$subCat->id && $preserve?" selected ":"" ?> >-- <?php echo $subCat->name?></option>
									<?php }?>
								<?php }?>
							<?php }?>
						</select>
					</div>
					<?php }?>

					<?php if($params->get('showTypes')){ ?>
					<div class="form-field">
						<select name="typeSearch" id="typeSearch">
							<option value="0"><?php echo JText::_("LNG_ALL_TYPES") ?></option>
							<?php foreach($types as $type){?>
								<option value="<?php echo $type->id?>" <?php  echo $session->get('ev-typeSearch')==$type->id && $preserve?" selected ":"" ?> ><?php echo $type->name?></option>
							<?php } ?>
						</select>
					</div>
					<?php }?>


					<?php if($params->get('showStartDate')){ ?>
						<div class="form-field">
							<?php echo JHTML::calendar($startDate,'startDate','startDate',$appSettings->calendarFormat, array('class'=>'dir-date','onchange'=>'', 'placeholder'=>JText::_("LNG_START_DATE"))); ?>
						</div>
					<?php } ?>

					<?php if($params->get('showEndDate')){ ?>
						<div class="form-field">
							<?php echo JHTML::calendar($endDate,'endDate','endDate',$appSettings->calendarFormat, array('class'=>'dir-date','onchange'=>'', 'placeholder'=>JText::_("LNG_END_DATE"))); ?>
						</div>
					<?php } ?>

					<?php if($params->get('showZipcode')){ ?>
						<div class="form-field">
							<div id="dir-search-preferences" style="display:none">
								<h3 class="title"><?php echo JText::_("LNG_SEARCH_PREFERENCES")?><i class="dir-icon-close" onclick="jQuery('#dir-search-preferences').hide()"></i></h3>
								<div class="geo-radius">
									<div><?php echo JText::_("LNG_RADIUS")?> (<?php echo $appSettings->metric==1?JText::_("LNG_MILES"):JText::_("LNG_KM") ?>)</div>
								</div>
								<div>
									<input type="text" id="geo-location-radius" name="radius" value="<?php echo !empty($radius)?$radius: "0" ?>">
								</div>
								<div class="geo-location">
									<?php echo JText::_("LNG_GEOLOCATION")?>
									<div id="loading-geo-locaiton" class="ui-autocomplete-loading" style="display:none"></div>
									<a id="enable-geolocation" class="toggle btn-on <?php echo !empty($geoLocation)?"active":""?>" title="Grid" href="javascript:enableGeoLocation()"><?php echo strtoupper(JText::_("LNG_ON")) ?></a>
									<a id="disable-geolocation" class="toggle btn-off <?php echo empty($geoLocation)?"active":""?>" title="List" href="javascript:disableGeoLocation()"><?php echo strtoupper(JText::_("LNG_OFF")) ?></a>
								</div>
							</div>
							<i class="dir-icon-map-marker"></i>
							<input class="search-field" placeholder="<?php echo JText::_("LNG_ZIPCODE")?>" type="text" name="zipcode" id="zipcode" value="<?php  echo $preserve?$session->get('zipcode'):"";?>" />
							<i class="dir-icon-bullseye"  onclick='jQuery("#dir-search-preferences").show()'></i>
						</div>
					<?php } ?>

					<?php if($params->get('showCities')){ ?>
						<div class="form-field">
							<select name="citySearch" id="citySearch">
								<option value="0"><?php echo JText::_("LNG_ALL_CITIES") ?></option>
								<?php foreach($cities as $city){?>
									<option value="<?php echo $city->city?>" <?php echo $session->get('ev-citySearch')==$city->city && $preserve?" selected ":"" ?> ><?php echo $city->city?></option>
								<?php }?>
							</select>
						</div>
					<?php } ?>

					<?php if($params->get('showRegions')){ ?>
						<div class="form-field">
							<select name="regionSearch" id="regionSearch">
								<option value="0"><?php echo JText::_("LNG_ALL_REGIONS") ?></option>
								<?php foreach($regions as $region){?>
									<option value="<?php echo $region->county?>" <?php echo $session->get('ev-regionSearch')==$region->county && $preserve?" selected ":"" ?> ><?php echo $region->county?></option>
								<?php }?>
							</select>
						</div>
					<?php } ?>

					<button type="submit" class="ui-dir-button ui-dir-button-green search-dir-button">
						<i class="dir-icon-search"></i>
						<span class="ui-button-text"><?php echo JText::_("LNG_SEARCH")?></span>
					</button>
				</div>

				<input type="hidden" name="option" value="com_jbusinessdirectory">
				<input type='hidden' name='view' value='events'>
				<input type="hidden" name="resetSearch" value="1">
				<input type='hidden' name='preserve' value='<?php echo $preserve?>'>
				<input type="hidden" name="geo-latitude" id="geo-latitude" value="">
				<input type="hidden" name="geo-longitude" id="geo-longitude" value="">
				<input type="hidden" name="geolocation" id="geolocation" value="<?php echo $geoLocation ?>">

			</form>
	</div>
	<div class="clear"></div>
</div>

<?php } ?>

<script>

	function checkSearch(){
		<?php if($params->get('mandatoryKeyword')){ ?>
		if(document.getElementById('searchkeyword') && jQuery("#searchkeyword").val().length == 0){
			jQuery("#searchkeyword").focus();
			return false;
		}
		<?php } ?>

		<?php if($params->get('mandatoryCategories')){ ?>
		var foo = document.getElementById('categories');
		if (foo)
		{
			console.debug(foo.selectedIndex);
			if (foo.selectedIndex == 0)
			{
				jQuery("#categories").focus();
				return false;
			}
		}
		<?php } ?>

		<?php if($params->get('mandatoryTypes')){ ?>
		var foo = document.getElementById('typeSearch');
		if (foo)
		{
			if (foo.selectedIndex == 0)
			{
				jQuery("#typeSearch").focus();
				jQuery("#typeSearch_chosen span").trigger("click");
				return false;
			}
		}
		<?php } ?>

		<?php if($params->get('mandatoryStartDate')){ ?>
		if(document.getElementById('startDate') && jQuery("#startDate").val().length == 0){
			jQuery("#startDate").focus();
			return false;
		}
		<?php } ?>

		<?php if($params->get('mandatoryEndDate')){ ?>
		if(document.getElementById('endDate') && jQuery("#endDate").val().length == 0){
			jQuery("#endDate").focus();
			return false;
		}
		<?php } ?>

		<?php if($params->get('mandatoryCities')){ ?>
		var foo = document.getElementById('citySearch');
		if (foo)
		{
			if (foo.selectedIndex == 0)
			{
				jQuery("#citySearch").focus();
				return false;
			}
		}
		<?php } ?>

		<?php if($params->get('mandatoryRegions')){ ?>
		var foo = document.getElementById('regionSearch');
		if (foo)
		{
			if (foo.selectedIndex == 0)
			{
				jQuery("#regionSearch").focus();
				return false;
			}
		}
		<?php } ?>


		return true;
	}

	jQuery(document).ready(function(){
		<?php if($params->get('autocomplete')){?>
		jQuery("#categories").chosen();
		jQuery("#citySearch").chosen();
		jQuery("#regionSearch").chosen();
		jQuery("#countrySearch").chosen();
		jQuery("#typeSearch").chosen();
		<?php } ?>

		jQuery("#searchkeyword").autocomplete({
			source: "<?php echo JURI::base().'index.php?option=com_jbusinessdirectory&task=categories.getCategories&type='.CATEGORY_TYPE_EVENT ?>",
			minLength: 2,
			select: function( event, ui ) {
				jQuery(this).val(ui.item.label);
				return false;
			}
		});

		jQuery("#zipcode").focusin(function() {
			jQuery("#dir-search-preferences").slideDown(500);
		});
		jQuery("#zipcode").focusout(function() {
			jQuery("#dir-search-preferences").slideUp(500);
		});

		<?php if($params->get('showZipcode')){ ?>
		jQuery("#geo-location-radius").ionRangeSlider({
			grid: true,
			min: 0,
			max: 500,
			from: <?php echo !empty($radius)?$radius: "0" ?>,
			to: 500,
		});
		<?php } ?>

		//disable all empty fields to have a nice url
		<?php if($appSettings->submit_method=="get"){?>
		jQuery('#companies-search').submit(function() {
			console.debug("submit");
			jQuery(':input', this).each(function() {
				this.disabled = !(jQuery(this).val());
			});

			jQuery('#companies-search select').each(function() {
				if(!(jQuery(this).val()) || jQuery(this).val()==0){
					jQuery(this).attr('disabled', 'disabled');
				}
			});
		});
		<?php }?>
	});

	function enableGeoLocation(){
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(setGeoLocation);
			jQuery("#loading-geo-locaiton").show();
			jQuery(".dir-icon-bullseye").addClass("dir-beat-animation");
		}
		jQuery("#enable-geolocation").addClass("active");
		jQuery("#disable-geolocation").removeClass("active");
		jQuery("#geolocation").val(1);
	}

	function disableGeoLocation(){
		jQuery("#enable-geolocation").removeClass("active");
		jQuery("#disable-geolocation").addClass("active");
		jQuery("#geolocation").val(0);
		jQuery("#loading-geo-locaiton").hide();
		jQuery(".dir-icon-bullseye").removeClass("dir-beat-animation");
		jQuery("#geo-latitude").val('');
		jQuery("#geo-longitude").val('');
	}

	function setGeoLocation(position){
		jQuery("#loading-geo-locaiton").hide();
		jQuery(".dir-icon-bullseye").removeClass("dir-beat-animation");
		var latitude = position.coords.latitude;
		var longitude = position.coords.longitude;
		jQuery("#geo-latitude").val(latitude);
		jQuery("#geo-longitude").val(longitude);
	}

	<?php if($params->get('autolocation')){ ?>
	enableGeoLocation();
	<?php }?>

	<?php if($params->get('showMap')){ ?>
	loadMapScript();
	<?php }?>

	<?php if($params->get('linklocation')) { ?>

	var siteRoot = '<?php echo JURI::root(); ?>';
	var url = siteRoot+'index.php?option=com_jbusinessdirectory';

	jQuery(document).ready(function() {
		<?php if($choices==1) { ?>
		getCitiesByCountry();
		<?php } elseif($choices==2) { ?>
		getRegionsByCountry();
		<?php } elseif($choices==3) { ?>
		getCitiesByRegion();
		<?php } ?>
	});

	function getRegionsByCountry() {
		var urlRegionsByCountry = url+'&task=search.getRegionsByCountryAjax&countryId='+jQuery("#countrySearch").val();
		var urlCitiesByRegion = url+'&task=search.getCitiesByRegionAjax&region='+jQuery("#regionSearch").val();
		jQuery.ajax({
			type: "GET",
			url: urlRegionsByCountry,
			dataType: 'json',
			success: function(data){
				jQuery("#regionSearch").empty();
				jQuery("#regionSearch").html(data);
				jQuery('#regionSearch').trigger("chosen:updated");
				getCitiesByCountry();
			}
		});
	}

	function getCitiesByRegion() {
		var urlCitiesByRegion = url+'&task=search.getCitiesByRegionAjax&region='+jQuery("#regionSearch").val();
		jQuery.ajax({
			type: "GET",
			url: urlCitiesByRegion,
			dataType: 'json',
			success: function(data){
				jQuery("#citySearch").empty();
				jQuery("#citySearch").html(data);
				jQuery('#citySearch').trigger("chosen:updated");
			}
		});
	}

	function getCitiesByCountry() {
		var urlCitiesByCountry = url+'&task=search.getCitiesByCountryAjax&countryId='+jQuery("#countrySearch").val();
		jQuery.ajax({
			type: "GET",
			url: urlCitiesByCountry,
			dataType: 'json',
			success: function(data){
				jQuery("#citySearch").empty();
				jQuery("#citySearch").html(data);
				jQuery('#citySearch').trigger("chosen:updated");
			}
		});
	}
	<?php } ?>

</script>



