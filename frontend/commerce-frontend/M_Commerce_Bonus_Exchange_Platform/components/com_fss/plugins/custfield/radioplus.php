<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class RadioPlusPlugin extends FSSCustFieldPlugin
{
	var $name = "Radio Plus";
	
	var $default_params = array(
		'items' => array(),
		'other_label' => '',
		);

	function DisplaySettings($params)
	{
		$params = $this->parseParams($params);
 
		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'plugins'.DS.'custfield'.DS.'radioplus'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}

	function SaveSettings() // return object with settings in
	{
		$values = explode("\n", FSS_Input::getString('radioplus_items'));
		$res = array();
		foreach ($values as $value)
		{
			$value = trim($value);
			if ($value !== "")
				$res[] = $value;
		}

		return $this->encodeParams( array ( 
			'items'		=> $res,
			'other_label'		=> FSS_Input::getString('radioplus_other_label')
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{	
		$params = $this->parseParams($params);
		$output = array();

		$found = false;

		foreach($params->items as $item)
		{
			$checked = "";
			if ($item == $current)
			{
				$found = true;
				$checked = ' checked="checked" ';
			}
			$output[] = '<label class="radio">';
			$output[] = '	<input type="radio" name="custom_'.$id.'" value="'.$item.'" '.$checked.'>';
			$output[] = $item;
			$output[] = '</label>';
		}

		if (trim($current) == "") $found = true;

		if ($params->other_label != "")
		{
			$checked = "";
			if (!$found) 
				$checked = ' checked="checked" ';
			$output[] = '<label class="radio">';
			$output[] = "	<input type='radio' name='custom_{$id}' value='xxxotherxxx' {$checked}>";
			$output[] = $params->other_label;

			$otherval = "";
			if (!$found)
				$otherval = $current;
			$output[] = "&nbsp;<input type='text' name='custom_{$id}_other' value='{$otherval}' />";
			$output[] = '</label>';
		}

		return implode($output);
	}
	
	function Save($id, $params, $value = "")
	{
		$params = $this->parseParams($params);

		$value = FSS_Input::getString("custom_{$id}");
		if ($value == "xxxotherxxx") $value = FSS_Input::getString("custom_{$id}_other");
		
		return $value;
	}

	function CanEdit()
	{
		return true;	
	}
}