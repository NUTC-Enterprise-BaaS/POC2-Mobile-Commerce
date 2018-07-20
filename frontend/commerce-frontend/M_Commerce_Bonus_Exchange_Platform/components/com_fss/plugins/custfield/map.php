<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class MapPlugin extends FSSCustFieldPlugin
{
	var $name = "Map / Address";
	
	var $default_params = array(
		'lat' => 0,
		'lng' => 0,
		'zoom' => 3,
		'width' => '100%',
		'height' => '250px'
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'map'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'lat'		=> FSS_Input::GetFloat('map_lat'),
			'lng'		=> FSS_Input::GetFloat('map_lng'),
			'zoom'		=> FSS_Input::getInt('map_zoom'),
			'width'		=> FSS_Input::getString('map_width'),
			'height'    => FSS_Input::getString('map_height'),
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		$params = $this->parseParams($params);

		$data = json_decode($current);

		$lng = $data ? $data->lng : $params->lng;
		$lat = $data ? $data->lat : $params->lat;
		$zoom = $data ? $data->zoom : $params->zoom;
		$address = $data ? $data->address : "";

		$rand = mt_rand(10000,99999);

		$output = array();
		$output[] = "<p><textarea cols='80' name='custom_$id' id='custom_$id' rows='4' style='width: 300px'>" . $address . "</textarea></p>";
		$output[] = "<input type='hidden' name='custom_{$id}_lng' id='custom_{$id}_lng' value='{$lng}' />";
		$output[] = "<input type='hidden' name='custom_{$id}_lat' id='custom_{$id}_lat' value='{$lat}' />";
		$output[] = "<input type='hidden' name='custom_{$id}_zoom' id='custom_{$id}_zoom' value='{$zoom}' />";
		$output[] = "<p><a id='getgeo' class='btn btn-default' onclick='map_cf_geocode(\"$id\")' />" . JText::_("Get_location") . "</a></p>";
		$output[] = "<div id='map-cf-$id-$rand' class='map_cf' style='width: {$params->width}; height: {$params->height};'></div>";

		// need to pull the lat and lng out of the current var
		$this->addJS();

		$isset = 0;

		if ($data) $isset = 1;

		$js = "
		jQuery(document).ready( function () {
		    init_map_cf({$id}, {$rand}, '{$lat}', '{$lng}', parseInt('{$zoom}'), {$isset});
		});
		";
		JFactory::getDocument()->addScriptDeclaration($js);

		return implode("\n", $output);
	}
	
	function Save($id, $params, $value = "")
	{
		$params = $this->parseParams($params);

		$data = new stdClass();

		$data->address = FSS_Input::GetString("custom_$id");
		$data->lng = FSS_Input::GetString("custom_{$id}_lng");
		$data->lat = FSS_Input::GetString("custom_{$id}_lat");
		$data->zoom = FSS_Input::GetString("custom_{$id}_zoom");

		return json_encode($data);
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		$params = $this->parseParams($params);
		$data = json_decode($value);

		$output = array();

		if ($data)
		{
			$this->addJS();

			$lng = $data ? $data->lng : $params->lng;
			$lat = $data ? $data->lat : $params->lat;
			$zoom = $data ? $data->zoom : $params->zoom;

			$output[] = nl2br($data->address);

			if (!isset($context['report']) || !$context['report'])
			{
				$rand = mt_rand(10000,99999);
				$output[] = "<div id='map-cf-$id-$rand' class='map_cf' style='width: {$params->width}; height: {$params->height};'></div>";
		
				$js = "
			jQuery(document).ready( function () {
				init_map_cf({$id}, {$rand}, '{$lat}', '{$lng}', parseInt('{$zoom}'), 2);
			});
			";
				JFactory::getDocument()->addScriptDeclaration($js);
			}
		}

		return implode("\n", $output);
	}

	function CanEdit()
	{
		return true;	
	}

	function addJS()
	{
		$document = JFactory::getDocument();
		$document->addScript("//maps.google.com/maps/api/js?libraries=places&sensor=true");
		$document->addScript(JURI::root().'components/com_fss/plugins/custfield/map/map_cf.js'); 
		$document->addStyleSheet(JURI::root().'components/com_fss/plugins/custfield/map/map_cf.css'); 
	}
}