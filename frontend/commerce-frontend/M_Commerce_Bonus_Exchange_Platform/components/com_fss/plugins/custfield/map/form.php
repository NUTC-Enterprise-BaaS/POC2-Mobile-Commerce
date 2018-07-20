<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="control-group">
	<div class="control-label">
		<label class="control-label">Default Latitude</label>
	</div>
	<div class="controls">
		<input type='text' name='map_lat' id='map_lat' value='<?php echo $params->lat; ?>'>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Default Longitude</label>
	</div>
	<div class="controls">
		<input type='text' name='map_lng' id='map_lng' value='<?php echo $params->lng; ?>'>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Default Zoom</label>
	</div>
	<div class="controls">
		<input type='text' name='map_zoom' id='map_zoom' value='<?php echo $params->zoom; ?>'>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Default Location</label>
	</div>
	<div class="controls">
		<div id='map-canvas' style='width: 100%; height: 400px;margin-top: 8px;border: 1px solid #ccc;'></div>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Width</label>
	</div>
	<div class="controls">
		<input type='text' name='map_width' id='map_width' value='<?php echo $params->width; ?>'>
	</div>
</div>

<div class="control-group">
	<div class="control-label">
		<label class="control-label">Height</label>
	</div>
	<div class="controls">
		<input type='text' name='map_height' id='map_height' value='<?php echo $params->height; ?>'>
	</div>
</div>

<script>
var cf_map_lat = '<?php echo $params->lat; ?>';
var cf_map_lng = '<?php echo $params->lng; ?>';
var cf_map_zoom = '<?php echo $params->zoom; ?>';
</script>
<script src="//maps.googleapis.com/maps/api/js" type="text/javascript"></script>
<script src="<?php echo JURI::root().'components/com_fss/plugins/custfield/map/form.js'; ?>" type="text/javascript"></script>