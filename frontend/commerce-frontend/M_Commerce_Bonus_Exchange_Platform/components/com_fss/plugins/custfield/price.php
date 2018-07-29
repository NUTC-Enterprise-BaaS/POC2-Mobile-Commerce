<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class PricePlugin extends FSSCustFieldPlugin
{
	var $name = "Price";
	
	var $default_params = array(
		'symbol' => '$',
		'location' => 0
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'price'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'symbol'		=> FSS_Input::getString('price_symbol'),
			'location'		=> FSS_Input::getInt('price_location')
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		$params = json_decode($params, true);
		
		$js = "jQuery(document).ready(function () {          
					jQuery('#custom_{$id}_input').autoNumeric('init');
				}); ";
			
		JFactory::getDocument()->addScript(JURI::root().'components/com_fss/assets/js/jquery/jquery.numeric.js'); 	
		JFactory::getDocument()->addScriptDeclaration($js);

		$input = "<input type='text' class='input-small' name='custom_$id' id='custom_{$id}_input' value='$current' data-a-sign=' " . $params['symbol'] . " ' ";
		if ($params['location']) $input .= " data-p-sign='s' ";
		$input .= " >";
		
		return $input;
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		if ($value == "") return "";
		
		$params = $this->parseParams($params);
		
		if ($params->symbol)
		{
			if ($params->location)
			{
				$value = $value . " " . $params->symbol . " ";
			} else {
				$value = " " . $params->symbol . " " . $value;
			}
		}

		return $value;
	}
		
	function Save($id, $params, $value = "")
	{
		$params = $this->parseParams($params);

		$value = FSS_Input::getFloat("custom_$id");

		return $value;
	}

	function CanEdit()
	{
		return true;	
	}
}