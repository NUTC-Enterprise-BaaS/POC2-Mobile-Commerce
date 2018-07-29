<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class IntPlugin extends FSSCustFieldPlugin
{
	var $name = "Number";
	
	var $default_params = array(
		'min' => 0,
		'max' => 10,
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'int'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'min'		=> FSS_Input::getInt('int_min'),
			'max'		=> FSS_Input::getInt('int_max')
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		return "<input name='custom_$id' value='$current'>";
	}
	
	function Save($id, $params, $value = "")
	{
		$params = $this->parseParams($params);

		$value = FSS_Input::getInt("custom_$id");
		
		if ($value < $params->min)
			$value = $params->min;
		if ($value > $params->max)
			$value = $params->max;
		
		return $value;
	}

	function CanEdit()
	{
		return true;	
	}
}